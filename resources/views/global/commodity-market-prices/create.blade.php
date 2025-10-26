@extends('layouts.layout')

@section('content')
    <div class="max-w-5xl mx-auto py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add New Market Price</h3>
                <a href="{{ route('global.commodity-market-prices.index') }}"
                   class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    &larr; Back to list
                </a>
            </div>

            <div class="p-6">
                <form action="{{ route('global.commodity-market-prices.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Commodity -->
                        <div>
                            <label for="global_commodity_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Commodity <span class="text-red-500">*</span>
                            </label>
                            <select name="global_commodity_id"
                                    id="global_commodity_id"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select a commodity</option>
                                @foreach($commodities as $commodity)
                                    <option value="{{ $commodity->id }}" {{ old('global_commodity_id') == $commodity->id ? 'selected' : '' }}>
                                        {{ $commodity->name }} ({{ $commodity->category->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('global_commodity_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Season -->
                        <div>
                            <label for="global_season_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Season (Optional)
                            </label>
                            <select name="global_season_id"
                                    id="global_season_id"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select a season (optional)</option>
                                @foreach($seasons as $season)
                                    <option value="{{ $season->id }}" {{ old('global_season_id') == $season->id ? 'selected' : '' }}>
                                        {{ $season->name }} ({{ $season->start_date->format('M Y') }} - {{ $season->end_date->format('M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('global_season_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Price -->
                        <div>
                            <label for="current_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Current Price <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">
                                        {{ config('app.currency', '₦') }}
                                    </span>
                                </div>
                                <input type="number"
                                       name="current_price"
                                       id="current_price"
                                       value="{{ old('current_price') }}"
                                       step="0.01"
                                       min="0"
                                       required
                                       class="pl-10 w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            @error('current_price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Effective Date -->
                        <div>
                            <label for="effective_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Effective Date
                            </label>
                            <input type="date"
                                   name="effective_date"
                                   id="effective_date"
                                   value="{{ old('effective_date', now()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                            @error('effective_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Notes (Optional)
                            </label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('global.commodity-market-prices.index') }}"
                           class="bg-white dark:bg-gray-600 py-2 px-4 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Cancel
                        </a>
                        <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Save Market Price
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

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
    // Initialize any required JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // You can add any client-side validation or dynamic behavior here
    });
</script>
@endpush
