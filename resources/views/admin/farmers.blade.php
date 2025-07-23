@extends('layouts.layout')

@section('content')
    <div id="farmers-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div id="farmerList" class="p-6">
            <div class="mb-6 flex justify-between items-center">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Farmers</h4>
                <button onclick="exportFarmersToCSV()"
                    class="bg-gray-100 dark:bg-gray-700 text-sm px-4 py-2 rounded-md text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600">
                    Export CSV
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <input class="search w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"
                    placeholder="Search farmer..." />
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Farmer</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Farm Info</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Applications</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Example Farmer Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap name">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">JD</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">John Doe
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 phone">+234 803 123
                                            4567</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap cluster">
                                <div class="text-sm text-gray-900 dark:text-white">5.2 hectares</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Ikeja, Lagos</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Cluster A</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">3 total</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <button onclick="viewFarmerProfile('john-doe')"
                                    class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 mr-3">View</button>
                                <button onclick="confirmDeleteFarmer('john-doe')"
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                        <!-- Add more rows dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Farmer profile modal -->
    <div id="farmerProfileModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl rounded-lg shadow-lg p-6 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Farmer Profile</h3>
                <button onclick="closeFarmerModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Farmer Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Basic Info</h4>
                    <p class="text-gray-700 dark:text-white"><strong>Name:</strong> John Doe</p>
                    <p class="text-gray-700 dark:text-white"><strong>Phone:</strong> +234 803 123 4567</p>
                    <p class="text-gray-700 dark:text-white"><strong>BVN:</strong> 12345678901</p>
                    <p class="text-gray-700 dark:text-white"><strong>NIN:</strong> 98765432109</p>
                    <p class="text-gray-700 dark:text-white"><strong>Cluster:</strong> Cluster A</p>
                    <p class="text-gray-700 dark:text-white"><strong>State:</strong> Lagos</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Farm(s)</h4>
                    <ul class="space-y-2">
                        <li>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-700 dark:text-white"><strong>Farm 1:</strong> 5.2 hectares –
                                        Ikeja</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Current Season</p>
                                </div>
                                <span
                                    class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">Active</span>
                            </div>
                        </li>
                        <li>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-700 dark:text-white"><strong>Farm 2:</strong> 3.7 hectares –
                                        Badagry</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Available for next season</p>
                                </div>
                                <button class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Make
                                    Active</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Application History -->
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Application History</h4>
                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Season</th>
                            <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Commodity</th>
                            <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="px-4 py-2 text-gray-700 dark:text-white">2024 Dry Season</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-white">Maize</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-white"><span
                                    class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">Approved</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-gray-700 dark:text-white">2023 Wet Season</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-white">Fertilizer</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-white"><span
                                    class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full">Collected</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end">
                <button onclick="closeFarmerModal()"
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>
@endsection
