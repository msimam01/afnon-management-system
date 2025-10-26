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
                        <span>Edit Allocation</span>
                        <p class="text-lg font-normal text-gray-600 mt-1">{{ $season->name }} - {{ ucfirst($tenant->id) }}</p>
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
                        <i class="fas fa-building mr-2"></i>
                        {{ ucfirst($tenant->id) }} ({{ $tenant->domain }})
                    </span>
                </div>
            </div>

            <div class="mt-6 md:mt-0 flex flex-col gap-3">
                <a href="{{ route('global.allocations.index', $season->uuid) }}"
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Stock Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @foreach($season->commodities as $commodity)
            @php
                $stock = $availableStock[$commodity->id];
                $maxAvailable = $stock['available'];
                $currentAllocation = $stock['current_allocation'];
                $utilizationPercent = $stock['total'] > 0 ? ($currentAllocation / $stock['total']) * 100 : 0;
            @endphp

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                        <span class="text-white text-xs font-semibold px-3 py-1 bg-white/20 rounded-full">
                            {{ ucfirst($commodity->unit) }}
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    <h6 class="font-bold text-gray-900 text-lg mb-1">{{ $commodity->name }}</h6>
                    <p class="text-sm text-gray-600 mb-4">ID: {{ $commodity->id }}</p>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Current Allocation</span>
                            <span class="text-lg font-bold text-blue-600">
                                {{ number_format($currentAllocation, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Available Stock</span>
                            <span class="text-lg font-bold text-green-600 max-available" data-commodity-id="{{ $commodity->id }}">
                                {{ number_format($maxAvailable, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Total Stock</span>
                            <span class="text-sm font-semibold text-gray-800">
                                {{ number_format($stock['total'], 2) }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Utilization</span>
                                <span class="text-sm font-bold utilization-percent text-purple-600" data-commodity-id="{{ $commodity->id }}">
                                    {{ number_format($utilizationPercent, 1) }}%
                                </span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="progress-bar-preview bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                                     data-commodity-id="{{ $commodity->id }}"
                                     style="width: {{ $utilizationPercent }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Enhanced Edit Form -->
    <div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6">
            <h3 class="text-white text-xl font-bold flex items-center">
                <i class="fas fa-sliders-h mr-3"></i>
                Update Commodity Allocations
            </h3>
            <p class="text-indigo-100 mt-2">Modify allocation quantities for each commodity</p>
        </div>

        <!-- Form Body -->
        <div class="p-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-red-800 mb-2">
                                Please fix the following errors:
                            </h3>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('global.allocations.update', ['seasonUuid' => $season->uuid, 'tenantId' => $tenant->id]) }}"
                  method="POST"
                  id="editForm">
                @csrf
                @method('PUT')

                <!-- Allocation Table -->
                <div class="overflow-x-auto mb-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-boxes mr-2"></i>Commodity
                                </th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-chart-pie mr-2"></i>Current
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-edit mr-2"></i>New Allocation
                                </th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-warehouse mr-2"></i>Available
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($season->commodities as $index => $commodity)
                                @php
                                    $stock = $availableStock[$commodity->id];
                                    $currentAllocation = $allocations->get($commodity->id)?->allocated_stock ?? 0;
                                    $maxAvailable = $stock['available'];
                                    $utilizationPercent = $stock['total'] > 0 ? (($currentAllocation / $stock['total']) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-blue-50 transition-colors duration-200">
                                    <!-- Commodity Column -->
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-box text-blue-600 text-sm"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-lg font-semibold text-gray-900">{{ $commodity->name }}</div>
                                                <div class="text-sm text-gray-500 flex items-center">
                                                    <span>ID: {{ $commodity->id }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span>Unit: {{ $commodity->unit }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden"
                                               name="commodities[{{ $index }}][commodity_id]"
                                               value="{{ $commodity->id }}">
                                    </td>

                                    <!-- Current Allocation Column -->
                                    <td class="px-6 py-6 whitespace-nowrap text-center">
                                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 inline-block">
                                            <div class="text-2xl font-bold text-green-700">
                                                {{ number_format($currentAllocation, 2) }}
                                            </div>
                                            <div class="text-sm text-green-600 font-medium">{{ $commodity->unit }}</div>
                                            <div class="mt-2 w-full bg-green-200 rounded-full h-1">
                                                <div class="bg-green-500 h-1 rounded-full" style="width: {{ min($utilizationPercent, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- New Allocation Input Column -->
                                    <td class="px-6 py-6">
                                        <div class="space-y-3">
                                            <div class="flex rounded-lg shadow-sm">
                                                <input type="number"
                                                       name="commodities[{{ $index }}][allocated_stock]"
                                                       class="flex-1 min-w-0 px-4 py-3 border border-gray-300 rounded-l-lg text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors allocation-input"
                                                       data-commodity-id="{{ $commodity->id }}"
                                                       data-current="{{ $currentAllocation }}"
                                                       data-max="{{ $maxAvailable }}"
                                                       data-total="{{ $stock['total'] }}"
                                                       value="{{ number_format($currentAllocation, 2, '.', '') }}"
                                                       min="0"
                                                       step="0.01"
                                                       placeholder="0.00">
                                                <span class="inline-flex items-center px-4 py-3 rounded-r-lg border border-l-0 border-gray-300 bg-gray-50 text-gray-600 font-medium">
                                                    {{ $commodity->unit }}
                                                </span>
                                            </div>

                                            <!-- Error Message -->
                                            <p class="text-red-600 text-sm allocation-error hidden" data-commodity-id="{{ $commodity->id }}">
                                                Invalid allocation amount
                                            </p>

                                            <!-- Change Indicator -->
                                            <div class="flex items-center">
                                                <span class="text-sm text-gray-600 mr-3">Change:</span>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 change-indicator" data-commodity-id="{{ $commodity->id }}">
                                                    <i class="fas fa-minus text-gray-500 mr-1"></i>
                                                    No change
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Available Stock Column -->
                                    <td class="px-6 py-6 whitespace-nowrap text-center">
                                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 inline-block">
                                            <div class="text-2xl font-bold text-blue-700 available-display" data-commodity-id="{{ $commodity->id }}">
                                                {{ number_format($maxAvailable, 2) }}
                                            </div>
                                            <div class="text-sm text-blue-600 font-medium">{{ $commodity->unit }} available</div>
                                            @if($stock['total'] > 0)
                                                <div class="mt-2 w-full bg-blue-200 rounded-full h-1">
                                                    <div class="bg-blue-500 h-1 rounded-full" style="width: {{ min((($maxAvailable / $stock['total']) * 100), 100) }}%"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Info Alert -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-blue-800">
                                Auto-sync Enabled
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                All changes will be automatically synchronized to the tenant database after saving. This ensures data consistency across all systems.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <button type="submit"
                                id="submitBtn"
                                class="inline-flex items-center px-8 py-3 border border-transparent text-base font-semibold rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fas fa-save mr-3"></i>
                            Save Changes & Sync
                        </button>

                        <a href="{{ route('global.allocations.index', $season->uuid) }}"
                           class="inline-flex items-center px-8 py-3 border border-gray-300 text-base font-semibold rounded-xl shadow-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fas fa-times mr-3"></i>
                            Cancel
                        </a>
                    </div>

                    <button type="button"
                            onclick="resetForm()"
                            class="inline-flex items-center px-6 py-3 border border-red-300 text-base font-semibold rounded-xl shadow-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                        <i class="fas fa-undo mr-3"></i>
                        Reset to Current
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

// Initialize available stock data from server
const availableStockData = @json($availableStock);
const initialValues = {};
const form = document.getElementById('editForm');

// Store initial values and set up event listeners
document.querySelectorAll('.allocation-input').forEach(input => {
    const commodityId = input.dataset.commodityId;
    initialValues[commodityId] = parseFloat(input.value) || 0;

    // Set initial max value based on available stock including current allocation
    const maxAvailable = availableStockData[commodityId]?.available_including_current || 0;
    input.max = maxAvailable;

    // Update the available display
    updateAvailableDisplay(commodityId, maxAvailable);

    // Set up event listeners
    input.addEventListener('input', function() {
        handleAllocationChange(this);
    });

    input.addEventListener('change', function() {
        validateInput(this);
    });
});

// Update the available stock display for a commodity
function updateAvailableDisplay(commodityId, available) {
    const displayElement = document.querySelector(`.available-display[data-commodity-id="${commodityId}"]`);
    if (displayElement) {
        displayElement.textContent = available.toLocaleString(undefined, { maximumFractionDigits: 2 });

        // Update the status indicator
        const container = document.querySelector(`.available-stock-display[data-commodity-id="${commodityId}"]`);
        if (container) {
            // Remove all status classes
            container.classList.remove('bg-success-light', 'bg-warning-light', 'bg-danger-light');

            // Add appropriate status class
            if (available > 0) {
                container.classList.add('bg-success-light');
            } else {
                container.classList.add('bg-danger-light');
            }
        }
    }
}

// Handle form submission
form.addEventListener('submit', function(e) {
    // Don't prevent default - let the form submit normally
    // This way redirects and flash messages work properly

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving & Syncing...';

    // The form will submit normally and handle redirects/flash messages
});

// Helper function to show toast notifications
function showToast(title, message, type = 'info') {
    // Implementation of toast notification
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <strong>${title}</strong><br>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    const toastContainer = document.querySelector('.toast-container') || (() => {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '11';
        document.body.appendChild(container);
        return container;
    })();

    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

updateSubmitButtonState();

function handleAllocationChange(input) {
    const commodityId = input.dataset.commodityId;
    const newValue = parseFloat(input.value) || 0;
    const currentValue = parseFloat(input.dataset.current) || 0;

    // Get available stock data for this commodity
    const stockData = availableStockData[commodityId] || {};
    const maxAvailable = stockData.available_including_current || 0;
    const totalStock = stockData.total || 0;

    const change = newValue - currentValue;
    const changeIndicator = document.querySelector(`.change-indicator[data-commodity-id="${commodityId}"]`);

    if (Math.abs(change) < 0.01) {
        changeIndicator.textContent = 'No change';
        changeIndicator.className = 'badge badge-secondary change-indicator';
    } else if (change > 0) {
        changeIndicator.textContent = `+${change.toFixed(2)} (Increase)`;
        changeIndicator.className = 'badge badge-success change-indicator';
    } else {
        changeIndicator.textContent = `${change.toFixed(2)} (Decrease)`;
        changeIndicator.className = 'badge badge-warning change-indicator';
    }

    // Update available stock display
    const remainingAvailable = maxAvailable - newValue + currentValue;
    updateAvailableDisplay(commodityId, remainingAvailable);

    const availableAfter = maxAvailable - (newValue - currentValue);
    const availableDisplay = document.querySelector(`.available-display[data-commodity-id="${commodityId}"]`);
    if (availableDisplay) {
        availableDisplay.textContent = availableAfter.toFixed(2);

        const stockDisplay = document.querySelector(`.available-stock-display[data-commodity-id="${commodityId}"]`);
        if (stockDisplay) {
            stockDisplay.classList.remove('stock-high', 'stock-medium', 'stock-low');
            if (availableAfter < totalStock * 0.1) {
                stockDisplay.classList.add('stock-low');
                stockDisplay.dataset.status = 'low';
            } else if (availableAfter < totalStock * 0.3) {
                stockDisplay.classList.add('stock-medium');
                stockDisplay.dataset.status = 'medium';
            } else {
                stockDisplay.classList.add('stock-high');
                stockDisplay.dataset.status = 'high';
            }
        }
    }

    const percentage = totalStock > 0 ? (newValue / totalStock) * 100 : 0;
    const progressBar = document.querySelector(`.progress-bar-preview[data-commodity-id="${commodityId}"]`);
    if (progressBar) {
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
    }

    const utilizationPercent = document.querySelector(`.utilization-percent[data-commodity-id="${commodityId}"]`);
    if (utilizationPercent) {
        utilizationPercent.textContent = percentage.toFixed(1) + '%';
    }

    const maxAvailableSpan = document.querySelector(`.max-available[data-commodity-id="${commodityId}"]`);
    if (maxAvailableSpan) {
        maxAvailableSpan.textContent = availableAfter.toFixed(2);
    }
}

function validateInput(input) {
    const commodityId = input.dataset.commodityId;
    const value = parseFloat(input.value) || 0;
    const currentValue = parseFloat(input.dataset.current) || 0;
    const maxAvailable = parseFloat(input.dataset.max) || 0;
    const maxPossible = maxAvailable + currentValue;

    const errorSpan = input.closest('td').querySelector('.allocation-error');

    if (value > maxPossible) {
        input.classList.add('is-invalid');
        errorSpan.style.display = 'block';
        errorSpan.textContent = `Exceeds available stock. Maximum: ${maxPossible.toFixed(2)}`;
        input.value = maxPossible.toFixed(2);
        handleAllocationChange(input);
    } else if (value < 0) {
        input.classList.add('is-invalid');
        errorSpan.style.display = 'block';
        errorSpan.textContent = 'Cannot be negative';
        input.value = 0;
        handleAllocationChange(input);
    } else {
        input.classList.remove('is-invalid');
        errorSpan.style.display = 'none';
    }

    updateSubmitButtonState();
}

function updateSubmitButtonState() {
    const submitBtn = document.querySelector('button[type="submit"]');
    const invalidInputs = document.querySelectorAll('.allocation-input.is-invalid');

    const hasValidationErrors = invalidInputs.length > 0;

    submitBtn.disabled = hasValidationErrors;

    if (hasValidationErrors) {
        submitBtn.classList.remove('btn-primary');
        submitBtn.classList.add('btn-secondary');
        submitBtn.title = 'Please fix allocation errors before saving';
    } else {
        submitBtn.classList.remove('btn-secondary');
        submitBtn.classList.add('btn-primary');
        submitBtn.title = 'Save changes and sync to tenant';
    }
}

function resetForm() {
    if (!confirm('Reset all allocations to their current values?')) {
        return;
    }

    document.querySelectorAll('.allocation-input').forEach(input => {
        const commodityId = input.dataset.commodityId;
        input.value = initialValues[commodityId];
        handleAllocationChange(input);
    });

    updateSubmitButtonState();
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    const invalidInputs = document.querySelectorAll('.allocation-input.is-invalid');

    if (invalidInputs.length > 0) {
        e.preventDefault();
        alert('Please fix invalid allocations before submitting.');
        return false;
    }

    let hasChanges = false;
    document.querySelectorAll('.allocation-input').forEach(input => {
        const commodityId = input.dataset.commodityId;
        const currentValue = parseFloat(input.value) || 0;
        if (Math.abs(currentValue - initialValues[commodityId]) >= 0.01) {
            hasChanges = true;
        }
    });

    if (!hasChanges) {
        if (!confirm('No changes detected. Submit anyway?')) {
            e.preventDefault();
            return false;
        }
    }

    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving & Syncing...';
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced Global Styles */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.bg-gradient-primary {
    background: var(--primary-gradient);
}

/* Card Enhancements */
.card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.shadow-sm {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
}

/* Badge Enhancements */
.badge-pill {
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.875rem;
}

/* Commodity Cards */
.commodity-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.commodity-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 1.1rem;
}

/* Progress Bar */
.progress {
    background-color: #e9ecef;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}

.progress-bar {
    transition: width 0.4s ease;
    background: var(--primary-gradient);
}

/* Table Enhancements */
.allocation-table {
    margin-bottom: 0;
}

.allocation-table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #495057;
    padding: 1rem;
}

.allocation-table tbody tr {
    transition: background-color 0.2s ease;
}

.allocation-table tbody tr:hover {
    background-color: #f8f9fb;
}

.allocation-table td {
    vertical-align: middle;
    padding: 1.25rem 1rem;
    border-bottom: 1px solid #e9ecef;
}

/* Input Enhancements */
.allocation-input {
    font-size: 1.1rem;
    font-weight: 600;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.allocation-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.allocation-input.is-invalid {
    border-color: #dc3545;
    background-color: #fff5f5;
    animation: shake 0.3s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.input-group-text {
    font-weight: 600;
    border: 2px solid #e9ecef;
    border-left: none;
}

/* Current Allocation Badge */
.current-allocation-badge {
    background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);
    padding: 12px 20px;
    border-radius: 10px;
    display: inline-block;
}

/* Available Stock Display */
.available-stock-display {
    transition: all 0.3s ease;
}

.available-stock-display.stock-high {
    background-color: #d4edda;
    border: 2px solid #c3e6cb;
}

.available-stock-display.stock-medium {
    background-color: #fff3cd;
    border: 2px solid #ffeaa7;
}

.available-stock-display.stock-low {
    background-color: #f8d7da;
    border: 2px solid #f5c6cb;
}

/* Change Indicator */
.change-indicator {
    font-size: 0.85rem;
    padding: 0.35rem 0.75rem;
    font-weight: 600;
    border-radius: 20px;
}

/* Button Enhancements */
.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 0.5rem 1.5rem;
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1rem;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
}

/* Alert Enhancements */
.alert {
    border-radius: 10px;
    border: none;
}

.bg-light-info {
    background-color: #e7f3ff !important;
}

/* Breadcrumb */
.breadcrumb {
    font-size: 0.875rem;
}

.breadcrumb-item a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

/* Responsive Spacing */
.g-4 {
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .allocation-table thead th {
        font-size: 0.7rem;
        padding: 0.75rem 0.5rem;
    }

    .allocation-table td {
        padding: 1rem 0.5rem;
    }

    .btn-lg {
        padding: 0.6rem 1.5rem;
        font-size: 0.95rem;
    }
}

/* Loading State */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}
</style>
@endpush
@endsection
