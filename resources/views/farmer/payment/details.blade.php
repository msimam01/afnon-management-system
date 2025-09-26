<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details - AFNON</title>
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

        .form-input {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .info-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(16, 185, 129, 0.1);
        }

        .dark .info-card {
            background: rgba(31, 41, 55, 0.9);
            border: 1px solid rgba(75, 85, 99, 0.3);
        }

        /* Loading state animations */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Button loading state */
        .btn-loading {
            position: relative;
            overflow: hidden;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Simplified Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg border-b border-emerald-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('farmer.payment.index') }}" aria-label="Go back"
                        class="mr-4 p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <div class="h-10 w-10 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-credit-card text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Payment Details</h1>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">{{ $application->reference_number }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="darkModeToggle" aria-label="Toggle dark mode"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-emerald-100 dark:hover:bg-gray-600 hover:text-emerald-600 dark:hover:text-emerald-400">
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
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg mb-4">
                    <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Payment Required
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Review your application details and proceed with payment
                </p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Application Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Farmer Information -->
                    <div class="info-card rounded-2xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Farmer Information</h3>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Full Name</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $application->farmer->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Phone Number</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $application->farmer->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Registration Number</p>
                                <p class="font-semibold text-gray-900 dark:text-white font-mono">{{ $application->farmer->registration_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Application Reference</p>
                                <p class="font-semibold text-gray-900 dark:text-white font-mono">{{ $application->reference_number }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Season Information -->
                    <div class="info-card rounded-2xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Season Information</h3>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Season</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $application->season->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Scenario</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $application->season->return_deadline ? 'Complete Loan (commodity return)' : 'Co-funded (50% upfront)' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Return Deadline</p>
                                @if($application->season->return_deadline)
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($application->season->return_deadline)->format('M d, Y') }}</p>
                                @else
                                    <p class="font-semibold text-gray-900 dark:text-white">Not required</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Commodity Breakdown -->
                    @if($paymentCalculation['calculation_method'] === 'commodity_allocations' && !empty($paymentCalculation['breakdown']))
                    <div class="info-card rounded-2xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-seedling text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Commodity Breakdown</h3>
                        </div>
                        <div class="space-y-4">
                            @foreach($paymentCalculation['breakdown'] as $commodity)
                            <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $commodity['commodity_name'] }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($commodity['allocated_quantity'], 2) }} units × ₦{{ number_format($commodity['unit_price'], 2) }}
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $commodity['price_source'] === 'market_price' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                            {{ $commodity['price_source'] === 'market_price' ? 'Market Price' : 'Base Price' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900 dark:text-white">₦{{ number_format($commodity['total_value'], 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Payment Summary -->
                <div class="lg:col-span-1">
                    <div class="info-card rounded-2xl p-6 sticky top-24">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Payment Summary</h3>
                        </div>

                        @if($application->season->return_deadline)
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-yellow-600 mr-2 mt-0.5"></i>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    This season uses commodity return. No monetary payment is required. Please return the expected quantity of your seed by the return deadline.
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Amount Due</span>
                                <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                    @if(isset($paymentCalculation['total_amount']) && $paymentCalculation['total_amount'] > 0)
                                        ₦{{ number_format($paymentCalculation['total_amount'], 2) }}
                                    @else
                                        <span class="text-red-500">Amount not calculated</span>
                                    @endif
                                </span>
                            </div>
                            @if(isset($paymentCalculation['calculation_method']))
                                @if($paymentCalculation['calculation_method'] === 'commodity_allocations')
                                <div class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Calculated from {{ count($paymentCalculation['breakdown']) }} allocated commodities with current market prices
                                </div>
                                @elseif($paymentCalculation['calculation_method'] === 'disbursed_amount_fallback')
                                <div class="text-xs text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Using disbursed amount (commodity allocations not found)
                                </div>
                                @elseif($paymentCalculation['calculation_method'] === 'no_amount_available')
                                <div class="text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-2 rounded">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    No payment amount available - Please contact support
                                </div>
                                @endif
                            @else
                                <div class="text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-2 rounded">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Payment calculation failed - Debug: {{ json_encode($paymentCalculation) }}
                                </div>
                            @endif
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded-full text-sm font-medium">
                                    @if($application->monetaryReturn)
                                        {{ ucfirst($application->monetaryReturn->status) }}
                                    @else
                                        Pending
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        @if(isset($paymentCalculation['total_amount']) && $paymentCalculation['total_amount'] > 0)
                        <form method="POST" action="{{ route('farmer.payment.initiate') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="application_id" value="{{ $application->id }}">

                            <!-- Payment Provider Selection -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-3">
                                    Choose Payment Method *
                                </label>
                                <div class="space-y-3">
                                    @foreach($paymentProviders as $providerKey => $provider)
                                    <div class="payment-provider-option">
                                        <input type="radio"
                                               id="provider_{{ $providerKey }}"
                                               name="payment_provider"
                                               value="{{ $providerKey }}"
                                               class="sr-only peer"
                                               {{ $loop->first ? 'checked' : '' }}
                                               required>
                                        <label for="provider_{{ $providerKey }}"
                                               class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 hover:border-gray-300 dark:hover:border-gray-600 transition-colors">
                                            <div class="flex items-center flex-1">
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="{{ $provider['icon'] }} text-white"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $provider['name'] }}</h4>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $provider['description'] }}</p>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-full peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>


                            <div>
                                <label for="farmer_phone" class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">
                                    Confirm Phone Number *
                                </label>
                                <input
                                    type="tel"
                                    id="farmer_phone"
                                    name="farmer_phone"
                                    required
                                    value="{{ $application->farmer->phone }}"
                                    class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    This must match your registered phone number
                                </p>
                            </div>

                            <div>
                                <label for="farmer_email" class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">
                                    Email Address (Optional)
                                </label>
                                <input
                                    type="email"
                                    id="farmer_email"
                                    name="farmer_email"
                                    placeholder="your.email@example.com"
                                    class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    For payment receipt and notifications
                                </p>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="flex items-start gap-3 pt-4">
                                <input type="checkbox" required id="payment-terms"
                                    class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="payment-terms" class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    I confirm that the information provided is accurate and I agree to proceed with the payment of
                                    <strong>₦{{ number_format($paymentCalculation['total_amount'], 2) }}</strong> for my monetary return obligation.
                                </label>
                            </div>

                            <button type="submit" id="paymentButton" class="btn-primary w-full py-4 text-white font-bold rounded-xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="paymentButtonContent">
                                    <i class="fas fa-credit-card mr-3"></i>
                                    Proceed to Payment
                                </span>
                                <span id="paymentButtonLoading" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-3"></i>
                                    Processing...
                                </span>
                            </button>
                        </form>
                        @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Payment Not Available</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                @if(isset($paymentCalculation['calculation_method']) && $paymentCalculation['calculation_method'] === 'no_amount_available')
                                    No payment amount has been calculated for this application. Please contact support for assistance.
                                @else
                                    Unable to calculate payment amount. Please contact support.
                                @endif
                            </p>
                            <a href="{{ route('farmer.payment.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Payment Lookup
                            </a>
                        </div>
                        @endif

                        @endif
                        <!-- Security Notice -->
                        <div class="mt-6 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-shield-alt text-blue-600 mr-2 mt-0.5 text-sm"></i>
                                <p class="text-xs text-blue-700 dark:text-blue-300">
                                    Your payment will be processed securely through our trusted payment gateway.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="mt-8 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-l-4 border-yellow-400 rounded-lg p-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 mr-4 mt-1"></i>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Important Payment Information</p>
                        <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                            @if($application->season->return_deadline)
                                <li>• No monetary payment is required for this season. You are required to return the expected quantity of your selected seed by the return deadline.</li>
                                <li>• Return Deadline: {{ \Carbon\Carbon::parse($application->season->return_deadline)->format('M d, Y') }}</li>
                                <li>• Contact support if you need assistance with the return process.</li>
                            @else
                                <li>• This payment covers your 50% equity for the {{ $application->season->name }} season.</li>
                                <li>• Payment is required to proceed with commodity collection.</li>
                                <li>• You will receive a payment confirmation and receipt upon successful completion.</li>
                                <li>• Contact support if you encounter any issues during payment.</li>
                            @endif
                        </ul>
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

        // Phone number validation
        document.getElementById('farmer_phone').addEventListener('input', function() {
            const originalPhone = '{{ $application->farmer->phone }}';
            const currentPhone = this.value.trim();

            if (currentPhone !== originalPhone) {
                this.classList.add('border-yellow-500', 'bg-yellow-50');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-yellow-500', 'bg-yellow-50');
                this.classList.add('border-gray-300');
            }
        });


        // Form validation and loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const phone = document.getElementById('farmer_phone').value.trim();
            const originalPhone = '{{ $application->farmer->phone }}';
            const terms = document.getElementById('payment-terms').checked;
            const paymentButton = document.getElementById('paymentButton');

            if (phone !== originalPhone) {
                e.preventDefault();
                showNotification('Phone number must match your registered number: ' + originalPhone, 'error');
                return;
            }

            if (!terms) {
                e.preventDefault();
                showNotification('Please accept the terms and conditions to proceed.', 'error');
                return;
            }

            // Prevent double submission
            if (paymentButton.disabled) {
                e.preventDefault();
                return;
            }

            // Show loading state
            toggleButtonLoading(paymentButton, true);

            // Add timeout to prevent infinite loading (30 seconds)
            setTimeout(() => {
                if (paymentButton.disabled) {
                    toggleButtonLoading(paymentButton, false);
                    showNotification('Request timed out. Please try again.', 'error');
                }
            }, 30000);
        });

        // Utility function to show/hide loading state
        function toggleButtonLoading(button, isLoading = true) {
            const buttonContent = button.querySelector('[id$="Content"]');
            const buttonLoading = button.querySelector('[id$="Loading"]');

            if (isLoading) {
                button.disabled = true;
                button.classList.add('btn-loading');
                if (buttonContent) buttonContent.classList.add('hidden');
                if (buttonLoading) buttonLoading.classList.remove('hidden');
            } else {
                button.disabled = false;
                button.classList.remove('btn-loading');
                if (buttonContent) buttonContent.classList.remove('hidden');
                if (buttonLoading) buttonLoading.classList.add('hidden');
            }
        }

        // Utility function for notifications
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-24 right-4 z-50 p-4 rounded-lg shadow-lg ${
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

            // Remove after 5 seconds
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 5000);
        }
    </script>
</body>
</html>
