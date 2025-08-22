@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto mt-6 p-6 bg-white dark:bg-gray-900 shadow-xl rounded-xl">
    <!-- Enhanced Header with Search and Filters -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 -m-6 mb-6 p-6 rounded-t-xl">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4">
            <div class="text-white mb-4 md:mb-0">
                <h2 class="text-3xl font-bold mb-2">Activity Logs</h2>
                <p class="text-blue-100">Monitor and track all activities in your tenant</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <button onclick="showStatistics()"
                        class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center justify-center space-x-2 border border-white/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Statistics</span>
                </button>
                <a href="{{ route('admin.logs.export', request()->query()) }}"
                   class="bg-emerald-500 text-white px-6 py-3 rounded-lg hover:bg-emerald-600 transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>
        
        <!-- Advanced Search and Filter Bar -->
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <input type="text" name="search" placeholder="Search logs..." 
                           value="{{ request('search') }}"
                           class="w-full bg-white/20 text-white placeholder-white/70 border border-white/30 rounded-lg px-4 py-2 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <svg class="absolute right-3 top-2.5 w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select name="action_type" 
                        class="bg-white/20 text-white border border-white/30 rounded-lg px-4 py-2 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <option value="">All Actions</option>
                    <option value="user_management" {{ request('action_type') == 'user_management' ? 'selected' : '' }}>User Management</option>
                    <option value="loan_management" {{ request('action_type') == 'loan_management' ? 'selected' : '' }}>Loan Management</option>
                    <option value="authentication" {{ request('action_type') == 'authentication' ? 'selected' : '' }}>Authentication</option>
                    <option value="system" {{ request('action_type') == 'system' ? 'selected' : '' }}>System</option>
                </select>
                <input type="date" name="date_from" 
                       value="{{ request('date_from') }}"
                       class="bg-white/20 text-white border border-white/30 rounded-lg px-4 py-2 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.logs.index') }}" 
                       class="px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 dark:text-blue-400">Today's Actions</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $todayLogs ?? '0' }}</p>
                </div>
                <div class="p-3 bg-blue-500 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900 dark:to-emerald-800 p-4 rounded-lg border border-emerald-200 dark:border-emerald-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400">Active Users</p>
                    <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300">{{ $activeUsers ?? '0' }}</p>
                </div>
                <div class="p-3 bg-emerald-500 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-600 dark:text-purple-400">Failed Logins</p>
                    <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ $failedLogins ?? '0' }}</p>
                </div>
                <div class="p-3 bg-purple-500 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 p-4 rounded-lg border border-orange-200 dark:border-orange-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-orange-600 dark:text-orange-400">System Alerts</p>
                    <p class="text-2xl font-bold text-orange-700 dark:text-orange-300">{{ $systemAlerts ?? '0' }}</p>
                </div>
                <div class="p-3 bg-orange-500 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Date & Time</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $log->created_at->format('M j, Y') }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->causer)
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                                                <span class="text-sm text-white font-bold">
                                                    {{ strtoupper(substr($log->causer->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                {{ $log->causer->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $log->causer->email }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400 italic">System</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold shadow-sm
                                    @switch($log->log_name)
                                        @case('user_management')
                                            bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300
                                            @break
                                        @case('loan_management')
                                            bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300
                                            @break
                                        @case('authentication')
                                            bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 border border-purple-300
                                            @break
                                        @case('system')
                                            bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-300
                                            @break
                                        @default
                                            bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300
                                    @endswitch
                                ">
                                    <div class="w-2 h-2 rounded-full mr-2
                                        @switch($log->log_name)
                                            @case('user_management')
                                                bg-blue-600
                                                @break
                                            @case('loan_management')
                                                bg-green-600
                                                @break
                                            @case('authentication')
                                                bg-purple-600
                                                @break
                                            @case('system')
                                                bg-gray-600
                                                @break
                                            @default
                                                bg-yellow-600
                                        @endswitch
                                    "></div>
                                    {{ ucfirst(str_replace('_', ' ', $log->log_name)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-sm text-gray-900 dark:text-gray-100 truncate" 
                                       title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-xs font-mono text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                        {{ $log->properties['ip_address'] ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.logs.show', ['uuid' => $log->uuid]) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-lg hover:bg-emerald-200 transition-colors duration-150 shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No activity logs found</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Activity will appear here once users start interacting with the system</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enhanced Pagination -->
    @if(isset($logs) && $logs->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-2">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Enhanced Statistics Modal -->
<div id="statisticsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Activity Statistics</h3>
                    <button onclick="closeStatistics()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Activity Timeline Chart -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-4">Daily Activity</h4>
                        <div class="h-48 flex items-end justify-between space-x-2">
                            <div class="flex flex-col items-center space-y-2">
                                <div class="w-8 bg-blue-500 rounded-t" style="height: 60px;"></div>
                                <span class="text-xs text-blue-600 dark:text-blue-300">Mon</span>
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <div class="w-8 bg-blue-500 rounded-t" style="height: 80px;"></div>
                                <span class="text-xs text-blue-600 dark:text-blue-300">Tue</span>
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <div class="w-8 bg-blue-500 rounded-t" style="height: 45px;"></div>
                                <span class="text-xs text-blue-600 dark:text-blue-300">Wed</span>
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <div class="w-8 bg-blue-500 rounded-t" style="height: 90px;"></div>
                                <span class="text-xs text-blue-600 dark:text-blue-300">Thu</span>
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <div class="w-8 bg-blue-500 rounded-t" style="height: 70px;"></div>
                                <span class="text-xs text-blue-600 dark:text-blue-300">Fri</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Types Distribution -->
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900 dark:to-emerald-800 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-emerald-800 dark:text-emerald-200 mb-4">Action Distribution</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-emerald-700 dark:text-emerald-300">User Management</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 h-2 bg-emerald-200 rounded-full">
                                        <div class="w-3/4 h-2 bg-emerald-500 rounded-full"></div>
                                    </div>
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400">75%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-emerald-700 dark:text-emerald-300">Authentication</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 h-2 bg-emerald-200 rounded-full">
                                        <div class="w-1/2 h-2 bg-emerald-500 rounded-full"></div>
                                    </div>
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400">50%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-emerald-700 dark:text-emerald-300">Loan Management</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 h-2 bg-emerald-200 rounded-full">
                                        <div class="w-1/3 h-2 bg-emerald-500 rounded-full"></div>
                                    </div>
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400">33%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showStatistics() {
    document.getElementById('statisticsModal').classList.remove('hidden');
}

function closeStatistics() {
    document.getElementById('statisticsModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('statisticsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatistics();
    }
});

// Enhanced search functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit search after typing stops
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Auto-submit could be implemented here
                // document.querySelector('form').submit();
            }, 1000);
        });
    }
    
    // Animate table rows on load
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(10px)';
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 50);
    });
    
    // Enhanced tooltips for truncated text
    const truncatedElements = document.querySelectorAll('[title]');
    truncatedElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 bg-gray-900 text-white text-xs rounded py-2 px-3 shadow-lg max-w-xs';
            tooltip.textContent = this.getAttribute('title');
            tooltip.style.pointerEvents = 'none';
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
            tooltip.style.left = rect.left + 'px';
            
            this.addEventListener('mouseleave', function() {
                tooltip.remove();
            }, { once: true });
        });
    });
    
    // Live time updates
    updateRelativeTimes();
    setInterval(updateRelativeTimes, 60000); // Update every minute
});

