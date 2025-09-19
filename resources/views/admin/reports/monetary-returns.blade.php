@extends('layouts.layout')

@section('content')
<div class="w-full px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Monetary Returns Reports</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Generate comprehensive reports on monetary returns</p>
                </div>
            </div>

            <!-- Export Actions -->
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.monetary-returns.export', request()->query()) }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('admin.reports.monetary-returns.pdf', request()->query()) }}"
                   class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-money-bill-wave text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Collected</p>
                        <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">₦{{ number_format($statistics['total_collected'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-green-600 dark:text-green-400 font-medium">Total Payments</p>
                        <p class="text-2xl font-bold text-green-800 dark:text-green-200">{{ number_format($statistics['total_payments'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl p-6 border border-yellow-200 dark:border-yellow-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Pending Payments</p>
                        <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ number_format($statistics['pending_payments'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-purple-600 dark:text-purple-400 font-medium">Average Payment</p>
                        <p class="text-2xl font-bold text-purple-800 dark:text-purple-200">₦{{ number_format($statistics['average_payment'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.reports.monetary-returns') }}"
              class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">

            <div class="flex gap-4 flex-wrap">
                <!-- Search -->
                <input type="text" name="filter" placeholder="Search Farmer Name or ID"
                    value="{{ request('filter') }}"
                    class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white" />

                <!-- Season Filter -->
                <select name="season"
                    class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white">
                    <option value="">All Seasons</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->slug }}" {{ request('season') == $season->slug ? 'selected' : '' }}>
                            {{ $season->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select name="status"
                    class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>

                <!-- Date Range -->
                <input type="date" name="from" value="{{ request('from') }}"
                    class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white" />
                <input type="date" name="to" value="{{ request('to') }}"
                    class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white" />

                <!-- Submit -->
                <button type="submit" class="px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Apply Filters
                </button>
            </div>
        </form>

        <!-- Reports Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-green-500 to-green-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Farmer Details</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Season</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Commodities</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Payment Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($returns as $return)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $return->application->farmer->full_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $return->application->farmer->registration_number }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $return->application->reference_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $return->application->season->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($return->application->season->return_deadline)->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($return->application->commodity_allocations as $c)
                                    <div class="text-sm">{{ $c->commodity_name }} ({{ $c->allocated_quantity }})</div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-green-600 dark:text-green-400 font-semibold">
                                ₦{{ number_format($return->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($return->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($return->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $return->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.monetary-returns.show', $return->uuid) }}"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.monetary-returns.report', $return->uuid) }}"
                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                No monetary returns found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $returns->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
