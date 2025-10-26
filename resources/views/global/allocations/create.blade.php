@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <i class="fas fa-boxes mr-2 text-blue-600"></i>
                Allocate Stock to Tenants
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $season->name }} - {{ $season->start_date->format('M d, Y') }} to {{ $season->end_date->format('M d, Y') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('global.seasons.show', $season->uuid) }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Season
            </a>
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

    <form action="{{ route('global.allocations.store', $season->uuid) }}" method="POST" class="space-y-6" id="allocationForm">
        @csrf

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
                    <p class="text-sm text-gray-600 mb-1">{{ $tenants->count() }} Tenants</p>
                    <p class="text-sm text-gray-600">{{ $existingAllocations->count() }} Allocations</p>
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
                                $allocated = $existingAllocations->where('commodity_id', $commodity->id)->sum('allocated_stock');
                                $available = $commodity->pivot->stock - $allocated;
                                $percentage = $commodity->pivot->stock > 0 ? ($allocated / $commodity->pivot->stock) * 100 : 0;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $commodity->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $commodity->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $commodity->unit }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($commodity->pivot->stock, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($allocated, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($available, 2) }}</td>
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

        <!-- Allocation Form -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Tenant Allocations</h3>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="addTenantBtn">
                    <i class="fas fa-plus mr-2"></i>Add Tenant
                </button>
            </div>
            <div class="px-6 py-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="allocationTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">Tenant</th>
                                @foreach($season->commodities as $commodity)
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div>{{ $commodity->name }}</div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            Available: {{ number_format($availableStock[$commodity->id]['remaining'] ?? 0, 2) }} {{ $commodity->unit }}
                                        </div>
                                    </th>
                                @endforeach
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tenantRows">
                            <!-- Tenant rows will be added here -->
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                    Total Allocated:
                                </td>
                                @foreach($season->commodities as $commodity)
                                    <td class="px-6 py-3 text-center text-sm font-medium text-gray-900">
                                        <span class="total-allocated inline-block px-2 py-1 bg-blue-100 rounded-full text-xs" data-commodity-id="{{ $commodity->id }}">
                                            0.00 {{ $commodity->unit }}
                                        </span>
                                    </td>
                                @endforeach
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Form Controls -->
                <div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <div class="flex items-center">
                        <input id="autoCalculate" type="checkbox" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="autoCalculate" class="ml-2 block text-sm text-gray-700">
                            Auto-update available quantities
                        </label>
                    </div>

                    <div class="flex space-x-3">
                        <a href="{{ route('global.seasons.show', $season->uuid) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-save mr-2"></i>Save Allocations
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Tenant Row Template -->
<template id="tenantRowTemplate">
    <tr class="bg-white hover:bg-gray-50 tenant-row">
        <td class="px-6 py-4 whitespace-nowrap">
            <select name="allocations[INDEX][tenant_id]"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm tenant-select">
                <option value="">Select Tenant</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}" data-domain="{{ $tenant->domain }}">
                        {{ $tenant->id }} ({{ $tenant->domain }})
                    </option>
                @endforeach
            </select>
        </td>
        @foreach($season->commodities as $commodity)
            <td class="px-6 py-4 whitespace-nowrap text-center">
                <div class="flex rounded-md shadow-sm">
                    <input type="number"
                           name="allocations[INDEX][commodities][{{ $loop->index }}][allocated_stock]"
                           class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-center allocation-input"
                           data-commodity-id="{{ $commodity->id }}"
                           data-max-available="{{ $availableStock[$commodity->id]['remaining'] ?? 0 }}"
                           min="0"
                           step="0.01"
                           value="0"
                           placeholder="0.00">
                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        {{ $commodity->unit }}
                    </span>
                </div>
                <input type="hidden"
                       name="allocations[INDEX][commodities][{{ $loop->index }}][commodity_id]"
                       value="{{ $commodity->id }}">
                <p class="mt-1 text-xs text-red-600 allocation-error hidden">
                    Exceeds available stock
                </p>
            </td>
        @endforeach
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <button type="button"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 remove-tenant">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
</template>

@push('styles')
<style>
    .allocation-input:focus {
        @apply ring-2 ring-blue-500 border-blue-500;
    }

    .allocation-input.is-invalid {
        @apply border-red-500 focus:ring-red-500 focus:border-red-500;
    }

    .allocation-input.is-valid {
        @apply border-green-500 focus:ring-green-500 focus:border-green-500;
    }

    .tenant-row {
        @apply transition-colors duration-200;
    }

    .tenant-row:hover {
        @apply bg-gray-50;
    }

    .remove-tenant {
        @apply opacity-70 transition-all duration-200;
    }

    .remove-tenant:hover {
        @apply opacity-100 transform scale-110;
    }

    .allocation-error {
        @apply text-red-600 text-xs mt-1;
    }

    .total-allocated {
        @apply transition-colors duration-200;
    }

    /* Responsive table improvements */
    @media (max-width: 768px) {
        .allocation-table {
            @apply text-sm;
        }

        .allocation-table th,
        .allocation-table td {
            @apply px-2 py-2;
        }
    }
