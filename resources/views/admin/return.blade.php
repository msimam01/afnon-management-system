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
    <div id="returnModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl rounded-lg shadow-lg p-6 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Verify Return Submission</h3>
                <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Return Details</h4>
                    <p class="text-gray-700 dark:text-white"><strong>Farmer:</strong> Mary Adams</p>
                    <p class="text-gray-700 dark:text-white"><strong>Agent:</strong> Mike Wilson</p>
                    <p class="text-gray-700 dark:text-white"><strong>Commodity:</strong> Maize</p>
                    <p class="text-gray-700 dark:text-white"><strong>Submitted:</strong> Mar 10, 2024</p>
                    <p class="text-gray-700 dark:text-white"><strong>Expected Return:</strong> 30 bags</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Agent Comments</h4>
                    <p class="text-gray-700 dark:text-white italic">"Farmer returned clean, properly packed maize.
                        Verified by ID."</p>
                </div>
            </div>

            <!-- Photo Previews -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm mb-1 text-gray-600 dark:text-gray-400">Return Commodity Image</p>
                    <img src="/uploads/maize.jpg" alt="Returned Commodity"
                        class="rounded-lg w-full h-40 object-cover border border-gray-300 dark:border-gray-600" />
                </div>
                <div>
                    <p class="text-sm mb-1 text-gray-600 dark:text-gray-400">Farmer + ID Proof</p>
                    <img src="/uploads/farmer-id.jpg" alt="Farmer ID"
                        class="rounded-lg w-full h-40 object-cover border border-gray-300 dark:border-gray-600" />
                </div>
            </div>

            <!-- Optional rejection note -->
            <div class="mb-4">
                <label for="rejectionNote"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rejection Reason
                    (optional)</label>
                <textarea id="rejectionNote" rows="2"
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="Explain if rejected..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button onclick="approveReturn()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Approve</button>
                <button onclick="rejectReturn()"
                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Reject</button>
            </div>
        </div>
    </div>
@endsection
