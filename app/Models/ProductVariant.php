<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class ProductVariant extends Model
{
    protected $fillable = [
    'product_id',
    'color',
    'size',
    'stock',
    'price'
];

public function product()
{
    return $this->belongsTo(Product::class);
}
}
