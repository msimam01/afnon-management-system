@extends('layouts.layout')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Create New Global Season</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Define a new agricultural season with commodities and stock allocation</p>
            </div>
            <a href="{{ route('global.seasons.index') }}"
               class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Seasons
            </a>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="m-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were errors with your submission</h3>
                        <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div>
            <form action="{{ route('global.seasons.store') }}" method="POST" class="p-6 space-y-8" id="seasonForm">
                @csrf

                <!-- Basic Information Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">Basic Information</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Season Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Season Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                <option value="">Select Season Type</option>
                                <option value="dry" {{ old('type') == 'dry' ? 'selected' : '' }}>Dry Season</option>
                                <option value="wet" {{ old('type') == 'wet' ? 'selected' : '' }}>Wet Season</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Application Scenario -->
                        <div>
                            <label for="loan_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Application Scenario <span class="text-red-500">*</span>
                            </label>
                            <select name="loan_type" id="loan_type" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                <option value="co-funded" {{ old('loan_type', 'co-funded') == 'co-funded' ? 'selected' : '' }}>
                                    Co-funded (50% upfront)
                                </option>
                                <option value="complete-loan" {{ old('loan_type') == 'complete-loan' ? 'selected' : '' }}>
                                    Complete Loan (commodity return)
                                </option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Defines the loan structure for this season</p>
                            <x-input-error :messages="$errors->get('loan_type')" class="mt-2" />
                        </div>

                        <!-- Season Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Season Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                placeholder="e.g., 2025 Dry Season - Northern Region"
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Date Configuration Section -->
                <div class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">Date Configuration</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Season Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Season End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <!-- Collection Start Date -->
                        <div>
                            <label for="collection_start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Collection Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="collection_start_date" name="collection_start_date" value="{{ old('collection_start_date') }}" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">When collection begins after season ends</p>
                            <x-input-error :messages="$errors->get('collection_start_date')" class="mt-2" />
                        </div>

                        <!-- Collection End Date -->
                        <div>
                            <label for="collection_end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Collection End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="collection_end_date" name="collection_end_date" value="{{ old('collection_end_date') }}" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Final date for commodity collection</p>
                            <x-input-error :messages="$errors->get('collection_end_date')" class="mt-2" />
                        </div>

                        <!-- Return Deadline (Conditional) -->
                        <div id="return-deadline-wrapper" class="hidden">
                            <label for="return_deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Return Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="return_deadline" name="return_deadline" value="{{ old('return_deadline') }}"
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Deadline for commodity returns (Complete Loan only)</p>
                            <x-input-error :messages="$errors->get('return_deadline')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Financial Configuration Section -->
                <div class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">Financial Configuration</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Insurance Rate -->
                        <div>
                            <label for="insurance_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Insurance Rate (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="insurance_rate" name="insurance_rate" value="{{ old('insurance_rate', 5) }}"
                                    min="0" max="100" step="0.01" required
                                    class="mt-1 w-full px-3 py-2 pr-8 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">%</span>
                            </div>
                            <x-input-error :messages="$errors->get('insurance_rate')" class="mt-2" />
                        </div>

                        <!-- Budget -->
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Season Budget
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">₦</span>
                                <input type="number" id="budget" name="budget" value="{{ old('budget') }}"
                                    min="0" step="0.01"
                                    class="mt-1 w-full pl-8 pr-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                    placeholder="Optional">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total budget allocation</p>
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <!-- Send Reminder After Days -->
                        <div>
                            <label for="send_reminder_after_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Reminder Interval (Days) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="send_reminder_after_days" name="send_reminder_after_days"
                                value="{{ old('send_reminder_after_days', 30) }}" min="1" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Days before sending payment reminders</p>
                            <x-input-error :messages="$errors->get('send_reminder_after_days')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Commodities Section -->
                <div class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white">Commodities & Stock</h4>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            <span id="commodity-count">0</span> commodities added
                        </span>
                    </div>

                    <div id="commodities-container" class="space-y-4">
                        <!-- Commodity rows will be added here by JavaScript -->
                    </div>

                    <div class="flex items-center justify-center p-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg" id="empty-state">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No commodities added yet</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Click the button below to add commodities</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="button" id="add-commodity"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Commodity
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('commodities')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="text-red-500">*</span> Required fields
                    </p>
                    <div class="flex space-x-3">
                        <a href="{{ route('global.seasons.index') }}"
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Season
                        </button>
                    </div>
                </div>
            </form>
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
        // Available commodities data
        const availableCommodities = @json($commodities);
        let commodityCounter = 0;

        // Update commodity count display
        function updateCommodityCount() {
            const count = document.querySelectorAll('.commodity-row').length;
            document.getElementById('commodity-count').textContent = count;

            // Show/hide empty state
            const emptyState = document.getElementById('empty-state');
            if (count === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        // Add commodity row
        function addCommodityRow(commodityId = '') {
            const container = document.getElementById('commodities-container');
            const index = commodityCounter++;
            const commodity = availableCommodities.find(c => c.id == commodityId) || availableCommodities[0];

            if (!commodity) {
                alert('No commodities available. Please add commodities first.');
                return;
            }

            const row = document.createElement('div');
            row.className = 'commodity-row bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700 p-5 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm hover:shadow-md transition-shadow';
            row.innerHTML = `
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 mt-2">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Commodity <span class="text-red-500">*</span>
                            </label>
                            <select name="commodities[${index}][id]" required
                                class="commodity-select block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition"
                                onchange="updateCommodityRow(this)">
                                ${availableCommodities.map(c =>
                                    `<option value="${c.id}" data-unit="${c.unit}" ${c.id == commodityId ? 'selected' : ''}>
                                        ${c.name} (${c.category})
                                    </option>`
                                ).join('')}
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Stock Quantity <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-md shadow-sm">
                                <input type="number" name="commodities[${index}][stock]" required min="1" step="1"
                                    class="block w-full rounded-l-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 transition"
                                    placeholder="Enter quantity">
                                <span class="unit-display inline-flex items-center px-4 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-medium">
                                    ${commodity.unit}
                                </span>
                            </div>
                        </div>
                        <div class="md:col-span-2 flex items-end">
                            <button type="button" onclick="removeCommodityRow(this)"
                                class="w-full inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(row);
            updateAvailableCommodities();
            updateCommodityCount();
        }

        // Update commodity row when selection changes
        function updateCommodityRow(select) {
            const row = select.closest('.commodity-row');
            const unitDisplay = row.querySelector('.unit-display');
            const selectedOption = select.options[select.selectedIndex];
            const unit = selectedOption.getAttribute('data-unit');
            unitDisplay.textContent = unit;
            updateAvailableCommodities();
        }

        // Remove commodity row
        function removeCommodityRow(button) {
            const row = button.closest('.commodity-row');
            row.remove();
            updateAvailableCommodities();
            updateCommodityCount();

            // Renumber remaining rows
            document.querySelectorAll('.commodity-row').forEach((row, index) => {
                const select = row.querySelector('select');
                const stockInput = row.querySelector('input[name$="[stock]"]');

                select.name = `commodities[${index}][id]`;
                stockInput.name = `commodities[${index}][stock]`;
            });
        }

        // Update available commodities in dropdowns
        function updateAvailableCommodities() {
            const selectedIds = Array.from(document.querySelectorAll('.commodity-select'))
                .map(select => select.value);

            document.querySelectorAll('.commodity-select').forEach(select => {
                const currentValue = select.value;

                Array.from(select.options).forEach(option => {
                    const isSelected = selectedIds.includes(option.value) && option.value !== currentValue;
                    option.disabled = isSelected;
                    if (isSelected) {
                        option.classList.add('text-gray-400');
                    } else {
                        option.classList.remove('text-gray-400');
                    }
                });
            });
        }

        // Toggle return deadline field based on loan type
        function toggleReturnFields() {
            const loanType = document.getElementById('loan_type').value;
            const returnDeadlineWrapper = document.getElementById('return-deadline-wrapper');
            const returnDeadlineInput = document.getElementById('return_deadline');

            if (loanType === 'complete-loan') {
                returnDeadlineWrapper.classList.remove('hidden');
                returnDeadlineInput.required = true;
            } else {
                returnDeadlineWrapper.classList.add('hidden');
                returnDeadlineInput.required = false;
            }
        }

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial state
            toggleReturnFields();
            updateCommodityCount();

            // Add change event listener for loan type
            document.getElementById('loan_type').addEventListener('change', toggleReturnFields);

            // Set minimum dates for date inputs
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').min = today;
            document.getElementById('end_date').min = today;
            document.getElementById('collection_start_date').min = today;
            document.getElementById('collection_end_date').min = today;
            document.getElementById('return_deadline').min = today;

            // Update min dates when start date changes
            document.getElementById('start_date').addEventListener('change', function() {
                const startDate = this.value;
                document.getElementById('end_date').min = startDate;
                document.getElementById('collection_start_date').min = startDate;
                document.getElementById('collection_end_date').min = startDate;
                document.getElementById('return_deadline').min = startDate;
            });

            // Update min date for collection dates when end date changes
            document.getElementById('end_date').addEventListener('change', function() {
                const endDate = this.value;
                document.getElementById('collection_start_date').min = endDate;
                document.getElementById('collection_end_date').min = endDate;
                document.getElementById('return_deadline').min = endDate;
            });

            // Update min date for collection end date when collection start date changes
            document.getElementById('collection_start_date').addEventListener('change', function() {
                document.getElementById('collection_end_date').min = this.value;
            });

            // Add commodity button click handler
            document.getElementById('add-commodity').addEventListener('click', function() {
                if (availableCommodities.length > 0) {
                    addCommodityRow();
                } else {
                    alert('No commodities available. Please create commodities first.');
                }
            });

            // Form validation before submit
            document.getElementById('seasonForm').addEventListener('submit', function(e) {
                const commodityRows = document.querySelectorAll('.commodity-row');
                if (commodityRows.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one commodity before creating the season.');
                    return false;
                }
            });
        });
    </script>
    @endpush
@endsection
