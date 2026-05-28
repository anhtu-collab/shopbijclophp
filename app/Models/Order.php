<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'locality',
        'landmark',
        'address_id',
        'address_type',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
        'delivered_date',
        'canceled_date',
        'coupon_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction() {
        return $this->hasOne(Transaction::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
