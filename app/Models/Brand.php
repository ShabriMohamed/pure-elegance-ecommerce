<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'background_path',
        'tagline',
        'accent_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Brand $brand) {
            if (blank($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Products carry a plain `brand` string, so this is the join key between the
     * catalogue and a brand's presentation assets.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'brand', 'name');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    public function getBackgroundUrlAttribute(): ?string
    {
        return $this->background_path ? asset('storage/' . $this->background_path) : null;
    }

    /**
     * Two-letter monogram used whenever no logo has been uploaded yet.
     */
    public function getMonogramAttribute(): string
    {
        return static::monogramFor($this->name);
    }

    /**
     * Initials for any brand name — shared so catalogue brands without a Brand row
     * render the same monogram as registered ones ("Calvin Klein" -> "CK", not "CA").
     */
    public static function monogramFor(?string $name): string
    {
        $words = preg_split('/\s+/', trim((string) $name)) ?: [];

        return strtoupper(
            mb_substr($words[0] ?? '', 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : '')
        );
    }

    /**
     * Deterministic accent for a brand name that has no Brand row yet, so the
     * storefront fallback matches what the model would produce.
     */
    public static function accentFor(?string $name): string
    {
        $hue = crc32((string) $name) % 360;

        return "hsl({$hue} 38% 26%)";
    }

    /**
     * Deterministic accent so a brand without an uploaded background still gets a
     * distinct, stable gradient rather than a random or identical one each render.
     */
    public function getAccentAttribute(): string
    {
        if (filled($this->accent_color)) {
            return $this->accent_color;
        }

        return static::accentFor($this->name);
    }
}
