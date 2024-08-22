<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class);
    // }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
