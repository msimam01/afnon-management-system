<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgement Slip - AFNON</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    {!! ToastMagic::styles() !!}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: { 'sans': ['Inter', 'system-ui', 'sans-serif'] },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'scale-in': 'scaleIn 0.5s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'shimmer': 'shimmer 2s infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' }
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        'shimmer': 'linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent)',
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-emerald-900 transition-all duration-500">

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-40 left-1/2 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 4s;"></div>
    </div>

    <!-- ENHANCED HEADER -->
    <div class="relative bg-gradient-to-r from-emerald-600 via-emerald-700 to-emerald-800 dark:from-emerald-800 dark:via-emerald-900 dark:to-gray-900 shadow-2xl animate-fade-in">
        <!-- Header Pattern Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>

        <div class="relative p-8 flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <!-- Left Section -->
            <div class="flex items-center space-x-6 animate-slide-up">
                <!-- Logo/Icon -->
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm animate-bounce-gentle">
                    <i class="fas fa-file-contract text-2xl text-white"></i>
                </div>

                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1 tracking-tight">
                        Acknowledgement Slip
                    </h1>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-hashtag text-emerald-200 text-sm"></i>
                        <p class="text-emerald-100 font-medium">{{ $application->reference_number }}</p>
                    </div>
                    <div class="flex items-center space-x-2 mt-1">
                        <i class="fas fa-calendar text-emerald-200 text-sm"></i>
                        <p class="text-emerald-100 text-sm">{{ now()->format('M d, Y \a\t H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Section - QR Code -->
            <div class="flex flex-col items-center mt-6 lg:mt-0 animate-scale-in" style="animation-delay: 0.3s;">
                <div class="relative">
                    <!-- QR Code Container with Glow Effect -->
                    <div class="relative p-4 bg-white rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-blue-500 rounded-2xl blur opacity-20 animate-pulse-slow"></div>
                        <div class="relative bg-white rounded-xl p-2 border border-gray-200">
                            {!! QrCode::size(100)->backgroundColor(255,255,255)
                                ->generate(url('/verify/'.$application->reference_number)) !!}
                        </div>
                    </div>

                    <!-- Scan Instruction -->
                    <div class="text-center mt-3">
                        <p class="text-white font-medium text-sm flex items-center justify-center space-x-2">
                            <i class="fas fa-qrcode animate-pulse"></i>
                            <span>Scan to Verify</span>
                        </p>
                        <p class="text-emerald-200 text-xs mt-1">Instant verification available</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-8 fill-current text-white dark:text-gray-100">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
            </svg>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4">
        <!-- Main Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 overflow-hidden animate-slide-up" style="animation-delay: 0.4s;">

            <!-- Status Badge -->
            <div class="absolute top-6 right-6 z-10">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg animate-bounce-gentle">
                    <i class="fas fa-check-circle mr-2"></i>
                    Verified
                </div>
            </div>

            <div class="p-8 lg:p-12">
                <!-- Application Details Section -->
                <div class="mb-10">
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-8 flex items-center">
                        <i class="fas fa-user-circle text-emerald-600 mr-3"></i>
                        Application Details
                    </h2>

                    <!-- Enhanced Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div class="group">
                                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-emerald-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-hashtag text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Application Reference</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->reference_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-blue-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Farmer Name</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->farmer->full_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-purple-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                    <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-id-card text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Registration Number</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->farmer->registration_number }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div class="group">
                                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-orange-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Season</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->season->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-green-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                    <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Farm Size</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->farm->size }} hectares</p>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-indigo-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                    <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-phone text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Contact</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->farmer->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commodities Section -->
                <div class="mb-10">
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-8 flex items-center">
                        <i class="fas fa-seedling text-emerald-600 mr-3"></i>
                        Commodities & Financial Summary
                    </h2>

                    <!-- Enhanced Commodities Table -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-emerald-500 to-blue-600 text-white">
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-leaf mr-2"></i>Commodity
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-weight mr-2"></i>Quantity
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-tag mr-2"></i>Unit Price
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-calculator mr-2"></i>Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($application->commodities as $index => $commodity)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s;">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-seedling text-white text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $commodity->name }}</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">Premium Quality</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                        {{ number_format($commodity->pivot->quantity) }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">
                                                        {{ $commodity->unit }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">
                                                    ₦{{ number_format($commodity->price_per_unit, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                    ₦{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Financial Summary -->
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
                                <!-- Insurance -->
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-shield-alt text-white"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Insurance ({{ $application->insurance_rate }}%)</p>
                                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400">₦{{ number_format($application->insurance_amount, 2) }}</p>
                                </div>

                                <!-- Total Loan -->
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-hand-holding-usd text-white"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Loan</p>
                                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">₦{{ number_format($application->total_loan, 2) }}</p>
                                </div>

                                <!-- Equity Held -->
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-piggy-bank text-white"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Equity Held</p>
                                    <p class="text-xl font-bold text-purple-600 dark:text-purple-400">₦{{ number_format($application->equity, 2) }}</p>
                                </div>

                                <!-- Disbursed Amount -->
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-money-bill-wave text-white"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Disbursed Amount</p>
                                    <p class="text-xl font-bold text-orange-600 dark:text-orange-400">₦{{ number_format($application->disbursed_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commodity Breakdown Section -->
                @if($application->commodities && $application->commodities->count() > 0)
                <div class="mt-8 animate-fade-in" style="animation-delay: 0.6s;">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-purple-200 dark:border-purple-800">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-boxes text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Commodity Details</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold rounded-tl-lg">Commodity</th>
                                        <th class="px-4 py-3 text-left font-semibold">Qty/Ha</th>
                                        <th class="px-4 py-3 text-left font-semibold">Farm Size</th>
                                        <th class="px-4 py-3 text-left font-semibold">Requested Qty</th>
                                        <th class="px-4 py-3 text-left font-semibold">Unit Price</th>
                                        <th class="px-4 py-3 text-left font-semibold rounded-tr-lg">Total Value</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($application->commodities as $commodity)
                                    @php
                                        $requestedQty = $commodity->pivot->quantity ?? 0;
                                        $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
                                        $unitPrice = $commodity->price_per_unit ?? 0;
                                        $totalValue = $requestedQty * $unitPrice;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 bg-gradient-to-br from-green-400 to-green-500 rounded-lg flex items-center justify-center mr-2">
                                                    <span class="text-white text-xs font-bold">{{ substr($commodity->name, 0, 1) }}</span>
                                                </div>
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $commodity->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $qtyPerHectare }}</td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $application->farm->size }} ha</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $requestedQty }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-gray-700 dark:text-gray-300">₦{{ number_format($unitPrice, 2) }}</td>
                                        <td class="px-4 py-3 font-semibold text-emerald-600 dark:text-emerald-400">₦{{ number_format($totalValue, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Financial Summary -->
                        @if($application->total_loan || $application->insurance_amount || $application->equity || $application->disbursed_amount)
                        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if($application->total_loan)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Loan</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">₦{{ number_format($application->total_loan) }}</p>
                            </div>
                            @endif
                            @if($application->insurance_amount)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Insurance</p>
                                <p class="text-sm font-bold text-orange-600 dark:text-orange-400">₦{{ number_format($application->insurance_amount) }}</p>
                            </div>
                            @endif
                            @if($application->equity)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Equity Held</p>
                                <p class="text-sm font-bold text-purple-600 dark:text-purple-400">₦{{ number_format($application->equity) }}</p>
                            </div>
                            @endif
                            @if($application->disbursed_amount)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Disbursed</p>
                                <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">₦{{ number_format($application->disbursed_amount) }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-8 border-t border-gray-200 dark:border-gray-700">
                    <!-- Print Button -->
                    <button onclick="window.print()" aria-label="Print acknowledgment slip"
                            class="group relative px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-print group-hover:animate-bounce"></i>
                        <span>Print Slip</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </button>

                    <!-- Download PDF Button -->
                    <a href="{{ route('applications.slip.pdf', $application->uuid) }}" aria-label="Download acknowledgment PDF"
                       class="group relative px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-download group-hover:animate-bounce"></i>
                        <span>Download PDF</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </a>

                    <!-- Verify Button -->
                    <a href="{{ route('applications.verify', $application->reference_number) }}" aria-label="Verify acknowledgment online"
                       class="group relative px-8 py-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-check-circle group-hover:animate-bounce"></i>
                        <span>Verify Online</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Signature / Stamp Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="h-16 border-b border-dashed border-gray-300 dark:border-gray-600"></div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Applicant Signature</p>
            </div>
            <div class="text-center">
                <div class="h-16 border-b border-dashed border-gray-300 dark:border-gray-600"></div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Officer Signature</p>
            </div>
            <div class="text-center">
                <div class="h-16 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded"></div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Official Stamp</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 pb-8">
            <div class="inline-flex items-center space-x-2 text-gray-500 dark:text-gray-400">
                <i class="fas fa-shield-alt text-emerald-500"></i>
                <span class="text-sm">Secured by AFNON Management System</span>
                <i class="fas fa-shield-alt text-emerald-500"></i>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                Generated on {{ now()->format('M d, Y \a\t H:i A') }} | Document ID: {{ $application->uuid }}
            </p>
        </div>
    </div>

    <!-- Enhanced Dark Mode Toggle -->
    <div class="fixed bottom-6 right-6 z-50">
        <button id="darkModeToggle" aria-label="Toggle dark mode"
                class="group p-4 rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-2xl border border-white/20 dark:border-gray-700/20 hover:scale-110 transition-all duration-300">
            <svg id="sunIcon" class="h-6 w-6 hidden dark:block text-yellow-400 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="moonIcon" class="h-6 w-6 block dark:hidden text-gray-800 group-hover:rotate-12 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>
    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 left-6 z-50 space-y-4">
        <!-- Share Button -->
        <button onclick="shareApplication()" aria-label="Share acknowledgment slip"
                class="group p-4 rounded-2xl bg-emerald-500/90 hover:bg-emerald-600 backdrop-blur-xl shadow-2xl text-white hover:scale-110 transition-all duration-300">
            <i class="fas fa-share-alt group-hover:rotate-12 transition-transform duration-300"></i>
        </button>

        <!-- Help Button -->
        <button onclick="showHelp()" aria-label="Help information for this page"
                class="group p-4 rounded-2xl bg-blue-500/90 hover:bg-blue-600 backdrop-blur-xl shadow-2xl text-white hover:scale-110 transition-all duration-300">
            <i class="fas fa-question group-hover:bounce transition-transform duration-300"></i>
        </button>
    </div>

    {!! ToastMagic::scripts() !!}

    <script>
        // Enhanced Dark Mode Toggle
        const toggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';

        if (savedTheme === 'dark') {
            html.classList.add('dark');
        }

        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const newTheme = html.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', newTheme);

            // Add a nice transition effect
            document.body.style.transition = 'background-color 0.3s ease';
            setTimeout(() => {
                document.body.style.transition = '';
            }, 300);
        });

        // Share Application Function
        function shareApplication() {
            if (navigator.share) {
                navigator.share({
                    title: 'AFNON Application Acknowledgment',
                    text: 'Application Reference: {{ $application->reference_number }}',
                    url: window.location.href
                });
            } else {
                // Fallback: Copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link copied to clipboard!');
                });
            }
        }

        // Help Function
        function showHelp() {
            alert('This is your application acknowledgment slip. You can:\n\n• Print this document\n• Download as PDF\n• Scan the QR code for verification\n• Share this link with others\n\nKeep this reference number safe: {{ $application->reference_number }}');
        }

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add loading animation for buttons
        document.querySelectorAll('button, a').forEach(element => {
            element.addEventListener('click', function() {
                if (!this.classList.contains('loading')) {
                    this.classList.add('loading');
                    setTimeout(() => {
                        this.classList.remove('loading');
                    }, 1000);
                }
            });
        });

        // Print optimization
        window.addEventListener('beforeprint', () => {
            document.body.classList.add('printing');
        });

        window.addEventListener('afterprint', () => {
            document.body.classList.remove('printing');
        });
    </script>

    <style>
        @media print {
            html, body { background: #fff !important; color: #000 !important; }
            .printing { background: #fff !important; }
            /* Hide floating/animated and decorative elements */
            .fixed, .animate-float, .animate-pulse-slow, .shadow-2xl, .shadow-xl { display: none !important; }
            /* Neutralize gradients and blurs for crisp print */
            .bg-gradient-to-r, .bg-gradient-to-br, .backdrop-blur-xl { background: #fff !important; backdrop-filter: none !important; }
            /* Improve table readability */
            table { border-collapse: collapse !important; }
            th, td { border: 1px solid #000 !important; padding: 6px !important; }
            thead tr { background: #eee !important; color: #000 !important; }
            /* Remove printed link URLs */
            a[href]:after { content: '' !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }

        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</body>
</html>
