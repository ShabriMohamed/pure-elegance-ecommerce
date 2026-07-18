<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        $store = site('site_name', config('app.name'));

        return new Envelope(
            subject: "Your {$store} order {$this->order->order_number} is confirmed",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.customer',
            with: [
                'order' => $this->order,
                'trackUrl' => $this->order->track_url,
            ],
        );
    }
}
