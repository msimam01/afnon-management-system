<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Payment Portal - AFNON</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-in-out',
                        'slide-up': 'slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1)',
                        'scale-in': 'scaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.9)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '33%': { transform: 'translateY(-10px) rotate(1deg)' },
                            '66%': { transform: 'translateY(5px) rotate(-1deg)' }
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 20px rgba(16, 185, 129, 0.3)' },
                            '100%': { boxShadow: '0 0 40px rgba(16, 185, 129, 0.6)' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(14, 165, 233, 0.1));
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .floating-element:nth-child(1) { width: 100px; height: 100px; top: 10%; left: 5%; animation-delay: 0s; }
        .floating-element:nth-child(2) { width: 150px; height: 150px; top: 60%; right: 10%; animation-delay: 2s; }
        .floating-element:nth-child(3) { width: 80px; height: 80px; bottom: 20%; left: 15%; animation-delay: 4s; }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #0ea5e9 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }

        .form-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-input:focus {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.15);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-all duration-300">
    <!-- Floating Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg border-b border-emerald-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <button onclick="history.back()" aria-label="Go back"
                        class="mr-4 p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-700 transition-all duration-200">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </button>
                    <div class="h-10 w-10 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center shadow-lg animate-bounce-gentle">
                        <i class="fas fa-credit-card text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">AFNON Payment Portal</h1>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Farmer Self-Service Payment</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="darkModeToggle" aria-label="Toggle dark mode"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-emerald-100 dark:hover:bg-gray-600 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all duration-200">
                        <i id="sunIcon" class="fas fa-sun hidden dark:block"></i>
                        <i id="moonIcon" class="fas fa-moon block dark:hidden"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12 animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg mb-6 animate-bounce-gentle">
                    <i class="fas fa-wallet text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Make Your <span class="text-emerald-600 dark:text-emerald-400">Payment</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-xl mx-auto">
                    Enter your application reference number to proceed with your monetary return payment.
                </p>
            </div>

            <!-- Payment Form -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg shadow-2xl rounded-3xl overflow-hidden border border-emerald-100 dark:border-gray-700 animate-scale-in">
                <div class="p-8">
                    <!-- Toast Messages -->
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:text-green-300 dark:border-green-700">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:text-red-300 dark:border-red-700">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:text-red-300 dark:border-red-700">
                            <p class="text-sm font-semibold mb-2">Please review the following issues:</p>
                            <ul class="list-disc pl-5 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('farmer.payment.lookup') }}" class="space-y-6">
                        @csrf

                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg mb-4">
                                <i class="fas fa-search text-white text-2xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Find Your Application</h2>
                            <p class="text-gray-600 dark:text-gray-300">Enter your application reference number to get started</p>
                        </div>

                        <div>
                            <label for="reference_number" class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-3">
                                Application Reference Number *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-hashtag text-gray-400"></i>
                                </div>
                                <input
                                    type="text"
                                    id="reference_number"
                                    name="reference_number"
                                    required
                                    value="{{ old('reference_number') }}"
                                    placeholder="e.g., AF:REF-KN-DRY-25-1234"
                                    class="form-input w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-lg font-mono"
                                    style="text-transform: uppercase;"
                                />
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                You can find this reference number on your application acknowledgment slip
                            </p>
                        </div>

                        <!-- How to Find Reference Number -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-l-4 border-blue-400 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-lightbulb text-blue-600 mr-3 mt-1"></i>
                                <div>
                                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-1">Need help finding your reference number?</p>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Your reference number was provided when you submitted your application. It starts with "AF:REF" followed by your state and season information.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full py-4 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg text-lg">
                            <i class="fas fa-search mr-3"></i>
                            Find My Application
                        </button>
                    </form>

                    <!-- Additional Help -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Need assistance? Contact our support team
                            </p>
                            <div class="flex justify-center space-x-6">
                                <a href="tel:+2348000000000" class="flex items-center text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors">
                                    <i class="fas fa-phone mr-2"></i>
                                    <span class="text-sm font-medium">Call Support</span>
                                </a>
                                <a href="mailto:support@afnon.com" class="flex items-center text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <span class="text-sm font-medium">Email Support</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-8 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-4">
                <div class="flex items-start">
                    <i class="fas fa-shield-alt text-yellow-600 mr-3 mt-1"></i>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-1">Secure Payment</p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            Your payment information is protected with bank-level security. We never store your payment details.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') html.classList.add('dark');

        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            });
        }

        // Auto-uppercase reference number input
        document.getElementById('reference_number').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const refNumber = document.getElementById('reference_number').value.trim();
            if (!refNumber) {
                e.preventDefault();
                showNotification('Please enter your application reference number.', 'error');
            }
        });

        // Utility function for notifications
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-24 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'success' ? 'bg-green-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} mr-2"></i>
                    ${message}
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(-100%)';
            }, 100);

            // Remove after 5 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }
    </script>
</body>
</html>