function updateRelativeTimes() {
    const timeElements = document.querySelectorAll('[data-timestamp]');
    timeElements.forEach(element => {
        const timestamp = element.getAttribute('data-timestamp');
        const relativeTime = getRelativeTime(new Date(timestamp));
        element.textContent = relativeTime;
    });
}

function getRelativeTime(date) {
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
    if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)}d ago`;
    return date.toLocaleDateString();
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.querySelector('input[name="search"]').focus();
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        closeStatistics();
    }
    
    // Ctrl/Cmd + E to export
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        const exportLink = document.querySelector('a[href*="export"]');
        if (exportLink) exportLink.click();
    }
});

// Enhanced filtering with debounce
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Real-time search suggestions (if implemented on backend)
const debouncedSearch = debounce(function(query) {
    if (query.length > 2) {
        // Fetch search suggestions
        // fetch(`/admin/logs/suggestions?q=${encodeURIComponent(query)}`)
        //     .then(response => response.json())
        //     .then(data => showSuggestions(data));
    }
}, 300);

// Loading states for actions
function showLoadingState(element) {
    const originalText = element.textContent;
    element.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-current" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading...
    `;
    
    setTimeout(() => {
        element.textContent = originalText;
    }, 2000);
}

// Add click handlers for loading states
document.querySelectorAll('a[href*="export"]').forEach(link => {
    link.addEventListener('click', function() {
        showLoadingState(this);
    });
});

