@extends('layouts.layout')

@section('content')
    <!-- Enhanced Application Approval Modal -->
    <div class="max-w-7xl mx-auto">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full p-6 sm:p-8 overflow-y-auto border border-gray-200 dark:border-gray-700">
            <!-- Enhanced Header with breadcrumb and status -->
            <div class="mb-8">
                <!-- Breadcrumb -->
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.applications.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                Applications
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span
                                    class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $application->reference_number }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header with status badge -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Application Review</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Reference: {{ $application->reference_number }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <span
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full
                            {{ $application->status === 'pending'
                                ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800'
                                : ($application->status === 'approved'
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border border-green-200 dark:border-green-800'
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-800') }}">
                            @if ($application->status === 'pending')
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @elseif($application->status === 'approved')
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Farmer & Application Info Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Farmer Information Card -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-blue-200 dark:border-gray-600 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Farmer Information</h4>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Full Name</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $application->farmer->full_name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Registration No.</span>
                            <span
                                class="text-sm font-mono bg-blue-100 dark:bg-gray-600 px-2 py-1 rounded text-blue-800 dark:text-blue-200">{{ $application->farmer->registration_number }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Phone Number</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $application->farmer->phone }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">NIN</span>
                            <span
                                class="text-sm font-mono bg-blue-100 dark:bg-gray-600 px-2 py-1 rounded text-blue-800 dark:text-blue-200">{{ $application->farmer->nin }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">BVN</span>
                            <span
                                class="text-sm font-mono bg-blue-100 dark:bg-gray-600 px-2 py-1 rounded text-blue-800 dark:text-blue-200">{{ $application->farmer->bvn }}</span>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Address</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white text-right max-w-xs">{{ $application->farmer->address }}</span>
                        </div>
                    </div>
                </div>

                <!-- Application Information Card -->
                <div
                    class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-emerald-200 dark:border-gray-600 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Application Details</h4>
                    </div>
                    <div class="space-y-3">
                        <div
                            class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Reference Number</span>
                            <span
                                class="text-sm font-mono bg-emerald-100 dark:bg-gray-600 px-2 py-1 rounded text-emerald-800 dark:text-emerald-200">{{ $application->reference_number }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Season</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $application->season->name }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Farm Location</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $application->farm->location }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Farm Size</span>
                            <span
                                class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ $application->farm->size }}
                                hectares</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Application Date</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white">{{ $application->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Commodity Breakdown -->
            <div class="mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Commodity Breakdown & Financial Summary
                    </h4>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold">Commodity</th>
                                    <th class="px-6 py-4 text-left font-semibold">Qty/Ha</th>
                                    <th class="px-6 py-4 text-left font-semibold">Farm Size</th>
                                    <th class="px-6 py-4 text-left font-semibold">Allocated Qty</th>
                                    <th class="px-6 py-4 text-left font-semibold">Unit Price</th>
                                    <th class="px-6 py-4 text-left font-semibold">Total Value</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($allocations as $index => $alloc)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-500 rounded-lg flex items-center justify-center mr-3">
                                                    <span
                                                        class="text-white text-xs font-bold">{{ substr($alloc['commodity'], 0, 1) }}</span>
                                                </div>
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white">{{ $alloc['commodity'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                            {{ $alloc['qty_per_hectare'] }}</td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $alloc['farm_size'] }}
                                            ha</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $alloc['allocated_quantity'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-gray-700 dark:text-gray-300">
                                            ₦{{ number_format($alloc['unit_price'], 2) }}</td>
                                        <td class="px-6 py-4 font-semibold text-emerald-600 dark:text-emerald-400">
                                            ₦{{ number_format($alloc['total_value'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Financial Summary Section -->
                    <div
                        class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-t border-gray-200 dark:border-gray-600">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 p-6">
                            <!-- Total Loan -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Total Loan</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            ₦{{ number_format($total_loan) }}</p>
                                    </div>
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Insurance Rate -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Insurance Rate</p>
                                        <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                                            {{ $insurance_rate }}%</p>
                                    </div>
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Insurance Amount -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Insurance Amount</p>
                                        <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                                            ₦{{ number_format($insurance_amount, 2) }}</p>
                                    </div>
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Equity Held -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Oragnization Contribution</p>
                                        <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                            ₦{{ number_format($equity_held, 2) }}</p>
                                    </div>
                                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Disbursed Amount -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Farmer Contribution</p>
                                        <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                            ₦{{ number_format($disbursed_amount, 2) }}</p>
                                    </div>
                                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proportional Commodity Disbursement Section -->
            @if(isset($disbursementSummary) && $disbursementSummary['disbursement_percentage'] < 100 && $application->season && $application->season->loan_type === 'complete-loan')
            <div class="mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Proportional Commodity Disbursement ({{ number_format($disbursementSummary['disbursement_percentage'], 1) }}%)
                    </h4>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold">Commodity</th>
                                    <th class="px-6 py-4 text-left font-semibold">Original Qty</th>
                                    <th class="px-6 py-4 text-left font-semibold">Disbursed Qty</th>
                                    <th class="px-6 py-4 text-left font-semibold">Unit Price</th>
                                    <th class="px-6 py-4 text-left font-semibold">Original Value</th>
                                    <th class="px-6 py-4 text-left font-semibold">Disbursed Value</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($disbursementSummary['commodity_disbursement']['commodities'] as $commodity)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ $commodity['commodity_id'] === 'insurance' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($commodity['commodity_id'] === 'insurance')
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-shield-alt text-white text-xs"></i>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                                                    <span class="text-white text-xs font-bold">{{ substr($commodity['commodity_name'], 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900 dark:text-white {{ $commodity['commodity_id'] === 'insurance' ? 'text-blue-800 dark:text-blue-200' : '' }}">
                                                {{ $commodity['commodity_name'] }}
                                                @if($commodity['commodity_id'] === 'insurance')
                                                    <span class="text-xs text-blue-600 dark:text-blue-400 ml-2">({{ $application->insurance_rate ?? 0 }}%)</span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        @if($commodity['commodity_id'] === 'insurance')
                                            <span class="text-blue-600 dark:text-blue-400">-</span>
                                        @else
                                            {{ number_format($commodity['original_quantity'], 2) }} {{ $commodity['unit'] }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($commodity['commodity_id'] === 'insurance')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fas fa-shield-alt mr-1"></i>Included
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                {{ number_format($commodity['disbursed_quantity'], 2) }} {{ $commodity['unit'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-mono text-gray-700 dark:text-gray-300">
                                        @if($commodity['commodity_id'] === 'insurance')
                                            <span class="text-blue-600 dark:text-blue-400">-</span>
                                        @else
                                            ₦{{ number_format($commodity['unit_price'], 2) }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-400">
                                        ₦{{ number_format($commodity['original_value'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold {{ $commodity['commodity_id'] === 'insurance' ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}">
                                        ₦{{ number_format($commodity['disbursed_value'], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Disbursement Summary -->
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-t border-orange-200 dark:border-orange-700">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6">
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-orange-200 dark:border-orange-700 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-orange-600 dark:text-orange-400 uppercase tracking-wide">Total Original Value</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            ₦{{ number_format($disbursementSummary['commodity_disbursement']['total_original_value'], 2) }}
                                        </p>
                                    </div>
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-orange-200 dark:border-orange-700 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-orange-600 dark:text-orange-400 uppercase tracking-wide">Disbursed Value</p>
                                        <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                                            ₦{{ number_format($disbursementSummary['commodity_disbursement']['total_disbursed_value'], 2) }}
                                        </p>
                                    </div>
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-orange-200 dark:border-orange-700 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-orange-600 dark:text-orange-400 uppercase tracking-wide">Disbursement %</p>
                                        <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                                            {{ number_format($disbursementSummary['disbursement_percentage'], 1) }}%
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Includes {{ $application->insurance_rate ?? 0 }}% insurance
                                        </p>
                                    </div>
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-blue-200 dark:border-blue-700 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">Insurance Disbursed</p>
                                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                            ₦{{ number_format($disbursementSummary['commodity_disbursement']['disbursed_insurance_amount'] ?? 0, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Original: ₦{{ number_format($disbursementSummary['commodity_disbursement']['original_insurance_amount'] ?? 0, 2) }}
                                        </p>
                                    </div>
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-white text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if ($application->status === 'pending')
                <!-- Enhanced Approval Form -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Application Approval</h4>
                    </div>

                    <form action="{{ route('admin.applications.approve', $application->uuid) }}" method="POST"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Collection Center -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Collection Center *
                                </label>
                                <!-- Collection Center -->
                                <select name="collection_center_id" id="collectionCenter" required class="form-select w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 dark:text-white smooth-transition">
                                    <option value="">-- Select Collection Center --</option>
                                    @foreach ($collectionCenters as $center)
                                        <option value="{{ $center->id }}" data-type="{{ $center->type }}">
                                            {{ $center->name }} ({{ ucfirst($center->type) }})
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Hidden field for collection center -->
                                <input type="hidden" name="collection_center_id" id="collectionCenterHidden">
                            </div>

                            <!-- Return Center -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    Return Center *
                                </label>
                                <!-- Return Center -->
                                <select name="return_center_id" id="returnCenter" required class="form-select w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 dark:text-white smooth-transition">
                                    <option value="">-- Select Return Center --</option>
                                    @foreach ($returnCenters as $center)
                                        <option value="{{ $center->id }}" data-type="{{ $center->type }}">
                                            {{ $center->name }} ({{ ucfirst($center->type) }})
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Hidden field for return center -->
                                <input type="hidden" name="return_center_id" id="returnCenterHidden">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <button type="button" id="rejectBtn"
                                class="inline-flex items-center justify-center px-6 py-3 border border-red-300 dark:border-red-600 text-sm font-medium rounded-lg text-red-700 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject Application
                            </button>
                            <button id="approveBtn" type="submit" disabled
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span id="approveText">Select Centers to Approve</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Enhanced Rejection Modal -->
                <div id="rejectModal"
                    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
                    <div
                        class="relative top-20 mx-auto p-6 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white dark:bg-gray-800">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Reject Application</h3>
                            </div>
                            <button type="button" id="closeRejectModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mb-6">
                            <div
                                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    <strong>Warning:</strong> This action cannot be undone. The application will be
                                    permanently rejected.
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('admin.applications.reject', $application->uuid) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Rejection Reason (Optional)
                                </label>
                                <textarea name="rejection_note" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
                                    placeholder="Provide a reason for rejecting this application (optional)..."></textarea>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                                <button type="button" id="cancelReject"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Confirm Rejection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <!-- Enhanced Status Information -->
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Application Status</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-200">
                                This application has already been <strong
                                    class="font-semibold">{{ ucfirst($application->status) }}</strong> and cannot be
                                modified.
                            </p>
                            @if ($application->status === 'approved')
                                <div class="mt-3 text-xs text-blue-600 dark:text-blue-300">
                                    <p>✓ Application approved and processed successfully</p>
                                </div>
                            @elseif($application->status === 'rejected')
                                <div class="mt-3 text-xs text-blue-600 dark:text-blue-300">
                                    <p>✗ Application was rejected</p>
                                    @if ($application->rejection_note)
                                        <p class="mt-1"><strong>Reason:</strong> {{ $application->rejection_note }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const collectionSelect = document.getElementById("collectionCenter");
            const returnSelect = document.getElementById("returnCenter");
            const approveBtn = document.getElementById("approveBtn");
            const approveText = document.getElementById("approveText");
            const rejectBtn = document.getElementById("rejectBtn");
            const rejectModal = document.getElementById("rejectModal");
            const cancelReject = document.getElementById("cancelReject");
            const closeRejectModal = document.getElementById("closeRejectModal");
            const collectionHidden = document.getElementById("collectionCenterHidden");
            const returnHidden = document.getElementById("returnCenterHidden");
            // Only run if form elements exist (application is pending)
            if (!collectionSelect || !returnSelect || !approveBtn) {
                return;
            }

            // Enhanced rejection modal handlers
            if (rejectBtn && rejectModal) {
                rejectBtn.addEventListener("click", function() {
                    rejectModal.classList.remove("hidden");
                    // Add smooth fade-in animation
                    setTimeout(() => {
                        rejectModal.querySelector('.relative').classList.add('animate-pulse');
                    }, 10);
                });

                // Multiple ways to close the modal
                [cancelReject, closeRejectModal].forEach(btn => {
                    if (btn) {
                        btn.addEventListener("click", function() {
                            rejectModal.classList.add("hidden");
                        });
                    }
                });

                // Close modal when clicking outside
                rejectModal.addEventListener("click", function(e) {
                    if (e.target === rejectModal) {
                        rejectModal.classList.add("hidden");
                    }
                });

                // Close modal with Escape key
                document.addEventListener("keydown", function(e) {
                    if (e.key === "Escape" && !rejectModal.classList.contains("hidden")) {
                        rejectModal.classList.add("hidden");
                    }
                });
            }

            function handleCenterSelection(changedSelect, otherSelect, hiddenInput) {
                const selectedOption = changedSelect.options[changedSelect.selectedIndex];
                const centerType = selectedOption ? selectedOption.getAttribute("data-type") : null;

                if (centerType === "both") {
                    otherSelect.value = changedSelect.value;
                    otherSelect.disabled = true;

                    // copy value into hidden input
                    hiddenInput.value = changedSelect.value;

                    otherSelect.classList.add("bg-gray-100", "dark:bg-gray-600", "cursor-not-allowed");
                } else {
                    if (otherSelect.disabled) {
                        otherSelect.disabled = false;
                        otherSelect.value = "";
                        hiddenInput.value = "";

                        otherSelect.classList.remove("bg-gray-100", "dark:bg-gray-600", "cursor-not-allowed");
                    }
                }
            }

            function toggleApprove() {
                const hasCollection = !!(collectionSelect.value || collectionHidden.value);
                const hasReturn = !!(returnSelect.value || returnHidden.value);
                const canApprove = hasCollection && hasReturn;

                approveBtn.disabled = !canApprove;

                if (canApprove) {
                    approveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    approveBtn.classList.add('hover:bg-emerald-700', 'transform', 'hover:scale-105');
                    if (approveText) {
                        approveText.textContent = 'Approve Application';
                    }
                } else {
                    approveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    approveBtn.classList.remove('hover:bg-emerald-700', 'transform', 'hover:scale-105');
                    if (approveText) {
                        approveText.textContent = 'Select Centers to Approve';
                    }
                }
            }


            // Enhanced change handlers with visual feedback
            collectionSelect.addEventListener("change", function() {
    handleCenterSelection(collectionSelect, returnSelect, returnHidden);
    collectionHidden.value = collectionSelect.value;
    toggleApprove(); // 👈 add this

    this.classList.add('ring-2', 'ring-indigo-500');
    setTimeout(() => {
        this.classList.remove('ring-2', 'ring-indigo-500');
    }, 1000);
});

returnSelect.addEventListener("change", function() {
    handleCenterSelection(returnSelect, collectionSelect, collectionHidden);
    returnHidden.value = returnSelect.value;
    toggleApprove(); // 👈 add this

    this.classList.add('ring-2', 'ring-indigo-500');
    setTimeout(() => {
        this.classList.remove('ring-2', 'ring-indigo-500');
    }, 1000);
});


            // Initialize state on load
            toggleApprove();

            // Add loading state to approve button on form submission
            if (approveBtn) {
                approveBtn.closest('form').addEventListener('submit', function() {
                    if (!approveBtn.disabled) {
                        approveBtn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        `;
                        approveBtn.disabled = true;
                    }
                });
            }
        });
    </script>
@endsection