</style>
@endpush

@push('scripts')
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
    let rowIndex = 0;
    const tenantRows = document.getElementById('tenantRows');
    const template = document.getElementById('tenantRowTemplate');
    const addTenantBtn = document.getElementById('addTenantBtn');
    const allocationForm = document.getElementById('allocationForm');
    const autoCalculate = document.getElementById('autoCalculate');

    // Available stock data
    const availableStock = @json($availableStock);
    const seasonCommodities = @json($season->commodities->pluck('id', 'name'));

    // Track selected tenants to prevent duplicates
    const selectedTenants = new Set();

    // Add tenant row
    function addTenantRow() {
        const clone = template.content.cloneNode(true);
        const row = clone.querySelector('.tenant-row');

        // Replace INDEX placeholder
        row.querySelectorAll('[name*="INDEX"]').forEach(element => {
            const name = element.getAttribute('name');
            element.setAttribute('name', name.replace('INDEX', rowIndex));
        });

        // Set up event listeners
        setupRowEventListeners(row);

        // Add to table
        tenantRows.appendChild(row);
        rowIndex++;

        // Debug: Log the form structure
        console.log('Added row with index:', rowIndex - 1);
        console.log('Form inputs:', row.querySelectorAll('input, select'));

        return row;
    }

    // Set up event listeners for a row
    function setupRowEventListeners(row) {
        const tenantSelect = row.querySelector('.tenant-select');
        const allocationInputs = row.querySelectorAll('.allocation-input');
        const removeBtn = row.querySelector('.remove-tenant');

        // Tenant selection
        tenantSelect.addEventListener('change', function() {
            const tenantId = this.value;

            if (tenantId) {
                if (selectedTenants.has(tenantId)) {
                    alert('This tenant is already selected. Please choose a different tenant.');
                    this.value = '';
                    return;
                }
                selectedTenants.add(tenantId);
                updateTenantDropdowns();
            } else {
                selectedTenants.delete(tenantId);
                updateTenantDropdowns();
            }

            // Update submit button state
            updateSubmitButtonState();
        });

        // Allocation inputs
        allocationInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (autoCalculate.checked) {
                    updateAvailableQuantities();
                } else {
                    validateAllocation(this);
                    updateTotalAllocatedDisplay();
                    updateSubmitButtonState();
                }
            });

            input.addEventListener('blur', function() {
                validateAllocation(this);
                updateTotalAllocatedDisplay();
                updateSubmitButtonState();
            });
        });

        // Remove button
        removeBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this tenant allocation?')) {
                // Remove from selected tenants
                if (tenantSelect.value) {
                    selectedTenants.delete(tenantSelect.value);
                }

                row.remove();
                updateTenantDropdowns();
                updateAvailableQuantities();
                updateSubmitButtonState();

                // Re-index all remaining rows to ensure proper form data structure
                reindexFormRows();
            }
        });
    }

    // Remove empty rows (rows without tenant selection) before form submission
    function removeEmptyRows() {
        const rows = document.querySelectorAll('.tenant-row');
        console.log('Removing empty rows. Total rows before:', rows.length);

        rows.forEach((row, index) => {
            const tenantSelect = row.querySelector('.tenant-select');
            if (!tenantSelect || !tenantSelect.value) {
                console.log(`Removing empty row at index ${index}`);
                row.remove();
            }
        });

        console.log('Empty rows removed. Remaining rows:', document.querySelectorAll('.tenant-row').length);
    }

    // Re-index all form rows to ensure proper sequential indexing
    function reindexFormRows() {
        const rows = document.querySelectorAll('.tenant-row');
        console.log('Re-indexing form rows. Total rows:', rows.length);

        rows.forEach((row, rowIndex) => {
            console.log(`Processing row ${rowIndex}:`);

            // Update tenant_id field
            const tenantSelect = row.querySelector('.tenant-select');
            if (tenantSelect) {
                tenantSelect.name = `allocations[${rowIndex}][tenant_id]`;
                console.log(`  Updated tenant select name to: ${tenantSelect.name}`);
            }

            // Update commodity fields
            const commodityInputs = row.querySelectorAll('.allocation-input');
            commodityInputs.forEach((input, commodityIndex) => {
                const commodityId = input.dataset.commodityId;
                input.name = `allocations[${rowIndex}][commodities][${commodityIndex}][allocated_stock]`;

                // Update the corresponding hidden input for commodity_id
                const hiddenInput = input.parentElement.parentElement.querySelector('input[type="hidden"]');
                if (hiddenInput) {
                    hiddenInput.name = `allocations[${rowIndex}][commodities][${commodityIndex}][commodity_id]`;
                }

                console.log(`  Updated input for commodity ${commodityId}:`, {
                    name: input.name,
                    value: input.value,
                    commodityId: commodityId
                });
            });
        });

        // Update the global row index
        rowIndex = rows.length;
        console.log('Updated rowIndex to:', rowIndex);
    }

    // Update tenant dropdowns to disable selected options
    function updateTenantDropdowns() {
        const selects = document.querySelectorAll('.tenant-select');

        selects.forEach(select => {
            const currentValue = select.value;

            // Reset all options
            select.querySelectorAll('option').forEach(option => {
                option.disabled = false;
            });

            // Disable selected options in other selects
            selectedTenants.forEach(tenantId => {
                if (tenantId && tenantId !== currentValue) {
                    const option = select.querySelector(`option[value="${tenantId}"]`);
                    if (option) {
                        option.disabled = true;
                    }
                }
            });
        });
    }

    // Validate allocation input
    function validateAllocation(input) {
        const value = parseFloat(input.value) || 0;
        const commodityId = input.dataset.commodityId;
        const errorElement = input.closest('td').querySelector('.allocation-error');

        // Remove previous validation classes
        input.classList.remove('is-valid', 'is-invalid');

        if (value < 0) {
            input.value = 0;
            input.classList.add('is-invalid');
            if (errorElement) {
                errorElement.textContent = 'Cannot be negative';
                errorElement.classList.remove('hidden');
            }
            return false;
        }

        // Only validate against total stock if value is greater than 0
        if (value > 0) {
            // Calculate total allocated for this commodity across all tenants
            const totalAllocated = calculateTotalAllocatedForCommodity(commodityId);
            const maxAvailable = parseFloat(input.dataset.maxAvailable) || 0;

            if (totalAllocated > maxAvailable) {
                input.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = `Total allocation (${totalAllocated.toFixed(2)}) exceeds available stock (${maxAvailable.toFixed(2)})`;
                    errorElement.classList.remove('hidden');
                }
                return false;
            }
        }

        input.classList.add('is-valid');
        if (errorElement) {
            errorElement.classList.add('hidden');
        }
        return true;
    }

    // Calculate total allocated stock for a specific commodity across all tenants
    function calculateTotalAllocatedForCommodity(commodityId) {
        const inputs = document.querySelectorAll(`.allocation-input[data-commodity-id="${commodityId}"]`);
        let total = 0;

        inputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });

        return total;
    }

    // Update available quantities display
    function updateAvailableQuantities() {
        const inputs = document.querySelectorAll('.allocation-input');

        // First, update all inputs with their max values
        inputs.forEach(input => {
            const maxAvailable = parseFloat(input.dataset.maxAvailable) || 0;
            input.max = maxAvailable;
        });

        // Then validate all inputs (this will check total allocations)
        inputs.forEach(input => {
            validateAllocation(input);
        });

        // Update submit button state
        updateSubmitButtonState();

        // Update total allocated display
        updateTotalAllocatedDisplay();
    }

    // Update the total allocated display in the footer
    function updateTotalAllocatedDisplay() {
        const seasonCommodities = @json($season->commodities);

        seasonCommodities.forEach(commodity => {
            const totalAllocated = calculateTotalAllocatedForCommodity(commodity.id);
            const totalElement = document.querySelector(`.total-allocated[data-commodity-id="${commodity.id}"]`);

            if (totalElement) {
                totalElement.textContent = `${totalAllocated.toFixed(2)} ${commodity.unit}`;

                // Change color based on allocation level
                const maxAvailable = parseFloat(document.querySelector(`[data-commodity-id="${commodity.id}"]`)?.dataset.maxAvailable || 0);
                const percentage = maxAvailable > 0 ? (totalAllocated / maxAvailable) * 100 : 0;

                totalElement.classList.remove('bg-blue-100', 'bg-yellow-100', 'bg-red-100');
                if (percentage >= 90) {
                    totalElement.classList.add('bg-red-100');
                } else if (percentage >= 70) {
                    totalElement.classList.add('bg-yellow-100');
                } else {
                    totalElement.classList.add('bg-blue-100');
                }
            }
        });
    }

    // Check if form has validation errors and update submit button
    function updateSubmitButtonState() {
        const submitBtn = document.getElementById('submitBtn');
        const invalidInputs = document.querySelectorAll('.allocation-input.is-invalid');
        const tenantSelects = document.querySelectorAll('.tenant-select');
        const selectedTenants = Array.from(tenantSelects).map(select => select.value).filter(Boolean);

        // Check for validation errors
        const hasValidationErrors = invalidInputs.length > 0;

        // Check if at least one tenant is selected
        const hasSelectedTenants = selectedTenants.length > 0;

        // Check for duplicate tenants
        const hasDuplicateTenants = new Set(selectedTenants).size !== selectedTenants.length;

        // Disable submit button if there are errors or no tenants selected
        const shouldDisable = hasValidationErrors || !hasSelectedTenants || hasDuplicateTenants;

        submitBtn.disabled = shouldDisable;

        if (shouldDisable) {
            submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');

            if (!hasSelectedTenants) {
                submitBtn.title = 'Please add at least one tenant';
            } else if (hasDuplicateTenants) {
                submitBtn.title = 'Please remove duplicate tenants';
            } else if (hasValidationErrors) {
                submitBtn.title = 'Please fix allocation errors';
            }
        } else {
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            submitBtn.title = 'Save allocations';
        }
    }

    // Form submission
    allocationForm.addEventListener('submit', function(e) {
        // Ensure all form fields are properly indexed before submission
        reindexFormRows();

        // Remove empty rows from form before submission
        removeEmptyRows();

        // Get all tenant rows
        const rows = document.querySelectorAll('.tenant-row');
        const tenantSelects = document.querySelectorAll('.tenant-select');
        const selectedTenants = Array.from(tenantSelects).map(select => select.value).filter(Boolean);

        // Debug: Log form data structure
        console.log('=== FORM SUBMISSION DEBUG ===');
        console.log('Total tenant rows:', rows.length);
        console.log('Selected tenants:', selectedTenants);

        // Build and log the form data structure
        const formData = new FormData(this);
        const formDataObj = {};

        for (let [key, value] of formData.entries()) {
            // Parse the key to handle nested arrays
            const keys = key.split(/\[|\]\[|\]/).filter(k => k !== '');
            let current = formDataObj;

            for (let i = 0; i < keys.length - 1; i++) {
                const k = keys[i];
                if (!current[k]) {
                    // If it's a number, create an array, otherwise an object
                    current[k] = /^\d+$/.test(keys[i + 1]) ? [] : {};
                }
                current = current[k];
            }

            const lastKey = keys[keys.length - 1];
            if (Array.isArray(current)) {
                current.push(value);
            } else {
                current[lastKey] = value;
            }
        }

        console.log('Form data structure:', JSON.stringify(formDataObj, null, 2));

        // Verify all commodities are included for each tenant
        const hasValidAllocations = Array.from(rows).every(row => {
            const tenantId = row.querySelector('.tenant-select')?.value;
            if (!tenantId) return false;

            const commodityInputs = row.querySelectorAll('.allocation-input');
            const hasAllocations = Array.from(commodityInputs).some(input => {
                const value = parseFloat(input.value) || 0;
                return value > 0;
            });

            if (!hasAllocations) {
                alert(`Please allocate at least one commodity for the selected tenant.`);
                return false;
            }

            return true;
        });

        if (!hasValidAllocations) {
            e.preventDefault();
            return false;
        }

        // Check for duplicates
        if (new Set(selectedTenants).size !== selectedTenants.length) {
            e.preventDefault();
            alert('Error: Duplicate tenants found. Please remove duplicates before saving.');
            return false;
        }

        // Check if at least one tenant is selected
        if (selectedTenants.length === 0) {
            e.preventDefault();
            alert('Error: Please add at least one tenant before saving.');
            return false;
        }

        // Validate all allocations
        let hasErrors = false;

        allocationInputs.forEach(input => {
            if (!validateAllocation(input)) {
                hasErrors = true;
            }
        });

        if (hasErrors) {
            e.preventDefault();
            alert('Error: Please fix allocation errors before saving.');
            return false;
        }

        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

        return true;
    });

    // Auto-calculate toggle
    autoCalculate.addEventListener('change', function() {
        if (this.checked) {
            updateAvailableQuantities();
        }
    });

    // Add initial row
    addTenantRow();

    // Add tenant button
    addTenantBtn.addEventListener('click', function() {
        addTenantRow();
    });

    // Initial setup
    updateTenantDropdowns();
    updateSubmitButtonState();
});
</script>
@endpush
@endsection
