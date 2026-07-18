@php
    $store = site('site_name', config('app.name'));
    // Brand gold reads well on the dark header (7.24:1) but only hits 2.39:1 on the
    // cream footer, so light surfaces use the darker, AA-compliant gold (4.61:1).
    $gold = '#c89b3c';
    $goldInk = '#A16207';
    $ink = '#16130f';
    $contactEmail = site('contact_email');
    $contactPhone = site('contact_phone');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $store }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f1ec;font-family:-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#2b2b2b;-webkit-font-smoothing:antialiased;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">@yield('preview')</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ec;padding:24px 12px;">
        <tr><td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:100%;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,0.06);">
                <tr><td style="background:{{ $ink }};padding:28px 32px;text-align:center;">
                    <div style="color:{{ $gold }};font-size:12px;letter-spacing:5px;text-transform:uppercase;font-weight:700;">{{ $store }}</div>
                    <div style="color:#ffffff;font-size:12px;letter-spacing:1px;margin-top:6px;opacity:0.65;">@yield('kicker', 'Timeless Fashion')</div>
                </td></tr>
                <tr><td style="padding:32px;">
                    @yield('email_content')
                </td></tr>
                <tr><td style="background:#faf7f2;padding:24px 32px;text-align:center;border-top:1px solid #eeeae3;">
                    <div style="font-size:12px;color:#6f6a63;line-height:1.8;">
                        <strong style="color:{{ $ink }};letter-spacing:1px;">{{ strtoupper($store) }}</strong>@if($contactPhone) &nbsp;&middot;&nbsp; {{ $contactPhone }}@endif<br>
                        @if($contactEmail)<a href="mailto:{{ $contactEmail }}" style="color:{{ $goldInk }};text-decoration:underline;">{{ $contactEmail }}</a><br>@endif
                        <span style="color:#6f6a63;">You’re receiving this because an order was placed at {{ $store }}.</span>
                    </div>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
