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
</style>

@section('content')
<div id="dashboard-section" class="section">
    <!-- Enhanced Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-800 bg-clip-text text-transparent">
                    Agent Dashboard
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Monitor your assigned farmers and verification tasks</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg px-4 py-2 border border-gray-200/50 dark:border-gray-700/50">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Live Updates</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Assigned Farmers -->
        <div class="group card-hover bg-gradient-to-br from-emerald-400 via-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm stat-icon">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-emerald-100">Total Assigned</span>
                    </div>
                    <p class="text-3xl font-bold mb-1">{{ number_format($totalFarmers) }}</p>
                    <p class="text-emerald-100 text-sm">Active farmers</p>
                </div>
                <div class="text-right">
                    <div class="text-emerald-200 text-xs">This Month</div>
                </div>
            </div>
        </div>

        <!-- Collections Verified -->
        <div class="group card-hover bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm stat-icon">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-blue-100">Collections</span>
                    </div>
                    <p class="text-3xl font-bold mb-1">{{ number_format($collectionsVerified) }}</p>
                    <p class="text-blue-100 text-sm">Verified collections</p>
                </div>
                <div class="text-right">
                    <div class="text-blue-200 text-xs">Completed</div>
                </div>
            </div>
        </div>

        <!-- Returns Verified -->
        <div class="group card-hover bg-gradient-to-br from-yellow-400 via-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm stat-icon">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-yellow-100">Returns</span>
                    </div>
                    <p class="text-3xl font-bold mb-1">{{ number_format($returnsVerified) }}</p>
                    <p class="text-yellow-100 text-sm">Verified returns</p>
                </div>
                <div class="text-right">
                    <div class="text-yellow-200 text-xs">Processed</div>
                </div>
            </div>
        </div>

        <!-- Today's Tasks -->
        <div class="group card-hover bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm stat-icon">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-purple-100">Today's Tasks</span>
                    </div>
                    <p class="text-3xl font-bold mb-1">{{ number_format($todayTasks) }}</p>
                    <p class="text-purple-100 text-sm">Pending tasks</p>
                </div>
                <div class="text-right">
                    <div class="text-purple-200 text-xs">Today</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Verification Activity</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Collections vs Returns verification trends</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-xs text-gray-600 dark:text-gray-400">Collections</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-xs text-gray-600 dark:text-gray-400">Returns</span>
                    </div>
                </div>
            </div>
            <div class="relative">
                <canvas id="tasksChart" height="120"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
            <div class="space-y-4">
                <a href="{{ route('agent.verify.collection') }}" class="group flex items-center p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-xl border border-emerald-200 dark:border-emerald-800 hover:shadow-md transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Verify Collections</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Check commodity collections</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('agent.verify.return') }}" class="group flex items-center p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl border border-indigo-200 dark:border-indigo-800 hover:shadow-md transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Verify Returns</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Process commodity returns</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Completion Rate</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">This week</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $totalFarmers > 0 ? round((($collectionsVerified + $returnsVerified) / ($totalFarmers * 2)) * 100, 1) : 0 }}%
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full transition-all duration-500"
                                 style="width: {{ $totalFarmers > 0 ? round((($collectionsVerified + $returnsVerified) / ($totalFarmers * 2)) * 100, 1) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('tasksChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Verification Count',
                data: @json($chartData['values']),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(250, 204, 21, 0.8)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(250, 204, 21, 1)'
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
                            return context[0].label + ' Verifications';
                        },
                        label: function(context) {
                            return 'Count: ' + context.parsed.y;
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
                        stepSize: 1
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
