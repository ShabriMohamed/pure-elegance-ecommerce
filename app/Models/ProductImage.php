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
        if ($this->image_path) {
            $path = public_path('storage/' . $this->image_path);
            if (file_exists($path)) {
                return asset('storage/' . $this->image_path);
            }
        }
        return asset('images/placeholder.jpg');
    }
}
