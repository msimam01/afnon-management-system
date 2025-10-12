<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - AFNEN</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Custom Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        .animate-slide-up { animation: slideUp 0.5s ease-out; }
        .animate-shake { animation: shake 0.5s ease-in-out; }
    </style>

    <style>
        /* Simplified styles - removed heavy animations */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #0ea5e9 100%);
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 50%, #0284c7 100%);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Simplified Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-red-300 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg border-b border-red-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-gradient-to-br from-red-600 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Payment Failed</h1>
                        <p class="text-xs text-red-600 dark:text-red-400 font-medium">{{ $application->reference_number }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="darkModeToggle" aria-label="Toggle dark mode"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-gray-600 hover:text-red-600 dark:hover:text-red-400">
                        <i id="sunIcon" class="fas fa-sun hidden dark:block"></i>
                        <i id="moonIcon" class="fas fa-moon block dark:hidden"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Error Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-red-500 to-orange-600 rounded-full shadow-lg mb-6">
                    <i class="fas fa-times text-white text-4xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Payment <span class="text-red-600 dark:text-red-400">Failed</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    We encountered an issue processing your payment. Please try again or contact support for assistance.
                </p>
            </div>

            <!-- Error Details Card -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg shadow-2xl rounded-3xl overflow-hidden border border-red-100 dark:border-gray-700 mb-8">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-circle text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Details</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Application Reference</p>
                                <p class="font-mono font-semibold text-gray-900 dark:text-white">{{ $application->reference_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Farmer Name</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $application->farmer->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Season</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $application->season->name }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
                                <p class="text-2xl font-bold text-red-600 dark:text-red-400">₦{{ number_format($application->monetaryReturn->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Attempted At</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ now()->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <div class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded-full text-sm font-medium inline-block">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Failed
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    @if(isset($error) && $error)
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-red-600 mr-3 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-red-800 dark:text-red-200 mb-1">Error Details</p>
                                <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('farmer.payment.lookup') }}"
                           class="btn-primary px-6 py-3 text-white font-semibold rounded-lg text-center flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Try Again
                        </a>
                        <a href="{{ route('farmer.payment.index') }}"
                           class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-center flex items-center justify-center">
                            <i class="fas fa-home mr-2"></i>
                            Back to Portal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting Tips -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-l-4 border-yellow-400 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-yellow-600 mr-4 mt-1"></i>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Common Issues & Solutions</p>
                        <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                            <li>• Check your internet connection and try again</li>
                            <li>• Ensure you have sufficient funds in your account</li>
                            <li>• Verify your card details are correct</li>
                            <li>• Try using a different payment method</li>
                            <li>• Contact your bank if the issue persists</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Support Information -->
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Still having trouble? Our support team is here to help
                </p>
                <div class="flex justify-center space-x-6">
                    <a href="tel:+2348000000000" class="flex items-center text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        <i class="fas fa-phone mr-2"></i>
                        <span class="text-sm font-medium">Call Support</span>
                    </a>
                    <a href="mailto:support@afnon.com" class="flex items-center text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        <i class="fas fa-envelope mr-2"></i>
                        <span class="text-sm font-medium">Email Support</span>
                    </a>
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
    </script>
</body>
</html>
