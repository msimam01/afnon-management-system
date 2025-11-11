@extends('layouts.layout')

@section('content')
<div class="p-4 md:p-6 space-y-6">

    {{-- Top Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-blue-700 dark:text-blue-300 font-semibold">Total Applications</p>
            <p class="text-2xl font-bold text-blue-900 dark:text-white">{{ number_format($totalApplications) }}</p>
        </div>
        <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-purple-700 dark:text-purple-300 font-semibold">Total Farmers</p>
            <p class="text-2xl font-bold text-purple-900 dark:text-white">{{ number_format($totalFarmers) }}</p>
        </div>
        <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-yellow-700 dark:text-yellow-300 font-semibold">Pending</p>
            <p class="text-2xl font-bold text-yellow-900 dark:text-white">{{ number_format($pendingApplications) }}</p>
        </div>
        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-green-700 dark:text-green-300 font-semibold">Approved</p>
            <p class="text-2xl font-bold text-green-900 dark:text-white">{{ number_format($approvedApplications) }}</p>
        </div>
        <div class="bg-indigo-100 dark:bg-indigo-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-indigo-700 dark:text-indigo-300 font-semibold">Distributed</p>
            <p class="text-2xl font-bold text-indigo-900 dark:text-white">{{ number_format($totalDistributed) }}</p>
        </div>
        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-red-700 dark:text-red-300 font-semibold">Rejected</p>
            <p class="text-2xl font-bold text-red-900 dark:text-white">{{ number_format($rejectedApplications) }}</p>
        </div>
    </div>

    {{-- Season Progress and Status --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Season Progress</h3>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>Collection Period Elapsed: {{ $collectionProgress }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $collectionProgress }}%"></div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Distribution Status</h3>
            <div class="flex items-center justify-center">
                @if($totalRemaining == 0)
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">✅ Fully Distributed</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">⏳ In Progress</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Commodity Allocation Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-cyan-100 dark:bg-cyan-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-cyan-700 dark:text-cyan-300 font-semibold">Total Allocated</p>
            <p class="text-2xl font-bold text-cyan-900 dark:text-white">{{ number_format($totalAllocated) }}</p>
        </div>
        <div class="bg-emerald-100 dark:bg-emerald-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-emerald-700 dark:text-emerald-300 font-semibold">Distributed</p>
            <p class="text-2xl font-bold text-emerald-900 dark:text-white">{{ number_format($totalDistributed) }}</p>
        </div>
        <div class="bg-orange-100 dark:bg-orange-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-orange-700 dark:text-orange-300 font-semibold">Remaining</p>
            <p class="text-2xl font-bold text-orange-900 dark:text-white">{{ number_format($totalRemaining) }}</p>
        </div>
        <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-purple-700 dark:text-purple-300 font-semibold">Available Stock</p>
            <p class="text-2xl font-bold text-purple-900 dark:text-white">{{ number_format($totalAvailableStock) }}</p>
        </div>
    </div>

    {{-- Season Snapshot Card --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">📊 Season Snapshot</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Days Remaining</h3>
                <div class="text-3xl font-bold text-blue-600">{{ $daysRemainingInCollection }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">until collection ends</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Distribution Progress</h3>
                <div class="relative w-24 h-24 mx-auto">
                    <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                        <path d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="2"
                              stroke-dasharray="100, 100"
                              class="text-gray-200 dark:text-gray-700" />
                        <path d="M18 2.0845
                                a 15.9155 15.9155 0 0 1 0 31.831
                                a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="2"
                              stroke-dasharray="{{ $distributionProgress }}, 100"
                              class="text-green-500" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $distributionProgress }}%</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Distributed</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Alerts</h3>
                <ul class="space-y-2 text-sm">
                    @if($daysRemainingInCollection <= 7)
                        <li class="text-red-600">⚠️ Collection ends in {{ $daysRemainingInCollection }} days</li>
                    @endif
                    @if($season->loan_type === 'complete-loan' && $daysUntilReturn <= 14)
                        <li class="text-yellow-600">🔄 Return deadline in {{ $daysUntilReturn }} days</li>
                    @endif
                    @if($totalRemaining > 0)
                        <li class="text-blue-600">📦 {{ $totalRemaining }} units remaining to distribute</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    {{-- Financial Summary --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">💰 Financial Summary</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Loan Amount</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($totalLoanAmount) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Co-funded Payments</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($coFundedPayments) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Outstanding Balance</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($outstandingBalance) }}</p>
            </div>
            <!-- <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Insurance Contributions</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($insuranceContributions) }}</p>
            </div> -->
        </div>
    </div>

    {{-- Farmers & Applications Section --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">👥 Farmers & Applications</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Applications by Status</h3>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left">
                            <th class="pb-2">Status</th>
                            <th class="pb-2">Count</th>
                            <th class="pb-2">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="py-2 text-yellow-600">Pending</td>
                            <td class="py-2">{{ number_format($pendingApplications) }}</td>
                            <td class="py-2">{{ $totalApplications > 0 ? round(($pendingApplications / $totalApplications) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-green-600">Approved</td>
                            <td class="py-2">{{ number_format($approvedApplications) }}</td>
                            <td class="py-2">{{ $totalApplications > 0 ? round(($approvedApplications / $totalApplications) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-blue-600">Distributed</td>
                            <td class="py-2">{{ number_format($totalDistributed) }}</td>
                            <td class="py-2">{{ $totalApplications > 0 ? round(($totalDistributed / $totalApplications) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-red-600">Rejected</td>
                            <td class="py-2">{{ number_format($rejectedApplications) }}</td>
                            <td class="py-2">{{ $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100, 1) : 0 }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Collection & Payment Status</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pending Collections</span>
                        <span class="text-lg font-bold text-yellow-600">{{ number_format($pendingCollections) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completed Collections</span>
                        <span class="text-lg font-bold text-green-600">{{ number_format($completedCollections) }}</span>
                    </div>
                    @if($season->loan_type === 'co-funded')
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pending Payments</span>
                            <span class="text-lg font-bold text-orange-600">{{ number_format($pendingPayments) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completed Payments</span>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($completedPayments) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed Farmer Allocations --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">📋 Farmer Allocations & Collections</h2>

        @if($farmerAllocations->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No farmer allocations found for this season.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Farmer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Farm Size</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Allocations</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Collection</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($farmerAllocations as $farmer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $farmer['farmer_name'] }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $farmer['registration_number'] }}</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ number_format($farmer['farm_size'], 2) }} ha
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($farmer['status'] === 'approved') bg-green-100 text-green-800
                                        @elseif($farmer['status'] === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($farmer['status'] === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($farmer['status']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <div class="font-medium">Total: {{ number_format($farmer['total_allocated']) }}</div>
                                        @foreach($farmer['allocations'] as $allocation)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $allocation['commodity_name'] }}: {{ number_format($allocation['allocated_quantity']) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($farmer['collection_status'] === 'collected') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($farmer['collection_status']) }}
                                    </span>
                                    @if($farmer['total_collected'] > 0)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Collected: {{ number_format($farmer['total_collected']) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($farmer['payment_status'] === 'paid') bg-green-100 text-green-800
                                        @elseif($farmer['payment_status'] === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($farmer['payment_status']) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Applications Trend Chart --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">📈 Applications Trend</h2>
        <div class="w-full h-[300px]">
            <canvas id="applicationsTrendChart"></canvas>
        </div>
    </div>

    {{-- Season Overview --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">🌾 {{ $season->name }} Overview</h2>
            <div class="flex flex-wrap items-center gap-2">
                <!-- Export Buttons -->
                <!-- <a href="{{ route('admin.seasons.export.excel', $season->uuid) }}"
                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a> -->
                <a href="{{ route('admin.seasons.export.pdf', $season->uuid) }}"
                   target="_blank"
                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <!-- Toggle Season Status Button -->
                <!-- <form method="POST"
                    action="{{ $season->status === 'open' ? route('admin.seasons.close', $season->uuid) : route('admin.seasons.reopen', $season->uuid) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="{{ $season->status === 'open' ? 'closed' : 'open' }}">
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-white {{ $season->status === 'open' ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2">
                        {{ $season->status === 'open' ? '🔒 Close Season' : '🔓 Reopen Season' }}
                    </button>
                </form> -->
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-300">
            <div>
                <p><strong>Start:</strong> {{ $season->start_date }}</p>
                <p><strong>End:</strong> {{ $season->end_date }}</p>
            </div>
            <div>
                <p><strong>Collection Start Date:</strong> {{ $season->collection_start_date }}</p>
                <p><strong>Collection End Date:</strong> {{ $season->collection_end_date }}</p>
            </div>
            <div>
                <p><strong>Scenario:</strong> {{ $season->loan_type === 'complete-loan' ? 'Complete Loan (commodity return)' : 'Co-funded (50% upfront)' }}</p>
                @if ($season->loan_type === 'complete-loan')
                    <p><strong>Return Deadline:</strong> {{ $season->return_deadline }}</p>
                    <p><strong>Reminder Days:</strong> {{ $season->send_reminder_after_days }}</p>
                @else
                    <p><strong>Return:</strong> Not required</p>
                @endif
            </div>
            <div>
                <p><strong>Insurance Rate:</strong> {{ $season->insurance_rate }}%</p>
                <p><strong>Status:</strong>
                    <span class="{{ $season->status === 'open' ? 'text-green-600' : 'text-red-600' }}">
                        {{ ucfirst($season->status) }}
                    </span>
                </p>
            </div>
        </div>

        <hr class="my-6 border-gray-300 dark:border-gray-700">

        {{-- Commodity Distribution Table --}}
        @if ($commodities->isEmpty())
            <p class="text-sm text-gray-500">No commodities associated to this season yet.</p>
        @else
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">🧺 Commodities Distribution</h3>
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-3">Commodity</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Unit</th>
                            <th class="px-4 py-3">Available Stock</th>
                            <th class="px-4 py-3">Allocated</th>
                            <th class="px-4 py-3 text-green-600">Distributed</th>
                            <th class="px-4 py-3 text-yellow-600">Remaining</th>
                            <th class="px-4 py-3">Distributed %</th>
                            <th class="px-4 py-3">Remaining %</th>
                            <th class="px-4 py-3">Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                        @foreach ($commodities as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $item->name }}</td>
                                <td class="px-4 py-3">{{ $item->category }}</td>
                                <td class="px-4 py-3">{{ $item->unit }}</td>
                                <td class="px-4 py-3 text-purple-600 font-medium">{{ number_format($item->available_stock ?? 0) }}</td>
                                <td class="px-4 py-3">{{ number_format($item->allocated ?? 0) }}</td>
                                <td class="px-4 py-3 text-green-600 font-medium">{{ number_format($item->distributed ?? 0) }}</td>
                                <td class="px-4 py-3 text-yellow-600">{{ number_format($item->remaining ?? 0) }}</td>
                                <td class="px-4 py-3">{{ $item->allocated > 0 ? round(($item->distributed / $item->allocated) * 100, 1) : 0 }}%</td>
                                <td class="px-4 py-3">{{ $item->allocated > 0 ? round(($item->remaining / $item->allocated) * 100, 1) : 0 }}%</td>
                                <td class="px-4 py-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $item->allocated > 0 ? (($item->distributed / $item->allocated) * 100) : 0 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Alerts Panel --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">🚨 Alerts & Reminders</h2>

        <div class="space-y-3">
            @if($pendingCollections > 0)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                <strong>{{ $pendingCollections }}</strong> farmers yet to collect their inputs. Send reminders.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($season->loan_type === 'complete-loan' && $daysUntilReturn <= 10)
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-times text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-200">
                                Return deadline approaching in <strong>{{ $daysUntilReturn }}</strong> days. Remind farmers.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($totalRemaining == 0 && $season->status === 'open')
                <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-200">
                                All commodities have been distributed. Consider closing the season.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($overdueReturns > 0)
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-200">
                                <strong>{{ $overdueReturns }}</strong> overdue returns. Take action.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Chart Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Applications Trend Chart
        const trendLabels = @json($applicationTrendLabels); // e.g., ['Week 1', 'Week 2', ...]
        const trendData = @json($applicationTrendData); // counts per period

        const trendChartCanvas = document.getElementById('applicationsTrendChart');
        if (trendChartCanvas) {
            const ctx2 = trendChartCanvas.getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Applications',
                        data: trendData,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Applications Trend' }
                    }
                }
            });
        }
    });
</script>
@endsection
