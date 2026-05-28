<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
 class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'thumbnail', 'excerpt',
        'content', 'category', 'author', 'status'
    ];

    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = self::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->title) . '-' . time();
        });
    }
}