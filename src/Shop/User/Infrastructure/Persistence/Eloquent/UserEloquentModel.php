<?php

declare(strict_types=1);

namespace Src\Shop\User\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 *
 *
 * @property string $id
 * @property string $customer_id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserEloquentModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserEloquentModel extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    public $incrementing = false;

    protected $connection = 'mysql-laravel';
    protected $table = 'users';
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
