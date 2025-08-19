@extends('layouts.layout')

@section('content')
    <!-- Applications Section -->
    <div id="applications-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Management</h3>
            </div>
            <div class="p-6">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.applications.index') }}" id="filters-form">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Season</label>
                            <select name="season" onchange="document.getElementById('filters-form').submit()"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Seasons</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->name }}"
                                        {{ request('season') == $season->name ? 'selected' : '' }}>
                                        {{ $season->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status" onchange="document.getElementById('filters-form').submit()"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="distributed" {{ request('status') == 'distributed' ? 'selected' : '' }}>
                                    Distributed</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search farmer..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                onkeyup="if(event.key === 'Enter') document.getElementById('filters-form').submit()">
                        </div>
                        
                    </form>
                </div>

                <form id="bulk-approval-form" action="{{ route('admin.applications.bulk-approve') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Collection Center -->
                        <select name="collection_center_id" id="collectionCenter"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                            <option value="">-- Select Collection Center --</option>
                            @foreach ($collectionCenters as $center)
                                <option value="{{ $center->id }}" data-type="{{ $center->type }}">{{ $center->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Return Center -->
                        <select name="return_center_id" id="returnCenter"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                            <option value="">-- Select Return Center --</option>
                            @foreach ($returnCenters as $center)
                                <option value="{{ $center->id }}" data-type="{{ $center->type }}">{{ $center->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                            Approve Selected
                        </button>
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
                                    <tr>
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="application_ids[]" value="{{ $application->id }}">
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
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">BVN:
                                                        {{ $application->farmer->bvn }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
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
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            <a href="{{ route('admin.applications.show', $application->uuid) }}"
                                                class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300">
                                                View Full Info
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No applications found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="mt-4">
                    {{ $applications->links('pagination::tailwind') }}
                </div>
            </div>

        </div>
    </div>
    <script>
        document.getElementById('select-all').addEventListener('change', function(e) {
            document.querySelectorAll('input[name="application_ids[]"]').forEach(cb => cb.checked = e.target
                .checked);
        });
        document.addEventListener("DOMContentLoaded", function() {
            const collectionSelect = document.getElementById("collectionCenter");
            const returnSelect = document.getElementById("returnCenter");

            function handleCenterSelection(changedSelect, otherSelect) {
                const selectedOption = changedSelect.options[changedSelect.selectedIndex];
                const centerType = selectedOption.getAttribute("data-type");

                if (centerType === "both") {
                    otherSelect.value = changedSelect.value;
                    otherSelect.disabled = true;
                } else {
                    if (otherSelect.disabled) {
                        otherSelect.disabled = false;
                        otherSelect.value = "";
                    }
                }
            }

            collectionSelect.addEventListener("change", function() {
                handleCenterSelection(collectionSelect, returnSelect);
            });

            returnSelect.addEventListener("change", function() {
                handleCenterSelection(returnSelect, collectionSelect);
            });
        });
    </script>
@endsection
