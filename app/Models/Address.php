<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
      protected $fillable = [
        'user_id',
        'name',
        'phone',
        'zip',
        'state',
        'city',
        'country',
        'address',
        'locality',
        'landmark',
        'is_default',
    ];
}
