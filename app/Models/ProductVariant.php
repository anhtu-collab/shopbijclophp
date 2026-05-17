<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
class ProductVariant extends Model
{
  
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'quantity'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function size() {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }
}
