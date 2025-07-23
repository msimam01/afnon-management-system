@extends('layouts.layout')

@section('content')
    <div id="commodities-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Commodity Management</h3>
                {{-- <button onclick="openCommodityModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">
                    Add / Re-Allocate Commodity
                </button> --}}
            </div>

            <!-- Season Filter -->
            <div class="px-6 pt-4">
                <label for="seasonFilter" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Filter by
                    Season</label>
                <select id="seasonFilter"
                    class="w-full md:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="dry">2024 Dry Season</option>
                    <option value="wet">2024 Wet Season</option>
                </select>
            </div>

            <!-- Zone Allocations -->
            <div class="p-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">2024 Dry Season - Maize Seeds
                    Allocation</h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Zone Card Template (Repeatable) -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <h5 class="font-medium text-gray-900 dark:text-white mb-2">North Zone</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Allocated:</span>
                                <span class="font-medium text-gray-900 dark:text-white">7,000 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Distributed:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">4,200 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Remaining:</span>
                                <span class="font-medium text-yellow-600 dark:text-yellow-400">2,800 bags</span>
                            </div>

                            <!-- Distribution Progress -->
                            <div class="mt-2 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 60%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Repeat for other zones as needed -->
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Commodity Types</h3>
                    {{-- <button onclick="openNewCommodityModal()"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">
                        Add Commodity
                    </button> --}}
                </div>
                <div class="p-6">
                    <ul class="space-y-3 text-sm">
                        <li class="flex justify-between items-center border-b border-gray-200 dark:border-gray-600 py-2">
                            <span class="text-gray-800 dark:text-white">Maize Seeds (bags)</span>
                            <span class="text-xs text-gray-500">Used in 3 seasons</span>
                        </li>
                        <li class="flex justify-between items-center border-b border-gray-200 dark:border-gray-600 py-2">
                            <span class="text-gray-800 dark:text-white">Fertilizer (kg)</span>
                            <span class="text-xs text-gray-500">Used in 2 seasons</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


    </div>
        <!-- Commodity Modal -->
    <div id="commodityModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Allocate Commodity to Zone</h3>
            <form id="commodityForm" class="space-y-4">
                <select
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>
                    <option value="">Select Season</option>
                    <option value="dry">2024 Dry Season</option>
                    <option value="wet">2024 Wet Season</option>
                </select>

                <select
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>
                    <option value="">Select Zone</option>
                    <option value="north">North Zone</option>
                    <option value="south">South Zone</option>
                    <option value="east">East Zone</option>
                </select>

                <input type="text" placeholder="Commodity Name (e.g., Maize)"
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>
                <input type="number" placeholder="Allocation Quantity (bags)"
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCommodityModal()"
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Save
                        Allocation</button>
                </div>
            </form>
        </div>
    </div>
    <div id="newCommodityModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add New Commodity</h3>
            <form id="commodityTypeForm" class="space-y-4">
                <input type="text" placeholder="Commodity Name (e.g., Maize Seeds)" required
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                <input type="text" placeholder="Unit (e.g., bags, kg)" required
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                <textarea placeholder="Optional description..." rows="3"
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeNewCommodityModal()"
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Add</button>
                </div>
            </form>
        </div>
    </div>
@endsection
