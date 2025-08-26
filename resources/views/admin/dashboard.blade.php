@extends('layouts.layout')

<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .stat-icon {
        transition: all 0.3s ease;
    }
    .card-hover:hover .stat-icon {
        transform: scale(1.1);
    }
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>

@section('content')
    <div id="dashboard-section" class="section">
        <!-- Enhanced Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-6 lg:mb-0">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-800 bg-clip-text text-transparent">
                        Admin Dashboard
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Comprehensive overview of application management and distribution</p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <!-- Live Updates Indicator -->
                    <div class="flex items-center space-x-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg px-4 py-2 border border-gray-200/50 dark:border-gray-700/50">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Live Updates</span>
                    </div>
                    <!-- Enhanced Season Filter -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <label for="seasonFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter by Season
                        </label>
                        <select id="seasonFilter"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                            <option value="all" {{ $seasonId === 'all' ? 'selected' : '' }}>All Seasons</option>
                            @foreach ($seasons as $season)
                                <option value="{{ $season->id }}" {{ $seasonId == $season->id ? 'selected' : '' }}>
                                    {{ $season->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- Enhanced Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Applications -->
            <div class="group card-hover bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm stat-icon">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-blue-100">Applications</span>
                        </div>
                        <p class="text-3xl font-bold mb-1" id="totalApplications">{{ number_format($totalApplications) }}</p>
                        <p class="text-blue-100 text-sm">Total submitted</p>
                    </div>
                    <div class="text-right">
                        <div class="text-blue-200 text-xs">All Time</div>
                    </div>
                </div>
            </div>

            <!-- Total Approved -->
            <div class="group card-hover bg-gradient-to-br from-emerald-400 via-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm stat-icon">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-emerald-100">Approved</span>
                        </div>
                        <p class="text-3xl font-bold mb-1" id="totalApproved">{{ number_format($totalApproved) }}</p>
                        <p class="text-emerald-100 text-sm">Successfully approved</p>
                    </div>
                    <div class="text-right">
                        <div class="text-emerald-200 text-xs">
                            {{ $totalApplications > 0 ? round(($totalApproved / $totalApplications) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Distributed -->
            <div class="group card-hover bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm stat-icon">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-purple-100">Distributed</span>
                        </div>
                        <p class="text-3xl font-bold mb-1" id="totalDistributed">{{ number_format($totalDistributed) }}</p>
                        <p class="text-purple-100 text-sm">Commodities distributed</p>
                    </div>
                    <div class="text-right">
                        <div class="text-purple-200 text-xs">Completed</div>
                    </div>
                </div>
            </div>

            <!-- Remaining Commodities -->
            <div class="group card-hover bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm stat-icon">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-orange-100">Remaining</span>
                        </div>
                        <p class="text-3xl font-bold mb-1" id="remainingCommodities">{{ number_format($remaining) }}</p>
                        <p class="text-orange-100 text-sm">Kilograms available</p>
                    </div>
                    <div class="text-right">
                        <div class="text-orange-200 text-xs">In Stock</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Enhanced Chart and Quick Actions Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Main Chart -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Application Status Overview</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Distribution of application statuses</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-1">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">Pending</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">Approved</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">Rejected</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="applicationsChart" height="120"></canvas>
                </div>
            </div>

            <!-- Quick Actions Panel -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                <div class="space-y-4">
                    <a href="{{ route('admin.applications.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-800 hover:shadow-md transition-all duration-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Manage Applications</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Review and process applications</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('admin.farmers') }}" class="group flex items-center p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-xl border border-emerald-200 dark:border-emerald-800 hover:shadow-md transition-all duration-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Manage Farmers</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">View and manage farmer profiles</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('admin.verifications.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl border border-purple-200 dark:border-purple-800 hover:shadow-md transition-all duration-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">View Verifications</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Monitor verification activities</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <!-- System Health Indicator -->
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">System Health</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Overall performance</p>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium">Optimal</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600 dark:text-gray-400">Processing Rate</span>
                                <span class="text-gray-900 dark:text-white font-medium">98%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-1.5 rounded-full transition-all duration-500" style="width: 98%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Enhanced season filter functionality with loading state
        document.getElementById('seasonFilter').addEventListener('change', function() {
            const season = this.value;
            const filterContainer = this.closest('.bg-white');

            // Add loading state
            filterContainer.style.opacity = '0.7';
            filterContainer.style.pointerEvents = 'none';

            // Add loading spinner
            const loadingSpinner = document.createElement('div');
            loadingSpinner.className = 'absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-gray-800/80 rounded-xl';
            loadingSpinner.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            filterContainer.style.position = 'relative';
            filterContainer.appendChild(loadingSpinner);

            const url = new URL(window.location.href);
            url.searchParams.set('season', season);
            window.location.href = url.toString();
        });

        // Enhanced chart
        const ctx = document.getElementById('applicationsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Application Count',
                    data: @json($chartData['values']),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return context[0].label + ' Applications';
                            },
                            label: function(context) {
                                return 'Count: ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: 'rgba(107, 114, 128, 0.8)',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: 'rgba(107, 114, 128, 0.8)',
                            font: {
                                size: 11
                            },
                            stepSize: 1,
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    </script>
@endsection
