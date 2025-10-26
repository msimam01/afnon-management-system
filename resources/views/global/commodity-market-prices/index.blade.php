@extends('layouts.layout')

@section('content')
    <div id="global-commodity-market-prices-section" class="w-full px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Global Commodity Market Prices</h3>
                {{-- <div class="flex space-x-2">
                    <a href="{{ route('global.commodity-market-prices.create') }}"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                        + Add Market Price
                    </a>
                </div> --}}
            </div>

            <!-- Search and Filter -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <form action="{{ route('global.commodity-market-prices.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <select name="commodity_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Commodities</option>
                            @foreach($commodities as $commodity)
                                <option value="{{ $commodity->id }}" {{ request('commodity_id') == $commodity->id ? 'selected' : '' }}>
                                    {{ $commodity->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="season_id" class="w-full md:w-auto px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Seasons</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>
                                    {{ $season->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                                class="w-full md:w-auto bg-gray-800 dark:bg-gray-700 text-white px-6 py-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Market Prices Table -->
            <div class="px-6 pb-6 pt-3">
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Commodity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Season
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Price ({{ config('app.currency', '₦') }})
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Last Updated
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($marketPrices as $price)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $price->commodity->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $price->commodity->category->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $price->season ? $price->season->name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($price->current_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $price->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="openEditModal('{{ $price->uuid }}', '{{ $price->commodity->name }}', '{{ $price->season ? $price->season->name : '' }}', '{{ $price->global_season_id }}', '{{ $price->global_commodity_id }}', '{{ $price->current_price }}', '{{ $price->effective_date }}', '{{ $price->notes }}')"
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-4">
                                            Edit
                                        </button>
                                        <form action="{{ route('global.commodity-market-prices.destroy', $price) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    onclick="return confirm('Are you sure you want to delete this market price?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No market prices found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 px-2">
                    {{ $marketPrices->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Market Price Modal -->
    <div id="editMarketPriceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden" style="z-index: 1000;">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Market Price</h3>
                    <button onclick="closeModal('editMarketPriceModal')"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editMarketPriceForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit_commodity_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity</label>
                            <select id="edit_commodity_id" name="global_commodity_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Select a commodity</option>
                                @foreach($commodities as $commodity)
                                    <option value="{{ $commodity->id }}">{{ $commodity->name }} ({{ $commodity->category->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="edit_season_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season</label>
                            <select id="edit_season_id" name="global_season_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                @foreach($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="edit_effective_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Effective Date</label>
                            <input type="date" id="edit_effective_date" name="effective_date" required
                                class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="edit_current_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Price (₦)</label>
                            <input type="number" id="edit_current_price" name="current_price" required min="0" step="0.01"
                                class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="edit_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (optional)</label>
                            <textarea id="edit_notes" name="notes" rows="3"
                                class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('editMarketPriceModal')"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Price
                        </button>
                    </div>
                </form>
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

        function openEditModal(uuid, commodityName, seasonName, seasonId, commodityId, currentPrice, effectiveDate, notes) {
            document.getElementById('editMarketPriceForm').action = `/global/commodity-market-prices/${uuid}`;
            document.getElementById('edit_commodity_id').value = commodityId;
            document.getElementById('edit_season_id').value = seasonId;
            document.getElementById('edit_current_price').value = currentPrice;
            document.getElementById('edit_effective_date').value = effectiveDate;
            document.getElementById('edit_notes').value = notes || '';
            document.getElementById('editMarketPriceModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('editMarketPriceModal');
            if (event.target === modal) {
                closeModal('editMarketPriceModal');
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('editMarketPriceModal');
            }
        });

        // Handle form submission success - close modal and reload page
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editMarketPriceForm');
            if (form) {
                form.addEventListener('submit', function(event) {
                    console.log('Form submitting to:', form.action);
                    console.log('Form method:', form.method);
                    console.log('Form data:', new FormData(form));
                    // The modal will be closed by the page reload after successful submission
                    // The observer will handle the synchronization automatically
                });
            }
        });

        // If there are form errors, don't close modal (errors will be shown)
        @if($errors->hasAny(['global_commodity_id', 'global_season_id', 'current_price', 'effective_date', 'notes']))
            document.addEventListener('DOMContentLoaded', function() {
                // If there are validation errors, keep the modal open
                console.log('Validation errors found, keeping modal open');
                document.getElementById('editMarketPriceModal').classList.remove('hidden');
            });
        @endif
    </script>
@endsection
