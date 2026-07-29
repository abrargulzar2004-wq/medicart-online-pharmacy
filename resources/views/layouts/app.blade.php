<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediCart - Premium Online Pharmacy')</title>
    <!-- Custom CSS Design System -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Phosphor Icons for professional iconography -->
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

    <!-- Main Navigation -->
    <header class="header">
        <div class="container header-main flex justify-between items-center">
            <a href="/" class="logo">
                <i class="ph-fill ph-pill" style="font-size: 2rem;"></i> Medi<span>Cart</span>
            </a>
            
            <nav class="nav-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('shop.index') }}">Shop Medicines</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('contact') }}">Contact</a>
            </nav>

            <div class="flex items-center gap-6">
                <!-- Search Icon -->
                <a href="{{ route('shop.index') }}" class="cart-icon"><i class="ph ph-magnifying-glass"></i></a>
                
                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="cart-icon" style="text-decoration:none;">
                    <i class="ph ph-shopping-cart"></i>
                    <span class="cart-badge">{{ session()->has('cart') ? count(session('cart')) : 0 }}</span>
                </a>
                
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Admin Panel</a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Dashboard</a>
                    @endif
                    <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; cursor:pointer; color:#CBD5E1; font-weight:600; margin-left:10px; font-size:1.25rem;"><i class="ph ph-sign-out"></i></button>
                    </form>
                @else
                    <div class="flex gap-2">
                        <a href="{{ route('auth.login') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Login</a>
                        <a href="{{ route('auth.register') }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container" style="min-height: 70vh; padding-top: 2rem; padding-bottom: 2rem;">
        @if(session('success')) 
            <div class="alert alert-success animate-fade-in"><i class="ph-fill ph-check-circle"></i> {{ session('success') }}</div> 
        @endif
        @if(session('error')) 
            <div class="alert alert-danger animate-fade-in"><i class="ph-fill ph-warning-circle"></i> {{ session('error') }}</div> 
        @endif
        @if($errors->any())
            <div class="alert alert-danger animate-fade-in">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Professional Footer -->
    <footer class="footer">
        <div class="container footer-grid">
            <div>
                <a href="/" class="logo" style="margin-bottom: 1rem;">
                    <i class="ph-fill ph-pill"></i> Medi<span>Cart</span>
                </a>
                <p>Your trusted online pharmacy for authentic medicines, healthcare products, and professional advice delivered directly to your door.</p>
                <div class="flex gap-4" style="margin-top: 1.5rem; font-size: 1.5rem;">
                    <a href="#" style="color:#94A3B8;"><i class="ph-fill ph-facebook-logo"></i></a>
                    <a href="#" style="color:#94A3B8;"><i class="ph-fill ph-twitter-logo"></i></a>
                    <a href="#" style="color:#94A3B8;"><i class="ph-fill ph-instagram-logo"></i></a>
                </div>
            </div>
            <div>
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop.index') }}">Shop All</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h4>Customer Service</h4>
                <ul class="footer-links">
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Returns & Refunds</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Prescription Guide</a></li>
                </ul>
            </div>
            <div>
                <h4>Newsletter</h4>
                <p style="margin-bottom: 1rem;">Subscribe to receive updates, access to exclusive deals, and more.</p>
                <form class="flex">
                    <input type="email" placeholder="Enter your email" class="form-control" style="border-radius: var(--radius-md) 0 0 var(--radius-md); border-right:none;" required>
                    <button type="submit" class="btn btn-primary" style="border-radius: 0 var(--radius-md) var(--radius-md) 0;">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container flex justify-between items-center">
                <p style="margin:0;">&copy; {{ date('Y') }} MediCart E-Commerce. All rights reserved.</p>
                <div class="flex gap-2" style="font-size: 2rem; color: #94A3B8;">
                    <i class="ph-fill ph-stripe-logo"></i>
                    <i class="ph-fill ph-paypal-logo"></i>
                </div>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>