@extends('layouts.layout')

@section('content')
        <div id="returns-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Return Verification</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Farmer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Return Details</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Agent</th>
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
                                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">MA</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Mary
                                                Adams</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">+234 803 987
                                                6543</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">Maize (harvested)</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">From 3 bags of seeds
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Submitted: Mar 10,
                                        2024</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    Mike Wilson</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Pending
                                        Review</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <button onclick="viewReturnDetails('mary-adams')"
                                        class="text-emerald-600 dark:text-emerald-400 hover:underline mr-3">View
                                        Photos</button>
                                    <button
                                        class="bg-emerald-600 text-white px-3 py-1 rounded-md text-xs hover:bg-emerald-700 mr-2">Approve</button>
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
    <!-- Return Verification Modal -->
<div id="returnModal"
    class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center px-4 sm:px-0">
    <div class="bg-white dark:bg-gray-800 w-full max-w-4xl rounded-xl shadow-xl p-6 sm:p-8 overflow-y-auto max-h-[90vh]">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 dark:border-gray-600">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Verify Commodity Return</h3>
            <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Info Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Farmer & Application Info</h4>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1" id="return-farmer-info">
                    <li><strong>Name:</strong> Mary Adams</li>
                    <li><strong>Phone:</strong> 08012345678</li>
                    <li><strong>Cluster:</strong> Cluster A</li>
                    <li><strong>Season:</strong> 2024 Dry Season</li>
                    <li><strong>Farm Size:</strong> 5.2 hectares</li>
                </ul>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Return Details</h4>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                    <li><strong>Agent:</strong> Mike Wilson</li>
                    <li><strong>Commodity:</strong> Maize</li>
                    <li><strong>Submitted:</strong> Mar 10, 2024</li>
                    <li><strong>Expected Return:</strong> 30 bags</li>
                    <li><strong>Returned Quantity:</strong> 28 bags</li>
                </ul>
            </div>
        </div>

        <!-- Commodity Breakdown Table -->
        <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Return Breakdown</h4>
            <div class="overflow-x-auto">
                <table
                    class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">Commodity</th>
                            <th class="px-4 py-2 text-left">Expected</th>
                            <th class="px-4 py-2 text-left">Returned</th>
                            <th class="px-4 py-2 text-left">Unit</th>
                            <th class="px-4 py-2 text-left">Value</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white" id="return-breakdown-body">
                        <tr>
                            <td class="px-4 py-2">Maize</td>
                            <td class="px-4 py-2">30</td>
                            <td class="px-4 py-2">28</td>
                            <td class="px-4 py-2">bags</td>
                            <td class="px-4 py-2">₦112,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Agent Note -->
        <div class="mb-4">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-1">Agent Comment</h4>
            <p class="text-sm text-gray-700 dark:text-gray-300 italic">"Farmer returned clean, properly packed maize.
                Verified by ID."</p>
        </div>

        <!-- Image Previews -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-sm mb-1 text-gray-600 dark:text-gray-400">Returned Commodity Image</p>
                <img src="/uploads/maize.jpg" alt="Returned Commodity"
                    class="rounded-lg w-full h-40 object-cover border border-gray-300 dark:border-gray-600" />
            </div>
            <div>
                <p class="text-sm mb-1 text-gray-600 dark:text-gray-400">Farmer with ID</p>
                <img src="/uploads/farmer-id.jpg" alt="Farmer ID"
                    class="rounded-lg w-full h-40 object-cover border border-gray-300 dark:border-gray-600" />
            </div>
        </div>

        <!-- Rejection Note -->
        <div class="mb-6">
            <label for="rejectionNote"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rejection Note
                (optional)</label>
            <textarea id="rejectionNote" rows="2"
                class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="Reason for rejection..."></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <button onclick="approveReturn()"
                class="bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 transition">Approve</button>
            <button onclick="rejectReturn()"
                class="bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-700 transition">Reject</button>
        </div>
    </div>
</div>

@endsection
