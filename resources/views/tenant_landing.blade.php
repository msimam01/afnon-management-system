@php
    $centralDomains = ['localhost', '127.0.0.1', 'afnon.com']; // Add your real central domains here
    $host = request()->getHost();
    $isCentral = in_array($host, $centralDomains);
@endphp

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>North East Commodity Distribution Associations (NECAS)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        };
    </script>
</head>

<body class="h-full bg-white text-gray-800 dark:bg-gray-900 dark:text-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700 fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900 dark:text-white">AFNON</span>
            </div>
            <div class="space-x-6 flex items-center">
                <a href="#about" class="hover:text-emerald-600 font-medium">About</a>
                <a href="#how-it-works" class="hover:text-emerald-600 font-medium">How It Works</a>
                <a href="#eligibility" class="hover:text-emerald-600 font-medium">Eligibility</a>
                <a href="#contact" class="hover:text-emerald-600 font-medium">Contact</a>
                <a href="https://necas.com.ng" target="_blank"
                    class="text-sm font-medium bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition">
                    Visit Official Site
                </a>
                @if ($isCentral)
                    {{-- Central login --}}
                    <a href="{{ route('central.login') }}" class="text-emerald-600 font-semibold hover:underline">
                        Super Admin Login
                    </a>
                @else
                    {{-- Tenant login --}}
                    <a href="{{ url('/login') }}" class="text-emerald-600 font-semibold hover:underline">
                        Tenant Login
                    </a>
                @endif

            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-24 pb-16 bg-gray-100 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white sm:text-5xl">
                    Empowering Nigerian Farmers
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-2xl">
                    Apply for seasonal agricultural loans through NECAS to grow your productivity and improve food
                    security.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="/apply"
                        class="bg-emerald-600 text-white px-6 py-3 rounded-md hover:bg-emerald-700 text-base font-medium">
                        Apply Now
                    </a>
                    <a href="https://necas.com.ng" target="_blank"
                        class="border border-emerald-600 text-emerald-600 px-6 py-3 rounded-md hover:bg-emerald-50 text-base font-medium">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="mt-8 lg:mt-0">
                <img src="https://images.pexels.com/photos/2132250/pexels-photo-2132250.jpeg?auto=compress&cs=tinysrgb&w=1920"
                    alt="Nigerian farmers in field" class="rounded-lg shadow-lg w-full h-auto object-cover">

                {{-- <img src="https://source.unsplash.com/600x400/?farmer,agriculture,nigeria" alt="Farmer Image" class="rounded-lg shadow-lg w-full h-auto object-cover"> --}}
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
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">About NECAS</h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                    NECAS (North East Commodity Association) is a private-sector-led initiative that provides support to
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
            <p class="mt-2 text-gray-700 dark:text-gray-200">Email: <a href="mailto:support@necas.gov.ng"
                    class="text-emerald-600 hover:underline">support@necas.gov.ng</a></p>
            <p class="mt-1 text-gray-700 dark:text-gray-200">Phone: <a href="tel:+23494615000"
                    class="text-emerald-600 hover:underline">+234 9 461 5000</a></p>
            <p class="mt-1 text-gray-700 dark:text-gray-200">Website: <a href="https://necas.com.ng" target="_blank"
                    class="text-emerald-600 hover:underline">www.necas.com.ng</a></p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-sm py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>© {{ date('Y') }} North East Commodity Distribution Associations (NECAS). All rights reserved.</p>
            <p class="mt-2">Visit: <a href="https://necas.com.ng"
                    class="text-emerald-400 hover:underline">necas.com.ng</a></p>
        </div>
    </footer>

</body>

</html>
