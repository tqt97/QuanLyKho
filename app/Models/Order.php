<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'uuid',
        // 'customer_name',
        // 'customer_phone',
        'customer_id',
        'bonus_id',
        'status',
        'total_price',
        'notes',
        // 'is_buy'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        // 'is_buy' => 'boolean',
    ];

    /** @return BelongsTo<Customer,self> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** @return HasMany<OrderItem> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeTotal($query, $total)
    {
        return $query->where('total', $total);
    }

    public function scopeTotalLessThan($query, $total)
    {
        return $query->where('total', '<', $total);
    }

    public function scopeTotalGreaterThan($query, $total)
    {
        return $query->where('total', '>', $total);
    }

    public function scopeTotalBetween($query, $min, $max)
    {
        return $query->whereBetween('total', [$min, $max]);
    }

    public function scopeTotalNotBetween($query, $min, $max)
    {
        return $query->whereNotBetween('total', [$min, $max]);
    }

    public function scopeTotalIn($query, $totals)
    {
        return $query->whereIn('total', $totals);
    }

    public function scopeTotalNotIn($query, $totals)
    {
        return $query->whereNotIn('total', $totals);
    }

    public function scopeTotalNull($query)
    {
        return $query->whereNull('total');
    }
}
