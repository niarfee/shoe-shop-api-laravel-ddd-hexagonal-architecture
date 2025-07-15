<?php

declare(strict_types=1);

namespace Src\Shop\Product\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property string $id
 * @property string $product_id
 * @property int $size
 * @property string $color
 * @property int $stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ProductEloquentModel $product
 * @method static \Database\Factories\ProductVariantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariantEloquentModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductVariantEloquentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $connection = 'mysql-shop';
    protected $table = 'product_variants';
    protected $keyType = 'string';
    protected $fillable = [
        'stock',
    ];
}
