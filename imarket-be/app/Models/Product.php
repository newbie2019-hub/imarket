<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    protected $guarded = [];
    protected $cascadeDeletes = ['product_info', 'rating'];

    public function product_info(){
        return $this->belongsTo(ProductInfo::class, 'product_info_id', 'id')->withTrashed();
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_id', 'id')->withTrashed();
    }

    public function rating(){
        return $this->hasMany(ProductRating::class, 'product_id', 'id');
    }

    public function tags(){
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }

    public function orders(){
        return $this->hasMany(OrderInfo::class, 'product_id', 'id');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A',
        'updated_at' => 'datetime:Y-m-d h:i A',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
