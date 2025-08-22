@extends('layouts.layout')

@section('content')
    <div id="dashboard-section" class="section">
        <!-- Season Filter -->
        <div class="mb-6">
            <label for="seasonFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by
                Season</label>
            <select id="seasonFilter"
                class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="all">All Seasons</option>
                <option value="2024-dry">2024 Dry Season</option>
                <option value="2024-wet">2024 Wet Season</option>
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
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="totalApplications">
                            2,847</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Applications</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        +12% from last month
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
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="totalApproved">2,103
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Approved</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        74% approval rate
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
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="totalDistributed">1,850
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Distributed</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        88% of approved
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
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="remainingCommodities">
                            12,450</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Remaining (kg)</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-yellow-600 dark:text-yellow-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Available for distribution
                    </div>
                </div>
            </div>
        </div>
        <!-- Chart Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Commodities Distribution Overview
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bar Chart (Mock) -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Distributed vs
                        Remaining</h4>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Maize Seeds</span>
                                <span class="text-gray-900 dark:text-white">756 / 1200 bags</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 63%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pie Chart (Mock) -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Distribution by Zone
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">North Zone</span>
                            <span class="text-gray-900 dark:text-white">35%</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">South Zone</span>
                            <span class="text-gray-900 dark:text-white">40%</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">East Zone</span>
                            <span class="text-gray-900 dark:text-white">25%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <!-- <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Application Management</h3> -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Applications</h3>
                    <button
                        class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 dark:hover:text-emerald-300 text-sm font-medium">View
                        All</button>
                </div>
                <!-- Filters -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="seasonFilterTable"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Season</label>
                        <select id="seasonFilterTable" onchange="filterApplications()"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                            <option value="">All Seasons</option>
                            <option value="2025-dry">2025 Dry Season</option>
                            <option value="2025-wet">2025 Wet Season</option>
                            <option value="2024-wet">2024 Wet Season</option>
                        </select>
                    </div>
                    <div>
                        <label for="statusFilterTable"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="statusFilterTable" onchange="filterApplications()"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="distributed">Distributed</option>
                            <option value="verified">Verified</option>
                        </select>
                    </div>
                    <div>
                        <label for="clusterFilterTable"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cluster</label>
                        <select id="clusterFilterTable" onchange="filterApplications()"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                            <option value="">All Clusters</option>
                            <option value="gwagwalada">Gwagwalada</option>
                            <option value="kuje">Kuje</option>
                            <option value="abaji">Abaji</option>
                            <option value="kwali">Kwali</option>
                        </select>
                    </div>
                    <div>
                        <label for="searchTable"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" id="searchTable" placeholder="Farmer name..."
                            onkeyup="filterApplications()"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Farmer Name</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Farm Size</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cluster</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Season</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody id="applicationsTableBody"
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr data-season="2025-dry" data-status="pending" data-cluster="gwagwalada"
                            data-farmer="john doe">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">JD</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">John Doe
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">NCF-2025-001</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">2.5
                                hectares</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                Gwagwalada</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                2025 Dry Season</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openApplicationModal('app-001')">View</button>

                            </td>
                        </tr>
                        <tr data-season="2025-dry" data-status="approved" data-cluster="kuje"
                            data-farmer="mary johnson">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">MJ</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Mary
                                            Johnson</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">NCF-2025-002</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">3.0
                                hectares</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                Kuje</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                2025 Dry Season</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Approved</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewApplication('APP-2025-002')"
                                    class="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">View</button>
                            </td>
                        </tr>
                        <tr data-season="2025-wet" data-status="distributed" data-cluster="abaji"
                            data-farmer="ahmed ibrahim">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">AI</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Ahmed
                                            Ibrahim</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">NCF-2025-003</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">1.8
                                hectares</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                Abaji</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                2025 Wet Season</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Distributed</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewApplication('APP-2025-003')"
                                    class="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">Previous</button>
                    <button
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">Next</button>
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
                            <button
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">1</button>
                            <button
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
