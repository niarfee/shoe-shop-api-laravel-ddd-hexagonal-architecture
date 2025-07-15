<?php

declare(strict_types=1);

namespace Src\Shop\Order\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Shop\Customer\Infrastructure\Persistence\Eloquent\CustomerEloquentModel;

/**
 *
 *
 * @property string $id
 * @property string $customer_id
 * @property int $status
 * @property float $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read CustomerEloquentModel $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderLineEloquentModel> $lines
 * @property-read int|null $order_lines_count
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderEloquentModel whereUpdatedAt($value)
 * @property-read int|null $lines_count
 * @mixin \Eloquent
 */
class OrderEloquentModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $connection = 'mysql-shop';
    protected $table = 'orders';
    protected $keyType = 'string';
    protected $fillable = [
        'status',
        'total_price',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLineEloquentModel::class, 'order_id', 'id');
    }
}
