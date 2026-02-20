<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $position
 * @property string|null $photo_path
 * @property string|null $linkedin_url
 * @property string|null $instagram_url
 * @property string|null $whatsapp_url
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereInstagramUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereLinkedinUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamMember whereWhatsappUrl($value)
 * @mixin \Eloquent
 */
class TeamMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position',
        'photo_path',
        'linkedin_url',
        'instagram_url',
        'whatsapp_url',
        'email',
    ];

    public function assignedOrders()
    {
        return $this->belongsToMany(Order::class, 'order_team_member');
    }
    
}