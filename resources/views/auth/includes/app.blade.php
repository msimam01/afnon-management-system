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
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>AFNON Management Software - Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {!! ToastMagic::styles() !!}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Enhanced focus styles */
        .focus-ring:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px #10b981, 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        /* Loading animation */
        .loading-dots::after {
            content: '';
            animation: loading-dots 1.5s infinite;
        }
        @keyframes loading-dots {
            0%, 20% { content: '.'; }
            40% { content: '..'; }
            60%, 100% { content: '...'; }
        }
    </style>
</head>

<body class="h-full bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 font-sans transition-all duration-300">
    <!-- Enhanced Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 dark:border-gray-700/50 fixed top-0 left-0 right-0 z-50 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <button onclick="history.back()"
                        class="mr-4 p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus-ring"
                        aria-label="Go back">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-emerald-800">{{ $isCentral ? $setting->name ?? 'AFNON' : $tenant->short_name ?? strtoupper($tenant->id) }}</h1>
                        <p class="text-xs text-emerald-600 font-medium">Agricultural Finance Network</p>
                    </div>
                </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Connection Status Indicator -->
                    <div id="connectionStatus" class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                        <div class="h-2 w-2 bg-green-500 rounded-full animate-pulse-slow"></div>
                        <span>Secure Connection</span>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 focus-ring"
                        aria-label="Toggle dark mode">
                        <svg id="sunIcon" class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg id="moonIcon" class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="animate-slide-up">
        @yield('content')
    </main>

    <!-- Enhanced Footer -->
    @include('layouts.footer')

    <!-- Security Notice Modal (Hidden by default) -->
    <div id="securityModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 animate-slide-up">
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Security Notice</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                For your security, this session will automatically expire after 15 minutes of inactivity. Please save your work regularly.
            </p>
            <button id="closeSecurityModal" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                Understood
            </button>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dark mode functionality
            const darkModeToggle = document.getElementById('darkModeToggle');
            const html = document.documentElement;

            // Check for saved theme preference or default to light mode
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            }

            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                const isDark = html.classList.contains('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');

                // Add a subtle animation feedback
                darkModeToggle.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    darkModeToggle.style.transform = 'scale(1)';
                }, 100);
            });

            // Connection status monitoring
            const connectionStatus = document.getElementById('connectionStatus');
            let isOnline = navigator.onLine;

            function updateConnectionStatus() {
                const dot = connectionStatus.querySelector('div');
                const text = connectionStatus.querySelector('span');

                if (isOnline) {
                    dot.className = 'h-2 w-2 bg-green-500 rounded-full animate-pulse-slow';
                    text.textContent = 'Secure Connection';
                    connectionStatus.className = 'flex items-center space-x-2 text-xs text-green-600 dark:text-green-400';
                } else {
                    dot.className = 'h-2 w-2 bg-red-500 rounded-full';
                    text.textContent = 'Connection Lost';
                    connectionStatus.className = 'flex items-center space-x-2 text-xs text-red-600 dark:text-red-400';
                }
            }

            window.addEventListener('online', () => {
                isOnline = true;
                updateConnectionStatus();
            });

            window.addEventListener('offline', () => {
                isOnline = false;
                updateConnectionStatus();
            });

            updateConnectionStatus();

            // Security modal functionality
            const securityModal = document.getElementById('securityModal');
            const closeSecurityModal = document.getElementById('closeSecurityModal');

            // Show security notice after 5 seconds (first visit)
            if (!localStorage.getItem('securityNoticeShown')) {
                setTimeout(() => {
                    securityModal.classList.remove('hidden');
                }, 5000);
            }

            closeSecurityModal.addEventListener('click', () => {
                securityModal.classList.add('hidden');
                localStorage.setItem('securityNoticeShown', 'true');
            });

            // Session timeout warning (13 minutes = 780000ms)
            let sessionWarningShown = false;
            setTimeout(() => {
                if (!sessionWarningShown) {
                    sessionWarningShown = true;
                    if (confirm('Your session will expire in 2 minutes due to inactivity. Click OK to stay logged in.')) {
                        // Refresh the page to extend session
                        window.location.reload();
                    }
                }
            }, 780000);

            // Enhanced keyboard navigation
            document.addEventListener('keydown', function(e) {
                // Alt + D for dark mode toggle
                if (e.altKey && e.key === 'd') {
                    e.preventDefault();
                    darkModeToggle.click();
                }

                // Escape to close modals
                if (e.key === 'Escape') {
                    if (!securityModal.classList.contains('hidden')) {
                        closeSecurityModal.click();
                    }
                }
            });

            // Add subtle animations to form elements
            const inputs = document.querySelectorAll('input, button');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                });

                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // CSRF token refresh every 10 minutes
            setInterval(() => {
                fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                    // Update all CSRF input fields
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });
                })
                .catch(error => {
                    console.warn('Failed to refresh CSRF token:', error);
                });
            }, 600000); // 10 minutes

            // Performance monitoring
            if ('performance' in window) {
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        const perfData = performance.getEntriesByType('navigation')[0];
                        if (perfData && perfData.loadEventEnd > 3000) {
                            console.warn('Page load time exceeded 3 seconds:', perfData.loadEventEnd + 'ms');
                        }
                    }, 0);
                });
            }
        });
    </script>
    {!! ToastMagic::scripts() !!}
</body>
</html>
