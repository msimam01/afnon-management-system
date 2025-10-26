@extends('layouts.layout')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Global Season: {{ $season->name }}</h3>
            <a href="{{ route('global.seasons.show', $season->uuid) }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                Back to Season
            </a>
        </div>

        <div>
            <form action="{{ route('global.seasons.update', $season->uuid) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Season Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Season Type *
                        </label>
                        <select name="type" id="type" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <option value="">Select</option>
                            <option value="dry" {{ old('type', $season->type) == 'dry' ? 'selected' : '' }}>Dry</option>
                            <option value="wet" {{ old('type', $season->type) == 'wet' ? 'selected' : '' }}>Wet</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- Application Scenario -->
                    <div>
                        <label for="loan_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Application Scenario *
                        </label>
                        <select name="loan_type" id="loan_type" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <option value="co-funded" {{ old('loan_type', $season->loan_type) == 'co-funded' ? 'selected' : '' }}>
                                Co-funded (50% upfront)
                            </option>
                            <option value="complete-loan" {{ old('loan_type', $season->loan_type) == 'complete-loan' ? 'selected' : '' }}>
                                Complete Loan (commodity return)
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('loan_type')" class="mt-2" />
                    </div>

                    <!-- Season Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Season Name *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $season->name) }}" required
                            placeholder="e.g: 2025 Dry Season"
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Start Date *
                        </label>
                        <input type="date" id="start_date" name="start_date"
                            value="{{ old('start_date', $season->start_date->format('Y-m-d')) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            End Date *
                        </label>
                        <input type="date" id="end_date" name="end_date"
                            value="{{ old('end_date', $season->end_date->format('Y-m-d')) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>

                    <!-- Collection Start Date -->
                    <div>
                        <label for="collection_start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Collection Start Date *
                        </label>
                        <input type="date" id="collection_start_date" name="collection_start_date"
                            value="{{ old('collection_start_date', $season->collection_start_date->format('Y-m-d')) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('collection_start_date')" class="mt-2" />
                    </div>

                    <!-- Collection End Date -->
                    <div>
                        <label for="collection_end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Collection End Date *
                        </label>
                        <input type="date" id="collection_end_date" name="collection_end_date"
                            value="{{ old('collection_end_date', $season->collection_end_date->format('Y-m-d')) }}" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('collection_end_date')" class="mt-2" />
                    </div>

                    <!-- Return Deadline (Conditional) -->
                    <div id="return-deadline-wrapper" class="{{ $season->loan_type === 'complete-loan' ? '' : 'hidden' }}">
                        <label for="return_deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Return Deadline *
                        </label>
                        <input type="date" id="return_deadline" name="return_deadline"
                            value="{{ old('return_deadline', $season->return_deadline ? $season->return_deadline->format('Y-m-d') : '') }}"
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('return_deadline')" class="mt-2" />
                    </div>

                    <!-- Insurance Rate -->
                    <div>
                        <label for="insurance_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Insurance Rate (%) *
                        </label>
                        <input type="number" id="insurance_rate" name="insurance_rate"
                            value="{{ old('insurance_rate', $season->insurance_rate) }}"
                            min="0" max="100" step="0.01" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('insurance_rate')" class="mt-2" />
                    </div>

                    <!-- Budget -->
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Budget (Optional)
                        </label>
                        <input type="number" id="budget" name="budget"
                            value="{{ old('budget', $season->budget) }}"
                            min="0" step="0.01"
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                    </div>

                    <!-- Send Reminder After Days -->
                    <div>
                        <label for="send_reminder_after_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Send Reminder After (Days) *
                        </label>
                        <input type="number" id="send_reminder_after_days" name="send_reminder_after_days"
                            value="{{ old('send_reminder_after_days', $season->send_reminder_after_days) }}"
                            min="1" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('send_reminder_after_days')" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status *
                        </label>
                        <select name="status" id="status" required
                            class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <option value="open" {{ old('status', $season->status) == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ old('status', $season->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6 space-x-3">
                    <a href="{{ route('global.seasons.show', $season->uuid) }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Update Season
                    </button>
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

        // Set minimum dates for date inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial state
            toggleReturnFields();

            // Add change event listener for loan type
            document.getElementById('loan_type').addEventListener('change', toggleReturnFields);

            // Set minimum dates for date inputs
            const today = new Date().toISOString().split('T')[0];
            const startDate = document.getElementById('start_date').value;

            // Update min dates based on start date
            document.getElementById('start_date').addEventListener('change', function() {
                const startDate = this.value;
                document.getElementById('end_date').min = startDate;
                document.getElementById('collection_start_date').min = startDate;
                document.getElementById('collection_end_date').min = startDate;
                document.getElementById('return_deadline').min = startDate;
            });

            // Set initial min values
            document.getElementById('start_date').min = today;
            document.getElementById('end_date').min = startDate || today;
            document.getElementById('collection_start_date').min = startDate || today;
            document.getElementById('collection_end_date').min = startDate || today;
            document.getElementById('return_deadline').min = startDate || today;

            // Update min date for end date when start date changes
            document.getElementById('start_date').addEventListener('change', function() {
                document.getElementById('end_date').min = this.value;
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
        });
    </script>
    @endpush
@endsection
