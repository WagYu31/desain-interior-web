<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mengambil 7 notifikasi terbaru milik pengguna.
     * Laravel secara otomatis menyertakan 'read_at' saat di-serialize ke JSON.
     */
    public function getLatest()
    {
        if (!Auth::check()) {
            return response()->json(['notifications' => []]);
        }

        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(7) // Ambil 7 notifikasi teratas
            ->get();

        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Menandai semua notifikasi yang belum dibaca sebagai sudah dibaca.
     */
    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Not authenticated'], 401);
        }

        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }
}