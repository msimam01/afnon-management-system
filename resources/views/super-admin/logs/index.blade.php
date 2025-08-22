@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 -m-6 mb-6 p-6 rounded-t-xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Activity Logs</h2>
            <p class="text-blue-100">Monitor all system activities and user actions</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="showStatistics()"
                    class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center justify-center space-x-2 border border-white/20">
                📊 Statistics
            </button>
            <a href="{{ route('superadmin.logs.export', request()->query()) }}"
               class="bg-emerald-500 text-white px-6 py-3 rounded-lg hover:bg-emerald-600 transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg">
                📥 Export CSV
            </a>
        </div>
    </div>
    <!-- Filters -->
    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
        <form method="GET" action="{{ route('superadmin.logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search in descriptions..."
                       class="w-full bg-white/20 text-white placeholder-white/70 border border-white/30 rounded-lg px-4 py-2 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tenant</label>
                <select name="tenant_id" class="w-full bg-white/20 text-white border border-white/30 rounded-lg px-4 py-2 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <option value="">All Tenants</option>
                    <option value="central" {{ request('tenant_id') === 'central' ? 'selected' : '' }}>Central System</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant['id'] }}" {{ request('tenant_id') === $tenant['id'] ? 'selected' : '' }}>
                            {{ $tenant['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action Type</label>
                <select name="log_name" class="w-full bg-white/20 text-white border border-white/30 rounded-lg px-4 py-2 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <option value="">All Actions</option>
                    @foreach($logTypes as $type)
                        <option value="{{ $type }}" {{ request('log_name') === $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    🔍 Filter
                </button>
                <a href="{{ route('superadmin.logs.index') }}" class="px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors flex items-center justify-center">
                    Clear
                </a>
            </div>
        </form>

        <!-- Date Range Filter -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full bg-white/20 text-white border border-white/30 rounded-lg px-4 py-2 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full bg-white/20 text-white border border-white/30 rounded-lg px-4 py-2 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
            </div>
        </div>
    </div>
</div>

    <!-- Logs Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                <tr>
                    <th class="px-4 py-2">Date/Time</th>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">Action</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Tenant</th>
                    <th class="px-4 py-2">IP</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white divide-y">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2 text-xs">
                            {{ $log->created_at->format('M j, Y H:i:s') }}
                        </td>
                        <td class="px-4 py-2">
                            @if($log->causer)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs text-emerald-600 font-medium">
                                            {{ strtoupper(substr($log->causer->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $log->causer->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->causer->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-500 italic">System</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($log->log_name)
                                    @case('user_management')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('tenant_management')
                                        bg-purple-100 text-purple-800
                                        @break
                                    @case('authentication')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('system')
                                        bg-gray-100 text-gray-800
                                        @break
                                    @default
                                        bg-yellow-100 text-yellow-800
                                @endswitch
                            ">
                                {{ ucfirst(str_replace('_', ' ', $log->log_name)) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 max-w-xs truncate">{{ $log->description }}</td>
                        <td class="px-4 py-2 text-xs">
                            @if(isset($log->properties['tenant_id']))
                                <span class="font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                    {{ $log->properties['tenant_id'] }}
                                </span>
                            @else
                                <span class="text-gray-500">Central</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-xs font-mono">
                            {{ $log->properties['ip_address'] ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('superadmin.logs.show', ['uuid' => $log->uuid]) }}"
                               class="text-emerald-600 hover:text-emerald-800 text-xs font-medium">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            No activity logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="mt-6">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Statistics Modal -->
<div id="statisticsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Activity Statistics</h3>
                <div id="statisticsContent">
                    <div class="text-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500 mx-auto"></div>
                        <p class="mt-2 text-gray-500">Loading statistics...</p>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button onclick="closeStatistics()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showStatistics() {
    document.getElementById('statisticsModal').classList.remove('hidden');

    fetch('{{ route("superadmin.logs.statistics") }}')
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-600">${data.stats.total_activities}</div>
                        <div class="text-sm text-gray-600">Total Activities</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">${data.stats.today_activities}</div>
                        <div class="text-sm text-gray-600">Today</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">${data.stats.this_week_activities}</div>
                        <div class="text-sm text-gray-600">This Week</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">${data.stats.this_month_activities}</div>
                        <div class="text-sm text-gray-600">This Month</div>
                    </div>
                </div>
                <div class="space-y-3">
                    <h4 class="font-medium text-gray-900 dark:text-white">Activity by Type</h4>
                    ${data.activity_by_type.map(item => `
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">${item.log_name.replace('_', ' ')}</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">${item.count}</span>
                        </div>
                    `).join('')}
                </div>
            `;
            document.getElementById('statisticsContent').innerHTML = content;
        })
        .catch(error => {
            document.getElementById('statisticsContent').innerHTML =
                '<div class="text-center text-red-500">Failed to load statistics</div>';
        });
}

function closeStatistics() {
    document.getElementById('statisticsModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('statisticsModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatistics();
});
</script>

@endsection
