<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users (role: user).
     */
    public function index(Request $request)
    {
        $query = User::role('user')->withCount('orders');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        $allowedSorts = ['name', 'email', 'created_at', 'orders_count'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display user detail.
     */
    public function show(User $user)
    {
        $user->load(['orders.latestDetail', 'orders.payments']);

        $totalOrders = $user->orders->count();
        $completedOrders = $user->orders->filter(fn($o) => $o->latestDetail?->status === 'completed')->count();
        $totalSpent = $user->orders->flatMap->payments->where('status', 'success')->sum('gross_amount');

        return view('admin.users.show', compact('user', 'totalOrders', 'completedOrders', 'totalSpent'));
    }

    /**
     * Show reset password form for a user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user password (reset).
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Password user {$user->name} berhasil direset.");
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }
}
