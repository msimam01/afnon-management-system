@php
    use App\Models\Setting;

    $centralDomains = ['localhost', '127.0.0.1', 'afnen.com'];
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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Association Of Farmers In The Northeast Of Nigeria') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="stylesheet" href="{{asset('css/style.css')}}"> --}}
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Performance CSS -->
    <link href="{{ asset('css/performance.css') }}" rel="stylesheet">

    <!-- Global Loader Styles -->
    <style>
        .global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        .global-loader .loader {
            position: relative;
            z-index: 10000;
            background: white;
            padding: 1.5rem;
            border-radius: 50%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #10b981; /* emerald-500 */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .dark .global-loader {
            background-color: rgba(0, 0, 0, 0.7);
        }
        .dark .loader {
            border-color: #374151; /* gray-700 */
            border-top-color: #10b981; /* emerald-500 */
        }
    </style>

    <!-- Load critical scripts first, defer non-critical -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <!-- Load DataTables only when needed -->
    @stack('datatables')

    <!-- Load jQuery only when needed -->
    @stack('jquery')

    <!-- Toast and SweetAlert - load on demand -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    {!! ToastMagic::styles() !!}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        /* Match Tailwind dark mode for DataTables */
        body.dark .dataTables_wrapper {
            color: #e5e7eb;
            /* text-gray-200 */
        }

        body.dark .dataTables_wrapper table {
            background-color: #1f2937;
            /* bg-gray-800 */
            color: #e5e7eb;
        }

        body.dark .dataTables_wrapper .dataTables_length label,
        body.dark .dataTables_wrapper .dataTables_filter label,
        body.dark .dataTables_wrapper .dataTables_info,
        body.dark .dataTables_wrapper .dataTables_paginate {
            color: #d1d5db;
            /* text-gray-300 */
        }

        body.dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #d1d5db !important;
            background-color: transparent !important;
        }

        body.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #10b981 !important;
            /* emerald-500 */
            color: white !important;
            border-radius: 4px;
        }

        body.dark .dataTables_wrapper .dataTables_filter input,
        body.dark .dataTables_wrapper .dataTables_length select {
            background-color: #374151;
            /* bg-gray-700 */
            border-color: #4b5563;
            /* border-gray-600 */
            color: #f9fafb;
        }

        body.dark .dataTables_wrapper table thead {
            background-color: #374151;
            color: #f9fafb;
        }

        body.dark .dataTables_wrapper table tbody tr {
            background-color: #1f2937;
        }

        body.dark .dataTables_wrapper table tbody tr:hover {
            background-color: #374151;
        }
        .sidebar-link {
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }
    </style>

</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans transition-colors duration-200">
    @include('layouts.navbar');
    @include('layouts.sidebar');

    <!-- Main Content Area -->
    <main class="ml-0 md:ml-80 pt-16 min-h-screen pl-4 md:pl-6">
        @yield('content')
    </main>

    @include('layouts.footer')
    @stack('scripts')
    
    <!-- Global Loader with Overlay -->
    <div id="globalLoader" class="global-loader">
        <div class="loader"></div>
    </div>

    <!-- Global Loader Script -->
    <script>
        // Show loader immediately (before DOM is ready)
        (function() {
            const loader = document.getElementById('globalLoader');
            if (loader) {
                // Hide loader when page is fully loaded
                window.addEventListener('load', function() {
                    // Fade out and remove
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                });
            }
        })();

        // Handle AJAX requests if jQuery is available
        if (typeof jQuery !== 'undefined') {
            $(document).ajaxStart(function() {
                const loader = $('#globalLoader');
                loader.css({
                    'display': 'flex',
                    'opacity': '1'
                });
            });

            $(document).ajaxStop(function() {
                const loader = $('#globalLoader');
                loader.css('opacity', '0');
                setTimeout(() => {
                    loader.css('display', 'none');
                }, 300);
            });

            // Handle AJAX errors to ensure loader is hidden
            $(document).ajaxError(function() {
                const loader = $('#globalLoader');
                loader.css('opacity', '0');
                setTimeout(() => {
                    loader.css('display', 'none');
                }, 300);
            });
        }

        // Handle page transitions
        document.addEventListener('click', function(e) {
            // Check if the clicked element is a link
            let target = e.target.closest('a');
            if (target && target.href && !target.hasAttribute('data-no-loader')) {
                const loader = document.getElementById('globalLoader');
                if (loader) {
                    loader.style.display = 'flex';
                    loader.style.opacity = '1';
                }
            }
        });
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
    <!-- Load Alpine.js and other scripts with optimization -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Load Font Awesome with optimization -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    {!! ToastMagic::scripts() !!}
    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileMenuButton = document.getElementById('mobileMenuButton');

        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Handle page refresh after successful operations
        window.addEventListener('load', function() {
            // Check for success messages in URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');
            const error = urlParams.get('error');

            if (success) {
                showToast(decodeURIComponent(success), 'success');
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            if (error) {
                showToast(decodeURIComponent(error), 'error');
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // Global showToast function for consistent notifications
        window.showToast = function(message, type = 'info') {
            // Use ToastMagic if available, otherwise fallback to native
            if (typeof ToastMagic !== 'undefined' && ToastMagic.show) {
                ToastMagic.show(message, type);
                return;
            }

            // Native JavaScript fallback
            const toast = document.createElement('div');
            const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';
            const icon = type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle';

            toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${bgColor} text-white max-w-sm`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            // Remove after 5 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(full)';
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 5000);
        };
    </script>

    @stack('scripts')
</body>

</html>
