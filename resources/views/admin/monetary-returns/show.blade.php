@extends('layouts.layout')

@section('content')
<div class="w-full px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Monetary Return Details</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Payment #{{ $return->invoice_number ?? $return->tx_ref }}</p>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('admin.monetary-returns') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
                <a href="{{ route('admin.monetary-returns.report', $return->id) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Generate Report
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Payment Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Payment Information</h3>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Transaction Reference</p>
                            <p class="font-semibold text-gray-900 dark:text-white font-mono">{{ $return->tx_ref }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Invoice Number</p>
                            <p class="font-semibold text-gray-900 dark:text-white font-mono">{{ $return->invoice_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Amount Paid</p>
                            <p class="font-semibold text-green-600 dark:text-green-400 text-xl">₦{{ number_format($return->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Payment Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($return->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($return->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ ucfirst($return->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Payment Provider</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($return->payment_provider ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Payment Date</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $return->created_at->format('M d, Y H:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Farmer Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Farmer Information</h3>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Full Name</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $return->application->farmer->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Registration Number</p>
                            <p class="font-semibold text-gray-900 dark:text-white font-mono">{{ $return->application->farmer->registration_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Phone Number</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $return->application->farmer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Application Reference</p>
                            <p class="font-semibold text-gray-900 dark:text-white font-mono">{{ $return->application->reference_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- Season Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Season Information</h3>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Season Name</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $return->application->season->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Return Deadline</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($return->application->season->return_deadline)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Commodity Allocations -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-seedling text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Commodity Allocations</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Commodity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Allocated Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Value</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($return->application->commodity_allocations as $allocation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $allocation->commodity_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($allocation->allocated_quantity) }} {{ $allocation->unit ?? 'units' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        ₦{{ number_format($allocation->unit_price ?? 0, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                        ₦{{ number_format(($allocation->allocated_quantity * ($allocation->unit_price ?? 0)), 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Calculation Details -->
                @if($return->calculation_details)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calculator text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Payment Calculation</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Calculation Method:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $return->calculation_method)) }}</span>
                        </div>
                        
                        @if(isset($return->calculation_details['breakdown']))
                            @foreach($return->calculation_details['breakdown'] as $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">{{ $item['description'] ?? 'Item' }}:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($item['amount'] ?? 0, 2) }}</span>
                            </div>
                            @endforeach
                        @endif
                        
                        <div class="flex justify-between items-center py-2 bg-gray-50 dark:bg-gray-700 rounded-lg px-4">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total Amount:</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">₦{{ number_format($return->amount, 2) }}</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Payment Status Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-bold text-green-800 dark:text-green-200">Payment Status</h4>
                    </div>
                    <p class="text-green-700 dark:text-green-300 text-sm">
                        This payment has been successfully processed and verified.
                    </p>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h4>
                    <div class="space-y-3">
                        <a href="{{ route('admin.monetary-returns.report', $return->id) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Generate PDF Report
                        </a>
                        <a href="{{ route('admin.monetary-returns.export', $return->id) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-download mr-2"></i>
                            Export Data
                        </a>
                    </div>
                </div>

                <!-- Payment Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Payment Timeline</h4>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Payment Created</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $return->created_at->format('M d, Y H:i A') }}</p>
                            </div>
                        </div>
                        @if($return->verified_at)
                        <div class="flex items-start">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Payment Verified</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $return->verified_at->format('M d, Y H:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
