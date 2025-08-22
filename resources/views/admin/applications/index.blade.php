@extends('layouts.layout')
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
@section('content')
    <!-- Applications Section -->
    <div class="">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-800 bg-clip-text text-transparent">
                        Application Management
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Manage farmer applications and distribution centers</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div
                        class="flex items-center space-x-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg px-4 py-2 border border-gray-200/50 dark:border-gray-700/50">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Live Updates</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Enhanced Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Applications -->
            <div
                class="group card-hover bg-gradient-to-br from-yellow-400 via-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-yellow-100">Pending</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ number_format($stats['total_pending']) }}</p>
                        <p class="text-yellow-100 text-sm">+12% from last week</p>
                    </div>
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                        <div class="w-8 h-8 bg-white/20 rounded-full animate-ping"></div>
                    </div>
                </div>
            </div>

            <!-- Approved Applications -->
            <div
                class="group card-hover bg-gradient-to-br from-emerald-400 via-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-emerald-100">Approved</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ number_format($stats['total_approved']) }}</p>
                        <p class="text-emerald-100 text-sm">+8% from last week</p>
                    </div>
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Distributed Applications -->
            <div
                class="group card-hover bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-blue-100">Distributed</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ number_format($stats['total_distributed']) }}</p>
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
            <div
                class="group card-hover bg-gradient-to-br from-red-400 via-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-red-100">Rejected</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ number_format($stats['total_rejected']) }}</p>
                        <p class="text-red-100 text-sm">-5% from last week</p>
                    </div>
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="applications-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="gradient-bg px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
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
            <div class="p-6">

                <form id="bulkApproveForm" action="{{ route('admin.applications.bulk-approve') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4 items-end">
                        <!-- Collection Center -->
                        <div class="md:col-span-4">
                            <label
                                class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                </svg>Collection Center *</label>
                            <select name="collection_center_id" id="bulkCollectionCenter"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 dark:text-white smooth-transition"
                                required>
                                <option value="">-- Select Collection Center --</option>
                                @foreach ($collectionCenters as $center)
                                    <option value="{{ $center->id }}" data-type="{{ $center->type }}">
                                        {{ $center->name }} ({{ ucfirst($center->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Return Center -->
                        <div class="md:col-span-4">
                            <label
                                class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8.445 14.832A1 1 0 0010 14v-2.798l5.445 3.63A1 1 0 0017 14V6a1 1 0 00-1.555-.832L10 8.798V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4z" />
                                </svg>
                                Return Center *</label>
                            <select name="return_center_id" id="bulkReturnCenter"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 dark:text-white smooth-transition"
                                required>
                                <option value="">-- Select Return Center --</option>
                                @foreach ($returnCenters as $center)
                                    <option value="{{ $center->id }}" data-type="{{ $center->type }}">
                                        {{ $center->name }} ({{ ucfirst($center->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Selected count + action -->
                        <div class="md:col-span-4 flex items-end justify-between gap-3">
                            <span id="selectedCount"
                                class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">0
                                selected</span>
                            <div class="flex gap-2">
                                <button id="bulkApproveBtn" type="submit" disabled
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Approve Selected
                                </button>
                                <button id="bulkRejectBtn" type="button" disabled
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-red-600 text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Reject Selected
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs holder for selected application IDs -->
                    <div id="selectedIdsContainer"></div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter
                            Status</label>
                        <select id="tableStatusFilter"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="distributed">Distributed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter
                            Season</label>
                        <select id="tableSeasonFilter"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">All</option>
                            @foreach ($seasons as $season)
                                <option value="{{ strtolower($season->name) }}">{{ $season->name }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Farmer Details</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Application</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Farm Info</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($applications as $application)
                            <tr class="appRow" data-status="{{ strtolower($application->status) }}"
                                data-season="{{ strtolower($application->season->name) }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox"
                                        class="rowCheckbox h-4 w-4 text-emerald-600 border-gray-300 rounded"
                                        value="{{ $application->id }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    {{ strtoupper(substr($application->farmer->full_name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $application->farmer->full_name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $application->farmer->phone }}</div>
                                            <div class="flex gap-1 mt-1">
                                                <div
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                    BVN: {{ $application->farmer->bvn ?? '—' }}</div>
                                                <div
                                                    class="text-xs inline-flex items-center px-2 py-0.5 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                                    NIN: {{ $application->farmer->nin ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white seasonText">
                                        {{ $application->season->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        @foreach ($application->commodities as $c)
                                            {{ $c->name }} ({{ $c->pivot->quantity }})<br>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $application->farm->size }} hectares</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $application->farm->location }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $application->farmer->cluster }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="statusBadge inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <a href="{{ route('admin.applications.show', $application->uuid) }}"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 smooth-transition transform hover:scale-105 shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        View Full Info
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No applications found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </form>

            <!-- Bulk Rejection Modal -->
            <div id="bulkRejectModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Bulk Reject Applications</h3>
                        <form id="bulkRejectForm" action="{{ route('admin.applications.bulk-reject') }}" method="POST">
                            @csrf
                            <div id="bulkRejectIdsContainer"></div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection
                                    Reason (Optional)</label>
                                <textarea name="rejection_note" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="Enter reason for rejection..."></textarea>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancelBulkReject"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Confirm
                                    Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $applications->links('pagination::tailwind') }}
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
                // Derive from checked checkboxes to avoid any timing issues with hidden inputs
                const hasIds = Array.from(rowChecks).some(cb => cb.checked);
                const canApprove =
                    hasIds; // Enable as soon as at least one row is selected. Centers validated server-side.

                bulkApproveBtn.disabled = !canApprove;
                bulkApproveBtn.classList.toggle('opacity-50', !canApprove);
                bulkApproveBtn.classList.toggle('cursor-not-allowed', !canApprove);

                // Also toggle bulk reject button
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

                // Temporarily re-enable the return center if it was disabled to ensure it's submitted
                if (returnSelect.disabled) {
                    returnSelect.disabled = false;
                }
            });

            // Bulk reject handlers
            bulkRejectBtn.addEventListener('click', function() {
                if (bulkRejectBtn.disabled) return;

                // Copy selected IDs to bulk reject form
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

            // Close modal when clicking outside
            bulkRejectModal.addEventListener('click', function(e) {
                if (e.target === bulkRejectModal) {
                    bulkRejectModal.classList.add('hidden');
                }
            });

            // Client-side table filtering
            const searchInput = document.getElementById('tableSearch');
            const statusFilter = document.getElementById('tableStatusFilter');
            const seasonFilter = document.getElementById('tableSeasonFilter');
            const rows = Array.from(document.querySelectorAll('tbody tr.appRow'));

            function textOf(el) {
                return (el?.textContent || '').toLowerCase();
            }

            function rowMatchesSearch(row, query) {
                if (!query) return true;
                const haystack = textOf(row);
                return haystack.includes(query);
            }

            function rowMatchesFilters(row) {
                const status = (row.getAttribute('data-status') || '').toLowerCase();
                const season = (row.getAttribute('data-season') || '').toLowerCase();
                const statusOk = !statusFilter.value || status === statusFilter.value;
                const seasonOk = !seasonFilter.value || season === seasonFilter.value;
                return statusOk && seasonOk;
            }

            function applyFilters() {
                const q = (searchInput.value || '').trim().toLowerCase();
                rows.forEach(row => {
                    const show = rowMatchesSearch(row, q) && rowMatchesFilters(row);
                    row.style.display = show ? '' : 'none';
                });
            }

            [searchInput, statusFilter, seasonFilter].forEach(el => {
                if (el) el.addEventListener('input', applyFilters);
                if (el && el.tagName === 'SELECT') el.addEventListener('change', applyFilters);
            });

            applyFilters();
        });
    </script>
@endsection
