@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-blue-50 p-8 rounded-2xl border border-blue-200 mb-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-4xl font-bold leading-tight text-gray-900 flex items-center mb-4">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white mr-4 shadow-xl">
                        <i class="fas fa-edit"></i>
                    </span>
                    <div>
                        <span>Bulk Edit Allocations</span>
                        <p class="text-lg font-normal text-gray-600 mt-1">{{ $season->name }}</p>
                    </div>
                </h1>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ ucfirst($season->type) }}
                    </span>
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold {{ $season->status === 'open' ? 'bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-800 border border-gray-200' }} shadow-sm">
                        <i class="fas fa-{{ $season->status === 'open' ? 'play-circle' : 'pause-circle' }} mr-2"></i>
                        {{ ucfirst($season->status) }}
                    </span>
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-white/50 text-gray-700 border border-gray-200 shadow-sm">
                        <i class="far fa-calendar mr-2"></i>
                        {{ $season->start_date->format('M d, Y') }} - {{ $season->end_date->format('M d, Y') }}
                    </span>
                </div>
            </div>

            <div class="mt-6 md:mt-0 flex flex-col gap-3">
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-lg text-white bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200"
                            onclick="showBulkActions()">
                        <i class="fas fa-tools mr-2"></i> Bulk Actions
                    </button>
                    <a href="{{ route('global.allocations.index', $season->uuid) }}"
                       class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        There were {{ $errors->count() }} errors with your submission
                    </h3>
                    <div class="mt-2 text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('global.allocations.update-all', $season->uuid) }}" method="POST" class="space-y-6" id="allocationForm">
        @csrf
        @method('PUT')

        <!-- Season Summary -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <h6 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Season Details</h6>
                    <p class="text-sm font-medium text-gray-900 mb-1">{{ $season->name }}</p>
                    <p class="text-sm text-gray-600 mb-1">{{ ucfirst($season->type) }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $season->status == 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($season->status) }}
                    </span>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <h6 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Duration</h6>
                    <p class="text-sm font-medium text-gray-900 mb-1">{{ $season->start_date->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600 mb-1">{{ $season->end_date->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $season->collection_start_date->format('M d, Y') }} - {{ $season->collection_end_date->format('M d, Y') }}</p>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <h6 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Budget & Rates</h6>
                    <p class="text-sm font-medium text-gray-900 mb-1">₦{{ number_format($season->budget, 2) }}</p>
                    <p class="text-sm text-gray-600 mb-1">{{ $season->insurance_rate }}%</p>
                    <p class="text-sm text-gray-600">{{ $season->send_reminder_after_days }} days</p>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <h6 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Overview</h6>
                    <p class="text-sm font-medium text-gray-900 mb-1">{{ $season->commodities->count() }} Commodities</p>
                    <p class="text-sm text-gray-600 mb-1">{{ $tenants->count() }} Allocated Tenants</p>
                    <p class="text-sm text-gray-600">{{ $existingAllocations->sum(fn($group) => $group->count()) }} Total Allocations</p>
                </div>
            </div>
        </div>

        <!-- Available Stock Summary -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Available Stock Summary</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commodity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocated</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($season->commodities as $commodity)
                            @php
                                $allocated = $existingAllocations->where('global_commodity_id', $commodity->id)->sum('allocated_stock');
                                $remainingStock = $commodity->pivot->stock;
                                $totalStock = $remainingStock + $allocated;
                                $percentage = $totalStock > 0 ? ($allocated / $totalStock) * 100 : 0;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $commodity->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $commodity->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $commodity->unit }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($commodity->pivot->stock, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($allocated, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($remainingStock, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($percentage >= 90)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">High</span>
                                    @elseif($percentage >= 70)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Medium</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Low</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enhanced Edit Form -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6">
                <h3 class="text-white text-xl font-bold flex items-center">
                    <i class="fas fa-sliders-h mr-3"></i>
                    Update All Tenant Allocations
                </h3>
                <p class="text-indigo-100 mt-2">Modify allocation quantities for all tenants across commodities</p>
            </div>

            <!-- Form Body -->
            <div class="p-8">
                @if($tenants->count() == 0)
                    <div class="text-center py-12">
                        <div class="text-gray-400">
                            <i class="fas fa-info-circle text-6xl mb-4"></i>
                            <p class="text-xl font-medium mb-2">No Allocated Tenants</p>
                            <p class="text-lg mb-6">No tenants have been allocated for this season yet.</p>
                            <a href="{{ route('global.allocations.create', $season->uuid) }}"
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800">
                                <i class="fas fa-plus mr-2"></i> Create Allocations
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Info Alert -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-blue-800">
                                    Bulk Allocation Update
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Modify commodity allocations for all {{ $tenants->count() }} allocated tenant(s). Changes will be automatically synchronized to tenant databases.</p>
                                    <p class="mt-2"><strong>Note:</strong> Ensure total allocations don't exceed available stock for each commodity.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allocation Table -->
                    <div class="overflow-x-auto mb-8">
                        <table class="min-w-full divide-y divide-gray-200" id="allocationTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-64">Tenant</th>
                                    @foreach($season->commodities as $commodity)
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            <div>{{ $commodity->name }}</div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                Available: {{ number_format($availableStock[$commodity->id]['total'] ?? 0, 2) }} {{ $commodity->unit }}
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tenants as $tenant)
                                    @php
                                        $tenantAllocations = $existingAllocations->get($tenant->id, collect());
                                    @endphp
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <!-- Tenant Column -->
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                                                    <i class="fas fa-building text-blue-600 text-sm"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-lg font-semibold text-gray-900">{{ ucfirst($tenant->id) }}</div>
                                                    <div class="text-sm text-gray-500">{{ $tenant->domain }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        @foreach($season->commodities as $commodity)
                                            @php
                                                $allocation = $tenantAllocations->firstWhere('global_commodity_id', $commodity->id);
                                                $currentAllocation = $allocation ? $allocation->allocated_stock : 0;
                                            @endphp
                                            <td class="px-6 py-6 whitespace-nowrap text-center">
                                                <div class="space-y-3">
                                                    <div class="flex rounded-lg shadow-sm">
                                                        <input type="number"
                                                               name="allocations[{{ $tenant->id }}][commodities][{{ $commodity->id }}][allocated_stock]"
                                                               class="flex-1 min-w-0 px-4 py-3 border border-gray-300 rounded-l-lg text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center allocation-input"
                                                               data-commodity-id="{{ $commodity->id }}"
                                                                data-max-available="{{ $availableStock[$commodity->id]['total'] ?? 0 }}"
                                                               min="0"
                                                               step="0.01"
                                                               value="{{ number_format($currentAllocation, 2, '.', '') }}">
                                                        <span class="inline-flex items-center px-4 py-3 rounded-r-lg border border-l-0 border-gray-300 bg-gray-50 text-gray-600 font-medium">
                                                            {{ $commodity->unit }}
                                                        </span>
                                                    </div>

                                                    <!-- Error Message -->
                                                    <p class="text-red-600 text-sm allocation-error hidden" data-commodity-id="{{ $commodity->id }}">
                                                        Invalid allocation amount
                                                    </p>

                                                    @if($currentAllocation > 0)
                                                        <p class="text-xs text-blue-600">Current: {{ number_format($currentAllocation, 2) }} {{ $commodity->unit }}</p>
                                                    @endif
                                                </div>

                                                <input type="hidden"
                                                       name="allocations[{{ $tenant->id }}][commodities][{{ $commodity->id }}][commodity_id]"
                                                       value="{{ $commodity->id }}">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Auto-calculation Toggle -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8">
                        <div class="flex items-center">
                            <input id="autoCalculate" type="checkbox" checked
                                   class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="autoCalculate" class="ml-3 block text-sm font-medium text-gray-700">
                                <span class="font-semibold">Auto-update available quantities</span>
                                <span class="block text-sm text-gray-500 mt-1">Automatically recalculate available stock as you modify allocations</span>
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 pt-6 border-t border-gray-200">
                        <div class="flex items-center space-x-3">
                            <button type="button"
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-semibold rounded-xl shadow-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                    onclick="showBulkActions()">
                                <i class="fas fa-tools mr-2"></i>
                                Bulk Actions
                            </button>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('global.allocations.index', $season->uuid) }}"
                               class="inline-flex items-center px-8 py-3 border border-gray-300 text-base font-semibold rounded-xl shadow-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-times mr-3"></i>
                                Cancel
                            </a>

                            <button type="submit" id="submitBtn"
                                    class="inline-flex items-center px-8 py-3 border border-transparent text-base font-semibold rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-save mr-3"></i>
                                Update All Allocations & Sync
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>

@push('scripts')
<!-- Bulk Actions Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden" id="bulkActionsModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Bulk Actions</h3>
                <button onclick="closeBulkModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-4">
                <button type="button"
                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
                        onclick="clearSelected()">
                    <i class="fas fa-eraser mr-2"></i> Clear Selected Values
                </button>

                <button type="button"
                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        onclick="setToZero()">
                    <i class="fas fa-ban mr-2"></i> Set Selected to Zero
                </button>

                <button type="button"
                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        onclick="copyValues()">
                    <i class="fas fa-copy mr-2"></i> Copy First Row Values
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') html.classList.add('dark');

        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            });
        }
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('allocationForm');
    const autoCalculate = document.getElementById('autoCalculate');
    const submitBtn = document.getElementById('submitBtn');
    const selectedCount = document.getElementById('selectedCount');

    let initialFormData = new FormData(form);
    let hasChanges = false;
    let selectedRows = new Set();

    // Convert FormData to string for comparison
    function formDataToString(formData) {
        const entries = [];
        for (const [key, value] of formData.entries()) {
            entries.push(`${key}:${value}`);
        }
        return entries.sort().join('|');
    }

    // Track form changes
    function trackChanges() {
        const currentFormData = new FormData(form);
        const currentDataString = formDataToString(currentFormData);
        const initialDataString = formDataToString(initialFormData);

        hasChanges = currentDataString !== initialDataString;

        // Warn user about unsaved changes
        window.onbeforeunload = hasChanges ?
            () => 'You have unsaved changes. Are you sure you want to leave?' : null;
    }

    // Available stock data
    const availableStock = @json($availableStock);

    // Validate allocation input with visual feedback
    function validateAllocation(input) {
        const value = parseFloat(input.value) || 0;
        const originalValue = parseFloat(input.defaultValue) || 0;
        const commodityId = input.dataset.commodityId;
        const errorElement = input.closest('td').querySelector('.allocation-error');
        const row = input.closest('tr');

        // Remove previous validation classes
        input.classList.remove('is-valid', 'is-invalid', 'unchanged', 'changed');
        row.classList.remove('row-changed', 'row-error');

        if (value < 0) {
            input.value = 0;
            input.classList.add('is-invalid');
            row.classList.add('row-error');
            if (errorElement) {
                errorElement.textContent = 'Cannot be negative';
                errorElement.classList.remove('hidden');
            }
            return false;
        }

        // Clear any previous errors first
        if (errorElement) {
            errorElement.classList.add('hidden');
        }

        input.classList.add('is-valid');

        // Show change indicator
        if (value !== originalValue) {
            input.classList.add('changed');
            row.classList.add('row-changed');
        } else {
            input.classList.add('unchanged');
        }

        return true;
    }

    // Validate the entire form
    function validateForm() {
        const allInputs = document.querySelectorAll('.allocation-input');
        let hasErrors = false;

        // Clear all previous error messages
        document.querySelectorAll('.allocation-error').forEach(error => {
            error.classList.add('hidden');
        });

        // Group inputs by commodity
        const byCommodity = {};
        allInputs.forEach(input => {
            const commodityId = input.dataset.commodityId;
            if (!byCommodity[commodityId]) {
                byCommodity[commodityId] = [];
            }
            byCommodity[commodityId].push(input);
        });

        // Validate each commodity's total allocation
        for (const [commodityId, inputs] of Object.entries(byCommodity)) {
            let totalAllocated = 0;
            inputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                totalAllocated += value;
            });

            const maxAvailable = parseFloat(inputs[0]?.dataset.maxAvailable) || 0;

            if (totalAllocated > maxAvailable) {
                // Show error on the first input of this commodity
                const errorElement = inputs[0].closest('td').querySelector('.allocation-error');
                if (errorElement) {
                    errorElement.textContent = `Total allocation (${totalAllocated.toFixed(2)}) exceeds available stock (${maxAvailable.toFixed(2)})`;
                    errorElement.classList.remove('hidden');
                    inputs[0].classList.add('is-invalid');
                    inputs[0].classList.remove('is-valid');
                    inputs[0].closest('tr').classList.add('row-error');
                }
                hasErrors = true;
            }
        }

        return !hasErrors;
    }

    // Toggle row selection (for bulk actions)
    function toggleRowSelection(row, checkbox) {
        const rowId = row.dataset.tenantId;

        if (checkbox.checked) {
            selectedRows.add(rowId);
        } else {
            selectedRows.delete(rowId);
        }

        // Update row visual state
        if (checkbox.checked) {
            row.classList.add('selected');
        } else {
            row.classList.remove('selected');
        }

        updateSelectedCount();
    }

    // Update selected count display
    function updateSelectedCount() {
        selectedCount.textContent = selectedRows.size;
    }

    // Attach event listeners to allocation inputs
    const allocationInputs = document.querySelectorAll('.allocation-input');
    allocationInputs.forEach(input => {
        input.addEventListener('input', function() {
            trackChanges();
            if (autoCalculate.checked) {
                // Re-validate all inputs when one changes
                allocationInputs.forEach(inp => validateAllocation(inp));
            } else {
                validateAllocation(this);
            }
            updateSubmitButtonState();
        });

        input.addEventListener('blur', function() {
            validateAllocation(this);
            updateSubmitButtonState();
        });
    });

    // Check if form has validation errors and update submit button
    function updateSubmitButtonState() {
        const invalidInputs = document.querySelectorAll('.allocation-input.is-invalid');

        // Check for validation errors
        const hasValidationErrors = invalidInputs.length > 0;

        // Disable submit button if there are errors
        const shouldDisable = hasValidationErrors;

        submitBtn.disabled = shouldDisable;

        if (shouldDisable) {
            submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Fix Errors to Save';
            submitBtn.title = 'Please fix allocation errors before saving';
        } else {
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            submitBtn.innerHTML = `<i class="fas fa-save mr-2"></i>Save Changes ${hasChanges ? '(*)' : ''}`;
            submitBtn.title = hasChanges ? 'Save your changes' : 'No changes made';
        }
    }

    // Initial validation and setup
    allocationInputs.forEach(input => validateAllocation(input));
    updateSubmitButtonState();
    updateSelectedCount();

    // Prevent form submission with unsaved changes warning
    form.addEventListener('submit', function(e) {
        if (hasChanges) {
            // Reset the beforeunload handler
            window.onbeforeunload = null;
        }
    });

    // Global functions for modal interactions
    window.showBulkActions = function() {
        const modal = document.getElementById('bulkActionsModal');
        modal.classList.remove('hidden');
    };

    window.closeBulkModal = function() {
        const modal = document.getElementById('bulkActionsModal');
        modal.classList.add('hidden');
    };

    window.clearSelected = function() {
        if (selectedRows.size === 0) {
            alert('Please select rows first using the checkboxes.');
            return;
        }

        if (confirm(`Clear values for ${selectedRows.size} selected tenant(s)?`)) {
            selectedRows.forEach(tenantId => {
                const row = document.querySelector(`[data-tenant-id="${tenantId}"]`);
                if (row) {
                    const inputs = row.querySelectorAll('.allocation-input');
                    inputs.forEach(input => {
                        input.value = input.defaultValue; // Reset to original value
                        validateAllocation(input);
                    });
                }
            });
            closeBulkModal();
            trackChanges();
            updateSubmitButtonState();
        }
    };

    window.setToZero = function() {
        if (selectedRows.size === 0) {
            alert('Please select rows first using the checkboxes.');
            return;
        }

        if (confirm(`Set all allocations to zero for ${selectedRows.size} selected tenant(s)?`)) {
            selectedRows.forEach(tenantId => {
                const row = document.querySelector(`[data-tenant-id="${tenantId}"]`);
                if (row) {
                    const inputs = row.querySelectorAll('.allocation-input');
                    inputs.forEach(input => {
                        input.value = '0.00';
                        validateAllocation(input);
                    });
                }
            });
            closeBulkModal();
            trackChanges();
            updateSubmitButtonState();
        }
    };

    window.copyValues = function() {
        if (selectedRows.size === 0) {
            alert('Please select rows first.');
            return;
        }

        const firstRow = document.querySelector('tbody tr');
        if (!firstRow) return;

        const firstValues = Array.from(firstRow.querySelectorAll('.allocation-input')).map(input => input.value);

        if (confirm(`Copy first row values to ${selectedRows.size} selected tenant(s)?`)) {
            selectedRows.forEach(tenantId => {
                if (tenantId !== firstRow.dataset.tenantId) { // Don't copy to itself
                    const row = document.querySelector(`[data-tenant-id="${tenantId}"]`);
                    if (row) {
                        const inputs = row.querySelectorAll('.allocation-input');
                        inputs.forEach((input, index) => {
                            if (firstValues[index]) {
                                input.value = firstValues[index];
                                validateAllocation(input);
                            }
                        });
                    }
                }
            });
            closeBulkModal();
            trackChanges();
            updateSubmitButtonState();
        }
    };
});

// Add visual styles for enhanced interactions
const additionalStyles = `
<style>
    .allocation-input.changed {
        background-color: #fef3c7 !important;
        border-color: #f59e0b !important;
        transition: all 0.2s ease;
    }

    .allocation-input.is-valid {
        border-color: #10b981 !important;
    }

    .allocation-input.is-invalid {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }

    tr.row-changed {
        background-color: #f0f9ff !important;
        border-left: 4px solid #3b82f6;
    }

    tr.row-error {
        background-color: #fef2f2 !important;
        border-left: 4px solid #ef4444;
    }

    tr.selected {
        background-color: #e0f2fe !important;
        border-left: 4px solid #0ea5e9;
    }

    .action-btn {
        min-width: 120px;
        transition: all 0.2s ease;
    }

    .allocation-error {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
`;

document.head.insertAdjacentHTML('beforeend', additionalStyles);
</script>
@endpush
@endsection
