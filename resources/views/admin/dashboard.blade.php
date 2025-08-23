@extends('layouts.layout')

@section('content')
    <div id="dashboard-section" class="section">
        <!-- Season Filter -->
        <div class="mb-6">
            <label for="seasonFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by
                Season</label>
            <select id="seasonFilter"
                class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="all" {{ $seasonId === 'all' ? 'selected' : '' }}>All Seasons</option>
                @foreach ($seasons as $season)
                    <option value="{{ $season->id }}" {{ $seasonId == $season->id ? 'selected' : '' }}>
                        {{ $season->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="totalApplications">
                            {{ number_format($totalApplications) }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Applications</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="totalApproved">
                            {{ number_format($totalApproved) }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Approved</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="totalDistributed">
                            {{ number_format($totalDistributed) }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Distributed</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="remainingCommodities">
                            {{ number_format($remaining) }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Remaining (kg)</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Chart Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <canvas id="applicationsChart" height="120"></canvas>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById('seasonFilter').addEventListener('change', function() {
            const season = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('season', season);
            window.location.href = url.toString();
        });
        const ctx = document.getElementById('applicationsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Applications',
                    data: @json($chartData['values']),
                    backgroundColor: ['#3b82f6', '#10b981', '#ef4444'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
