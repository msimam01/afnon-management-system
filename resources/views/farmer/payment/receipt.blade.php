<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - AFNON</title>
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
                        'scale-in': 'scaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
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

        .receipt-container {
            background: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-container, .receipt-container * {
                visibility: visible;
            }
            .receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-all duration-300">
    <!-- Floating Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none no-print">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg border-b border-emerald-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-50 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('farmer.payment.index') }}" aria-label="Go back"
                        class="mr-4 p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-700 transition-all duration-200">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <div class="h-10 w-10 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center shadow-lg animate-bounce-gentle">
                        <i class="fas fa-receipt text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Payment Receipt</h1>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">{{ $monetaryReturn->tx_ref }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="window.print()" aria-label="Print receipt"
                        class="p-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-all duration-200">
                        <i class="fas fa-print text-lg"></i>
                    </button>
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
        <div class="max-w-4xl mx-auto">
            <!-- Receipt Container -->
            <div class="receipt-container rounded-3xl overflow-hidden animate-scale-in">
                <!-- Receipt Header -->
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                                <i class="fas fa-seedling text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold">AFNON</h1>
                                <p class="text-emerald-100">Agricultural Finance Network of Nigeria</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-2xl font-bold">PAYMENT RECEIPT</h2>
                            <p class="text-emerald-100">{{ now()->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Receipt Body -->
                <div class="p-8">
                    <!-- Transaction Details -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-hashtag text-emerald-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Transaction Details</h3>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Transaction Reference:</span>
                                    <span class="font-mono font-semibold">{{ $monetaryReturn->tx_ref }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Application Reference:</span>
                                    <span class="font-mono font-semibold">{{ $monetaryReturn->application->reference_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Date:</span>
                                    <span class="font-semibold">
                                        @if($monetaryReturn->verified_at)
                                            {{ $monetaryReturn->verified_at->format('M d, Y \a\t g:i A') }}
                                        @else
                                            {{ $monetaryReturn->updated_at->format('M d, Y \a\t g:i A') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Season:</span>
                                    <span class="font-semibold">{{ $monetaryReturn->application->season->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <span class="font-semibold">Online Payment</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Completed
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Farmer Information -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Farmer Information</h3>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Full Name:</span>
                                    <span class="font-semibold">{{ $monetaryReturn->application->farmer->full_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Registration Number:</span>
                                    <span class="font-mono font-semibold">{{ $monetaryReturn->application->farmer->registration_number }}</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phone Number:</span>
                                    <span class="font-semibold">{{ $monetaryReturn->application->farmer->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-money-bill-wave text-green-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Payment Summary</h3>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-semibold text-gray-700">Monetary Return Payment:</span>
                                <span class="text-3xl font-bold text-green-600">₦{{ number_format($monetaryReturn->amount, 2) }}</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center text-xl font-bold">
                                    <span>Total Amount Paid:</span>
                                    <span class="text-green-600">₦{{ number_format($monetaryReturn->amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="text-center text-gray-600">
                            <p class="mb-2">Thank you for your payment!</p>
                            <p class="text-sm">This receipt serves as proof of payment for your monetary return obligation.</p>
                            <p class="text-sm mt-2">For support, contact us at support@afnon.com or +234-800-000-0000</p>
                        </div>
                    </div>

                    <!-- QR Code or Verification -->
                    <div class="mt-6 text-center">
                        <div class="inline-block p-4 bg-gray-100 rounded-lg">
                            <p class="text-xs text-gray-500 mb-2">Verification Code</p>
                            <p class="font-mono text-sm font-semibold">{{ strtoupper(substr(md5($monetaryReturn->tx_ref), 0, 8)) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center no-print">
                <button onclick="window.print()"
                        class="btn-primary px-6 py-3 text-white font-semibold rounded-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-print mr-2"></i>
                    Print Receipt
                </button>
                <a href="{{ route('farmer.payment.index') }}"
                   class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-center transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Back to Portal
                </a>
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

        // Auto-print functionality (optional)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            setTimeout(() => {
                window.print();
            }, 1000);
        }
    </script>
</body>
</html>
