<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ProductCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategoryEloquentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategoryEloquentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategoryEloquentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategoryEloquentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategoryEloquentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategoryEloquentModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategoryEloquentModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategoryEloquentModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductCategoryEloquentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $connection = 'mysql-shop';
    protected $table = 'product_categories';
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'slug',
    ];
}
