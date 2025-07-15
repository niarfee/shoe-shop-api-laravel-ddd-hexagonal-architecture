<?php

declare(strict_types=1);

namespace Src\Shop\Product\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property string $id
 * @property string $product_category_id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductVariantEloquentModel> variants
 * @property-read int|null $product_variants_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductEloquentModel whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductVariantEloquentModel> $variants
 * @property-read int|null $variants_count
 * @mixin \Eloquent
 */
class ProductEloquentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $connection = 'mysql-shop';
    protected $table = 'products';
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * Get all of the variants for the ProductEloquentModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariantEloquentModel::class, 'product_id', 'id');
    }
}
