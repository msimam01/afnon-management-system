@extends('layouts.layout')

@section('content')
    <!-- Applications Section -->
    <div id="applications-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Management</h3>
            </div>
            <div class="p-6">
                <!-- Filters -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option>All Status</option>
                            <option>Pending</option>
                            <option>Approved</option>
                            <option>Distributed</option>
                            <option>Rejected</option>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" placeholder="Search farmer..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>

                <!-- Applications Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Farmer Details</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Application</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Farm Info</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <span
                                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">JD</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">John
                                                Doe</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">+234 803 123
                                                4567</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">BVN:
                                                12345678901</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">2024 Dry Season</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Maize Seeds (5 bags)
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Type: Grant</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">5.2 hectares</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Ikeja, Lagos</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Cluster A</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Pending</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <button onclick="openApplicationModal('app-001')"
                                        class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 mr-3">View
                                        Full Info</button>
                                    <button
                                        class="bg-red-600 text-white px-3 py-1 rounded-md text-xs hover:bg-red-700">Reject</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Approval Modal -->
    <div id="applicationApprovalModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center px-4 sm:px-0">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-3xl p-6 sm:p-8 overflow-y-auto max-h-[90vh]">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 dark:border-gray-600">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Approve Application</h3>
                <button onclick="closeApplicationModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Info Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Farmer Info</h4>
                    <ul class="text-sm space-y-1 text-gray-700 dark:text-gray-300">
                        <li><strong>Name:</strong> Sarah Johnson</li>
                        <li><strong>Phone:</strong> 08012345678</li>
                        <li><strong>Location:</strong> Ikeja, Lagos</li>
                        <li><strong>Cluster:</strong> Cluster A</li>
                    </ul>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Application Info</h4>
                    <ul class="text-sm space-y-1 text-gray-700 dark:text-gray-300">
                        <li><strong>Season:</strong> 2024 Dry Season</li>
                        <li><strong>Farm Size:</strong> 5.2 hectares</li>
                        <li><strong>Type:</strong>
                            <span
                                class="inline-block px-2 py-1 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs rounded-full">Loan</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Collection Center Assignment -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Collection & Return Center</h4>

                <label for="collectionCenter"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Collection Center *</label>
                <select id="collectionCenter"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white mb-4">
                    <option value="">-- Select Collection Center --</option>
                    <option value="ikeja-cc">Ikeja Center</option>
                    <option value="ikotun-cc">Ikotun Center</option>
                </select>

                <!-- Toggle same as collection -->
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="sameAsCollection" onclick="toggleReturnCenter()"
                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                    <label for="sameAsCollection" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Use same
                        center for return</label>
                </div>

                <label for="returnCenter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Return
                    Center *</label>
                <select id="returnCenter"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">-- Select Return Center --</option>
                    <option value="ikeja-rc">Ikeja Center</option>
                    <option value="ikotun-rc">Ikotun Center</option>
                </select>
            </div>

            <!-- Allocation Mode -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Commodity Allocation</h4>
                <label for="allocationMode"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Allocation Mode</label>
                <select id="allocationMode" onchange="toggleAllocationMode()"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white mb-4">
                    <option value="auto">Auto (based on farm size)</option>
                    <option value="manual">Manual Entry</option>
                </select>

                <!-- Auto display -->
                <div id="autoQtyDisplay" class="space-y-3">
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-900 rounded-lg border">
                        <p class="text-sm text-emerald-800 dark:text-emerald-300"><strong>Maize Seeds:</strong> 5 bags
                            (25kg)</p>
                        <p class="text-sm text-emerald-800 dark:text-emerald-300"><strong>Rice Seeds:</strong> 3 bags
                            (25kg)</p>
                    </div>
                </div>

                <!-- Manual input -->
                <div id="manualQtyInput" class="space-y-4 hidden mt-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-gray-700 dark:text-gray-300">Maize Seeds</label>
                        <input type="number" id="maizeQty" min="0"
                            class="w-24 px-3 py-1 border rounded bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white"
                            placeholder="bags">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-gray-700 dark:text-gray-300">Rice Seeds</label>
                        <input type="number" id="riceQty" min="0"
                            class="w-24 px-3 py-1 border rounded bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white"
                            placeholder="bags">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeApplicationModal()"
                    class="px-5 py-2 bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 transition">Cancel</button>
                <button onclick="approveApplication()"
                    class="px-5 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">Approve</button>
            </div>
        </div>
    </div>
@endsection
