<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CustomerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerEloquentModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerEloquentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $connection = 'mysql-shop';
    protected $table = 'customers';
    protected $keyType = 'string';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];
}
