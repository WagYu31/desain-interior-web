<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Berikan semua izin kepada 'owner' sebelum pemeriksaan lain.
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('owner') || $user->hasRole('arsitek')) {
            return true;
        }
    }

    /**
     * Tentukan apakah user dapat melihat detail order tertentu.
     */
    public function view(User $user, Order $order): Response
    {
        return ($user->id === $order->user_id || $user->hasRole('admin'))
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat pesanan ini.');
    }

    /**
     * Tentukan apakah user (admin) dapat membuat order baru.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Tentukan apakah user (admin) dapat mengupdate order tertentu.
     */
    public function update(User $user, Order $order): Response
    {
        if (! $user->hasRole('admin')) {
            return Response::deny('Hanya admin yang dapat memperbarui pesanan.');
        }

        // PERBAIKAN: Periksa status dari 'latestDetail'.
        // Pastikan relasi 'latestDetail' sudah dimuat untuk efisiensi.
        $order->loadMissing('latestDetail');
        
        if ($order->latestDetail && in_array($order->latestDetail->status, ['completed', 'cancelled'])) {
            return Response::deny('Pesanan ini sudah final dan tidak dapat diubah lagi.');
        }

        return Response::allow();
    }
    
    /**
     * Tentukan apakah user dapat membatalkan order tertentu.
     */
    public function cancel(User $user, Order $order): Response
    {
        if ($user->id !== $order->user_id) {
            return Response::deny('Anda bukan pemilik pesanan ini.');
        }

        // PERBAIKAN: Periksa status dari 'latestDetail'.
        // Pastikan relasi 'latestDetail' sudah dimuat untuk efisiensi.
        $order->loadMissing('latestDetail');

        if (!$order->latestDetail || $order->latestDetail->status !== 'pending') {
            return Response::deny('Pesanan ini tidak dapat dibatalkan karena sudah diproses.');
        }
        
        return Response::allow();
    }
}