<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Application - AFNON</title>
    @include('application.includes.app')
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 1s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'shimmer': 'shimmer 2s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'success-pulse': 'successPulse 2s infinite',
                        'check-draw': 'checkDraw 0.8s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.8)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-8px)' }
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-15px)' }
                        },
                        successPulse: {
                            '0%, 100%': { transform: 'scale(1)', opacity: '1' },
                            '50%': { transform: 'scale(1.05)', opacity: '0.8' }
                        },
                        checkDraw: {
                            '0%': { strokeDashoffset: '100' },
                            '100%': { strokeDashoffset: '0' }
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

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50 to-blue-50 dark:from-gray-900 dark:via-emerald-900 dark:to-gray-800 flex items-center justify-center px-4 py-8">

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-green-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-40 left-1/2 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 4s;"></div>
    </div>

    <!-- Enhanced Dark Mode Toggle -->
    <div class="fixed bottom-6 right-6 z-50">
        <button id="darkModeToggle"
            class="group p-4 rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-2xl border border-white/20 dark:border-gray-700/20 hover:scale-110 transition-all duration-300">
            <svg id="sunIcon" class="h-6 w-6 hidden dark:block text-yellow-400 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg id="moonIcon" class="h-6 w-6 block dark:hidden text-gray-800 group-hover:rotate-12 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <!-- Enhanced Verification Card -->
    <div class="relative max-w-5xl w-full">
        <!-- Success Animation Container -->
        <div class="text-center mb-8 animate-fade-in">
            <!-- Animated Success Icon -->
            <div class="relative mx-auto w-32 h-32 mb-6">
                <!-- Outer Ring -->
                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-emerald-400 to-green-500 animate-success-pulse"></div>
                <!-- Inner Circle -->
                <div class="absolute inset-2 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center">
                    <!-- Checkmark with draw animation -->
                    <svg class="w-16 h-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                              d="M5 13l4 4L19 7"
                              stroke-dasharray="100"
                              stroke-dashoffset="100"
                              class="animate-check-draw"
                              style="animation-delay: 0.5s;"/>
                    </svg>
                </div>
                <!-- Floating particles -->
                <div class="absolute -top-2 -right-2 w-4 h-4 bg-yellow-400 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
                <div class="absolute -bottom-2 -left-2 w-3 h-3 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 1.5s;"></div>
                <div class="absolute top-1/2 -right-4 w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 2s;"></div>
            </div>

            <!-- Success Message -->
            <h1 class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent mb-4 animate-slide-up" style="animation-delay: 0.3s;">
                ✓ Application Verified
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto animate-slide-up" style="animation-delay: 0.6s;">
                🎉 Congratulations! This application is <span class="font-semibold text-emerald-600 dark:text-emerald-400">valid and officially recorded</span> in the AFNON Management System.
            </p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 overflow-hidden animate-scale-in" style="animation-delay: 0.8s;">

            <div class="p-8 lg:p-12">
                <!-- Application Details Section -->
                <div class="mb-10">
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-8 flex items-center">
                        <i class="fas fa-info-circle text-emerald-600 mr-3"></i>
                        Verified Application Details
                    </h2>

                    <!-- Enhanced Details Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div class="group p-4 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-emerald-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-hashtag text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Reference Number</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->reference_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="group p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-blue-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Farmer Name</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->farmer->full_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="group p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-purple-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3">
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
                        <div class="space-y-4">
                            <div class="group p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-orange-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Season</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->season->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="group p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-green-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Farm Size</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->farm->size }} hectares</p>
                                    </div>
                                </div>
                            </div>

                            <div class="group p-4 bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-teal-100 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-clock text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Verified On</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ now()->format('M d, Y') }}</p>
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

                    <!-- Enhanced Commodity Allocation Table -->
                    @if($application->commodity_allocations && $application->commodity_allocations->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-emerald-500 to-green-600 text-white">
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-leaf mr-2"></i>Commodity
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-chart-bar mr-2"></i>Qty/Ha
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-map mr-2"></i>Farm Size
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-weight mr-2"></i>Allocated Qty
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-tag mr-2"></i>Unit Price
                                        </th>
                                        <th class="px-6 py-4 text-left font-semibold">
                                            <i class="fas fa-calculator mr-2"></i>Total Value
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($application->commodity_allocations as $index => $allocation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 animate-fade-in" style="animation-delay: {{ $index * 0.1 + 1 }}s;">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                                                        <span class="text-white text-sm font-bold">{{ substr($allocation->commodity_name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $allocation->commodity_name }}</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">Premium Quality</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $allocation->qty_per_hectare }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->farm->size }} ha</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $allocation->allocated_quantity }}</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400 bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded-full">bags</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">
                                                    ₦{{ number_format($allocation->unit_price, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                    ₦{{ number_format($allocation->total_value, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <!-- Fallback to old commodity structure if allocations don't exist -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-emerald-500 to-green-600 text-white">
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
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 animate-fade-in" style="animation-delay: {{ $index * 0.1 + 1 }}s;">
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
                    </div>
                    @endif

                    <!-- Financial Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Insurance -->
                        <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-2xl border border-blue-200 dark:border-blue-700">
                            <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shield-alt text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Insurance ({{ $application->insurance_rate }}%)</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">₦{{ number_format($application->insurance_amount, 2) }}</p>
                        </div>

                        <!-- Total Loan -->
                        <div class="text-center p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900 dark:to-emerald-800 rounded-2xl border border-emerald-200 dark:border-emerald-700">
                            <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Total Loan</p>
                            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">₦{{ number_format($application->total_loan, 2) }}</p>
                        </div>

                        <!-- Equity Held -->
                        <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-2xl border border-purple-200 dark:border-purple-700">
                            <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-piggy-bank text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Equity Held</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">₦{{ number_format($application->equity, 2) }}</p>
                        </div>

                        <!-- Disbursed Amount -->
                        <div class="text-center p-6 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 rounded-2xl border border-orange-200 dark:border-orange-700">
                            <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-money-bill-wave text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Disbursed Amount</p>
                            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">₦{{ number_format($application->disbursed_amount, 2) }}</p>
                        </div>
                    </div>
                </div>


                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-8 border-t border-gray-200 dark:border-gray-700">
                    <!-- Download PDF Button -->
                    <a href="{{ route('applications.verify.pdf', $application->reference_number) }}"
                       class="group relative px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-download group-hover:animate-bounce"></i>
                        <span>Download Verification PDF</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </a>

                    <!-- Share Button -->
                    <button onclick="shareVerification()"
                            class="group relative px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-share-alt group-hover:animate-bounce"></i>
                        <span>Share Verification</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </button>

                    <!-- Go Home Button -->
                    <a href="/"
                       class="group relative px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-home group-hover:animate-bounce"></i>
                        <span>Go Home</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Trust Indicators -->
        <div class="text-center mt-12 animate-fade-in" style="animation-delay: 1.5s;">
            <div class="inline-flex items-center space-x-6 text-gray-500 dark:text-gray-400">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-emerald-500 text-xl"></i>
                    <span class="text-sm font-medium">Secured</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <span class="text-sm font-medium">Verified</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-database text-blue-500 text-xl"></i>
                    <span class="text-sm font-medium">Recorded</span>
                </div>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-4">
                Verified on {{ now()->format('M d, Y \a\t H:i A') }} | AFNON Management System
            </p>
        </div>
    </div>

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

            // Add transition effect
            document.body.style.transition = 'background-color 0.3s ease';
            setTimeout(() => {
                document.body.style.transition = '';
            }, 300);
        });

        // Share Verification Function
        function shareVerification() {
            const shareData = {
                title: 'AFNON Application Verification',
                text: `Application ${{{ $application->reference_number }}} has been verified in the AFNON system.`,
                url: window.location.href
            };

            if (navigator.share) {
                navigator.share(shareData);
            } else {
                // Fallback: Copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showNotification('Verification link copied to clipboard!', 'success');
                });
            }
        }

        // Show notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                'bg-blue-500'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Slide in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Slide out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
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

        // Confetti effect on page load
        function createConfetti() {
            const colors = ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444'];

            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.cssText = `
                        position: fixed;
                        top: -10px;
                        left: ${Math.random() * 100}%;
                        width: 10px;
                        height: 10px;
                        background: ${colors[Math.floor(Math.random() * colors.length)]};
                        border-radius: 50%;
                        pointer-events: none;
                        z-index: 1000;
                        animation: confettiFall 3s linear forwards;
                    `;

                    document.body.appendChild(confetti);

                    setTimeout(() => {
                        document.body.removeChild(confetti);
                    }, 3000);
                }, i * 100);
            }
        }

        // Add confetti animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes confettiFall {
                to {
                    transform: translateY(100vh) rotate(360deg);
                    opacity: 0;
                }
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
        `;
        document.head.appendChild(style);

        // Trigger confetti on page load
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 1000);
        });
    </script>
</body>

</html>
