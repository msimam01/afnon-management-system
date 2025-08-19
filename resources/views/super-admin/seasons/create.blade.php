@extends('layouts.layout')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Create New Season</h2>

        <form method="POST" action="{{ route('superadmin.seasons.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <section>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season Type</label>
                        <select name="season_type" required
                            class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <option value="wet">Wet</option>
                            <option value="dry">Dry</option>
                        </select>
                    </section>
                </div>
                <!-- Season Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season Name
                        *</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white"
                        placeholder="e.g. 2024 Dry Season">
                </div>
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date
                        *</label>
                    <input type="date" name="start_date" id="start_date" required
                        class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date
                        *</label>
                    <input type="date" name="end_date" id="end_date" required
                        class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                </div>

                <!-- Return Deadline -->
                <div>
                    <label for="return_deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Return
                        Deadline *</label>
                    <input type="date" name="return_deadline" id="return_deadline" required
                        class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                </div>

                <!-- Insurance Rate -->
                <div>
                    <label for="insurance_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Insurance
                        Rate (%) *</label>
                    <input type="number" name="insurance_rate" id="insurance_rate" min="0" max="100"
                        step="0.1" value="2" required
                        class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                </div>

                <!-- Reminder Days -->
                <div>
                    <label for="send_reminder_after_days"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reminder Days After Deadline
                        *</label>
                    <input type="number" name="send_reminder_after_days" id="send_reminder_after_days" value="7"
                        min="1" required
                        class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                </div>

                <!-- Budget -->
                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Budget
                        (₦) *</label>
                    <input type="number" name="budget" id="budget" required
                        class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white"
                        placeholder="e.g. 2500000000">
                </div>
            </div>
            <!-- Commodities -->
            <div>
                <label for="commodities" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodities
                    *</label>
                <select name="commodities[]" id="commodities" required multiple
                    class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                    @foreach ($commodities as $commodity)
                        <option value="{{ $commodity->id }}">{{ $commodity->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hold Ctrl or Cmd to select multiple</p>
            </div>
            <div class="mt-6 flex justify-end">
                <a href="{{ route('superadmin.seasons.index') }}"
                    class="text-sm text-gray-600 dark:text-gray-300 hover:underline mr-4">Cancel</a>

                <button type="submit"
                    class="bg-emerald-600 text-white px-6 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">
                    Create Season
                </button>
            </div>
        </form>
    </div>
@endsection
