<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'sort',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2);
    }

    public function getFormattedSortAttribute()
    {
        return number_format($this->sort, 2);
    }

    public function scopeSort($query)
    {
        return $query->orderBy('sort');
    }

    public function scopeTotal($query)
    {
        return $query->selectRaw('sum(quantity * price) as total');
    }

    public function scopeTotalLessThan($query, $total)
    {
        return $query->having('total', '<', $total);
    }
}
