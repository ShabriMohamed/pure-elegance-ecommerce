<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') · Pure Elegance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --gold: #C89B3C; --black: #0B0B0B; --muted: #999; }
        * { margin: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #FAFAFA; color: var(--black);
               min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; text-align: center; }
        .wrap { max-width: 480px; }
        .code { font-family: 'Playfair Display', serif; font-size: clamp(4rem, 18vw, 8rem); font-weight: 700;
                line-height: 1; background: linear-gradient(90deg, var(--black), var(--gold)); -webkit-background-clip: text;
                background-clip: text; -webkit-text-fill-color: transparent; }
        .title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 600; margin: 8px 0 12px; }
        .msg { color: var(--muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 28px; }
        .btn { display: inline-block; background: var(--black); color: #fff; text-decoration: none;
               padding: 14px 32px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; letter-spacing: 1.5px;
               text-transform: uppercase; transition: background .25s; }
        .btn:hover { background: var(--gold); }
        .brand { margin-top: 40px; font-size: 0.7rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); font-weight: 600; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="code">@yield('code')</div>
        <h1 class="title">@yield('title', 'Something went wrong')</h1>
        <p class="msg">@yield('message', 'An unexpected error occurred.')</p>
        <a href="{{ url('/') }}" class="btn">Back to Home</a>
        <div class="brand">Pure Elegance</div>
    </div>
</body>
</html>
