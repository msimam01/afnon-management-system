@extends('layouts.layout')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">🕘 Sync Logs</h2>
        <form method="GET" class="mb-4 flex flex-wrap gap-4">
            <select name="tenant" class="px-3 py-2 border rounded">
                <option value="">All Tenants</option>
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}" @selected(request('tenant') == $tenant->id)>
                        {{ ucfirst($tenant->id) }}
                    </option>
                @endforeach
            </select>

            <select name="type" class="px-3 py-2 border rounded">
                <option value="">All Types</option>
                <option value="season" @selected(request('type') == 'season')>Season</option>
                <option value="commodity" @selected(request('type') == 'commodity')>Commodity</option>
            </select>

            <input type="text" name="search" placeholder="Search Item Name..." value="{{ request('search') }}"
                class="px-3 py-2 border rounded" />

            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                Filter
            </button>
        </form>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Tenant</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Item Name</th>
                        <th class="px-4 py-2 text-left">Synced At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                    @forelse ($logs as $log)
                        <tr>
                            <td class="px-4 py-2">{{ ucfirst($log->tenant_id) }}</td>
                            <td class="px-4 py-2 capitalize">{{ $log->type }}</td>
                            <td class="px-4 py-2">{{ $log->item_name }}</td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($log->synced_at)->format('Y-m-d H:i') }}
                            </td>                            
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-400">No sync logs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
