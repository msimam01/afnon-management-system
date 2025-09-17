@extends('layouts.layout')

@section('content')
<div class="w-full px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Paid Monetary Returns</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">All payments collected so far</p>
                </div>
            </div>

            <!-- Total Collected -->
            <div class="bg-green-100 dark:bg-green-900 px-6 py-3 rounded-xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-300">Total Collected</p>
                <p class="text-2xl font-bold text-green-700 dark:text-green-300">
                    ₦{{ number_format($totalCollected, 2) }}
                </p>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.monetary-returns') }}"
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

                <!-- Date Range -->
                <input type="date" name="from" value="{{ request('from') }}"
                    class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white" />
                <input type="date" name="to" value="{{ request('to') }}"
                    class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white" />

                <!-- Submit -->
                <button type="submit" class="px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Apply
                </button>
            </div>
        </form>

        <!-- Paid Returns Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-green-500 to-green-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Farmer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Commodities</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Amount Paid</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
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
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($return->application->commodity_allocations as $c)
                                    <div class="text-sm">{{ $c->commodity_name }} ({{ $c->allocated_quantity }})</div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-green-600 dark:text-green-400 font-semibold">
                                ₦{{ number_format($return->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $return->created_at->format('d M, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                No paid returns found.
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
