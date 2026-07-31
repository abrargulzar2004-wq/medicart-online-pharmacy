<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MediCart</title>
    <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
</head>
<body class="auth-page">
    <div class="auth-container">
        @yield('content')
    </div>
</body>
</html>