// Theme toggle functionality (if dark mode toggle exists)
function toggleTheme() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
}

// Initialize theme from localStorage
if (localStorage.getItem('theme') === 'dark' || 
    (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
}

// Smooth scroll for pagination
document.querySelectorAll('a[href*="page="]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const url = new URL(this.href);
        
        // Show loading state
        const tbody = document.querySelector('tbody');
        tbody.style.opacity = '0.5';
        tbody.style.pointerEvents = 'none';
        
        // Navigate to new page
        window.location.href = this.href;
    });
});

// Auto-refresh functionality
let autoRefreshInterval;
function startAutoRefresh(interval = 30000) {
    autoRefreshInterval = setInterval(() => {
        // Only refresh if no modals are open and user is active
        if (!document.getElementById('statisticsModal').classList.contains('hidden')) return;
        
        const lastActivity = Date.now() - (window.lastActivityTime || Date.now());
        if (lastActivity < 60000) { // User was active in last minute
            location.reload();
        }
    }, interval);
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
}

// Track user activity for auto-refresh
window.addEventListener('mousemove', () => window.lastActivityTime = Date.now());
window.addEventListener('keydown', () => window.lastActivityTime = Date.now());

// Start auto-refresh (uncomment if desired)
// startAutoRefresh();

// Enhanced accessibility
document.addEventListener('keydown', function(e) {
    // Tab navigation enhancement
    if (e.key === 'Tab') {
        document.body.classList.add('keyboard-navigation');
    }
});

document.addEventListener('mousedown', function() {
    document.body.classList.remove('keyboard-navigation');
});

// Add focus styles for keyboard navigation
const style = document.createElement('style');
style.textContent = `
    .keyboard-navigation *:focus {
        outline: 2px solid #3b82f6 !important;
        outline-offset: 2px !important;
    }
`;
document.head.appendChild(style);
</script>

<!-- Additional CSS for enhanced animations and interactions -->
<style>
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-slide-up {
    animation: slideUp 0.3s ease-out;
}

.animate-fade-in {
    animation: fadeIn 0.2s ease-out;
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Dark mode scrollbar */
.dark .overflow-x-auto::-webkit-scrollbar-track {
    background: #374151;
}

.dark .overflow-x-auto::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Enhanced hover effects */
tr:hover .w-10 {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

/* Loading spinner */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Glass effect for modal */
#statisticsModal > div > div {
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.95);
}

.dark #statisticsModal > div > div {
    background: rgba(31, 41, 55, 0.95);
}

/* Smooth transitions for all interactive elements */
button, a, input, select {
    transition: all 0.2s ease;
}

/* Enhanced focus states */
button:focus, a:focus, input:focus, select:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Responsive table enhancements */
@media (max-width: 768px) {
    .table-mobile-stack td {
        display: block;
        text-align: right;
        border: none;
        padding: 8px 16px;
    }
    
    .table-mobile-stack td:before {
        content: attr(data-label) ": ";
        float: left;
        font-weight: bold;
        color: #6b7280;
    }
}

/* Status indicators */
.status-online {
    animation: pulse 2s infinite;
}

/* Card hover effects */
.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

/* Progress bars animation */
.progress-bar {
    transition: width 0.8s ease-in-out;
}
</style>
@endsection