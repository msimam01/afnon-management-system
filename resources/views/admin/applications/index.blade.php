@extends('layouts.layout')

@push('datatables')
    <!-- DataTables CSS and JS - Load only when needed -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Modern Header Section -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Application Management</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and process farmer applications efficiently</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Live Updates</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- Statistics Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-150">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_pending']) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Awaiting review</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Approved Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-150">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_approved']) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ready for distribution</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Distributed Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-150">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Distributed</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_distributed']) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Commodities delivered</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rejected Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-150">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rejected</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_rejected']) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Not approved</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Applications</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            {{ $applications->total() }} total
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span id="selectedCount" class="text-sm text-gray-500 dark:text-gray-400">0 selected</span>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <form method="GET" action="{{ route('admin.applications.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="distributed" {{ request('status') == 'distributed' ? 'selected' : '' }}>Distributed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Season</label>
                        <select name="season" class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Seasons</option>
                            @foreach ($seasons as $season)
                                <option value="{{ $season->name }}" {{ request('season') == $season->name ? 'selected' : '' }}>
                                    {{ $season->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full pl-10 pr-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="Search farmers...">
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions Form -->
            <form id="bulkApproveForm" action="{{ route('admin.applications.bulk-approve') }}" method="POST">
                @csrf
                <div class="px-6 py-4 bg-emerald-50 dark:bg-emerald-900/20 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Collection Center</label>
                            <select name="collection_center_id" id="bulkCollectionCenter"
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                <option value="">Select Collection Center</option>
                                @foreach ($collectionCenters as $center)
                                    <option value="{{ $center->id }}" data-type="{{ $center->type }}">
                                        {{ $center->name }} ({{ ucfirst($center->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Return Center</label>
                            <select name="return_center_id" id="bulkReturnCenter"
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                <option value="">Select Return Center</option>
                                @foreach ($returnCenters as $center)
                                    <option value="{{ $center->id }}" data-type="{{ $center->type }}">
                                        {{ $center->name }} ({{ ucfirst($center->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="bulkApproveBtn" type="submit" disabled
                                    class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                Approve Selected
                            </button>
                            <button id="bulkRejectBtn" type="button" disabled
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                Reject Selected
                            </button>
                        </div>
                    </div>
                    <div id="selectedIdsContainer"></div>
                </div>

                <!-- Applications Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="select-all" class="h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Farmer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Application</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Farm Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($applications as $application)
                                <tr class="appRow hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150"
                                    data-status="{{ strtolower($application->status) }}"
                                    data-season="{{ strtolower($application->season->name) }}">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="rowCheckbox h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" value="{{ $application->id }}">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                                                    <span class="text-sm font-semibold text-white">
                                                        {{ strtoupper(substr($application->farmer->full_name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $application->farmer->full_name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $application->farmer->phone }}
                                                </div>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                        {{ $application->farmer->registration_number }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $application->season->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Ref: {{ $application->reference_number }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @foreach ($application->commodities as $commodity)
                                                {{ $commodity->name }} ({{ $commodity->pivot->quantity ?? 0 }})
                                                @if (!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $application->farm->size }} hectares
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $application->farm->location }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Cluster: {{ $application->farmer->cluster ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/50', 'text' => 'text-yellow-800 dark:text-yellow-200', 'icon' => 'clock'],
                                                'approved' => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/50', 'text' => 'text-emerald-800 dark:text-emerald-200', 'icon' => 'check-circle'],
                                                'distributed' => ['bg' => 'bg-blue-100 dark:bg-blue-900/50', 'text' => 'text-blue-800 dark:text-blue-200', 'icon' => 'truck'],
                                                'rejected' => ['bg' => 'bg-red-100 dark:bg-red-900/50', 'text' => 'text-red-800 dark:text-red-200', 'icon' => 'x-circle']
                                            ];
                                            $config = $statusConfig[$application->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($config['icon'] === 'clock')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @elseif($config['icon'] === 'check-circle')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @elseif($config['icon'] === 'truck')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @endif
                                            </svg>
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.applications.show', $application->uuid) }}"
                                           class="inline-flex items-center px-3 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No applications found</h3>
                                            <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or search criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Pagination -->
            @if($applications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $applications->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Rejection Modal -->
    <div id="bulkRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Bulk Reject Applications</h3>
                <form id="bulkRejectForm" action="{{ route('admin.applications.bulk-reject') }}" method="POST">
                    @csrf
                    <div id="bulkRejectIdsContainer"></div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason (Optional)</label>
                        <textarea name="rejection_note" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                  placeholder="Enter reason for rejection..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelBulkReject"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">Cancel</button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">Confirm Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Ensure scripts are loaded before execution
document.addEventListener('DOMContentLoaded', function() {
    // Wait for all scripts to be available
    if (typeof $ === 'undefined') {
        console.warn('jQuery not loaded, retrying...');
        setTimeout(() => {
            if (typeof $ !== 'undefined') {
                initializeApplicationScripts();
            }
        }, 100);
        return;
    }

    initializeApplicationScripts();
});

function initializeApplicationScripts() {
    // DOM elements
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
    const collectionSelect = document.getElementById('bulkCollectionCenter');
    const returnSelect = document.getElementById('bulkReturnCenter');
    const selectedCount = document.getElementById('selectedCount');

    // Update selected IDs and UI
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

        toggleBulkActions();
    }

    // Toggle bulk action buttons
    function toggleBulkActions() {
        const hasIds = Array.from(rowChecks).some(cb => cb.checked);

        bulkApproveBtn.disabled = !hasIds;
        bulkApproveBtn.classList.toggle('opacity-50', !hasIds);
        bulkApproveBtn.classList.toggle('cursor-not-allowed', !hasIds);

        bulkRejectBtn.disabled = !hasIds;
        bulkRejectBtn.classList.toggle('opacity-50', !hasIds);
        bulkRejectBtn.classList.toggle('cursor-not-allowed', !hasIds);
    }

    // Handle center selection logic
    function syncBothTypeBehavior(changed, other) {
        const opt = changed.options[changed.selectedIndex];
        const type = opt ? opt.getAttribute('data-type') : null;
        if (type === 'both' && changed.value) {
            other.value = changed.value;
            other.disabled = true;
        } else {
            other.disabled = false;
        }
        toggleBulkActions();
    }

    // Event listeners
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowChecks.forEach(cb => cb.checked = selectAll.checked);
            updateSelectedIds();
        });
    }

    rowChecks.forEach(cb => cb.addEventListener('change', updateSelectedIds));

    collectionSelect.addEventListener('change', () => syncBothTypeBehavior(collectionSelect, returnSelect));
    returnSelect.addEventListener('change', () => syncBothTypeBehavior(returnSelect, collectionSelect));

    // Form submission guard with proper handling
    form.addEventListener('submit', function(e) {
        if (bulkApproveBtn.disabled) {
            e.preventDefault();
            return false;
        }

        if (returnSelect.disabled) {
            returnSelect.disabled = false;
        }

        // Show loading state
        bulkApproveBtn.disabled = true;
        bulkApproveBtn.textContent = 'Processing...';
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

    cancelBulkReject.addEventListener('click', function() {
        bulkRejectModal.classList.add('hidden');
    });

    bulkRejectModal.addEventListener('click', function(e) {
        if (e.target === bulkRejectModal) {
            bulkRejectModal.classList.add('hidden');
        }
    });

    // Initialize
    updateSelectedIds();
    toggleBulkActions();
}
</script>
@endsection
