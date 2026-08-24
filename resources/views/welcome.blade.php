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
    <title>
        {{ $isCentral ? $setting->name ?? 'AFNON - Empowering Nigerian Farmers' : $tenant->id . ' Portal' ?? 'Tenant Portal' }}
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">
    <!-- Header -->
    <div class="bg-emerald-700 text-white p-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            @if ($setting && $setting->logo)
                <a href="/">
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="h-16 mt-2">
                </a>
            @endif
            {{-- <span class="font-bold text-lg">
                {{ $isCentral ? ($setting->name ?? 'AFNON') : ($tenant->short_name ?? strtoupper($tenant->id)) }}
            </span> --}}
        </div>
        <div class="text-right text-sm">
            @if ($setting)
                <p>📍 {{ $setting->address ?? 'Nigeria' }}</p>
                <p>📞 <a href="tel:{{ $setting->phone }}" class="hover:underline">{{ $setting->phone }}</a></p>
                <p>✉️ <a href="mailto:{{ $setting->email }}" class="hover:underline">{{ $setting->email }}</a></p>
            @endif
        </div>
    </div>

    <!-- Navbar -->
    <header class="bg-white shadow sticky top-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
            <h1 class="text-2xl font-bold text-emerald-700">
                {{ $isCentral ? $setting->name ?? 'AFNON' : $tenant->short_name ?? strtoupper($tenant->id) }}
            </h1>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex space-x-6">
                <a href="#about" class="text-gray-700 hover:text-emerald-700">About</a>
                <a href="#services" class="text-gray-700 hover:text-emerald-700">Services</a>
                <a href="#contact" class="text-gray-700 hover:text-emerald-700">Contact</a>
                @guest
                    <a href="{{ $isCentral ? route('central.login.form') : route('tenant.login') }}"
                        class="bg-emerald-700 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-800">
                        Login
                    </a>
                @endguest

                @auth
                    @if (auth()->user()->hasRole('super-admin'))
                        <a href="{{ route('superadmin.dashboard') }}"
                            class="bg-emerald-700 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-800">
                            Dashboard
                        </a>
                    @elseif(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}"
                            class="bg-emerald-700 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-800">
                            Admin Dashboard
                        </a>
                    @elseif(auth()->user()->hasRole('agent'))
                        <a href="{{ route('agent.dashboard') }}"
                            class="bg-emerald-700 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-800">
                            Agent Dashboard
                        </a>
                    @endif
                @endauth

            </nav>

            <!-- Mobile Hamburger -->
            <button class="md:hidden text-gray-700" @click="open = !open">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path :class="{ 'hidden': open, 'block': !open }" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'block': open, 'hidden': !open }" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <nav class="md:hidden" x-show="open" @click.away="open = false" x-transition>
            <div class="bg-white px-4 pt-2 pb-4 space-y-2 shadow-md">
                <a href="#about" class="block text-gray-700 hover:text-emerald-700">About</a>
                <a href="#services" class="block text-gray-700 hover:text-emerald-700">Services</a>
                <a href="#contact" class="block text-gray-700 hover:text-emerald-700">Contact</a>
                <a href="#apply"
                    class="block bg-emerald-700 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-800">Apply
                    Now</a>
            </div>
        </nav>
    </header>

    <section class="pt-24 pb-16 bg-gray-100 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white sm:text-5xl">
                    Empowering Nigerian Farmers
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-2xl">
                    Apply for seasonal agricultural loans through {{ $setting->name ?? 'our program' }} to grow your
                    productivity and improve food security.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    @if ($isCentral)
                        <a href="#about"
                            class="border border-emerald-600 text-emerald-600 px-6 py-3 rounded-md hover:bg-emerald-50 text-base font-medium">
                            Learn More
                        </a>
                    @else
                        <a href="{{ route('applications.create') }}"
                            class="bg-emerald-600 text-white px-6 py-3 rounded-md hover:bg-emerald-700 text-base font-medium">
                            Apply Now
                        </a>
                        <a href="#about"
                            class="border border-emerald-600 text-emerald-600 px-6 py-3 rounded-md hover:bg-emerald-50 text-base font-medium">
                            Learn More
                        </a>
                    @endif
                </div>
            </div>
            <div class="mt-8 lg:mt-0">
                <img src="https://images.pexels.com/photos/2132250/pexels-photo-2132250.jpeg?auto=compress&cs=tinysrgb&w=1920"
                    alt="Nigerian farmers in field" class="rounded-lg shadow-lg w-full h-auto object-cover">
            </div>
        </div>
    </section>

    <!-- About -->
    <section id="about" class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-8 items-center">
            <div>
                <img src="https://images.pexels.com/photos/1595104/pexels-photo-1595104.jpeg?auto=compress&cs=tinysrgb&w=800"
                    alt="Farmers working together" class="rounded-lg shadow w-full">
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">About AFNON</h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                    AFNON (Association of farmers in the Northeast of Nigeria) is a private-sector-led initiative that
                    provides support to
                    Nigerian farmers including seasonal inputs, loans, and access to mechanization through
                    public-private partnerships.
                </p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white">How It Works</h2>
            <div class="mt-12 grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div
                        class="flex items-center justify-center w-16 h-16 bg-emerald-100 dark:bg-emerald-900 rounded-full mx-auto mb-4">
                        <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">1</span>
                    </div>
                    <h3 class="text-lg font-semibold">Apply</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Fill the application form with your BVN, NIN, farm
                        and location details.</p>
                </div>
                <div class="text-center">
                    <div
                        class="flex items-center justify-center w-16 h-16 bg-emerald-100 dark:bg-emerald-900 rounded-full mx-auto mb-4">
                        <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">2</span>
                    </div>
                    <h3 class="text-lg font-semibold">Get Approved</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Admin reviews and allocates commodities. You get
                        notified by SMS.</p>
                </div>
                <div class="text-center">
                    <div
                        class="flex items-center justify-center w-16 h-16 bg-emerald-100 dark:bg-emerald-900 rounded-full mx-auto mb-4">
                        <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">3</span>
                    </div>
                    <h3 class="text-lg font-semibold">Collect & Farm</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Collect from assigned center, plant, and return
                        specified quota post-harvest.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Eligibility -->
    <section id="eligibility" class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white">Eligibility</h2>
            <div class="mt-8 grid md:grid-cols-2 gap-8 items-center">
                <ul class="space-y-3 text-gray-700 dark:text-gray-300 text-lg">
                    <li>✔ Must be a Nigerian citizen (NIN required)</li>
                    <li>✔ Valid BVN linked to your phone</li>
                    <li>✔ Age between 18–65 years</li>
                    <li>✔ At least 0.5 hectares of farmland</li>
                </ul>
                <ul class="space-y-3 text-gray-700 dark:text-gray-300 text-lg">
                    <li>✔ Reside in participating states/clusters</li>
                    <li>✔ Must not owe previous seasons</li>
                    <li>✔ Willingness to return commodities or pay equivalent value</li>
                    <li>✔ Owns or leases agricultural land</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="py-16 bg-gray-100 dark:bg-gray-800">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Contact Us</h2>
            <p class="mt-4 text-gray-600 dark:text-gray-300">Have any questions or need assistance?</p>

            @if ($setting)
                <p class="mt-2 text-gray-700 dark:text-gray-200">Email:
                    <a href="mailto:{{ $setting->email }}" class="text-emerald-600 hover:underline">
                        {{ $setting->email }}
                    </a>
                </p>
                <p class="mt-1 text-gray-700 dark:text-gray-200">Phone:
                    <a href="tel:{{ $setting->phone }}" class="text-emerald-600 hover:underline">
                        {{ $setting->phone }}
                    </a>
                </p>
                <p class="mt-1 text-gray-700 dark:text-gray-200">Address:
                    <span class="text-emerald-600">{{ $setting->address }}</span>
                </p>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-sm py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>© {{ date('Y') }} {{ $setting->name ?? 'AFNON' }}. All rights reserved.</p>
        </div>
    </footer>
    {!! ToastMagic::scripts() !!}
</body>

</html>
