@extends('layouts.layout')

@section('content')
    <!-- Dashboard Section -->
    <div id="dashboard-section" class="section">
        <!-- Assigned Farmers Summary -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Assigned Farmers</h2>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">127</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Assigned</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">89</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Collections Verified</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">45</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Returns Verified</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Assigned Farmers</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">127</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Collections Verified
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">89</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Returns Verified</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">45</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Today's Tasks</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">12</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div
                                class="h-8 w-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-white">Verified commodity collection for John Doe</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-white">Searched for farmer ID: NEC001234</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">4 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div
                                class="h-8 w-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-white">Processed return verification for Mary Adams
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
