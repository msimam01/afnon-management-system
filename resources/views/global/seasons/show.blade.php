@extends('layouts.layout')

@section('content')
    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $season->name }}</h2>
                    <div class="flex items-center mt-1">
                        <span class="px-2 py-1 text-xs rounded-full {{ $season->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($season->status) }}
                        </span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ $season->type === 'dry' ? 'Dry Season' : 'Wet Season' }} | {{ ucfirst($season->loan_type) }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('global.seasons.edit', $season->uuid) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Edit Season
                    </a>
                    <a href="{{ route('global.seasons.index') }}"
                        class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Back to Seasons
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">📅 Season Dates</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="block">Start: {{ $season->start_date->format('M d, Y') }}</span>
                        <span class="block">End: {{ $season->end_date->format('M d, Y') }}</span>
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">📦 Collection Period</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="block">Start: {{ $season->collection_start_date->format('M d, Y') }}</span>
                        <span class="block">End: {{ $season->collection_end_date->format('M d, Y') }}</span>
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">📊 Details</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="block">Insurance: {{ $season->insurance_rate }}%</span>
                        @if($season->budget)
                            <span class="block">Budget: ₦{{ number_format($season->budget, 2) }}</span>
                        @endif
                        @if($season->return_deadline)
                            <span class="block">Return Deadline: {{ $season->return_deadline->format('M d, Y') }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Commodities Section -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Commodities</h3>
                    <button onclick="document.getElementById('addCommodityModal').classList.remove('hidden')"
                        class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">
                        + Add Commodity
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Commodity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price per Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($season->commodities as $commodity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $commodity->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $commodity->category->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format($commodity->pivot->stock) }} {{ $commodity->unit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            ₦{{ number_format($commodity->price_per_unit, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openEditModal('{{ $commodity->uuid }}', '{{ $commodity->name }}', '{{ $commodity->pivot->stock }}', '{{ $commodity->pivot->price_per_unit }}')"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-4">
                                            Edit
                                        </button>
                                        <form action="{{ route('global.seasons.remove-commodity', ['season' => $season->uuid, 'commodity' => $commodity->uuid]) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to remove this commodity from the season?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No commodities added to this season yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Commodity Modal -->
    <div id="addCommodityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden" style="z-index: 1000;">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Add Commodity</h3>
                    <button onclick="document.getElementById('addCommodityModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('global.seasons.add-commodity', $season->uuid) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="commodity_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity</label>
                            <select id="commodity_id" name="commodity_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Select a commodity</option>
                                @foreach($availableCommodities as $commodity)
                                    <option value="{{ $commodity->id }}">{{ $commodity->name }} ({{ $commodity->category->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                            <input type="number" name="stock" id="stock" required min="0" step="0.01"
                                class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <!-- <div>
                            <label for="price_per_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per Unit (₦)</label>
                            <input type="number" name="price_per_unit" id="price_per_unit" required min="0" step="0.01"
                                class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div> -->
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('addCommodityModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Add Commodity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Commodity Modal -->
    <div id="editCommodityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden" style="z-index: 1000;">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Commodity</h3>
                    <button onclick="document.getElementById('editCommodityModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editCommodityForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                            <input type="number" name="stock" id="edit_stock" required min="0" step="0.01"
                                class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <!-- <label for="edit_price_per_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per Unit (₦)</label> -->
                            <input type="hidden" name="price_per_unit" id="edit_price_per_unit" required min="0" step="0.01"
                                class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('editCommodityModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Commodity
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
        function openEditModal(commodityId, commodityName, stock, pricePerUnit) {
            document.getElementById('editCommodityForm').action = `/global/seasons/{{ $season->uuid }}/commodities/${commodityId}`;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_price_per_unit').value = pricePerUnit;
            document.getElementById('editCommodityModal').classList.remove('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.id === 'addCommodityModal') {
                document.getElementById('addCommodityModal').classList.add('hidden');
            }
            if (event.target.id === 'editCommodityModal') {
                document.getElementById('editCommodityModal').classList.add('hidden');
            }
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.getElementById('addCommodityModal').classList.add('hidden');
                document.getElementById('editCommodityModal').classList.add('hidden');
            }
        });
    </script>
    @endpush
@endsection
