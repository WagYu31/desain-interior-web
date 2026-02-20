<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'progress_description',
        'final_price',
        'photos',
        'team_member_ids',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'photos' => 'array',
        'team_member_ids' => 'array',
    ];

    /**
     * Relasi: Sebuah OrderDetail dimiliki oleh SATU Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi: Mendapatkan model TeamMember berdasarkan ID yang tersimpan.
     */
    public function assignedTeam()
    {
        $ids = $this->team_member_ids ?? [];
        return TeamMember::whereIn('id', $ids)->get();
    }

    public function getTranslatedStatusAttribute(): string
    {
        // 'match' adalah "kamus" terjemahan kita.
        // $this->status akan merujuk ke nilai kolom 'status' dari database.
        return match ($this->status) {
            'pending' => 'Menunggu',
            'in_progress' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui', // Fallback jika ada status lain
        };
    }
}