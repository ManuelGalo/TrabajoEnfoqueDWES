<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'gender',
        'price',
        'images',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
    ];
    public function getSlugOptions() : SlugOptions
     {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->startSlugSuffixFrom(2)
            ->doNotGenerateSlugsOnUpdate();
    }

    public function sizes() {
        return $this->hasMany(ProductSize::class);
    }
    public function orderItems(): HasMany
    {
        
        return $this->hasMany(OrderItem::class);
    }

}
