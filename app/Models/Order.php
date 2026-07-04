<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

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
     * Get all possible statuses for the order lifecycle.
     */
    public static function statuses(): array
    {
        return [
            'pending'    => 'Pending',
            'confirmed'  => 'Confirmed',
            'whatsapp_sent' => 'WhatsApp Sent',
            'processing' => 'Processing',
            'shipped'    => 'Shipped',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return static::statuses()[$this->status] ?? ucfirst($this->status);
    }
}
