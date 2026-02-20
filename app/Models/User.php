<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Collection; 
use Illuminate\Notifications\DatabaseNotification;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @property-read int|null $unread_notifications_count
 * @property-read Collection<int, ContactMessage> $contactMessages
 * @property-read int|null $contact_messages_count
 * @property-read Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @property string|null $alamat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAlamat($value)
 * @mixin \Eloquent
 */

class User extends Authenticatable implements MustVerifyEmail // Tambahkan MustVerifyEmail jika perlu
{
    use HasFactory, Notifiable, HasRoles; // Pastikan HasRoles ada

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'fcm_token',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array // Laravel 10+ style casting
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ================================================================
    // ===> TAMBAHKAN METHOD RELASI INI <===
    // ================================================================
    /**
     * Mendapatkan semua order (pemesanan) yang dimiliki oleh User.
     * Nama method HARUS sama dengan yang Anda panggil di controller.
     * Jika Anda memanggil ->orders(), maka nama methodnya harus orders().
     */
    public function orders(): HasMany // Atau nama lain jika Anda panggil dengan nama lain
    {
        // Relasi One-to-Many: Satu User memiliki banyak Order
        // Parameter kedua (opsional) adalah foreign key di tabel orders (default: user_id)
        // Parameter ketiga (opsional) adalah local key di tabel users (default: id)
        return $this->hasMany(Order::class);
    }

    /**
     * Alternatif jika Anda ingin nama method berbeda dari nama relasi.
     * Anda bisa memanggil Auth::user()->getOrders() di controller.
     */
    public function getOrders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Relasi untuk pesan (jika diperlukan)
    public function contactMessages(): HasMany
{
    return $this->hasMany(ContactMessage::class, 'user_id'); // Atau foreign key yang sesuai
}

}