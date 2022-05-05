<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cart_info(){
        return $this->hasMany(CartContent::class, 'cart_id', 'id');
    }
}
