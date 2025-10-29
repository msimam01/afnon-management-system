@extends('layouts.layout')

@push('styles')
    <style>
        .report-card {
            transition: box-shadow 0.3s ease;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .report-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .report-icon {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }

        .stats-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            transition: box-shadow 0.3s ease;
        }

        .stats-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-trend {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }

        .trend-up {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .trend-down {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .filter-bar {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .activity-chart {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .notification-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background: #f1f5f9;
        }

        .loading-spinner {
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 2px solid #3b82f6;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 gap-8">
            <!-- Header -->
            <div class="bg-white shadow-lg rounded-lg p-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex items-start gap-6">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-lg">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">Reports Dashboard</h1>
                                <button id="help-toggle" class="text-gray-400 hover:text-gray-600 transition-colors" title="Help & Tips">
                                    <i class="fas fa-question-circle text-sm"></i>
                                </button>
                            </div>
                            <p class="text-gray-600 text-lg mb-4">Comprehensive reporting and analytics platform for agricultural input distribution management</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                    <i class="fas fa-circle text-xs mr-2 text-green-600"></i>
                                    Live Data
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-shield-alt text-xs mr-2"></i>
                                    Compliant & Secure
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-bolt text-xs mr-2"></i>
                                    Real-time Updates
                                </span>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- Help Panel (Hidden by default) -->
                <div id="help-panel"
                    class="mt-6 bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-800 rounded-xl p-4 hidden">
                    <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Quick Help</h4>
                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li>• Use the search bar to quickly find specific seasons or reports</li>
                        <li>• Click on any report card to generate instant reports</li>
                        <li>• Monitor real-time notifications for system updates</li>
                        <li>• Hover over trend indicators to see detailed statistics</li>
                    </ul>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <div class="stats-card p-6">
                    <div class="stats-icon bg-emerald-500 text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl lg:text-3xl font-bold text-emerald-800">{{ $seasons->count() }}</h3>
                            <span class="stat-trend trend-up">+12% ↑</span>
                        </div>
                        <p class="text-sm font-medium text-emerald-700 mt-2">Active Seasons</p>
                        <p class="text-xs text-emerald-600 mt-1">
                            {{ $seasons->where('status', 'active')->count() }} active,
                            {{ $seasons->where('status', 'pending')->count() }} pending
                        </p>
                    </div>
                </div>
                <div class="stats-card p-6">
                    <div class="stats-icon bg-blue-500 text-white">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl lg:text-3xl font-bold text-blue-800">{{ $tenants->count() }}</h3>
                            <span class="stat-trend trend-up">+8% ↑</span>
                        </div>
                        <p class="text-sm font-medium text-blue-700 mt-2">Active Tenants</p>
                        <p class="text-xs text-blue-600 mt-1">
                            {{ $tenants->where('status', 'active')->count() }} active,
                            {{ $tenants->where('status', 'pending')->count() }} pending
                        </p>
                    </div>
                </div>
                <div class="stats-card p-6">
                    <div class="stats-icon bg-purple-500 text-white">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl lg:text-3xl font-bold text-purple-800">24</h3>
                            <span class="stat-trend trend-up">+15% ↑</span>
                        </div>
                        <p class="text-sm font-medium text-purple-700 mt-2">Reports Available</p>
                        <p class="text-xs text-purple-600 mt-1">3 primary types + 21 variations</p>
                    </div>
                </div>
                <div class="stats-card p-6">
                    <div class="stats-icon bg-yellow-500 text-white">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl lg:text-3xl font-bold text-yellow-800">30m</h3>
                            <span class="stat-trend trend-down">-5% ↓</span>
                        </div>
                        <p class="text-sm font-medium text-yellow-700 mt-2">Avg Response Time</p>
                        <p class="text-xs text-yellow-600 mt-1">Report generation optimized</p>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="globalSearch" placeholder="Search seasons, tenants..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        </div>
                    </div>
                    <div class="flex gap-2 sm:gap-3 flex-shrink-0">
                        <select id="filterType" class="px-3 sm:px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
                            <option value="all">All</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                        </select>
                        <button id="refreshBtn" class="px-3 sm:px-4 py-3 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 transition-colors flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i>
                            <span class="hidden sm:inline text-sm sm:text-base">Refresh</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="activity-chart">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-chart-area text-blue-500 mr-3"></i>
                            Recent Activity Overview
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Report generation and system activity
                            trends</p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">7D</button>
                        <button
                            class="px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-lg">30D</button>
                        <button
                            class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">90D</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/50 rounded-xl">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ rand(45, 67) }}</div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Reports Generated Today</p>
                        <div class="mt-2">
                            <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ rand(70, 95) }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="text-center p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/50 dark:to-emerald-800/50 rounded-xl">
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">{{ $seasons->count() }}
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Active Seasons Monitored</p>
                        <div class="mt-2">
                            <div class="w-full bg-emerald-200 dark:bg-emerald-800 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/50 rounded-xl">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-2">{{ $tenants->count() }}
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tenants with Reports</p>
                        <div class="mt-2">
                            <div class="w-full bg-purple-200 dark:bg-purple-800 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: 88%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mini Chart Placeholder -->
                <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                    <canvas id="activityChart" height="100"></canvas>
                </div>
            </div>

            <!-- Report Categories -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Season Allocation Report -->
                <div class="report-card bg-white dark:bg-gray-800 shadow-xl">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-6">
                        <div class="report-icon bg-white bg-opacity-20 text-white">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white text-center">Season Allocation</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Comprehensive view of commodity allocations across
                            all seasons and tenants with visual charts.</p>
                        <div class="space-y-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check text-emerald-500 mr-2"></i>
                                <span>Budget analysis</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-emerald-500 mr-2"></i>
                                <span>Stock distribution</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-emerald-500 mr-2"></i>
                                <span>Interactive charts</span>
                            </div>
                        </div>
                        <form action="{{ route('global.reports.season-allocation') }}" method="GET" class="space-y-4">
                            @csrf
                            <div>
                                <select name="season_uuid" id="season_uuid" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Select a season...</option>
                                    @foreach ($seasons as $season)
                                        <option value="{{ $season->uuid }}">{{ $season->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="w-full bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition-colors font-medium text-sm">
                                <i class="fas fa-chart-pie mr-2"></i> Generate Report
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tenant Distribution Report -->
                <div class="report-card bg-white dark:bg-gray-800 shadow-xl">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
                        <div class="report-icon bg-white bg-opacity-20 text-white">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white text-center">Tenant Distribution</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Detailed tenant-specific commodity distributions,
                            farmer returns, and variance analysis.</p>
                        <div class="space-y-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check text-blue-500 mr-2"></i>
                                <span>Farm-level tracking</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-blue-500 mr-2"></i>
                                <span>Return compliance</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-blue-500 mr-2"></i>
                                <span>Variance analysis</span>
                            </div>
                        </div>
                        <form action="{{ route('global.reports.tenant-distribution') }}" method="GET" class="space-y-4">
                            @csrf
                            <select name="season_uuid" id="td_season_uuid" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select season...</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->uuid }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                            <select name="tenant_id" id="tenant_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select tenant...</option>
                                @foreach ($tenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->id }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                                <i class="fas fa-users mr-2"></i> Generate Report
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Return Compliance Report -->
                <div class="report-card bg-white dark:bg-gray-800 shadow-xl">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-6">
                        <div class="report-icon bg-white bg-opacity-20 text-white">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white text-center">Return Compliance</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Season-wide return compliance analysis with
                            overdue alerts and shortfall tracking.</p>
                        <div class="space-y-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check text-purple-500 mr-2"></i>
                                <span>Overdue monitoring</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-purple-500 mr-2"></i>
                                <span>Variance reports</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-purple-500 mr-2"></i>
                                <span>Compliance alerts</span>
                            </div>
                        </div>
                        <form action="{{ route('global.reports.return-compliance') }}" method="GET" class="space-y-4">
                            @csrf
                            <select name="season_uuid" id="rc_season_uuid" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Select a season...</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->uuid }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition-colors font-medium text-sm">
                                <i class="fas fa-clipboard-check mr-2"></i> Generate Report
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Real-time Notifications -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-bell text-yellow-500 mr-3"></i>
                        Real-time Notifications
                    </h3>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Live</span>
                </div>
                <div id="notifications" class="space-y-3 min-h-[100px]">
                    <div class="flex items-center justify-center text-gray-500 dark:text-gray-400 py-8">
                        <div class="text-center">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>No new notifications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Enhanced interactive features
            document.addEventListener('DOMContentLoaded', function() {
                // Dark mode toggle with enhanced effects
                const darkModeToggle = document.getElementById('darkModeToggle');
                const html = document.documentElement;
                const savedTheme = localStorage.getItem('theme') || 'light';

                if (savedTheme === 'dark') {
                    html.classList.add('dark');
                    darkModeToggle.innerHTML = '<i class="fas fa-sun mr-2"></i> Light Mode';
                }

                darkModeToggle.addEventListener('click', () => {
                    const isDark = html.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    darkModeToggle.innerHTML = isDark ?
                        '<i class="fas fa-sun mr-2"></i> Light Mode' :
                        '<i class="fas fa-moon mr-2"></i> Dark Mode';

                    // Recreate chart on theme change
                    if (window.activityChart) {
                        window.activityChart.destroy();
                        createActivityChart();
                    }
                });

                // Help panel toggle
                const helpToggle = document.getElementById('help-toggle');
                const helpPanel = document.getElementById('help-panel');

                helpToggle.addEventListener('click', () => {
                    helpPanel.classList.toggle('hidden');
                });

                // Global search functionality
                const globalSearch = document.getElementById('globalSearch');
                let searchTimeout;

                globalSearch.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const query = this.value.toLowerCase();
                        const reportCards = document.querySelectorAll('.report-card');

                        reportCards.forEach(card => {
                            const title = card.querySelector('h3').textContent.toLowerCase();
                            const description = card.querySelector('p').textContent
                                .toLowerCase();

                            if (title.includes(query) || description.includes(query)) {
                                card.style.display = 'block';
                                card.style.animation = 'fadeIn 0.3s ease-out';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    }, 300);
                });

                // Filter functionality
                const filterType = document.getElementById('filterType');
                filterType.addEventListener('change', function() {
                    // This would filter the displayed seasons based on status
                    // For now, just show a visual feedback
                    console.log('Filtering by:', this.value);
                });

                // Refresh button with loading animation
                const refreshBtn = document.getElementById('refreshBtn');
                refreshBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    const text = this.querySelector('span');

                    icon.classList.add('fa-spin');
                    this.disabled = true;
                    text.textContent = 'Refreshing...';

                    // Simulate refresh
                    setTimeout(() => {
                        icon.classList.remove('fa-spin');
                        this.disabled = false;
                        text.textContent = 'Refresh';

                        // Show success notification
                        showNotification('Dashboard refreshed successfully!', 'success');
                    }, 2000);
                });

                // Quick actions
                document.querySelectorAll('.quick-action-button').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const action = this.dataset.action;

                        switch (action) {
                            case 'quick-season':
                                // Auto-select latest season and submit
                                const latestSeason = document.querySelector(
                                    '#season_uuid option:nth-child(2)');
                                if (latestSeason) {
                                    document.getElementById('season_uuid').value = latestSeason.value;
                                    document.querySelector('.report-card:first-child form').submit();
                                }
                                break;
                            case 'quick-tenant':
                                // Show tenant distribution for most active tenant
                                showNotification('Loading top tenant reports...', 'info');
                                break;
                            case 'quick-compliance':
                                // Navigate to compliance report
                                const complianceForm = document.querySelector(
                                    '.report-card:last-child form');
                                if (complianceForm) {
                                    complianceForm.submit();
                                }
                                break;
                            case 'quick-export':
                                // Trigger bulk export
                                showNotification('Preparing bulk export...', 'info');
                                break;
                        }
                    });
                });

                // Export all button
                document.getElementById('exportAllBtn').addEventListener('click', function() {
                    showNotification('Preparing comprehensive data export...', 'info');

                    // Simulate export process
                    setTimeout(() => {
                        showNotification('Export completed! Check your downloads.', 'success');
                    }, 3000);
                });

                // Enhanced form validation with better UX
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const selects = form.querySelectorAll('select[required]');
                        let isValid = true;

                        selects.forEach(select => {
                            if (!select.value) {
                                isValid = false;
                                e.preventDefault();

                                select.classList.add('border-red-500', 'animate-pulse');
                                select.nextElementSibling?.remove();

                                const errorMsg = document.createElement('p');
                                errorMsg.className =
                                    'text-red-500 text-xs mt-1 flex items-center';
                                errorMsg.innerHTML =
                                    '<i class="fas fa-exclamation-circle mr-1"></i> This field is required';
                                select.parentNode.insertBefore(errorMsg, select.nextSibling);
                            } else {
                                select.classList.remove('border-red-500', 'animate-pulse');
                                select.nextElementSibling?.remove();
                            }
                        });

                        if (isValid) {
                            // Show loading state
                            const button = form.querySelector('button[type="submit"]');
                            const originalText = button.innerHTML;
                            button.innerHTML = '<div class="loading-spinner"></div> Generating...';
                            button.disabled = true;

                            // Re-enable after 10 seconds (fallback)
                            setTimeout(() => {
                                button.innerHTML = originalText;
                                button.disabled = false;
                            }, 10000);
                        }
                    });
                });

                // Activity Chart
                createActivityChart();

                // Real-time notifications with enhanced styling
                let notificationCount = 0;
                const notificationTypes = [{
                        icon: 'fa-sync-alt',
                        title: 'Season sync completed',
                        message: 'Data updated for season monitoring',
                        type: 'success'
                    },
                    {
                        icon: 'fa-chart-line',
                        title: 'Report generated',
                        message: 'New analytics data available',
                        type: 'info'
                    },
                    {
                        icon: 'fa-user-check',
                        title: 'Tenant activity detected',
                        message: 'New farmer applications processed',
                        type: 'warning'
                    },
                    {
                        icon: 'fa-database',
                        title: 'Data backup completed',
                        message: 'System integrity verified',
                        type: 'success'
                    }
                ];

                // More frequent notifications for demo
                setInterval(() => {
                    if (Math.random() > 0.7) { // Increased frequency
                        notificationCount++;

                        const randomType = notificationTypes[Math.floor(Math.random() * notificationTypes
                            .length)];
                        const notifications = document.getElementById('notifications');
                        const newNotification = document.createElement('div');

                        let bgClass, textClass;
                        switch (randomType.type) {
                            case 'success':
                                bgClass =
                                    'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-800';
                                textClass = 'text-green-800 dark:text-green-200';
                                break;
                            case 'warning':
                                bgClass =
                                    'bg-yellow-50 dark:bg-yellow-900/30 border-yellow-200 dark:border-yellow-800';
                                textClass = 'text-yellow-800 dark:text-yellow-200';
                                break;
                            default:
                                bgClass = 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800';
                                textClass = 'text-blue-800 dark:text-blue-200';
                        }

                        newNotification.className =
                            `notification-item border ${bgClass} p-4 rounded-xl animate-fade-in cursor-pointer`;
                        newNotification.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas ${randomType.icon} ${textClass} mr-3 mt-1"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium ${textClass}">${randomType.title}</p>
                            <p class="text-xs opacity-75 ${textClass} mt-1">${randomType.message}</p>
                            <p class="text-xs opacity-50 ${textClass} mt-2">${new Date().toLocaleTimeString()}</p>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 ml-2" onclick="this.closest('.notification-item').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

                        // Add click handler for notification actions
                        newNotification.addEventListener('click', function(e) {
                            if (!e.target.closest('button')) {
                                // Handle notification click (could navigate to relevant page)
                                console.log('Notification clicked:', randomType.title);
                            }
                        });

                        notifications.insertBefore(newNotification, notifications.firstChild);

                        // Auto-remove after 45 seconds
                        setTimeout(() => {
                            if (newNotification.parentNode) {
                                newNotification.remove();
                                notificationCount--;
                            }
                        }, 45000);
                    }
                }, 8000); // More frequent updates

                // Performance monitoring (simulate real-time stats)
                setInterval(() => {
                    document.querySelectorAll('.stat-trend').forEach(trend => {
                        const isUp = trend.classList.contains('trend-up');
                        const currentValue = parseInt(trend.textContent.replace(/[^\d-]/g, ''));
                        const newValue = currentValue + (Math.random() > 0.5 ? 1 : -1);
                        const prefix = isUp ? '+' : '';

                        trend.textContent = `${prefix}${newValue}% ${isUp ? '↑' : '↓'}`;

                        // Update color based on value
                        if (newValue > 0) {
                            trend.className = 'stat-trend trend-up';
                        } else {
                            trend.className = 'stat-trend trend-down';
                        }
                    });
                }, 30000); // Update every 30 seconds
            });

            // Utility functions
            function showNotification(message, type = 'info') {
                // Create toast notification
                const toast = document.createElement('div');
                toast.className =
                    `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300 max-w-sm`;

                let bgClass, iconClass, title;
                switch (type) {
                    case 'success':
                        bgClass = 'bg-green-500 text-white';
                        iconClass = 'fas fa-check-circle';
                        title = 'Success';
                        break;
                    case 'error':
                        bgClass = 'bg-red-500 text-white';
                        iconClass = 'fas fa-exclamation-circle';
                        title = 'Error';
                        break;
                    case 'warning':
                        bgClass = 'bg-yellow-500 text-white';
                        iconClass = 'fas fa-exclamation-triangle';
                        title = 'Warning';
                        break;
                    default:
                        bgClass = 'bg-blue-500 text-white';
                        iconClass = 'fas fa-info-circle';
                        title = 'Info';
                }

                toast.className += ` ${bgClass}`;
                toast.innerHTML = `
            <div class="flex items-center">
                <i class="${iconClass} mr-3"></i>
                <div>
                    <p class="font-semibold">${title}</p>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                <button class="ml-auto hover:opacity-75" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.classList.add('translate-x-full');
                        setTimeout(() => toast.remove(), 300);
                    }
                }, 5000);
            }

            function createActivityChart() {
                const ctx = document.getElementById('activityChart');
                if (!ctx) return;

                const isDark = document.documentElement.classList.contains('dark');

                const labels = [];
                const data = [];

                // Generate last 30 days
                for (let i = 29; i >= 0; i--) {
                    const date = new Date();
                    date.setDate(date.getDate() - i);
                    labels.push(date.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    }));

                    // Simulate activity data
                    data.push(Math.floor(Math.random() * 50) + 10);
                }

                window.activityChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Daily Reports Generated',
                            data: data,
                            borderColor: isDark ? 'rgba(59, 130, 246, 0.8)' : 'rgba(59, 130, 246, 1)',
                            backgroundColor: isDark ? 'rgba(59, 130, 246, 0.1)' : 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: isDark ? 'rgba(59, 130, 246, 1)' : 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
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
                                backgroundColor: isDark ? 'rgba(31, 41, 55, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                                titleColor: isDark ? '#ffffff' : '#1f2937',
                                bodyColor: isDark ? '#d1d5db' : '#374151',
                                borderColor: isDark ? '#374151' : '#d1d5db',
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: isDark ? 'rgba(75, 85, 99, 0.2)' : 'rgba(209, 213, 219, 0.5)'
                                },
                                ticks: {
                                    color: isDark ? '#d1d5db' : '#374151',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: isDark ? '#d1d5db' : '#374151',
                                    font: {
                                        size: 12
                                    },
                                    maxTicksLimit: 7
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        animation: {
                            duration: 2000,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            }

            // Add fadeIn animation to CSS if not already present
            if (!document.querySelector('style[data-fadein]')) {
                const style = document.createElement('style');
                style.setAttribute('data-fadein', 'true');
                style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out;
            }
        `;
                document.head.appendChild(style);
            }
        </script>
    @endpush
@endsection
