@php
    $centralDomains = ['localhost', '127.0.0.1', 'afnon.com'];
    $host = request()->getHost();
    $isCentral = in_array($host, $centralDomains);

    // Example: Fetch tenant details (if using tenancy package like hyn/multi-tenant or stancl/tenancy)
    $tenant = null;
    if (! $isCentral) {
        // Replace with your actual tenant fetch logic
        $tenant = \App\Models\Tenant::where('domain', $host)->first();
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isCentral ? 'AFNEN - Empowering Nigerian Farmers' : $tenant->name ?? 'Tenant Portal' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <!-- Tenant Header -->
    @if(!$isCentral && $tenant)
        <div class="bg-emerald-700 text-white p-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($tenant->logo)
                    <img src="{{ asset('logo.png') }}" alt="AFNEN Logo" class="w-10 h-10 object-contain">
                @endif
                <span class="font-bold text-lg">{{ $tenant->name }}</span>
            </div>
            <div>
                <span class="text-sm">📍 {{ $tenant->location ?? 'Nigeria' }}</span>
            </div>
        </div>
    @endif

    <!-- Navbar -->
    <header class="bg-white shadow sticky top-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
            <h1 class="text-2xl font-bold text-emerald-800">{{ $isCentral ? $setting->name ?? 'AFNEN' : ($tenant->short_name ?? strtoupper($tenant->id)) . ' STATE CHAPTER' }}</h1>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex space-x-6">
                <a href="#about" class="text-gray-700 hover:text-emerald-700">About</a>
                <a href="#services" class="text-gray-700 hover:text-emerald-700">Services</a>
                <a href="#contact" class="text-gray-700 hover:text-emerald-700">Contact</a>
                <a href="#apply" class="bg-emerald-700 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-800">Apply Now</a>
            </nav>

            <!-- Mobile Hamburger -->
            <button class="md:hidden text-gray-700" @click="open = !open">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path :class="{'hidden': open, 'block': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'block': open, 'hidden': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <nav class="md:hidden" x-show="open" @click.away="open = false" x-transition>
            <div class="bg-white px-4 pt-2 pb-4 space-y-2 shadow-md">
                <a href="#about" class="block text-gray-700 hover:text-emerald-700">About</a>
                <a href="#services" class="block text-gray-700 hover:text-emerald-700">Services</a>
                <a href="#contact" class="block text-gray-700 hover:text-emerald-700">Contact</a>
                <a href="#apply" class="block bg-emerald-700 text-white px-4 py-2 rounded-lg shadow hover:bg-emerald-800">Apply Now</a>
            </div>
        </nav>
    </header>

    <!-- Hero -->
    <section class="pt-24 pb-16 bg-gray-100 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white sm:text-5xl">
                    Empowering Nigerian Farmers
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-2xl">
                    Hear from farmers who have transformed their lives through AFNEN's programs
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="/apply"
                        class="bg-emerald-600 text-white px-6 py-3 rounded-md hover:bg-emerald-700 text-base font-medium">
                        About AFNEN
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
                    AFNEN (Association of Farmers in the Northeast of Nigeria) is a pioneering private-sector initiative that bridges the gap between Nigerian farmers and modern agricultural opportunities.
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
                    <h3 class="text-xl font-bold">AFNEN</h3>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Contact Us</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-300">Have any questions or need assistance?</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Email Support</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">Send us an email anytime</p>
                            <a href="mailto:support@afnon.com.ng" class="text-emerald-600 hover:underline">support@afnon.com.ng</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Phone Support</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">Call us for immediate assistance</p>
                            <a href="tel:+23494615000" class="text-emerald-600 hover:underline">+234 9 461 5000</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-globe text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Website</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">Visit our main website</p>
                            <a href="https://afnon.com.ng" target="_blank" class="text-emerald-600 hover:underline">www.afnon.com.ng</a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Send us a Message</h3>

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:text-red-300 dark:border-red-700">
                            <p class="text-sm font-semibold mb-2">Please correct the following errors:</p>
                            <ul class="list-disc pl-5 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:text-green-300 dark:border-green-700">
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
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white @error('name') border-red-500 @enderror"
                                placeholder="John Doe">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white @error('email') border-red-500 @enderror"
                                placeholder="john@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white @error('phone') border-red-500 @enderror"
                                placeholder="+234 xxx xxx xxxx">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject *</label>
                            <select id="subject" name="subject" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white @error('subject') border-red-500 @enderror">
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
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message *</label>
                            <textarea id="message" name="message" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white @error('message') border-red-500 @enderror"
                                placeholder="How can we help you?">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-lg font-semibold transition-colors duration-200 relative">
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

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-sm py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>© {{ date('Y') }} North East Commodity Distribution Associations (NECAS). All rights reserved.</p>
            <p class="mt-2">Visit: <a href="https://necas.com.ng"
                    class="text-emerald-400 hover:underline">necas.com.ng</a></p>
        </div>
    </footer>
    {!! ToastMagic::scripts() !!}

    <script>
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

</body>

</html>
