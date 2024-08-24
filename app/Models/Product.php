<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Traits\Search;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Search;

    protected $searchable = [
        'common_title',
        'product_title',
    ];

    protected $fillable = [
        'common_title',
        'product_title',
        'slug',
        'dosage',
        'qty_per_product',
        'original_price',
        'sell_price',
        'description',
        'image',
        'expiry_date',
    ];
    public function scopeSearch(Builder $query, string $search = ''): void
    {
        $query->where('product_title', 'like', '%' . $search . '%');
    }
    // protected $casts = [
    //     'expiry_date' => 'date:dd/mm/yyyy',
    // ];

    /** @return BelongsTo<Brand,self> */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** @return BelongsToMany<Category> */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }


    public function getUrlImage()
    {
        if(strpos($this->image, 'http') === 0 || strpos($this->image, 'https') === 0) {
            return $this->image;
        }else {
            return asset('storage/' . $this->image);
        }
    }

    public function formatPrice()
    {
        return Number::format($this->sell_price , locale:'vi');
    }

    public function getExpiryDateAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }
    // public function orders()
    // {
    //     return $this->belongsToMany(Order::class)->withPivot('quantity');
    // }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeCheap($query)
    {
        return $query->where('price', '<', 100);
    }

    public function scopeExpensive($query)
    {
        return $query->where('price', '>=', 100);
    }

    public function scopeInCategory($query, $category)
    {
        return $query->where('category_id', $category->id);
    }

    public function scopeInCategories($query, $categories)
    {
        return $query->whereIn('category_id', $categories->pluck('id'));
    }

    public function scopeInCategoryTree($query, $category)
    {
        return $query->whereIn('category_id', $category->descendants()->pluck('id'));
    }

    public function scopeInCategoriesTree($query, $categories)
    {
        return $query->whereIn('category_id', Category::descendantsOf($categories)->pluck('id'));
    }

    public function scopeInCategoryBranch($query, $category)
    {
        return $query->whereIn('category_id', $category->descendantsAndSelf()->pluck('id'));
    }
}
