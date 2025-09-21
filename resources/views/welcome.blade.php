@php
    use App\Models\Setting;

    $centralDomains = ['localhost', '127.0.0.1', 'afnon.com'];
    $host = request()->getHost();
    $isCentral = in_array($host, $centralDomains);

    $tenant = null;
    $setting = null;

    if ($isCentral) {
        // Central settings (still stored in central DB settings table)
        $setting = Setting::first();
    } else {
        $tenant = \App\Models\SuperAdmin\Tenant::whereHas('domains', function ($q) use ($host) {
            $q->where('domain', $host);
        })->first();

        if ($tenant) {
            // Switch to tenant DB
            tenancy()->initialize($tenant);

            // Tenant settings (logo, phone, email, address, etc.)
            $setting = Setting::first();
        }
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isCentral ? ($setting->name ?? 'AFNON - Empowering Nigerian Farmers') : ((ucfirst($tenant?->id) ?? 'Unknown') . ' Portal') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
{!! ToastMagic::styles() !!}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 1s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-ring': 'pulseRing 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite',
                        'gradient-xy': 'gradientXY 15s ease infinite',
                        'text-shimmer': 'textShimmer 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '33%': { transform: 'translateY(-15px) rotate(1deg)' },
                            '66%': { transform: 'translateY(5px) rotate(-1deg)' }
                        },
                        pulseRing: {
                            '0%': { transform: 'scale(0.33)' },
                            '40%, 50%': { opacity: '1' },
                            '100%': { opacity: '0', transform: 'scale(1.03)' }
                        },
                        gradientXY: {
                            '0%, 100%': { 'background-size': '400% 400%', 'background-position': 'left center' },
                            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' }
                        },
                        textShimmer: {
                            '0%': { 'background-position': '0% 50%' },
                            '50%': { 'background-position': '100% 50%' },
                            '100%': { 'background-position': '0% 50%' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        html { scroll-behavior: smooth; }

        /* Custom gradient background */
        .gradient-bg {
            background: linear-gradient(-45deg, #065f46, #10b981, #059669, #047857);
            background-size: 400% 400%;
            animation: gradientXY 15s ease infinite;
        }

        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Text shimmer effect */
        .text-shimmer {
            background: linear-gradient(45deg, #10b981, #34d399, #6ee7b7, #10b981);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textShimmer 3s ease-in-out infinite;
        }

        /* Floating elements */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.05));
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        /* Pulse ring animation */
        .pulse-ring::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 100%;
            height: 100%;
            border: 3px solid #10b981;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: pulseRing 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        }

        /* Enhanced card hover effects */
        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.3);
        }

        /* Statistics counter animation */
        .stat-number {
            font-variant-numeric: tabular-nums;
        }

        /* Smooth parallax effect */
        .parallax {
            transform: translateZ(0);
            will-change: transform;
        }

        /* Navigation enhancements */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 50%;
            background: linear-gradient(90deg, #10b981, #34d399);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Button enhancements */
        .btn-primary {
            background: linear-gradient(135deg, #10b981, #059669);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }

        /* Section dividers */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #10b981, transparent);
        }

        /* Mobile menu enhancements */
        .mobile-menu {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>

<body class="bg-white text-gray-900 font-sans">
    <!-- Top Bar -->
    <div class="bg-emerald-800 text-white py-2 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center text-sm">
            <div class="flex items-center gap-4 mb-2 sm:mb-0">
                @if ($setting)
                <span class="flex items-center gap-1">
                    <i class="fas fa-map-marker-alt text-emerald-300"></i>
                    {{ $setting->address ?? 'Nigeria' }}
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-phone text-emerald-300"></i>
                    <a href="tel:{{ $setting->phone }}" class="hover:text-emerald-300 transition-colors">{{ $setting->phone }}</a>
                </span>
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1">
                    <i class="fas fa-envelope text-emerald-300"></i>
                    <a href="mailto:{{ $setting->email }}" class="hover:text-emerald-300 transition-colors">{{ $setting->email ?? 'info@afnon.com.ng' }}</a>
                </span>
                @endif
                <div class="flex gap-2">
                    @if($setting && $setting->facebook_url)
                    <a href="{{ $setting->facebook_url }}" target="_blank" class="text-emerald-300 hover:text-white transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @else
                    <a href="#" class="text-emerald-300 hover:text-white transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif

                    @if($setting && $setting->twitter_url)
                    <a href="{{ $setting->twitter_url }}" target="_blank" class="text-emerald-300 hover:text-white transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @else
                    <a href="#" class="text-emerald-300 hover:text-white transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @endif

                    @if($setting && $setting->instagram_url)
                    <a href="{{ $setting->instagram_url }}" target="_blank" class="text-emerald-300 hover:text-white transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @else
                    <a href="#" class="text-emerald-300 hover:text-white transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-lg shadow-lg sticky top-0 z-50 transition-all duration-300"
         x-data="{ open: false, scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
         :class="{ 'py-2': scrolled, 'py-4': !scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <img src="{{ asset('logo.png') }}" alt="AFNON Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-emerald-800">{{ $isCentral ? $setting->name ?? 'AFNON' : ($tenant->short_name ?? strtoupper($tenant->id)) . ' STATE CHAPTER' }}</h1>
                        <p class="text-xs text-emerald-600 font-medium">Association Of Farmers In The Northeast Of Nigeria</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="#home" class="nav-link text-gray-700 hover:text-emerald-600 font-medium">Home</a>
                    <a href="#about" class="nav-link text-gray-700 hover:text-emerald-600 font-medium">About</a>
                    <a href="#services" class="nav-link text-gray-700 hover:text-emerald-600 font-medium">Services</a>
                    <a href="#how-it-works" class="nav-link text-gray-700 hover:text-emerald-600 font-medium">How It Works</a>
                    <a href="#contact" class="nav-link text-gray-700 hover:text-emerald-600 font-medium">Contact</a>
                    <div class="flex items-center space-x-3">
                        @guest
                            <a href="{{ $isCentral ? route('central.login.form') : route('tenant.login') }}" class="text-gray-600 hover:text-emerald-600 font-medium">Login</a>
                        @endguest
                        @auth
                    @if (auth()->user()->hasRole('super-admin'))
                        <a href="{{ route('superadmin.dashboard') }}"
                            class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                            Dashboard
                        </a>
                    @elseif(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}"
                            class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                            Admin Dashboard
                        </a>
                    @elseif(auth()->user()->hasRole('agent'))
                        <a href="{{ route('agent.dashboard') }}"
                            class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                            Agent Dashboard
                        </a>
                    @endif
                @endauth
                        @if (!$isCentral)
                            <a href="{{ route('applications.create') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                                Apply Now
                            </a>
                        @endif
                    </div>
                </nav>

                <!-- Mobile menu button -->
                <button @click="open = !open" class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95"
                 class="lg:hidden mt-4 mobile-menu rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 space-y-4">
                    <a href="#home" class="block text-gray-700 hover:text-emerald-600 font-medium">Home</a>
                    <a href="#about" class="block text-gray-700 hover:text-emerald-600 font-medium">About</a>
                    <a href="#services" class="block text-gray-700 hover:text-emerald-600 font-medium">Services</a>
                    <a href="#how-it-works" class="block text-gray-700 hover:text-emerald-600 font-medium">How It Works</a>
                    <a href="#contact" class="block text-gray-700 hover:text-emerald-600 font-medium">Contact</a>
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        @guest
                        <a href="{{ $isCentral ? route('central.login.form') : route('tenant.login') }}" class="block text-gray-600 hover:text-emerald-600 font-medium">Login</a>
                        @endguest
                        @if (!$isCentral)
                        <a href="{{ route('applications.create') }}" class="btn-primary block text-center text-white px-6 py-3 rounded-lg font-semibold mb-3">
                            Apply Now
                        </a>
                        <a href="{{ route('farmer.payment.index') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 block text-center text-white px-6 py-3 rounded-lg font-semibold">
                            Make Payment
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-teal-50 min-h-screen flex items-center">
        <!-- Background decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="floating-element w-64 h-64 top-20 -left-32" style="animation-delay: 0s;"></div>
            <div class="floating-element w-96 h-96 top-1/2 -right-48" style="animation-delay: 2s;"></div>
            <div class="floating-element w-32 h-32 bottom-20 left-1/4" style="animation-delay: 4s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="animate-fade-in">
                    <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-leaf"></i>
                        Trusted by 50,000+ Farmers
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Empowering <span class="text-shimmer">Nigerian</span> Farmers
                    </h1>
                    <p class="text-xl text-gray-600 leading-relaxed mb-8 max-w-2xl">
                        Access quality agricultural inputs, seasonal loans, and modern farming techniques through our innovative platform. Join thousands of farmers growing their productivity across Nigeria.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        @if (!$isCentral)
                        <a href="{{ route('applications.create') }}" class="btn-primary text-white px-8 py-4 rounded-xl font-semibold text-lg inline-flex items-center gap-2 justify-center">
                            <i class="fas fa-rocket"></i>
                            Start Your Application
                        </a>
                        <a href="{{ route('farmer.payment.index') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg inline-flex items-center gap-2 justify-center transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-credit-card"></i>
                            Make Payment
                        </a>
                        @endif
                        <a href="#how-it-works" class="border-2 border-emerald-600 text-emerald-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-emerald-50 transition-all duration-300 inline-flex items-center gap-2 justify-center">
                            <i class="fas fa-play-circle"></i>
                            Learn How It Works
                        </a>
                    </div>

                    <!-- Statistics -->
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t border-gray-200">
                        <div class="text-center">
                            <div class="stat-number text-2xl font-bold text-emerald-600">50K+</div>
                            <div class="text-sm text-gray-600">Active Farmers</div>
                        </div>
                        <div class="text-center">
                            <div class="stat-number text-2xl font-bold text-emerald-600">₦2.5B+</div>
                            <div class="text-sm text-gray-600">Loans Disbursed</div>
                        </div>
                        <div class="text-center">
                            <div class="stat-number text-2xl font-bold text-emerald-600">95%</div>
                            <div class="text-sm text-gray-600">Success Rate</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative animate-slide-up">
                    <div class="relative z-10">
                        <img src="https://images.pexels.com/photos/2132250/pexels-photo-2132250.jpeg?auto=compress&cs=tinysrgb&w=1920"
                             alt="Nigerian farmers working in field"
                             class="rounded-2xl shadow-2xl w-full h-auto object-cover">
                        <!-- Play button overlay -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <button class="pulse-ring w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                <i class="fas fa-play text-emerald-600 text-2xl ml-1"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full opacity-20"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full opacity-20"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Divider -->
    <div class="section-divider"></div>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Content -->
                <div class="animate-fade-in">
                    <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-info-circle"></i>
                        About AFNON
                    </div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">
                        Leading Agricultural Innovation in Nigeria
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        AFNON (Association of Farmers in the Northeast of Nigeria) is a pioneering private-sector initiative that bridges the gap between Nigerian farmers and modern agricultural opportunities.
                    </p>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Through strategic public-private partnerships, we provide comprehensive support including seasonal inputs, accessible loans, mechanization services, and cutting-edge farming techniques to farmers across Nigeria.
                    </p>

                    <!-- Features Grid -->
                    <div class="grid sm:grid-cols-2 gap-6 mb-8">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Quality Inputs</h4>
                                <p class="text-sm text-gray-600">Premium seeds, fertilizers, and farming equipment</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Accessible Loans</h4>
                                <p class="text-sm text-gray-600">Flexible financing options for all farm sizes</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Expert Support</h4>
                                <p class="text-sm text-gray-600">Technical guidance and farming best practices</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Modern Technology</h4>
                                <p class="text-sm text-gray-600">Digital platform for seamless transactions</p>
                            </div>
                        </div>
                    </div>

                    <a href="#services" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center gap-2">
                        Explore Our Services
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Image -->
                <div class="relative animate-slide-up">
                    <img src="https://images.pexels.com/photos/1595104/pexels-photo-1595104.jpeg?auto=compress&cs=tinysrgb&w=800"
                         alt="Farmers working together"
                         class="rounded-2xl shadow-xl w-full h-auto object-cover">
                    <!-- Overlay badge -->
                    <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-sm rounded-lg px-4 py-2 shadow-lg">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-award text-emerald-600"></i>
                            <span class="text-sm font-semibold text-gray-900">Award Winning</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-fade-in">
                <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <i class="fas fa-cogs"></i>
                    Our Services
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Comprehensive Agricultural Solutions
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From financing to harvest, we support every step of your agricultural journey
                </p>
            </div>

            <!-- Services Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-coins text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Seasonal Loans</h3>
                    <p class="text-gray-600 mb-6">Access flexible financing options tailored to your farming cycle with competitive rates and farmer-friendly terms.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Low interest rates
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Flexible repayment
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Quick approval
                        </li>
                    </ul>
                </div>

                <!-- Service 2 -->
                <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-seedling text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Quality Inputs</h3>
                    <p class="text-gray-600 mb-6">Premium seeds, fertilizers, pesticides, and farming equipment sourced from trusted suppliers worldwide.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Certified seeds
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Organic fertilizers
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Modern equipment
                        </li>
                    </ul>
                </div>

                <!-- Service 3 -->
                <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-tractor text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Mechanization</h3>
                    <p class="text-gray-600 mb-6">Access modern farming machinery and equipment to increase efficiency and productivity on your farm.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Land preparation
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Harvesting services
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Equipment rental
                        </li>
                    </ul>
                </div>

                <!-- Service 4 -->
                <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Training & Support</h3>
                    <p class="text-gray-600 mb-6">Comprehensive training programs and ongoing support to help you adopt best farming practices and modern techniques.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Technical training
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Expert consultation
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            24/7 support
                        </li>
                    </ul>
                </div>

                <!-- Service 5 -->
                <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Market Access</h3>
                    <p class="text-gray-600 mb-6">Connect with buyers, access fair market prices, and secure contracts for your produce through our network.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Direct buyer connection
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Fair pricing
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Contract farming
                        </li>
                    </ul>
                </div>

                <!-- Service 6 -->
                <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Insurance Coverage</h3>
                    <p class="text-gray-600 mb-6">Protect your investment with comprehensive agricultural insurance covering weather risks and crop failures.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Weather protection
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Crop insurance
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-emerald-500"></i>
                            Quick claims
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-fade-in">
                <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <i class="fas fa-cog"></i>
                    How It Works
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Simple Process, Powerful Results
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Get started with our streamlined application process in just three easy steps
                </p>
            </div>

            <!-- Process Steps -->
            <div class="relative">
                <!-- Connection Line -->
                <div class="hidden lg:block absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-emerald-200 via-emerald-300 to-emerald-200 transform -translate-y-1/2 z-0"></div>

                <div class="grid md:grid-cols-3 gap-8 relative z-10">
                    <!-- Step 1 -->
                    <div class="text-center animate-slide-up" style="animation-delay: 0.2s;">
                        <div class="relative inline-block">
                            <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                                <i class="fas fa-file-alt text-white text-2xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-emerald-600 font-bold">1</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Apply Online</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Complete our simple online application form with your BVN, NIN, farm details, and location information. Takes less than 10 minutes.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center animate-slide-up" style="animation-delay: 0.4s;">
                        <div class="relative inline-block">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                                <i class="fas fa-check-circle text-white text-2xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-blue-600 font-bold">2</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Get Approved</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Our team reviews your application and allocates appropriate commodities based on your farm size. You'll receive SMS notification within 48 hours.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center animate-slide-up" style="animation-delay: 0.6s;">
                        <div class="relative inline-block">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                                <i class="fas fa-seedling text-white text-2xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-purple-600 font-bold">3</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Start Farming</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Collect your inputs from the assigned center, plant your crops, and return the specified quota post-harvest. It's that simple!
                        </p>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-12">
                <a href="#home" class="btn-primary text-white px-8 py-4 rounded-xl font-semibold text-lg inline-flex items-center gap-2">
                    <i class="fas fa-rocket"></i>
                    Start Your Journey Today
                </a>
            </div>
        </div>
    </section>

    <!-- Eligibility Section -->
    <section id="eligibility" class="py-20 bg-gradient-to-br from-emerald-50 to-teal-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Content -->
                <div class="animate-fade-in">
                    <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-list-check"></i>
                        Eligibility Requirements
                    </div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">
                        Are You Eligible?
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Check if you meet our simple requirements to join thousands of successful farmers in our program.
                    </p>

                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-700">Nigerian citizen with valid NIN</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-700">Valid BVN linked to your phone</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-700">Age between 18–65 years</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-700">Minimum 0.5 hectares of farmland</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-700">Reside in participating states</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-700">No outstanding debt from previous seasons</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-700">Commitment to return specified quota</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-700">Own or lease agricultural land</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-emerald-100 rounded-2xl border border-emerald-200">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-lightbulb text-emerald-600 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-emerald-800 mb-1">Good to Know</h4>
                                <p class="text-emerald-700 text-sm">
                                    Don't meet all requirements? Contact our support team to discuss alternative options and special programs available in your area.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visual Element -->
                <div class="relative animate-slide-up">
                    <div class="bg-white rounded-2xl p-8 shadow-xl">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Quick Eligibility Check</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg">
                                <span class="text-gray-700">Valid NIN</span>
                                <i class="fas fa-check-circle text-emerald-600"></i>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg">
                                <span class="text-gray-700">Active BVN</span>
                                <i class="fas fa-check-circle text-emerald-600"></i>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg">
                                <span class="text-gray-700">Farm Owner</span>
                                <i class="fas fa-check-circle text-emerald-600"></i>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg">
                                <span class="text-gray-700">18+ Years</span>
                                <i class="fas fa-check-circle text-emerald-600"></i>
                            </div>
                        </div>
                        <button class="w-full mt-6 btn-primary text-white py-3 rounded-lg font-semibold">
                            Check My Eligibility
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in">
                <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <i class="fas fa-heart"></i>
                    Success Stories
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    What Our Farmers Say
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Hear from farmers who have transformed their lives through AFNON's programs
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed italic">
                        "AFNON transformed my farming business. The quality inputs and support helped me double my yield this season. I'm forever grateful!"
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-emerald-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Aminu Kano</h4>
                            <p class="text-sm text-gray-500">Rice Farmer, Kaduna</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed italic">
                        "The loan process was so simple and fast. Within a week, I had everything I needed to start my maize farming. Amazing service!"
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-emerald-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Fatima Abdullahi</h4>
                            <p class="text-sm text-gray-500">Maize Farmer, Bauchi</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed italic">
                        "The training and ongoing support made all the difference. I learned modern techniques that increased my productivity tremendously."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-emerald-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Ibrahim Musa</h4>
                            <p class="text-sm text-gray-500">Mixed Farmer, Gombe</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in">
                <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <i class="fas fa-envelope"></i>
                    Get In Touch
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Contact Our Team
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Have questions or need assistance? Our dedicated support team is here to help you succeed.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <!-- Phone -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Phone Support</h3>
                            <p class="text-gray-600 mb-2">Call us for immediate assistance</p>
                            @if ($setting && $setting->phone)
    <a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a>
@else
    <a href="tel:00000000000">+2348000000000</a>
@endif

                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Email Support</h3>
                            <p class="text-gray-600 mb-2">Send us your questions</p>
                            @if ($setting && $setting->email)
                                <a href="mailto:{{ $setting->email }}" class="text-emerald-600 font-semibold hover:text-emerald-700">
                                    {{ $setting->email }}
                                </a>
                            @else

                                <a href="mailto:info@afnon.ng" class="text-emerald-600 font-semibold hover:text-emerald-700">
                                    info@afnon.ng
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Office Location</h3>
                            <p class="text-gray-600 mb-2">Visit our headquarters</p>
                            @if ($setting &&$setting->address)
                                <address class="text-emerald-600 font-semibold not-italic">
                                {{ $setting->address }}
                            </address>
                            @else
<address class="text-emerald-600 font-semibold not-italic">
                                123 Agricultural Way<br>
                                Abuja, FCT Nigeria
                            </address>
                            @endif
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Business Hours</h3>
                            <p class="text-gray-600">
                                Monday - Friday: 8:00 AM - 6:00 PM<br>
                                Saturday: 9:00 AM - 2:00 PM<br>
                                Sunday: Closed
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-2xl p-8 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                            <p class="text-sm font-semibold mb-2">Please correct the following errors:</p>
                            <ul class="list-disc pl-5 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                            <p class="text-sm font-semibold">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{
                        in_array(request()->getHost(), config('tenancy.central_domains', []))
                            ? route('contact.store')
                            : route('tenant.enquiries.store')
                    }}" class="space-y-6" id="enquiryForm">
                        @csrf

                        <!-- Honeypot field for spam protection -->
                        <input type="text" name="honeypot" style="display: none;" tabindex="-1" autocomplete="off">

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                                placeholder="John Doe">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                placeholder="john@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                                placeholder="+234 xxx xxx xxxx">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                            <select id="subject" name="subject" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 @error('subject') border-red-500 @enderror">
                                <option value="">Select a subject</option>
                                <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="Application Support" {{ old('subject') == 'Application Support' ? 'selected' : '' }}>Application Support</option>
                                <option value="Technical Issue" {{ old('subject') == 'Technical Issue' ? 'selected' : '' }}>Technical Issue</option>
                                <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea id="message" name="message" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 @error('message') border-red-500 @enderror"
                                placeholder="How can we help you?">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" id="submitBtn" class="w-full btn-primary text-white py-3 rounded-lg font-semibold hover:bg-emerald-600 transition-colors duration-200 relative">
                            <span id="submitText">Send Message</span>
                            <span id="submitLoader" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-fade-in">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                    Ready to Transform Your Farm?
                </h2>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto leading-relaxed">
                    Join thousands of successful farmers who have already improved their productivity and income through AFNON's comprehensive agricultural support program.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if (!$isCentral)
                        <a href="{{ route('applications.create') }}" class="bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all duration-300 inline-flex items-center gap-2 justify-center">
                        <i class="fas fa-rocket"></i>
                        Apply Now - It's Free
                    </a>
                    <a href="{{ route('farmer.payment.index') }}" class="glass text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 inline-flex items-center gap-2 justify-center">
                        <i class="fas fa-credit-card"></i>
                        Make Payment
                    </a>
                    @endif
                    @if ($setting && $setting->phone)
    <a href="tel:{{ $setting->phone }}" class="glass text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 inline-flex items-center gap-2 justify-center">
                        <i class="fas fa-phone"></i>
                        Call Us Today
                    </a>
@else
    <a href="tel:00000000000" class="glass text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 inline-flex items-center gap-2 justify-center">
                        <i class="fas fa-phone"></i>
                        Call Us Today
                    </a>
@endif


                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-8 mt-12 pt-8 border-t border-white/20">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">50,000+</div>
                        <div class="text-white/80 text-sm">Happy Farmers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">₦2.5B+</div>
                        <div class="text-white/80 text-sm">Total Disbursed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">95%</div>
                        <div class="text-white/80 text-sm">Success Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="lg:col-span-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                            <img src="{{ asset('logo.png') }}" alt="AFNON Logo" class="w-6 h-6 object-contain">
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">AFNON</h3>
                            <p class="text-xs text-emerald-400">Association Of Farmers In The Northeast Of Nigeria</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6 leading-relaxed">
                        Empowering Nigerian farmers with access to quality agricultural inputs, financing, and modern farming techniques.
                    </p>
                    <div class="flex space-x-4">
                        @if($setting && $setting->facebook_url)
                        <a href="{{ $setting->facebook_url }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @else
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif

                        @if($setting && $setting->twitter_url)
                        <a href="{{ $setting->twitter_url }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @else
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif

                        @if($setting && $setting->instagram_url)
                        <a href="{{ $setting->instagram_url }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @else
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif

                        @if($setting && $setting->linkedin_url)
                        <a href="{{ $setting->linkedin_url }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        @else
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-emerald-400">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-400 hover:text-emerald-400 transition-colors">Home</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-emerald-400 transition-colors">About Us</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-emerald-400 transition-colors">Our Services</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-emerald-400 transition-colors">How It Works</a></li>
                        <li><a href="#eligibility" class="text-gray-400 hover:text-emerald-400 transition-colors">Eligibility</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-emerald-400 transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-emerald-400">Our Services</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Seasonal Loans</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Quality Inputs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Mechanization</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Training & Support</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Market Access</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Insurance</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-emerald-400">Get In Touch</h4>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-emerald-400 mt-1"></i>
                            <div>
                                <p class="text-gray-400">123 Agricultural Way</p>
                                <p class="text-gray-400">Abuja, FCT Nigeria</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-emerald-400"></i>
                            <a href="tel:+2341234567890" class="text-gray-400 hover:text-emerald-400 transition-colors">
                                +234 123 456 7890
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-emerald-400"></i>
                            <a href="mailto:info@afnon.ng" class="text-gray-400 hover:text-emerald-400 transition-colors">
                                info@afnon.ng
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-globe text-emerald-400"></i>
                            <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">
                                www.afnon.ng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-400 text-sm mb-4 md:mb-0">
                        © 2025 AFNON (Association Of Farmers In The Northeast Nigeria). All rights reserved.
                    </div>
                    <div class="flex space-x-6 text-sm">
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-6 right-6 w-12 h-12 bg-emerald-600 text-white rounded-full shadow-lg hover:bg-emerald-700 transition-all duration-300 transform hover:scale-110 hidden z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- JavaScript -->
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scrollToTop');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.remove('hidden');
            } else {
                scrollToTopBtn.classList.add('hidden');
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Animate numbers
        function animateNumber(element, target, duration = 2000) {
            const start = 0;
            const startTime = performance.now();

            function updateNumber(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const current = Math.floor(progress * target);

                if (target >= 1000000000) {
                    element.textContent = '₦' + (current / 1000000000).toFixed(1) + 'B+';
                } else if (target >= 1000000) {
                    element.textContent = '₦' + (current / 1000000).toFixed(1) + 'M+';
                } else if (target >= 1000) {
                    element.textContent = (current / 1000).toFixed(0) + 'K+';
                } else if (target < 100) {
                    element.textContent = current + '%';
                } else {
                    element.textContent = current.toLocaleString();
                }

                if (progress < 1) {
                    requestAnimationFrame(updateNumber);
                }
            }

            requestAnimationFrame(updateNumber);
        }

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';

                    // Animate stats numbers
                    if (entry.target.classList.contains('stat-number')) {
                        const text = entry.target.textContent;
                        let target = 0;

                        if (text.includes('50K+')) target = 50000;
                        else if (text.includes('₦2.5B+')) target = 2500000000;
                        else if (text.includes('95%')) target = 95;

                        if (target > 0) {
                            animateNumber(entry.target, target);
                        }
                    }
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-scale-in, .stat-number').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(el);
        });

        // Enhanced form interactions
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        // Parallax effect for background elements
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.floating-element');

            parallaxElements.forEach((element, index) => {
                const speed = 0.5 + (index * 0.1);
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Loading animation
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
        });

        // Enquiry form submission with loader
        document.getElementById('enquiryForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');

            // Show loader
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoader.classList.remove('hidden');

            // Add loading class to button
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        });

        // Reset form loader on page load (in case of validation errors)
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');

            if (submitBtn && submitText && submitLoader) {
                // Reset button state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoader.classList.add('hidden');
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });

        // Handle success/error messages from server
        @if (session('success'))
            // Show success toast
            if (typeof ToastMagic !== 'undefined' && ToastMagic.success) {
                ToastMagic.success('{{ session('success') }}');
            } else {
                // Fallback to native alert
                alert('{{ session('success') }}');
            }
        @endif

        @if (session('error'))
            // Show error toast
            if (typeof ToastMagic !== 'undefined' && ToastMagic.error) {
                ToastMagic.error('{{ session('error') }}');
            } else {
                // Fallback to native alert
                alert('{{ session('error') }}');
            }
        @endif
    </script>

    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 bg-emerald-600 flex items-center justify-center z-50 transition-opacity duration-500">
        <div class="text-center text-white">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 mx-auto animate-bounce">
                <img src="{{ asset('logo.png') }}" alt="AFNON Logo" class="w-10 h-10 object-contain">
            </div>
            <h2 class="text-2xl font-bold mb-2">AFNON</h2>
            <p class="text-emerald-100">Loading...</p>
        </div>
    </div>

    <style>
        #loading-screen {
            opacity: 1;
            pointer-events: all;
        }

        body.loaded #loading-screen {
            opacity: 0;
            pointer-events: none;
        }

        .focused {
            transform: scale(1.02);
        }
    </style>
    {!! ToastMagic::scripts() !!}
</body>
</html>
