<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedVendor extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        $count = (int) $this->order->items->sum('quantity');

        return new Envelope(
            subject: "New order {$this->order->order_number} · " . money($this->order->total) . " ({$count} " . \Illuminate\Support\Str::plural('item', $count) . ')',
        );
    }

    public function content(): Content
    {
        // One-click reply link so the vendor can WhatsApp the customer straight from
        // the email, with a friendly order-referencing message prefilled.
        $wa = app(WhatsAppNotificationService::class);
        $store = site('site_name', config('app.name'));
        $greeting = "Hi {$this->order->customer_name}, thank you for your order {$this->order->order_number} with {$store}! ";
        $customerChatUrl = $wa->chatLink($this->order->customer_phone, $greeting);

        return new Content(
            view: 'emails.orders.vendor',
            with: [
                'order' => $this->order,
                'trackUrl' => $this->order->track_url,
                'adminUrl' => route('admin.orders.show', $this->order),
                'customerChatUrl' => $customerChatUrl,
            ],
        );
    }
}
