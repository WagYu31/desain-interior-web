<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCancelledByUserNotification;
use App\Notifications\NewOrderForAdminNotification;
use App\Notifications\OrderSuccessfullyCreatedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan dashboard ringkasan order terbaru milik user.
     */
    public function dashboard()
    {
        $orders = Auth::user()->orders()->with('latestDetail')->latest()->take(5)->get();
        return view('user.dashboard', compact('orders'));
    }

    /**
     * Menampilkan daftar semua order milik user dengan paginasi.
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with('latestDetail')->latest()->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Menampilkan form untuk membuat order baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('user.orders.create', compact('categories'));
    }

    /**
     * Menyimpan order baru, men-dispatch notifikasi, dan menyiapkan link WhatsApp.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_type' => 'required|string|in:Residensial,Bisnis',
            'property_type' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'address' => 'required|string|max:1000',
            'email' => 'nullable|email|max:255',
            'design_type' => 'nullable|array',
            'room_count' => 'nullable|string',
            'business_needs' => 'nullable|string',
            'company_name' => 'nullable|string',
            'project_value' => 'nullable|string',
            'area_size' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        // 1. Siapkan data HANYA untuk tabel 'orders' (data awal & tidak berubah)
        $orderData = [
            'user_id' => $user->id,
            'order_date' => now(),
            'status' => 'pending',
            'client_type' => $validated['client_type'],
            'property_type' => $validated['property_type'],

            // PEMETAAN YANG BENAR: dari 'name' form ke 'contact_name' database
            'contact_name' => $validated['name'],
            'contact_phone' => '+62' . ltrim($validated['phone'], '0'),
            'contact_email' => $validated['email'] ?? null,

            'province' => $validated['province'],
            'city' => $validated['city'],
            'district' => $validated['district'],
            'full_address' => $validated['address'],
            'notes' => $validated['notes'] ?? null,

            // Field spesifik yang mungkin ada atau tidak
            'design_type' => $validated['design_type'] ?? null,
            'room_count' => $validated['room_count'] ?? null,
            'business_needs' => $validated['business_needs'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
            'project_value' => $validated['project_value'] ?? null,
            'area_size' => $validated['area_size'] ?? null,
        ];

        // 2. Buat entri Order utama
        $order = Order::create($orderData);

        // 3. Siapkan data untuk 'order_details' pertama (data kontak, alamat, & status awal)
        $detailData = [
            'status' => 'pending',
            'progress_description' => 'Pesanan Anda telah kami terima dan sedang menunggu untuk direview oleh tim kami.',
        ];

        // 4. Buat entri OrderDetail pertama
        $order->details()->create($detailData);

        $adminUsers = User::role('admin')->get();
        if ($adminUsers->isNotEmpty()) {
            Notification::send($adminUsers, new NewOrderForAdminNotification($order));
        }

        $user->notify(new OrderSuccessfullyCreatedNotification($order));

        $message = "*PERMINTAAN KONSULTASI BARU*\n\n";
        $message .= "*Nama:* " . $validated['name'] . "\n";
        $message .= "*No. WA:* " . '+62' . ltrim($validated['phone'], '0') . "\n\n";

        if (!empty($validated['company_name'])) {
            $message .= "*Nama Perusahaan:* " . $validated['company_name'] . "\n";
        }

        $message .= "*Tipe Klien:* " . $validated['client_type'] . "\n";
        $message .= "*Tipe Properti:* " . $validated['property_type'] . "\n";

        if (!empty($validated['design_type']) && is_array($validated['design_type'])) {
            $designTypes = implode(', ', $validated['design_type']);
            $message .= "*Tipe Interior:* " . $designTypes . "\n";
        }

        if (!empty($validated['room_count'])) {
            $message .= "*Jumlah Ruangan:* " . $validated['room_count'] . "\n";
        }

        $message .= "*Lokasi:* " . $validated['district'] . ", " . $validated['city'] . "\n\n";

        if (!empty($validated['notes'])) {
            $message .= "*Catatan Tambahan:*\n" . $validated['notes'];
        }

        $adminWhatsappNumber = env('ADMIN_WHATSAPP_NUMBER', '6281703799099');
        $whatsappUrl = "https://wa.me/{$adminWhatsappNumber}?text=" . urlencode($message);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan Anda berhasil dikirim! Tim kami akan segera menghubungi Anda via WhatsApp.',
            'whatsapp_url' => $whatsappUrl
        ]);
    }

    /**
     * Menampilkan detail satu order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('details');
        return view('user.orders.show', compact('order'));
    }

    /**
     * Menangani pembatalan order oleh user.
     */
    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order);
        $validated = $request->validate(['cancellation_reason' => 'required|string|min:10|max:500']);

        $order->details()->create([
            'status' => 'cancelled',
            'progress_description' => 'Pesanan dibatalkan oleh pengguna. Alasan: ' . $validated['cancellation_reason']
        ]);

        $order->cancellation_reason = $validated['cancellation_reason'];
        $order->save();

        $adminUsers = User::role('admin')->get();
        if ($adminUsers->isNotEmpty()) {
            Notification::send($adminUsers, new OrderCancelledByUserNotification($order));
        }

        return redirect()->route('user.orders.index')->with('success', 'Pemesanan Anda telah berhasil dibatalkan.');
    }
}