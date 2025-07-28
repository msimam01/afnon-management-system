@extends('layouts.layout')

@section('content')
    <div id="commodities-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Commodities Management</h3>
                <button onclick="openCommodityModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                    + Add Commodity
                </button>
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
            <div class="p-6 gap-6">
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
                            <tr>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">Maize Seeds</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">Seed</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">Bags</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">10,000</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">2</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">12,000</td>
                                <td class="px-6 py-4 space-x-3">
                                    <button class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</button>
                                    <button class="text-red-600 dark:text-red-400 hover:underline text-xs">Delete</button>
                                </td>
                            </tr>
                            <!-- More rows... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Commodity Modal -->
    <div id="addCommodityModal"
        class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-start justify-center overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-xl mt-20 mx-4 p-6 relative">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New Commodity</h3>
                <button onclick="closeCommodityModal()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>

            <!-- Form -->
            <form id="commodityForm" class="space-y-6">
                <!-- Commodity Name -->
                <div>
                    <label for="commodityName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity
                        Name *</label>
                    <input type="text" id="commodityName" name="commodityName" required
                        placeholder="e.g., Maize Seeds, Urea"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Category -->
                <div>
                    <label for="commodityCategory"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                    <select id="commodityCategory" name="commodityCategory" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select category</option>
                        <option value="seed">Seed</option>
                        <option value="fertilizer">Fertilizer</option>
                        <option value="herbicide">Herbicide</option>
                        <option value="insecticide">Insecticide</option>
                        <option value="equipment">Equipment</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Unit -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit of
                        Measurement *</label>
                    <input type="text" id="unit" name="unit" required placeholder="e.g., bags, liters"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Price per Unit -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per
                        Unit
                        (₦) *</label>
                    <input type="number" id="price" name="price" required step="0.01" min="0"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Quantity per Hectare -->
                <div>
                    <label for="qtyPerHectare" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity
                        per Hectare *</label>
                    <input type="number" id="qtyPerHectare" name="qtyPerHectare" required step="0.1"
                        min="0"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="e.g., 2, 3.5">
                </div>

                <!-- Initial Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Initial Stock
                        *</label>
                    <input type="number" id="stock" name="stock" required min="0"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="e.g., 5000">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-2">
                    <button type="button" onclick="closeCommodityModal()"
                        class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancel</button>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">
                        Add Commodity
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
