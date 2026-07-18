@extends('emails.layout')

@section('kicker', 'Order Confirmation')
@section('preview', 'Order ' . $order->order_number . ' confirmed — ' . money($order->total))

@section('email_content')
    <h1 style="font-size:22px;margin:0 0 8px;color:#16130f;font-weight:700;">Thank you, {{ \Illuminate\Support\Str::of($order->customer_name)->before(' ') ?: $order->customer_name }}!</h1>
    <p style="font-size:14px;color:#6f6a63;margin:0 0 26px;line-height:1.7;">
        We’ve received your order <strong style="color:#16130f;">{{ $order->order_number }}</strong>. Our team will contact you shortly
        @if($order->customer_phone)on {{ $order->customer_phone }} @endif to confirm delivery.
    </p>

    @include('emails.partials.items')

    <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:28px auto 4px;">
        <tr><td style="border-radius:10px;background:#16130f;">
            <a href="{{ $trackUrl }}" style="display:inline-block;padding:14px 32px;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;letter-spacing:0.5px;">Track your order &rarr;</a>
        </td></tr>
    </table>

    <div style="margin-top:26px;background:#faf7f2;border-radius:10px;padding:16px 18px;">
        <div style="font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:#A16207;font-weight:700;margin-bottom:8px;">Delivery to</div>
        <div style="font-size:13px;color:#2b2b2b;line-height:1.7;">
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->delivery_address }}@if($order->city), {{ $order->city }}@endif @if($order->postal_code) {{ $order->postal_code }}@endif<br>
            {{ $order->customer_phone }}
        </div>
    </div>
@endsection
