@extends('layouts.layout')

@section('content')
    <!-- Search Section -->
    <div id="search-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Search Farmer by ID</h3>

            <!-- Search Form -->
            <div class="space-y-4">
                <div>
                    <label for="farmerSearch" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enter
                        Farmer
                        ID</label>
                    <div class="flex">
                        <input type="text" id="farmerSearch" placeholder="e.g., NEC001234"
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <button id="searchBtn"
                            class="bg-emerald-600 text-white px-6 py-2 rounded-r-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- No Result Message -->
            <div id="notFound"
                class="hidden mt-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 text-yellow-700 dark:text-yellow-200 px-4 py-3 rounded">
                Farmer ID not found. Please check and try again.
            </div>

            <!-- Search Results -->
            <div id="searchResults" class="mt-6">
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Farmer Details</h4>
                        <span
                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">Personal Information</h5>
                            <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Name:</span> John Doe</p>
                                <p><span class="font-medium">Phone:</span> +234 803 123 4567</p>
                                <p><span class="font-medium">BVN:</span> 12345678901</p>
                                <p><span class="font-medium">NIN:</span> 98765432109</p>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">Farm Information</h5>
                            <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Farm Size:</span> 5.2 hectares</p>
                                <p><span class="font-medium">Location:</span> Ikeja, Lagos</p>
                                <p><span class="font-medium">Cluster:</span> Cluster A</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5 class="font-medium text-gray-900 dark:text-white mb-2">Recent Applications</h5>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Season</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Commodity</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Status</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">2024 Dry
                                            Season</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">Maize Seeds
                                            (5 bags)</td>
                                        <td class="px-4 py-2">
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Distributed</span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <button onclick="alert('Redirect to verification page')"
                                                class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 text-sm">Verify
                                                Collection</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- <script>
        // ========== Farmer Search ==========
        const searchBtn = document.getElementById("searchBtn");
        if (searchBtn) {
            searchBtn.addEventListener("click", function() {
                const id = document.getElementById("farmerSearch").value.trim().toUpperCase();
                const validId = "NEC001234";

                if (id === validId) {
                    document.getElementById("searchResults").classList.remove("hidden");
                    document.getElementById("notFound").classList.add("hidden");
                } else {
                    document.getElementById("searchResults").classList.add("hidden");
                    document.getElementById("notFound").classList.remove("hidden");
                }
            });
        }
    </script> --}}
@endsection
