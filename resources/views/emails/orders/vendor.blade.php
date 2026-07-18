@extends('emails.layout')

@section('kicker', 'New Order Received')
@section('preview', 'New order ' . $order->order_number . ' — ' . money($order->total))

@section('email_content')
    <div style="font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:#A16207;font-weight:700;">New order received</div>
    <h1 style="font-size:22px;margin:6px 0 20px;color:#16130f;font-weight:700;">{{ $order->order_number }} &middot; {{ money($order->total) }}</h1>

    <div style="background:#faf7f2;border-radius:10px;padding:16px 18px;margin-bottom:22px;">
        <div style="font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:#6f6a63;font-weight:700;margin-bottom:8px;">Customer</div>
        <div style="font-size:14px;color:#2b2b2b;line-height:1.8;">
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->customer_phone }} &middot; {{ $order->customer_email }}<br>
            {{ $order->delivery_address }}@if($order->city), {{ $order->city }}@endif @if($order->postal_code) {{ $order->postal_code }}@endif
        </div>
        @if($customerChatUrl)
        <table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:14px;">
            <tr><td style="border-radius:8px;background:#25D366;">
                <a href="{{ $customerChatUrl }}" style="display:inline-block;padding:10px 20px;color:#ffffff;font-size:13px;font-weight:600;text-decoration:none;">Message {{ \Illuminate\Support\Str::of($order->customer_name)->before(' ') ?: 'customer' }} on WhatsApp</a>
            </td></tr>
        </table>
        @endif
    </div>

    @if($order->notes)
    <div style="background:#fff8e8;border-left:3px solid #c89b3c;border-radius:6px;padding:12px 14px;margin-bottom:22px;font-size:13px;color:#6f5a2a;line-height:1.6;">
        <strong>Note from customer:</strong> {{ $order->notes }}
    </div>
    @endif

    @include('emails.partials.items')

    <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:26px auto 0;">
        <tr><td style="border-radius:10px;background:#16130f;">
            <a href="{{ $adminUrl }}" style="display:inline-block;padding:14px 32px;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;letter-spacing:0.5px;">Manage order in admin &rarr;</a>
        </td></tr>
    </table>
@endsection
