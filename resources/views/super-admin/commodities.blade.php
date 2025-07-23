@extends('layouts.layout')

@section('content')
    <div id="commodities-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <!-- Commodities & Quotas Page -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Commodities & Quotas Management
                </h3>
                <button onclick="openCommodityModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                    + Add Commodity
                </button>
            </div>

            <!-- Filters -->
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 md:items-center">
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

            <!-- Global Allocation Summary -->
            <div class="p-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Global Quota Summary</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Procurement Summary -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <h5 class="font-medium text-gray-900 dark:text-white mb-3">2024 Dry Season - Maize Seeds
                        </h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Procured:</span>
                                <span class="font-medium text-gray-900 dark:text-white">50,000 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Allocated:</span>
                                <span class="font-medium text-blue-600 dark:text-blue-400">45,000 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Distributed:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">32,500 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Remaining:</span>
                                <span class="font-medium text-yellow-600 dark:text-yellow-400">12,500
                                    bags</span>
                            </div>
                        </div>
                    </div>

                    <!-- Zone-wise Distribution -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <h5 class="font-medium text-gray-900 dark:text-white mb-3">Tenant (Zone) Distribution
                        </h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">North Central:</span>
                                <span class="font-medium text-gray-900 dark:text-white">7,500 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">North East:</span>
                                <span class="font-medium text-gray-900 dark:text-white">9,000 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">North West:</span>
                                <span class="font-medium text-gray-900 dark:text-white">8,500 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Others:</span>
                                <span class="font-medium text-gray-900 dark:text-white">20,000 bags</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commodities Table -->
            <div class="px-6 pb-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Available Commodities</h4>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Commodity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Unit</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    Maize Seeds</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    Input</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    Bags</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Edit</button>
                                    <button
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </td>
                            </tr>
                            <!-- More commodities... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
        <!-- Add Commodity Modal -->
    <div id="addCommodityModal"
        class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-start justify-center overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg mt-20 mx-4 p-6 relative">

            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New Commodity</h3>
                <button onclick="closeCommodityModal()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>

            <!-- Form -->
            <form id="commodityForm" class="space-y-6">

                <!-- Commodity Name -->
                <div>
                    <label for="commodityName"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity Name *</label>
                    <input type="text" id="commodityName" name="commodityName" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Commodity Category -->
                <div>
                    <label for="commodityCategory"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                    <select id="commodityCategory" name="commodityCategory" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select category</option>
                        <option value="input">Input</option>
                        <option value="seed">Seed</option>
                        <option value="fertilizer">Fertilizer</option>
                        <option value="harvest">Harvest</option>
                    </select>
                </div>

                <!-- Unit of Measurement -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit (e.g.
                        bags, kg, liters) *</label>
                    <input type="text" id="unit" name="unit" required placeholder="e.g. bags"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCommodityModal()"
                        class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancel</button>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">Add
                        Commodity</button>
                </div>
            </form>
        </div>
    </div>
@endsection
