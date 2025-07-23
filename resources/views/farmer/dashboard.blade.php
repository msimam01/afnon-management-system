@extends('farmer.layout.app')

@section('content')
<!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Applications</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">3</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Approved</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">2</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Distributed</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">1</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Current Season</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">2024 Dry (Open)</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="farmer-application.html" class="bg-emerald-50 dark:bg-emerald-900 hover:bg-emerald-100 dark:hover:bg-emerald-800 p-4 rounded-lg border border-emerald-200 dark:border-emerald-700 transition-colors">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-900 dark:text-emerald-100">New Application</p>
                            <p class="text-xs text-emerald-700 dark:text-emerald-300">Apply for grant/loan</p>
                        </div>
                    </div>
                </a>

                <button class="bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 p-4 rounded-lg border border-blue-200 dark:border-blue-700 transition-colors">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">View Applications</p>
                            <p class="text-xs text-blue-700 dark:text-blue-300">Check status</p>
                        </div>
                    </div>
                </button>

                <button onclick="showReturnStatus('2024 Dry Season')" class="bg-yellow-50 dark:bg-yellow-900 hover:bg-yellow-100 dark:hover:bg-yellow-800 p-4 rounded-lg border border-yellow-200 dark:border-yellow-700 transition-colors">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-900 dark:text-yellow-100">View Return Instructions</p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300">See deadline & return center</p>
                        </div>
                    </div>
                </button>

            </div>
        </div>

        <!-- Admin Notices -->
        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Notice from Admin</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>2024 Dry Season applications are now open. Maize seeds are available for distribution. Apply before March 31, 2024.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application History -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Application History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Season</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Commodity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">2024 Dry Season</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Maize Seeds</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">5 bags</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Distributed</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <button class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 mr-3">View</button>
                                <button class="bg-emerald-600 text-white px-3 py-1 rounded-md text-xs hover:bg-emerald-700">Return Commodity</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">2023 Wet Season</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Maize Seeds</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">3 bags</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">Verified</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <button class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300">View</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">2024 Dry Season</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Maize Seeds</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">4 bags</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Pending</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <button class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SMS Notification Example -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Latest SMS Notification</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>NECAS: Your application for Maize Seeds (5 bags) has been approved. Collection date: March 15, 2024. Contact: 0800-NECAS-01</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Return Modal -->
    <div id="returnInfoModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Return Instructions</h3>
            <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            </div>
            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-3">
            <p><strong>Season:</strong> 2024 Dry Season</p>
            <p><strong>Return Deadline:</strong> July 15, 2024</p>
            <p><strong>Return Center:</strong> Ikeja Central Warehouse</p>
            <p><strong>Status:</strong> Pending Verification by Agent</p>
            <p>⚠️ Please wait for your agent to collect and submit your return commodity.</p>
            </div>
            <div class="mt-4 text-right">
            <button onclick="closeReturnModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Close</button>
            </div>
        </div>
    </div>
@endsection
