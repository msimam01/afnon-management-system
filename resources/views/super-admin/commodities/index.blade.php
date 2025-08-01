@extends('layouts.layout')

@section('content')
    <div id="commodities-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Commodities Management</h3>
                <a href="{{ route('superadmin.commodities.create') }}"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                    + Add Commodity
                </a>
            </div>

            <!-- Filters -->
            <div class="px-6 py-4 flex flex-col md:flex-row gap-4 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Season</label>
                    <select
                        class="w-full md:w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option>2024 Dry Season</option>
                        <option>2024 Wet Season</option>
                        <option>2023 Wet Season</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Commodity</label>
                    <select
                        class="w-full md:w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option>Maize Seeds</option>
                        <option>Fertilizer</option>
                        <option>Rice</option>
                    </select>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Season Summary -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-5 bg-white dark:bg-gray-800">
                    <h5 class="text-md font-semibold text-gray-900 dark:text-white mb-4">2024 Dry Season - Maize Seeds</h5>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-400">Total
                                Procured:</span><span class="font-semibold text-gray-900 dark:text-white">50,000 bags</span>
                        </div>
                        <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-400">Total
                                Allocated:</span><span class="font-semibold text-blue-600 dark:text-blue-400">45,000
                                bags</span></div>
                        <div class="flex justify-between"><span
                                class="text-gray-600 dark:text-gray-400">Distributed:</span><span
                                class="font-semibold text-green-600 dark:text-green-400">32,500 bags</span></div>
                        <div class="flex justify-between"><span
                                class="text-gray-600 dark:text-gray-400">Remaining:</span><span
                                class="font-semibold text-yellow-600 dark:text-yellow-400">12,500 bags</span></div>
                    </div>
                </div>

                <!-- State Distribution -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-5 bg-white dark:bg-gray-800">
                    <h5 class="text-md font-semibold text-gray-900 dark:text-white mb-4">State Distribution</h5>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-400">Kaduna:</span><span
                                class="font-medium text-gray-900 dark:text-white">7,500 bags</span></div>
                        <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-400">Kano:</span><span
                                class="font-medium text-gray-900 dark:text-white">9,000 bags</span></div>
                        <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-400">Niger:</span><span
                                class="font-medium text-gray-900 dark:text-white">8,500 bags</span></div>
                        <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-400">Others:</span><span
                                class="font-medium text-gray-900 dark:text-white">20,000 bags</span></div>
                    </div>
                </div>
            </div>
            <!-- Commodities Table -->
            <div class="px-6 pb-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Available Commodities</h4>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Commodity
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Category</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Unit</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Price (₦)
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Qty/ha</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Stock</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($commodities as $item)
                                <tr>
                                    <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->category }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ number_format($item->price_per_unit) }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ number_format($item->quantity_per_hectare) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->stock }}</td>
                                    <td class="px-4 py-4 flex space-x-3">
                                        <a href="{{ route('superadmin.commodities.edit', $item->uuid ) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>
                                        <form action="{{ route('superadmin.commodities.destroy', $item->uuid) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                            class="text-red-600 px-2 py-0 dark:text-red-400 hover:underline text-xs">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            
                            <!-- More rows... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Trigger -->

    </div>
@endsection
