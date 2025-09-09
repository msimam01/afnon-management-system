<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Already Completed - AFNON</title>
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
                    // Removed heavy animations for better performance
                }
            }
        }
    </script>

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

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Simplified Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg border-b border-blue-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('farmer.payment.index') }}" aria-label="Go back"
                        class="mr-4 p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <div class="h-10 w-10 bg-gradient-to-br from-blue-600 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Payment Completed</h1>
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ $application->reference_number }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="darkModeToggle" aria-label="Toggle dark mode"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-gray-600 hover:text-blue-600 dark:hover:text-blue-400">
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
            <!-- Info Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-500 to-emerald-600 rounded-full shadow-lg mb-6">
                    <i class="fas fa-info-circle text-white text-4xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Payment <span class="text-blue-600 dark:text-blue-400">Already Completed</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Your monetary return payment for this application has already been successfully processed.
                </p>
            </div>

            <!-- Payment Status Card -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg shadow-2xl rounded-3xl overflow-hidden border border-blue-100 dark:border-gray-700 mb-8">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Information</h2>
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
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount Paid</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">₦{{ number_format($application->monetaryReturn->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Payment Date</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $application->monetaryReturn->verified_at ? $application->monetaryReturn->verified_at->format('M d, Y \a\t g:i A') : 'Processing...' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <div class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full text-sm font-medium inline-block">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Completed
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @if($application->monetaryReturn->tx_ref)
                        <a href="{{ route('farmer.payment.receipt', $application->monetaryReturn->tx_ref) }}"
                           class="btn-primary px-6 py-3 text-white font-semibold rounded-lg text-center flex items-center justify-center">
                            <i class="fas fa-download mr-2"></i>
                            Download Receipt
                        </a>
                        @endif
                        <a href="{{ route('farmer.payment.index') }}"
                           class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-center flex items-center justify-center">
                            <i class="fas fa-home mr-2"></i>
                            Back to Portal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Information Notice -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-l-4 border-blue-400 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-4 mt-1"></i>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">Payment Status</p>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <li>• Your monetary return payment has been successfully processed</li>
                            <li>• No further payment is required for this application</li>
                            <li>• You can download your payment receipt for your records</li>
                            <li>• Contact support if you have any questions about this payment</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Support Information -->
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Questions about your payment? Contact our support team
                </p>
                <div class="flex justify-center space-x-6">
                    <a href="tel:+2348000000000" class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <i class="fas fa-phone mr-2"></i>
                        <span class="text-sm font-medium">Call Support</span>
                    </a>
                    <a href="mailto:support@afnon.com" class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
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
