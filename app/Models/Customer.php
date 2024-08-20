<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'gender',
        'phone',
    ];

    protected $casts = [
       //
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }

    public function scopeEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    public function scopePhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeAddress($query, $address)
    {
        return $query->where('address', $address);
    }

    public function scopeActiveOrders($query)
    {
        return $query->whereHas('orders', function ($query) {
            $query->active();
        });
    }

    public function scopeInactiveOrders($query)
    {
        return $query->whereHas('orders', function ($query) {
            $query->inactive();
        });
    }

    public function scopeTotalOrders($query, $total)
    {
        return $query->whereHas('orders', function ($query) use ($total) {
            $query->total($total);
        });
    }

    public function scopeTotalOrdersLessThan($query, $total)
    {
        return $query->whereHas('orders', function ($query) use ($total) {
            $query->totalLessThan($total);
        });
    }
}
