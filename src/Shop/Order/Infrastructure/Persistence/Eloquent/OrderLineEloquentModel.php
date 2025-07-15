<?php

declare(strict_types=1);

namespace Src\Shop\Order\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property string $id
 * @property string $order_id
 * @property string $product_variant_id
 * @property int $units
 * @property float $unit_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read OrderEloquentModel $order
 * @method static \Database\Factories\OrderLineFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel whereProductVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel whereUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLineEloquentModel withoutTrashed()
 * @mixin \Eloquent
 */
class OrderLineEloquentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $connection = 'mysql-shop';
    protected $table = 'order_lines';
    protected $keyType = 'string';
    protected $fillable = [
        'units',
        'unit_price',
    ];
}
