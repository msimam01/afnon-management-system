@extends('layouts.layout')

@section('content')
    <!-- Applications Section -->
    <div id="applications-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Management</h3>
            </div>
            <div class="p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600 dark:text-yellow-300">Pending</p>
                                <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ number_format($stats['total_pending']) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600 dark:text-green-300">Approved</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ number_format($stats['total_approved']) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-300">Distributed</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($stats['total_distributed']) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-600 dark:text-red-300">Rejected</p>
                                <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ number_format($stats['total_rejected']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="bulkApproveForm" action="{{ route('admin.applications.bulk-approve') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4 items-end">
                        <!-- Collection Center -->
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Collection Center *</label>
                            <select name="collection_center_id" id="bulkCollectionCenter"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                <option value="">-- Select Collection Center --</option>
                                @foreach ($collectionCenters as $center)
                                    <option value="{{ $center->id }}" data-type="{{ $center->type }}">{{ $center->name }} ({{ ucfirst($center->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Return Center -->
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Return Center *</label>
                            <select name="return_center_id" id="bulkReturnCenter"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                <option value="">-- Select Return Center --</option>
                                @foreach ($returnCenters as $center)
                                    <option value="{{ $center->id }}" data-type="{{ $center->type }}">{{ $center->name }} ({{ ucfirst($center->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Selected count + action -->
                        <div class="md:col-span-4 flex items-end justify-between gap-3">
                            <span id="selectedCount" class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">0 selected</span>
                            <div class="flex gap-2">
                                <button id="bulkApproveBtn" type="submit" disabled class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Approve Selected
                                </button>
                                <button id="bulkRejectBtn" type="button" disabled class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-red-600 text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Reject Selected
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs holder for selected application IDs -->
                    <div id="selectedIdsContainer"></div>

                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter Status</label>
                            <select id="tableStatusFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="distributed">Distributed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter Season</label>
                            <select id="tableSeasonFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
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
                                    <tr class="appRow" data-status="{{ strtolower($application->status) }}" data-season="{{ strtolower($application->season->name) }}">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" class="rowCheckbox h-4 w-4 text-emerald-600 border-gray-300 rounded" value="{{ $application->id }}">
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
                                                        <div class="text-xs inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">BVN: {{ $application->farmer->bvn ?? '—' }}</div>
                                                        <div class="text-xs inline-flex items-center px-2 py-0.5 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">NIN: {{ $application->farmer->nin ?? '—' }}</div>
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
                                            <span class="statusBadge inline-flex px-2 py-1 text-xs font-semibold rounded-full 
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
                                    <button type="button" id="cancelBulkReject" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Confirm Reject</button>
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
                const canApprove = hasIds; // Enable as soon as at least one row is selected. Centers validated server-side.

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
