@extends('layouts.layout')

@section('content')
<div class="w-full px-4 py-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.reports.seasons.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="w-12 h-12 {{ $season->loan_type === 'co-funded' ? 'bg-blue-500' : 'bg-green-500' }} rounded-xl flex items-center justify-center">
                    @if($season->loan_type === 'co-funded')
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $season->name }} Report</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ ucfirst(str_replace('-', ' ', $season->loan_type)) }} Season Analytics</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.seasons.pdf', $season) }}" 
                   class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.seasons.excel', $season) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Applications</p>
                    <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ number_format($statistics['total_applications']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-green-600 dark:text-green-400 font-medium">Approved Applications</p>
                    <p class="text-2xl font-bold text-green-800 dark:text-green-200">{{ number_format($statistics['approved_applications']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-purple-600 dark:text-purple-400 font-medium">Farmers Collected</p>
                    <p class="text-2xl font-bold text-purple-800 dark:text-purple-200">{{ number_format($collectionInsights['farmers_collected_count']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl p-6 border border-yellow-200 dark:border-yellow-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Collection Rate</p>
                    <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ number_format($statistics['collection_rate'], 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Commodity Collections -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Commodity Collections Summary</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Commodity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Collected</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Farmers Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($collectionInsights['commodity_collections'] as $commodity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $commodity->commodity_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ number_format($commodity->total_collected) }} bags
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ number_format($commodity->farmers_count) }} farmers
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No commodity collections found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Financial Insights -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Financial Insights</h2>
        </div>

        @if($financialInsights['type'] === 'co-funded')
            <!-- Co-funded Financial Insights -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6 border border-blue-200 dark:border-blue-700">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-200">Co-funded Season: Farmers pay 50% upfront to collect commodities. No commodity returns required.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Loan Value</p>
                    <p class="text-xl font-bold text-blue-800 dark:text-blue-200">₦{{ number_format($financialInsights['total_loan_amount'], 2) }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-700">
                    <p class="text-sm text-green-600 dark:text-green-400 font-medium">Disbursed (50%)</p>
                    <p class="text-xl font-bold text-green-800 dark:text-green-200">₦{{ number_format($financialInsights['total_disbursed'], 2) }}</p>
                </div>
                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-700">
                    <p class="text-sm text-orange-600 dark:text-orange-400 font-medium">Equity Held (50%)</p>
                    <p class="text-xl font-bold text-orange-800 dark:text-orange-200">₦{{ number_format($financialInsights['equity_held'], 2) }}</p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                    <p class="text-sm text-purple-600 dark:text-purple-400 font-medium">Payments Received</p>
                    <p class="text-xl font-bold text-purple-800 dark:text-purple-200">₦{{ number_format($financialInsights['actual_payments'], 2) }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-700">
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Payment Rate</p>
                    <p class="text-xl font-bold text-yellow-800 dark:text-yellow-200">{{ number_format($financialInsights['payment_rate'], 1) }}%</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Paid Applications</p>
                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">{{ number_format($financialInsights['paid_applications']) }}</p>
                    <p class="text-xs text-gray-500">Can collect commodities</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending Payments</p>
                    <p class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ number_format($financialInsights['pending_applications']) }}</p>
                    <p class="text-xs text-gray-500">Must pay to collect</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Outstanding Amount</p>
                    <p class="text-lg font-semibold text-red-600 dark:text-red-400">₦{{ number_format($financialInsights['outstanding_amount'], 2) }}</p>
                    <p class="text-xs text-gray-500">Amount still owed</p>
                </div>
            </div>
        @else
            <!-- Complete Loan Financial Insights -->
            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 mb-6 border border-green-200 dark:border-green-700">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-semibold text-green-800 dark:text-green-200">Complete Loan Season: No upfront payment required. Farmers collect commodities and return equivalent value by deadline.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Loan Value</p>
                    <p class="text-xl font-bold text-blue-800 dark:text-blue-200">₦{{ number_format($financialInsights['total_loan_amount'], 2) }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-700">
                    <p class="text-sm text-green-600 dark:text-green-400 font-medium">Collected</p>
                    <p class="text-xl font-bold text-green-800 dark:text-green-200">{{ number_format($financialInsights['collected_applications']) }}</p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                    <p class="text-sm text-purple-600 dark:text-purple-400 font-medium">Returned</p>
                    <p class="text-xl font-bold text-purple-800 dark:text-purple-200">{{ number_format($financialInsights['returned_applications']) }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-700">
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Pending Returns</p>
                    <p class="text-xl font-bold text-yellow-800 dark:text-yellow-200">{{ number_format($financialInsights['pending_returns']) }}</p>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-700">
                    <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Return Rate</p>
                    <p class="text-xl font-bold text-indigo-800 dark:text-indigo-200">{{ number_format($financialInsights['return_rate'], 1) }}%</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Collection Rate</p>
                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">{{ number_format($financialInsights['collection_rate'], 1) }}%</p>
                    <p class="text-xs text-gray-500">Farmers who collected</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending Collections</p>
                    <p class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ number_format($financialInsights['pending_collections']) }}</p>
                    <p class="text-xs text-gray-500">Yet to collect</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">No Payment Required</p>
                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">✓ Confirmed</p>
                    <p class="text-xs text-gray-500">Commodity returns only</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Expected Commodity Returns (for Complete Loan seasons only) -->
    @if($commodityInsights && count($commodityInsights) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Expected Commodity Returns</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Calculated using: Total Loan ÷ Current Market Price</p>
                </div>
            </div>

            <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 mb-6 border border-orange-200 dark:border-orange-700">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-orange-800 dark:text-orange-200">Complete Loan Applications Only</p>
                        <p class="text-xs text-orange-700 dark:text-orange-300">Only farmers who collected commodities without making upfront payments are included. They must return equivalent commodity value by {{ \Carbon\Carbon::parse($season->return_deadline)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($commodityInsights as $commodity)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $commodity['commodity_name'] }}</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Current Price: ₦{{ number_format($commodity['current_price'], 2) }}/{{ $commodity['unit'] }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Expected Quantity</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($commodity['total_expected_quantity'], 2) }} {{ $commodity['unit'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Loan Value</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">₦{{ number_format($commodity['total_loan_value'], 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Farmers Count</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($commodity['farmers_count']) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Avg per Farmer</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($commodity['total_expected_quantity'] / $commodity['farmers_count'], 2) }} {{ $commodity['unit'] }}</p>
                            </div>
                        </div>

                        <!-- Detailed breakdown -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-600">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Farmer</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Reference</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Loan Amount</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Expected Return</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($commodity['applications'] as $app)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $app['farmer_name'] }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $app['reference_number'] }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">₦{{ number_format($app['total_loan'], 2) }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ number_format($app['expected_quantity'], 2) }} {{ $commodity['unit'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($season->loan_type === 'co-funded')
        <!-- Co-funded Season - No Commodity Returns -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Commodity Returns</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Co-funded season requirements</p>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 text-center border border-blue-200 dark:border-blue-700">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">No Commodity Returns Required</h3>
                <p class="text-blue-700 dark:text-blue-300 mb-4">
                    In co-funded seasons, farmers only need to pay 50% of the loan value upfront to collect their commodities. 
                    No commodity returns are expected after collection.
                </p>
                <div class="bg-white dark:bg-blue-800 rounded-lg p-4 inline-block">
                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">
                        Payment Model: Upfront Payment Only<br>
                        <span class="text-xs text-blue-600 dark:text-blue-300">50% payment + 50% equity held by AFNON</span>
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection