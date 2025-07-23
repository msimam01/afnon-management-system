@extends('layouts.layout')

@section('content')
        <div id="reports-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Reports</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Total Applications</p>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white">324</h4>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Distributed</p>
                        <h4 class="text-2xl font-bold text-green-600 dark:text-green-400">276</h4>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Returns Verified</p>
                        <h4 class="text-2xl font-bold text-blue-600 dark:text-blue-400">205</h4>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Rejected</p>
                        <h4 class="text-2xl font-bold text-red-600 dark:text-red-400">19</h4>
                    </div>
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Season</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option>All Seasons</option>
                            <option>2024 Dry Season</option>
                            <option>2024 Wet Season</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cluster</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option>All Clusters</option>
                            <option>Cluster A</option>
                            <option>Cluster B</option>
                            <option>Cluster C</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button id="exportBtn"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">
                            Export to CSV
                        </button>

                    </div>
                </div>

                <!-- Reports Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Farmer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Season</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Commodity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Quantity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    John Doe</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    2024 Dry Season</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    Maize Seeds</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">5
                                    bags</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Distributed</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    Mar 15, 2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
