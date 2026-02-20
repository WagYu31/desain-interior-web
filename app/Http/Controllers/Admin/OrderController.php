<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Notifications\OrderProgressUpdatedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'property_type' => 'nullable|string|max:255',
            'design_type' => 'nullable|array',
            'room_count' => 'nullable|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'full_address' => 'required|string|max:1000',
            'notes' => 'nullable|string',
            'final_price' => 'nullable|numeric|min:0',
            'service_description' => 'required|string|min:10',
        ]);

        $validated['status'] = 'pending';
        $validated['order_date'] = now();

        Order::create($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Pemesanan baru berhasil dibuat.');
    }

    public function show(Order $order)
    {
        $order->load('user', 'details');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $allTeamMembers = \App\Models\TeamMember::all();
        return view('admin.orders.edit', compact('order', 'allTeamMembers'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_progress,completed,cancelled',
            'progress_details' => 'nullable|string',
            'final_price' => 'nullable|numeric|min:0',
            'new_photos' => 'nullable|array',
            'new_photos.*' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:2048',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:team_members,id',
        ]);

        // 1. Siapkan data untuk OrderDetail baru
        $detailData = [
            'status' => $validated['status'],
            'progress_description' => $validated['progress_details'] ?? null,
            'final_price' => $validated['final_price'] ?? null,
            'team_member_ids' => array_map('intval', $validated['team_members'] ?? []),
        ];

        // 2. Proses upload foto baru
        $photoPaths = [];
        if ($request->hasFile('new_photos')) {
            foreach ($request->file('new_photos') as $file) {
                $path = $file->store('order-progress-photos', 'public');
                $photoPaths[] = $path;
            }
        }
        $detailData['photos'] = $photoPaths;

        // 3. Buat entri OrderDetail baru
        $newDetail = $order->details()->create($detailData);

        // 4. Kirim notifikasi ke user
        if ($order->user) {
            Notification::send($order->user, new OrderProgressUpdatedNotification($order));
        }

        return redirect()->route('admin.orders.index')->with('success', 'Progress pemesanan berhasil diperbarui.');
    }
}