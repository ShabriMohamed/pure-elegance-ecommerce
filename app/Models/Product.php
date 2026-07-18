<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new_arrival' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Eager-load approved-review count + average rating so product grids can render
     * star ratings without an N+1 query per card.
     * Exposes: $product->reviews_count and $product->reviews_avg
     */
    public function scopeWithRatings($query)
    {
        return $query
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as reviews_avg'], 'rating');
    }

    public function scopeFeatured($query)
    {
        return $query->active()->where('is_featured', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->active()->where('is_new_arrival', true)->orderBy('created_at', 'desc');
    }

    public function scopeOnSale($query)
    {
        return $query->active()->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price');
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->is_on_sale ? (float) $this->sale_price : (float) $this->price;
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        // No per-render disk stat — a missing file falls back to the placeholder
        // client-side (img onerror handler in app.js).
        $img = $this->primaryImage;

        return ($img && $img->image_path)
            ? asset('storage/' . $img->image_path)
            : asset('images/placeholder.svg');
    }

    public function getAverageRatingAttribute(): float
    {
        // Prefer the eager-loaded aggregate (scopeWithRatings) to avoid an extra query.
        if (array_key_exists('reviews_avg', $this->attributes)) {
            return (float) ($this->attributes['reviews_avg'] ?? 0);
        }

        return (float) ($this->approvedReviews()->avg('rating') ?? 0);
    }
}
