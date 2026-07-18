<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        // Every order gets an unguessable public token for its shareable
        // order/tracking link (used in the WhatsApp card and by customers).
        static::creating(function (Order $order) {
            if (empty($order->public_token)) {
                $order->public_token = Str::random(48);
            }
        });
    }

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
     * Public, shareable order/tracking URL (token-authenticated).
     */
    public function getTrackUrlAttribute(): string
    {
        return route('order.track', $this->public_token);
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

    /**
     * Allowed forward status transitions. cancelled/refunded are terminal, so stock
     * is only ever restored once and never blindly re-deducted.
     */
    public static function allowedTransitions(): array
    {
        return [
            self::STATUS_PENDING       => [self::STATUS_WHATSAPP_SENT, self::STATUS_CONFIRMED, self::STATUS_PROCESSING, self::STATUS_CANCELLED],
            self::STATUS_WHATSAPP_SENT => [self::STATUS_CONFIRMED, self::STATUS_PROCESSING, self::STATUS_CANCELLED],
            self::STATUS_CONFIRMED     => [self::STATUS_PROCESSING, self::STATUS_SHIPPED, self::STATUS_CANCELLED],
            self::STATUS_PROCESSING    => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
            self::STATUS_SHIPPED       => [self::STATUS_DELIVERED, self::STATUS_CANCELLED],
            self::STATUS_DELIVERED     => [self::STATUS_REFUNDED],
            self::STATUS_CANCELLED     => [],
            self::STATUS_REFUNDED      => [],
        ];
    }

    /**
     * Statuses this order can move to next (excludes its current status).
     */
    public function nextStatuses(): array
    {
        return self::allowedTransitions()[$this->status] ?? [];
    }

    public function canTransitionTo(string $to): bool
    {
        if ($to === $this->status) {
            return true; // no-op save is allowed
        }

        return in_array($to, $this->nextStatuses(), true);
    }

    /**
     * Statuses in which inventory is considered committed (decremented).
     * Restoring happens when moving OUT of these into cancelled/refunded.
     */
    public function isStockCommitted(): bool
    {
        return ! in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_REFUNDED], true);
    }
}
