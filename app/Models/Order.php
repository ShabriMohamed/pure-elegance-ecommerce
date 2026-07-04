<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // Status constants matching DB enum exactly
    const STATUS_PENDING = 'pending';
    const STATUS_WHATSAPP_SENT = 'whatsapp_sent';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    protected $guarded = ['id'];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'whatsapp_sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate a unique order number (PE-XXXXXXXX format).
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'PE-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * All possible order statuses for the lifecycle.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING       => 'Pending',
            self::STATUS_WHATSAPP_SENT => 'WhatsApp Sent',
            self::STATUS_CONFIRMED     => 'Confirmed',
            self::STATUS_PROCESSING    => 'Processing',
            self::STATUS_SHIPPED       => 'Shipped',
            self::STATUS_DELIVERED     => 'Delivered',
            self::STATUS_CANCELLED     => 'Cancelled',
            self::STATUS_REFUNDED      => 'Refunded',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return static::statuses()[$this->status] ?? ucfirst($this->status);
    }
}
