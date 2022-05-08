<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product_info(){
        return $this->belongsTo(ProductInfo::class, 'product_info_id', 'id');
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function rating(){
        return $this->hasMany(ProductRating::class, 'product_id', 'id');
    }
}
