<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Application Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .status-animation {
            animation: pulse-color 2s infinite;
        }

        @keyframes pulse-color {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-emerald-50/30 to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
    <!-- Applications Section -->
    <div id="applications-section" class="w-full min-h-screen px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-800 bg-clip-text text-transparent">
                        Application Management
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Manage farmer applications and distribution centers</p>
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
            <!-- Pending Applications -->
            <div class="group card-hover bg-gradient-to-br from-yellow-400 via-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-yellow-100">Pending</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">1,247</p>
                        <p class="text-yellow-100 text-sm">+12% from last week</p>
                    </div>
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                        <div class="w-8 h-8 bg-white/20 rounded-full animate-ping"></div>
                    </div>
                </div>
            </div>

            <!-- Approved Applications -->
            <div class="group card-hover bg-gradient-to-br from-emerald-400 via-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-emerald-100">Approved</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">3,892</p>
                        <p class="text-emerald-100 text-sm">+8% from last week</p>
                    </div>
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Distributed Applications -->
            <div class="group card-hover bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-blue-100">Distributed</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">2,156</p>
                        <p class="text-blue-100 text-sm">+15% from last week</p>
                    </div>
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                        <div class="grid grid-cols-2 gap-1">
                            <div class="w-2 h-2 bg-white/60 rounded-full"></div>
                            <div class="w-2 h-2 bg-white/40 rounded-full"></div>
                            <div class="w-2 h-2 bg-white/40 rounded-full"></div>
                            <div class="w-2 h-2 bg-white/60 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejected Applications -->
            <div class="group card-hover bg-gradient-to-br from-red-400 via-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-red-100">Rejected</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">89</p>
                        <p class="text-red-100 text-sm">-5% from last week</p>
                    </div>
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/30 overflow-hidden">
            <!-- Enhanced Header -->
            <div class="gradient-bg px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Application Processing</h2>
                            <p class="text-emerald-100 text-sm">Bulk actions and individual management</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg border border-white/30">
                            <span id="selectedCount" class="text-white font-medium">0 selected</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form id="bulkApproveForm" action="#" method="POST" class="space-y-6">
                    <!-- Enhanced Control Panel -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                            <!-- Collection Center -->
                            <div class="lg:col-span-4">
                                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                    </svg>
                                    Collection Center *
                                </label>
                                <select name="collection_center_id" id="bulkCollectionCenter"
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 dark:text-white smooth-transition" required>
                                    <option value="">-- Select Collection Center --</option>
                                    <option value="1" data-type="collection">Lagos Collection Center (Collection)</option>
                                    <option value="2" data-type="both">Abuja Multi-Center (Both)</option>
                                    <option value="3" data-type="collection">Kano Collection Center (Collection)</option>
                                </select>
                            </div>

                            <!-- Return Center -->
                            <div class="lg:col-span-4">
                                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.445 14.832A1 1 0 0010 14v-2.798l5.445 3.63A1 1 0 0017 14V6a1 1 0 00-1.555-.832L10 8.798V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4z" />
                                    </svg>
                                    Return Center *
                                </label>
                                <select name="return_center_id" id="bulkReturnCenter"
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 dark:text-white smooth-transition" required>
                                    <option value="">-- Select Return Center --</option>
                                    <option value="1" data-type="return">Lagos Return Center (Return)</option>
                                    <option value="2" data-type="both">Abuja Multi-Center (Both)</option>
                                    <option value="3" data-type="return">Kano Return Center (Return)</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="lg:col-span-4 flex items-end justify-end gap-3">
                                <button id="bulkApproveBtn" type="submit" disabled
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed smooth-transition transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve Selected
                                </button>
                                <button id="bulkRejectBtn" type="button" disabled
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl shadow-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed smooth-transition transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject Selected
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs holder -->
                    <div id="selectedIdsContainer"></div>

                    <!-- Enhanced Filters -->
                    <div class="flex flex-wrap gap-4 items-center justify-between bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4 border border-gray-200/30 dark:border-gray-700/30">
                        <div class="flex flex-wrap gap-4 items-center">
                            <div class="min-w-0 flex-1 sm:flex-none sm:w-48">
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Filter Status</label>
                                <select id="tableStatusFilter" class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 smooth-transition">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="distributed">Distributed</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="min-w-0 flex-1 sm:flex-none sm:w-48">
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Filter Season</label>
                                <select id="tableSeasonFilter" class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 smooth-transition">
                                    <option value="">All Seasons</option>
                                    <option value="2024 dry season">2024 Dry Season</option>
                                    <option value="2024 wet season">2024 Wet Season</option>
                                    <option value="2025 dry season">2025 Dry Season</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span>Real-time filtering</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                        <!-- Enhanced Table -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200/50 dark:border-gray-700/50">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                <div class="flex items-center space-x-2">
                                                    <input type="checkbox" id="select-all" class="h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                                    <span>Select</span>
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Farmer Details
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Application Info
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Farm Details
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <!-- Sample rows with enhanced design -->
                                        <tr class="appRow hover:bg-gray-50 dark:hover:bg-gray-700/50 smooth-transition" data-status="pending" data-season="2024 dry season">
                                            <td class="px-6 py-4">
                                                <input type="checkbox" class="rowCheckbox h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" value="1">
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg">
                                                            <span class="text-sm font-bold text-white">JD</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">John Doe</div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">+234 801 234 5678</div>
                                                        <div class="flex gap-2 mt-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                                BVN: 22123456789
                                                            </span>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                                NIN: 12345678901
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white seasonText">2024 Dry Season</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <div class="flex flex-wrap gap-1">
                                                        <span class="inline-block bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 text-xs px-2 py-1 rounded-lg">Rice (50kg)</span>
                                                        <span class="inline-block bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-xs px-2 py-1 rounded-lg">Maize (25kg)</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">2.5 hectares</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Lagos State, Ibeju-Lekki</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">Cluster A-12</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800 status-animation">
                                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 smooth-transition transform hover:scale-105 shadow-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Approved Application -->
                                        <tr class="appRow hover:bg-gray-50 dark:hover:bg-gray-700/50 smooth-transition" data-status="approved" data-season="2024 wet season">
                                            <td class="px-6 py-4">
                                                <input type="checkbox" class="rowCheckbox h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" value="2">
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg">
                                                            <span class="text-sm font-bold text-white">AS</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">Amina Sani</div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">+234 802 345 6789</div>
                                                        <div class="flex gap-2 mt-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                                BVN: 22234567890
                                                            </span>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                                NIN: 23456789012
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white seasonText">2024 Wet Season</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <div class="flex flex-wrap gap-1">
                                                        <span class="inline-block bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded-lg">Cassava (100kg)</span>
                                                        <span class="inline-block bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-300 text-xs px-2 py-1 rounded-lg">Yam (75kg)</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">3.8 hectares</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Kano State, Dambatta</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">Cluster B-07</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 border border-green-200 dark:border-green-800">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                    Approved
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 smooth-transition transform hover:scale-105 shadow-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Distributed Application -->
                                        <tr class="appRow hover:bg-gray-50 dark:hover:bg-gray-700/50 smooth-transition" data-status="distributed" data-season="2025 dry season">
                                            <td class="px-6 py-4">
                                                <input type="checkbox" class="rowCheckbox h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" value="3">
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg">
                                                            <span class="text-sm font-bold text-white">OA</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">Olumide Adebayo</div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">+234 803 456 7890</div>
                                                        <div class="flex gap-2 mt-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                                BVN: 22345678901
                                                            </span>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                                NIN: 34567890123
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white seasonText">2025 Dry Season</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <div class="flex flex-wrap gap-1">
                                                        <span class="inline-block bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 text-xs px-2 py-1 rounded-lg">Soybeans (40kg)</span>
                                                        <span class="inline-block bg-pink-100 dark:bg-pink-900/50 text-pink-800 dark:text-pink-300 text-xs px-2 py-1 rounded-lg">Cowpea (30kg)</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">1.5 hectares</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Ogun State, Abeokuta</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">Cluster C-03</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                                    Distributed
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 smooth-transition transform hover:scale-105 shadow-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Rejected Application -->
                                        <tr class="appRow hover:bg-gray-50 dark:hover:bg-gray-700/50 smooth-transition" data-status="rejected" data-season="2024 dry season">
                                            <td class="px-6 py-4">
                                                <input type="checkbox" class="rowCheckbox h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" value="4">
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-lg">
                                                            <span class="text-sm font-bold text-white">MU</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">Mohammed Usman</div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">+234 804 567 8901</div>
                                                        <div class="flex gap-2 mt-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                                BVN: —
                                                            </span>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                                NIN: —
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white seasonText">2024 Dry Season</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <div class="flex flex-wrap gap-1">
                                                        <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs px-2 py-1 rounded-lg">Rice (25kg)</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">0.8 hectares</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Kaduna State, Zaria</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">Cluster D-15</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 border border-red-200 dark:border-red-800">
                                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                    Rejected
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 smooth-transition transform hover:scale-105 shadow-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Empty state row (hidden by default) -->
                                        <tr id="emptyStateRow" class="hidden">
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No applications found</h3>
                                                    <p class="text-gray-500 dark:text-gray-400">Try adjusting your search criteria or filters</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Enhanced Bulk Rejection Modal -->
                <div id="bulkRejectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
                    <div class="relative max-w-md w-full">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                            <!-- Modal Header -->
                            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-white/20 rounded-xl">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-white">Bulk Reject Applications</h3>
                                    </div>
                                    <button id="closeModal" class="p-1 text-white/80 hover:text-white hover:bg-white/20 rounded-lg smooth-transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6">
                                <form id="bulkRejectForm" action="#" method="POST" class="space-y-4">
                                    <div id="bulkRejectIdsContainer"></div>

                                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                                        <div class="flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <h4 class="font-medium text-amber-800 dark:text-amber-200">Warning</h4>
                                                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">This action will reject the selected applications and cannot be undone.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Rejection Reason (Optional)</label>
                                        <textarea name="rejection_note" rows="4"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none smooth-transition"
                                            placeholder="Enter reason for rejection (e.g., incomplete documentation, invalid farm size, etc.)"></textarea>
                                    </div>

                                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <button type="button" id="cancelBulkReject"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl smooth-transition">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl smooth-transition transform hover:scale-105 shadow-lg">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Confirm Reject
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Pagination -->
                <div class="flex items-center justify-between bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-4 border border-gray-200/30 dark:border-gray-700/30 mt-6">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <span>Showing <strong>1</strong> to <strong>10</strong> of <strong>45</strong> results</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed smooth-transition" disabled>
                            Previous
                        </button>
                        <button class="px-3 py-2 text-sm font-medium bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg shadow-md">1</button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 smooth-transition">2</button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 smooth-transition">3</button>
                        <span class="px-2 text-gray-500">...</span>
                        <button class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 smooth-transition">5</button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 smooth-transition">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const rowChecks = document.querySelectorAll('.rowCheckbox');
            const form = document.getElementById('bulkApproveForm');
            const selectedIdsContainer = document.getElementById('selectedIdsContainer');
            const bulkApproveBtn = document.getElementById('bulkApproveBtn');
            const bulkRejectBtn = document.getElementById('bulkRejectBtn');
            const bulkRejectModal = document.getElementById('bulkRejectModal');
            const bulkRejectForm = document.getElementById('bulkRejectForm');
            const bulkRejectIdsContainer = document.getElementById('bulkRejectIdsContainer');
            const cancelBulkReject = document.getElementById('cancelBulkReject');
            const closeModal = document.getElementById('closeModal');
            const collectionSelect = document.getElementById('bulkCollectionCenter');
            const returnSelect = document.getElementById('bulkReturnCenter');
            const selectedCount = document.getElementById('selectedCount');

            function updateSelectedIds() {
                selectedIdsContainer.innerHTML = '';
                const checked = Array.from(rowChecks).filter(cb => cb.checked);
                const checkedValues = checked.map(cb => cb.value);

                checkedValues.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = id;
                    selectedIdsContainer.appendChild(input);
                });

                // Update count and select-all state
                selectedCount.textContent = `${checked.length} selected`;
                if (selectAll) {
                    if (checked.length === 0) {
                        selectAll.indeterminate = false;
                        selectAll.checked = false;
                    } else if (checked.length === rowChecks.length) {
                        selectAll.indeterminate = false;
                        selectAll.checked = true;
                    } else {
                        selectAll.indeterminate = true;
                        selectAll.checked = false;
                    }
                }

                toggleBulkApprove();
            }

            function toggleBulkApprove() {
                const hasIds = Array.from(rowChecks).some(cb => cb.checked);
                const canApprove = hasIds;

                bulkApproveBtn.disabled = !canApprove;
                bulkApproveBtn.classList.toggle('opacity-50', !canApprove);
                bulkApproveBtn.classList.toggle('cursor-not-allowed', !canApprove);

                bulkRejectBtn.disabled = !hasIds;
                bulkRejectBtn.classList.toggle('opacity-50', !hasIds);
                bulkRejectBtn.classList.toggle('cursor-not-allowed', !hasIds);
            }

            function syncBothTypeBehavior(changed, other) {
                const opt = changed.options[changed.selectedIndex];
                const type = opt ? opt.getAttribute('data-type') : null;
                if (type === 'both' && changed.value) {
                    other.value = changed.value;
                    other.disabled = true;
                } else {
                    other.disabled = false;
                }
                toggleBulkApprove();
            }

            // Select-all behavior
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    rowChecks.forEach(cb => cb.checked = selectAll.checked);
                    updateSelectedIds();
                });
            }

            // Individual checkbox behavior
            rowChecks.forEach(cb => cb.addEventListener('change', updateSelectedIds));

            // Center select behavior
            collectionSelect.addEventListener('change', () => syncBothTypeBehavior(collectionSelect, returnSelect));
            returnSelect.addEventListener('change', () => syncBothTypeBehavior(returnSelect, collectionSelect));

            // Initialize
            updateSelectedIds();
            toggleBulkApprove();

            // Guard submit
            form.addEventListener('submit', function(e) {
                if (bulkApproveBtn.disabled) {
                    e.preventDefault();
                    return false;
                }

                if (returnSelect.disabled) {
                    returnSelect.disabled = false;
                }
            });

            // Bulk reject handlers
            bulkRejectBtn.addEventListener('click', function() {
                if (bulkRejectBtn.disabled) return;

                bulkRejectIdsContainer.innerHTML = '';
                const checked = Array.from(rowChecks).filter(cb => cb.checked);
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = cb.value;
                    bulkRejectIdsContainer.appendChild(input);
                });

                bulkRejectModal.classList.remove('hidden');
            });

            // Modal close handlers
            [cancelBulkReject, closeModal].forEach(btn => {
                btn.addEventListener('click', function() {
                    bulkRejectModal.classList.add('hidden');
                });
            });

            // Close modal when clicking outside
            bulkRejectModal.addEventListener('click', function(e) {
                if (e.target === bulkRejectModal) {
                    bulkRejectModal.classList.add('hidden');
                }
            });

            // Client-side table filtering
            const statusFilter = document.getElementById('tableStatusFilter');
            const seasonFilter = document.getElementById('tableSeasonFilter');
            const rows = Array.from(document.querySelectorAll('tbody tr.appRow'));
            const emptyStateRow = document.getElementById('emptyStateRow');

            function textOf(el) {
                return (el?.textContent || '').toLowerCase();
            }

            function rowMatchesFilters(row) {
                const status = (row.getAttribute('data-status') || '').toLowerCase();
                const season = (row.getAttribute('data-season') || '').toLowerCase();
                const statusOk = !statusFilter.value || status === statusFilter.value;
                const seasonOk = !seasonFilter.value || season === seasonFilter.value;
                return statusOk && seasonOk;
            }

            function applyFilters() {
                let visibleCount = 0;

                rows.forEach(row => {
                    const show = rowMatchesFilters(row);
                    row.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });

                // Show/hide empty state
                if (visibleCount === 0) {
                    emptyStateRow.classList.remove('hidden');
                } else {
                    emptyStateRow.classList.add('hidden');
                }

                // Update checkboxes after filtering
                updateSelectedIds();
            }

            [statusFilter, seasonFilter].forEach(el => {
                if (el) {
                    el.addEventListener('change', applyFilters);
                }
            });

            // Initialize filters
            applyFilters();

            // Add smooth animations for status badges
            document.querySelectorAll('.statusBadge').forEach(badge => {
                badge.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });
                badge.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Enhanced hover effects for cards
            document.querySelectorAll('.card-hover').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Simulate real-time updates
            function simulateRealTimeUpdates() {
                const statsCards = document.querySelectorAll('.card-hover');
                setInterval(() => {
                    statsCards.forEach(card => {
                        const badge = card.querySelector('.animate-ping');
                        if (badge && Math.random() > 0.8) {
                            badge.style.animationDuration = '1s';
                            setTimeout(() => {
                                badge.style.animationDuration = '2s';
                            }, 2000);
                        }
                    });
                }, 5000);
            }

            simulateRealTimeUpdates();

            // Enhanced form validation feedback
            [collectionSelect, returnSelect].forEach(select => {
                select.addEventListener('change', function() {
                    if (this.value) {
                        this.classList.add('ring-2', 'ring-emerald-200', 'border-emerald-300');
                        this.classList.remove('border-gray-300', 'dark:border-gray-600');
                    } else {
                        this.classList.remove('ring-2', 'ring-emerald-200', 'border-emerald-300');
                        this.classList.add('border-gray-300', 'dark:border-gray-600');
                    }
                });
            });

            // Add loading states for buttons
            function addLoadingState(button, text = 'Processing...') {
                const originalText = button.innerHTML;
                button.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    ${text}
                `;
                button.disabled = true;

                return () => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                };
            }

            // Enhanced form submission with loading states
            form.addEventListener('submit', function(e) {
                if (!bulkApproveBtn.disabled) {
                    const resetLoading = addLoadingState(bulkApproveBtn, 'Approving...');

                    // Simulate processing time
                    setTimeout(() => {
                        resetLoading();
                        // Here you would handle the actual form submission response
                    }, 2000);
                }
            });

            bulkRejectForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const resetLoading = addLoadingState(submitBtn, 'Rejecting...');

                setTimeout(() => {
                    resetLoading();
                    bulkRejectModal.classList.add('hidden');
                    // Here you would handle the actual form submission
                }, 2000);
            });

            // Accessibility improvements
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !bulkRejectModal.classList.contains('hidden')) {
                    bulkRejectModal.classList.add('hidden');
                }
            });

            // Enhanced tooltip system (you can extend this)
            function createTooltip(element, text) {
                element.setAttribute('title', text);
                element.style.position = 'relative';
            }

            // Add tooltips to status badges
            document.querySelectorAll('[class*="status"]').forEach(badge => {
                const status = badge.textContent.trim().toLowerCase();
                const tooltips = {
                    'pending': 'Application is waiting for review',
                    'approved': 'Application has been approved for distribution',
                    'distributed': 'Items have been distributed to farmer',
                    'rejected': 'Application was rejected due to validation issues'
                };
                if (tooltips[status]) {
                    createTooltip(badge, tooltips[status]);
                }
            });
        });
    </script>
</body>

</html>