<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $appends = ['full_name'];

    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }
}
