<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
    @foreach($order->items as $item)
    <tr>
        <td width="64" style="padding:12px 0;vertical-align:top;">
            <img src="{{ optional($item->product)->primary_image_url ?? asset('images/placeholder.svg') }}" width="56" height="70" alt="" style="width:56px;height:70px;object-fit:cover;border-radius:8px;background:#f0ece5;display:block;">
        </td>
        <td style="padding:12px 0 12px 14px;vertical-align:top;">
            <div style="font-size:14px;font-weight:600;color:#2b2b2b;">{{ $item->product_name }}</div>
            @if($item->variant_info)<div style="font-size:12px;color:#6f6a63;margin-top:2px;">{{ $item->variant_info }}</div>@endif
            <div style="font-size:12px;color:#6f6a63;margin-top:3px;">Qty {{ $item->quantity }} &times; {{ money($item->unit_price) }}</div>
        </td>
        <td align="right" style="padding:12px 0;vertical-align:top;font-size:14px;font-weight:600;color:#2b2b2b;white-space:nowrap;">{{ money($item->total_price) }}</td>
    </tr>
    <tr><td colspan="3" style="border-bottom:1px solid #f0ece5;font-size:0;line-height:0;">&nbsp;</td></tr>
    @endforeach
</table>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;">
    <tr><td style="font-size:13px;color:#6f6a63;padding:3px 0;">Subtotal</td><td align="right" style="font-size:13px;color:#2b2b2b;padding:3px 0;">{{ money($order->subtotal) }}</td></tr>
    @if($order->discount_amount > 0)
    <tr><td style="font-size:13px;color:#b23b3b;padding:3px 0;">Discount{{ $order->promo_code ? ' (' . $order->promo_code . ')' : '' }}</td><td align="right" style="font-size:13px;color:#b23b3b;padding:3px 0;">- {{ money($order->discount_amount) }}</td></tr>
    @endif
    <tr><td style="font-size:13px;color:#6f6a63;padding:3px 0;">Delivery</td><td align="right" style="font-size:13px;color:#2b2b2b;padding:3px 0;">{{ $order->delivery_fee > 0 ? money($order->delivery_fee) : 'FREE' }}</td></tr>
    <tr>
        <td style="font-size:16px;font-weight:700;color:#16130f;padding:12px 0 0;border-top:2px solid #16130f;">Total</td>
        <td align="right" style="font-size:16px;font-weight:700;color:#16130f;padding:12px 0 0;border-top:2px solid #16130f;">{{ money($order->total) }}</td>
    </tr>
</table>
