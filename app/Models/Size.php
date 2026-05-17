<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    // Cho phép thêm nhanh tên size vào DB thông qua Eloquent
    protected $fillable = ['name']; 
}