<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediCart - Premium Online Pharmacy')</title>

    <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @stack('styles')
</head>
<body>

<!-- Top Bar -->
<div class="header-top">
    <div class="container flex justify-between items-center">
        <div class="flex gap-4">
            <span><i class="ph ph-phone"></i> +1 (800) 123-4567</span>
            <span><i class="ph ph-envelope-simple"></i> support@medicart.com</span>
        </div>

        <div class="flex gap-4">
            <a href="#">Track Order</a>
            <a href="#">Help Center</a>
        </div>
    </div>
</div>

<!-- Header -->
<header class="header">

    <div class="container header-main flex justify-between items-center">

        <a href="{{ route('home') }}" class="logo">
            <i class="ph-fill ph-pill" style="font-size:2rem;"></i>
            Medi<span>Cart</span>
        </a>

        <nav class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('shop.index') }}">Shop Medicines</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>

        <div class="flex items-center gap-6">

            <!-- Search -->
            <a href="{{ route('shop.index') }}" class="cart-icon">
                <i class="ph ph-magnifying-glass"></i>
            </a>

            <!-- Cart -->
            <a href="{{ route('cart.index') }}" class="cart-icon" style="text-decoration:none;">
                <i class="ph ph-shopping-cart"></i>
                <span class="cart-badge">
                    {{ session()->has('cart') ? count(session('cart')) : 0 }}
                </span>
            </a>

            @auth

                @if(Auth::user()->role === 'admin')

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
                        Admin Panel
                    </a>

                @else

                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline">
                        Dashboard
                    </a>

                @endif

                <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;color:#CBD5E1;font-size:22px;">
                        <i class="ph ph-sign-out"></i>
                    </button>
                </form>

            @else

                <div class="flex gap-2">

                    <a href="{{ route('login') }}" class="btn btn-outline">
                        Login
                    </a>

                    <a href="{{ route('auth.register') }}" class="btn btn-primary">
                        Register
                    </a>

                </div>

            @endauth

        </div>

    </div>

</header>

<!-- Main -->
<main class="container" style="min-height:70vh;padding-top:2rem;padding-bottom:2rem;">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</main>

<!-- Footer -->
<footer class="footer">

    <div class="container footer-grid">

        <div>
            <a href="{{ route('home') }}" class="logo">
                <i class="ph-fill ph-pill"></i>
                Medi<span>Cart</span>
            </a>

            <p>
                Your trusted online pharmacy for authentic medicines and healthcare products.
            </p>
        </div>

        <div>

            <h4>Quick Links</h4>

            <ul class="footer-links">

                <li><a href="{{ route('home') }}">Home</a></li>

                <li><a href="{{ route('shop.index') }}">Shop</a></li>

                <li><a href="{{ route('about') }}">About</a></li>

                <li><a href="{{ route('contact') }}">Contact</a></li>

            </ul>

        </div>

        <div>

            <h4>Customer Service</h4>

            <ul class="footer-links">

                <li><a href="#">Shipping Policy</a></li>

                <li><a href="#">Returns</a></li>

                <li><a href="#">FAQ</a></li>

            </ul>

        </div>

        <div>

            <h4>Newsletter</h4>

            <form>

                <input
                    type="email"
                    class="form-control"
                    placeholder="Email">

                <button class="btn btn-primary">
                    Subscribe
                </button>

            </form>

        </div>

    </div>

    <div class="footer-bottom">

        <div class="container">

            © {{ date('Y') }} MediCart. All Rights Reserved.

        </div>

    </div>

</footer>

@stack('scripts')

</body>
</html>