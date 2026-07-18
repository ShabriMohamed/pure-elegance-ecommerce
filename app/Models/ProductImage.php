<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL for this image.
     */
    public function getUrlAttribute(): string
    {
        // Missing files fall back to the placeholder client-side (app.js onerror).
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : asset('images/placeholder.svg');
    }
}
