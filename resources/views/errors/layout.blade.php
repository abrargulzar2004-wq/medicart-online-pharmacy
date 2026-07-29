<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MediCart</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .error-container { text-align: center; background: #fff; padding: 40px; border-radius: 6px; border: 1px solid #E2E8F0; max-width: 500px; width: 100%; }
        h1 { font-size: 80px; margin: 0; color: #2563EB; font-weight: 800; }
        h2 { font-size: 24px; margin: 10px 0 20px; color: #334155; }
        p { font-size: 16px; color: #64748B; margin-bottom: 30px; }
        .error-container a { text-decoration: none; }
    </style>
</head>
<body class="auth-page">
    <div class="error-container">
        <h1>@yield('code')</h1>
        <h2>@yield('message')</h2>
        <p>@yield('description')</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Return to Homepage</a>
    </div>
</body>
</html>
