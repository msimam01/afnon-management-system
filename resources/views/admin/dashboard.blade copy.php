<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Agricultural Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 2s infinite;
        }
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .stats-counter {
            animation: countUp 2s ease-out;
        }
        @keyframes countUp {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Agricultural Distribution Dashboard</h1>
                    <p class="text-emerald-100 mt-1">Real-time monitoring and management system</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-emerald-100">Last Updated</p>
                        <p class="font-semibold" id="lastUpdated"></p>
                    </div>
                    <button class="bg-white/20 backdrop-blur-sm rounded-lg p-2 hover:bg-white/30 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Season Filter -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Filter Dashboard Data</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Select a season to view specific data</p>
                </div>
                <select id="seasonFilter" onchange="updateDashboard()" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="all">All Seasons</option>
                    <option value="2025-dry">2025 Dry Season</option>
                    <option value="2025-wet">2025 Wet Season</option>
                    <option value="2024-wet">2024 Wet Season</option>
                </select>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm card-hover p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-xl">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white stats-counter" id="totalApplications">2,847</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Applications</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="flex items-center text-sm text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        +12% from last month
                    </div>
                    <div class="ml-auto">
                        <div class="w-16 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                            <div class="w-12 h-2 bg-blue-500 rounded-full animate-pulse-subtle"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Approved -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm card-hover p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-xl">
                        <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white stats-counter" id="totalApproved">2,103</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Approved</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="flex items-center text-sm text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        74% approval rate
                    </div>
                    <div class="ml-auto">
                        <div class="w-16 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                            <div class="w-12 h-2 bg-emerald-500 rounded-full animate-pulse-subtle"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Distributed -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm card-hover p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-xl">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white stats-counter" id="totalDistributed">1,850</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Distributed</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="flex items-center text-sm text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        88% of approved
                    </div>
                    <div class="ml-auto">
                        <div class="w-16 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                            <div class="w-14 h-2 bg-green-500 rounded-full animate-pulse-subtle"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remaining Commodities -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm card-hover p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-xl">
                        <svg class="w-7 h-7 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white stats-counter" id="remainingCommodities">12,450</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Remaining (kg)</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="flex items-center text-sm text-yellow-600 dark:text-yellow-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Available for distribution
                    </div>
                    <div class="ml-auto">
                        <div class="w-16 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                            <div class="w-8 h-2 bg-yellow-500 rounded-full animate-pulse-subtle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Distribution Progress</h3>
                    <button class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">Export</button>
                </div>
                <canvas id="distributionChart" width="400" height="300"></canvas>
            </div>

            <!-- Zone Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Distribution by Zone</h3>
                    <button class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">View Details</button>
                </div>
                <canvas id="zoneChart" width="400" height="300"></canvas>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Applications</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and track farmer applications</p>
                    </div>
                    <div class="flex space-x-3">
                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Application
                        </button>
                        <button class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">View All</button>
                    </div>
                </div>

                <!-- Enhanced Filters -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Season</label>
                        <select id="seasonFilterTable" onchange="filterApplications()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Seasons</option>
                            <option value="2025-dry">2025 Dry Season</option>
                            <option value="2025-wet">2025 Wet Season</option>
                            <option value="2024-wet">2024 Wet Season</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="statusFilterTable" onchange="filterApplications()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="distributed">Distributed</option>
                            <option value="verified">Verified</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cluster</label>
                        <select id="clusterFilterTable" onchange="filterApplications()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Clusters</option>
                            <option value="gwagwalada">Gwagwalada</option>
                            <option value="kuje">Kuje</option>
                            <option value="abaji">Abaji</option>
                            <option value="kwali">Kwali</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" id="searchTable" placeholder="Search farmers..." onkeyup="filterApplications()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Actions</label>
                        <button onclick="resetFilters()" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">Reset Filters</button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer" onclick="sortTable('farmer')">
                                    Farmer Name
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Farm Size</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cluster</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Season</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="applicationsTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Enhanced table rows -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-season="2025-dry" data-status="pending" data-cluster="gwagwalada" data-farmer="john doe">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center shadow-sm">
                                        <span class="text-sm font-semibold text-white">JD</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">John Doe</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">NCF-2025-001</div>
                                        <div class="text-xs text-emerald-600 dark:text-emerald-400">Maize, Soybean</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">2.5 hectares</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Medium farm</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">Gwagwalada</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">North Zone</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">2025 Dry Season</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="viewApplication('NCF-2025-001')" class="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 font-medium">View</button>
                                    <button onclick="approveApplication('NCF-2025-001')" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">Approve</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-season="2025-dry" data-status="approved" data-cluster="kuje" data-farmer="mary johnson">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                        <span class="text-sm font-semibold text-white">MJ</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">Mary Johnson</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">NCF-2025-002</div>
                                        <div class="text-xs text-emerald-600 dark:text-emerald-400">Rice, Cassava</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">3.0 hectares</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Large farm</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">Kuje</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">South Zone</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">2025 Dry Season</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                    <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2"></span>
                                    Approved
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="viewApplication('NCF-2025-002')" class="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 font-medium">View</button>
                                    <button onclick="distributeApplication('NCF-2025-002')" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 font-medium">Distribute</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-season="2025-wet" data-status="distributed" data-cluster="abaji" data-farmer="ahmed ibrahim">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center shadow-sm">
                                        <span class="text-sm font-semibold text-white">AI</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">Ahmed Ibrahim</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">NCF-2025-003</div>
                                        <div class="text-xs text-emerald-600 dark:text-emerald-400">Yam, Plantain</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">1.8 hectares</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Small farm</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">Abaji</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">East Zone</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">2025 Wet Season</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                    Distributed
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="viewApplication('NCF-2025-003')" class="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 font-medium">View</button>
                                    <button onclick="verifyApplication('NCF-2025-003')" class="text-green-600 hover:text-green-700 dark:text-green-400 font-medium">Verify</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            <div class="bg-white dark:bg-gray-800 px-6 py-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">Previous</button>
                    <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">Next</button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of
                            <span class="font-medium">3</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button class="bg-emerald-50 dark:bg-emerald-900 border-emerald-500 text-emerald-600 dark:text-emerald-400 relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</button>
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">2</button>
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">3</button>
                            <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl p-6 text-white card-hover">
                <div class="flex items-center">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold">Add New Application</h3>
                        <p class="text-emerald-100 text-sm">Register a new farmer application</p>
                    </div>
                </div>
                <button class="mt-4 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Create Application
                </button>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white card-hover">
                <div class="flex items-center">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a4 4 0 01-4-4V5a4 4 0 014-4h2m4 0h2a4 4 0 014 4v12a4 4 0 01-4 4h-2m-4-16v4h4V4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold">Generate Report</h3>
                        <p class="text-blue-100 text-sm">Create distribution reports</p>
                    </div>
                </div>
                <button class="mt-4 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Generate Report
                </button>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white card-hover">
                <div class="flex items-center">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold">System Settings</h3>
                        <p class="text-purple-100 text-sm">Configure system parameters</p>
                    </div>
                </div>
                <button class="mt-4 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Open Settings
                </button>
            </div>
        </div>
    </div>

    <!-- Application Modal -->
    <div id="applicationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Application Details</h3>
                    <button onclick="closeApplicationModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="modalContent" class="space-y-4">
                    <!-- Modal content will be populated by JavaScript -->
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button onclick="closeApplicationModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Close
                    </button>
                    <button class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                        Take Action
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            updateLastUpdated();
            initializeCharts();
            animateCounters();
        });

        // Update last updated time
        function updateLastUpdated() {
            const now = new Date();
            document.getElementById('lastUpdated').textContent = now.toLocaleTimeString();
        }

        // Initialize charts
        function initializeCharts() {
            // Distribution Progress Chart
            const distributionCtx = document.getElementById('distributionChart').getContext('2d');
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Distributed', 'Approved (Pending)', 'Remaining Stock'],
                    datasets: [{
                        data: [1850, 253, 12450],
                        backgroundColor: ['#10b981', '#f59e0b', '#6b7280'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Zone Distribution Chart
            const zoneCtx = document.getElementById('zoneChart').getContext('2d');
            new Chart(zoneCtx, {
                type: 'bar',
                data: {
                    labels: ['North Zone', 'South Zone', 'East Zone', 'West Zone'],
                    datasets: [{
                        label: 'Applications',
                        data: [647, 740, 463, 520],
                        backgroundColor: ['#10b981', '#059669', '#047857', '#065f46'],
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
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Animate counters
        function animateCounters() {
            const counters = document.querySelectorAll('.stats-counter');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/,/g, ''));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current).toLocaleString();
                }, 20);
            });
        }

        // Dashboard update function
        function updateDashboard() {
            const season = document.getElementById('seasonFilter').value;
            // Simulate data update based on season
            const data = {
                all: { apps: 2847, approved: 2103, distributed: 1850, remaining: 12450 },
                '2025-dry': { apps: 1200, approved: 890, distributed: 780, remaining: 5200 },
                '2025-wet': { apps: 950, approved: 720, distributed: 620, remaining: 4100 },
                '2024-wet': { apps: 697, approved: 493, distributed: 450, remaining: 3150 }
            };
            
            const selectedData = data[season] || data.all;
            
            document.getElementById('totalApplications').textContent = selectedData.apps.toLocaleString();
            document.getElementById('totalApproved').textContent = selectedData.approved.toLocaleString();
            document.getElementById('totalDistributed').textContent = selectedData.distributed.toLocaleString();
            document.getElementById('remainingCommodities').textContent = selectedData.remaining.toLocaleString();
            
            animateCounters();
        }

        // Filter applications
        function filterApplications() {
            const season = document.getElementById('seasonFilterTable').value.toLowerCase();
            const status = document.getElementById('statusFilterTable').value.toLowerCase();
            const cluster = document.getElementById('clusterFilterTable').value.toLowerCase();
            const search = document.getElementById('searchTable').value.toLowerCase();
            
            const rows = document.querySelectorAll('#applicationsTableBody tr');
            
            rows.forEach(row => {
                const rowSeason = row.dataset.season.toLowerCase();
                const rowStatus = row.dataset.status.toLowerCase();
                const rowCluster = row.dataset.cluster.toLowerCase();
                const rowFarmer = row.dataset.farmer.toLowerCase();
                
                const matchesSeason = !season || rowSeason.includes(season);
                const matchesStatus = !status || rowStatus === status;
                const matchesCluster = !cluster || rowCluster === cluster;
                const matchesSearch = !search || rowFarmer.includes(search);
                
                row.style.display = matchesSeason && matchesStatus && matchesCluster && matchesSearch ? '' : 'none';
            });
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('seasonFilterTable').value = '';
            document.getElementById('statusFilterTable').value = '';
            document.getElementById('clusterFilterTable').value = '';
            document.getElementById('searchTable').value = '';
            filterApplications();
        }

        // Sort table
        function sortTable(column) {
            // Implementation for table sorting
            console.log('Sorting by:', column);
        }

        // Application actions
        function viewApplication(id) {
            const applicationData = {
                'NCF-2025-001': {
                    name: 'John Doe',
                    id: 'NCF-2025-001',
                    farmSize: '2.5 hectares',
                    cluster: 'Gwagwalada',
                    season: '2025 Dry Season',
                    status: 'Pending',
                    crops: ['Maize', 'Soybean'],
                    phone: '+234 801 234 5678',
                    address: 'Plot 15, Gwagwalada Town'
                }
            };
            
            const data = applicationData[id] || applicationData['NCF-2025-001'];
            
            document.getElementById('modalContent').innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Farmer Name</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">${data.name}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Application ID</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">${data.id}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Farm Size</label>
                        <p class="text-gray-900 dark:text-white">${data.farmSize}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cluster</label>
                        <p class="text-gray-900 dark:text-white">${data.cluster}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season</label>
                        <p class="text-gray-900 dark:text-white">${data.season}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">${data.status}</span>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Requested Crops</label>
                        <p class="text-gray-900 dark:text-white">${data.crops.join(', ')}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('applicationModal').classList.remove('hidden');
        }

        function closeApplicationModal() {
            document.getElementById('applicationModal').classList.add('hidden');
        }

        function approveApplication(id) {
            alert(`Approving application: ${id}`);
        }

        function distributeApplication(id) {
            alert(`Marking as distributed: ${id}`);
        }

        function verifyApplication(id) {
            alert(`Verifying application: ${id}`);
        }

        // Update time every minute
        setInterval(updateLastUpdated, 60000);
    </script>
</body>
</html>