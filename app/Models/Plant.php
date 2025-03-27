<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = ['name', 'description', 'price', 'images', 'slug', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_plant')->withPivot('quantity');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($plant) {
            if (empty($plant->slug)) {
                $plant->slug = Str::slug($plant->name);
                $baseSlug = $plant->slug;
                $counter = 1;
                while (self::where('slug', $plant->slug)->exists()) {
                    $plant->slug = "$baseSlug-$counter";
                    $counter++;
                }
            }
        });
    }
}