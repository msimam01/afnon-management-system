@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Activity Logs</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Monitor activities in your tenant</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="showStatistics()"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                📊 Statistics
            </button>
            <a href="{{ route('admin.logs.export', request()->query()) }}"
               class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                📥 Export CSV
            </a>
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
                                    @case('loan_management')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('authentication')
                                        bg-purple-100 text-purple-800
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
                        <td class="px-4 py-2 text-xs font-mono">
                            {{ $log->properties['ip_address'] ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.logs.show', ['uuid' => $log->uuid]) }}"
                               class="text-emerald-600 hover:text-emerald-800 text-xs font-medium">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            No activity logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($logs) && $logs->hasPages())
        <div class="mt-6">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
function showStatistics() {
    alert('Statistics feature coming soon!');
}
</script>

@endsection
