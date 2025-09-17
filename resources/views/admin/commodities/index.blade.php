use App\Http\Controllers\CommodityCategoryController;
@extends('layouts.layout')

@section('content')
    <div id="commodities-section" class="w-full px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Available Commodities</h3>
                <div class="flex space-x-2">
                    <!-- Existing Add Commodity -->
                    <a href="{{ route('admin.commodities.create') }}"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                        + Add Commodity
                    </a>

                    <!-- Trigger Commodity Categories Modal -->
                    <button onclick="openModal('categoryModal')"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        + Categories
                    </button>

                    <!-- Trigger Commodity Market Prices Modal -->
                    <button onclick="openModal('marketPriceModal')"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                        + Market Prices
                    </button>
                </div>
            </div>

            {{-- Search + Table remains the same --}}
            <form method="GET" action="{{ route('admin.commodities.index') }}"
                class="px-6 py-4 flex flex-col md:flex-row gap-4 border-b border-gray-200 dark:border-gray-700">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or category"
                        class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                </div>

                <div class="self-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">Search</button>
                </div>
            </form>

            {{-- Commodities Table --}}
            <div class="px-6 pb-6 pt-3">
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Commodity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Unit</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Price (₦)</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Qty/ha</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Stock / Allocation</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($commodities as $item)
                                <tr>
                                    <!-- Commodity + Badge + Timestamp -->
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</div>
                                        <div class="flex items-center gap-2 mt-1 text-xs">
                                            <span
                                                class="text-gray-400 dark:text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->category }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                        ₦{{ number_format($item->price_per_unit) }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                        {{ number_format($item->quantity_per_hectare) }}</td>

                                    <!-- Stock / Allocated -->
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">

                                        {{ $item->stock }}

                                    </td>

                                    <!-- Actions -->
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.commodities.edit', $item->uuid) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>


                                            <form action="{{ route('admin.commodities.destroy', $item->uuid) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 dark:text-red-400 hover:underline text-xs">Delete</button>
                                            </form>



                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No
                                        commodities found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                    <div class="px-6 mt-4">
                        {{ $commodities->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Commodity Categories Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Add Commodity Category</h2>
            <form action="{{ route('admin.commodities.category') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                    <input type="text" name="name" required
                        class="w-full mt-1 px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('categoryModal')"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Commodity Market Prices Modal -->
    <div id="marketPriceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Add Market Price</h2>
            <form action="{{ route('admin.commodities.market-price') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity</label>
                    <select name="commodity_id" required
                        class="w-full mt-1 px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        @foreach($commodities as $commodity)
                            <option value="{{ $commodity->id }}">{{ $commodity->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season (optional)</label>
                    <select name="season_id"
                        class="w-full mt-1 px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">-- None --</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}">{{ $season->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Price (₦)</label>
                    <input type="number" step="0.01" name="current_price" required
                        class="w-full mt-1 px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('marketPriceModal')"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
                </div>
            </form>
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
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden')
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden')
        }
    </script>
@endsection
