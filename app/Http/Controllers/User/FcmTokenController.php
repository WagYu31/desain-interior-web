<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    /**
     * Menerima dan menyimpan FCM token dari frontend.
     */
    public function updateFcmToken(Request $request)
    {
        // Validasi bahwa request berisi 'token' dan berupa string
        $request->validate(['token' => 'required|string']);

        try {
            // Ambil pengguna yang sedang login dan update fcm_token-nya
            $request->user()->update(['fcm_token' => $request->token]);

            // Kirim respons sukses
            return response()->json(['message' => 'Token updated successfully.']);
        } catch (\Exception $e) {
            // Jika ada error, kirim respons gagal
            \Log::error('FCM Token Update Failed: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating token.'], 500);
        }
    }
}