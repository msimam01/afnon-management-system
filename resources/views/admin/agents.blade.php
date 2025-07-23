@extends('layouts.layout')

@section('content')
    <div id="agents-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Agent Management</h3>
                <button onclick="openAssignCenterModal()"
                    class="text-indigo-600 dark:text-indigo-400 hover:underline">Assign Center</button>
                <button onclick="openAddAgentModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Add New Agent
                </button>
            </div>

            <!-- Filters -->
            <div class="px-6 pt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" placeholder="Search agent..."
                    class="search w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                <select class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">All States</option>
                    <option value="lagos">Lagos</option>
                    <option value="kano">Kano</option>
                </select>
                <select class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Table -->
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Agent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Assigned Area</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Farmers</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 list">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap name">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-sm font-medium text-green-700 dark:text-green-300">
                                        MW</div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Mike
                                            Wilson</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">mike@necas.gov.ng
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Ikeja, Lagos</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">127</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <button onclick="viewAgentProfile('mike-wilson')"
                                    class="text-emerald-600 dark:text-emerald-400 hover:underline mr-3">View</button>
                                <button onclick="openEditAgentModal('mike-wilson')"
                                    class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</button>
                                <button onclick="deleteAgent('mike-wilson')"
                                    class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Add agent modal -->
    <div id="addAgentModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add New Agent</h3>
            <form id="agentForm" class="space-y-4">
                <input type="text" placeholder="Full Name" required
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <input type="email" placeholder="Email" required
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <input type="tel" placeholder="Phone Number" required
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <select required
                    class="w-full px-3 py-2 border rounded-md dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">Assign Zone</option>
                    <option value="ikeja">Ikeja</option>
                    <option value="zaria">Zaria</option>
                </select>
                <select multiple required
                    class="w-full px-3 py-2 border rounded-md dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="center-1">Ikeja Collection Center</option>
                    <option value="center-2">Yaba Return Center</option>
                </select>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddAgentModal()"
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Add
                        Agent</button>
                </div>
            </form>
        </div>
    </div>

    <!-- agent profile modal -->
    <div id="agentProfileModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl rounded-lg shadow-lg p-6 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Agent Profile</h3>
                <button onclick="closeAgentProfile()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Agent Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Basic Info</h4>
                    <p class="text-gray-700 dark:text-white"><strong>Name:</strong> Mike Wilson</p>
                    <p class="text-gray-700 dark:text-white"><strong>Email:</strong> mike@necas.gov.ng</p>
                    <p class="text-gray-700 dark:text-white"><strong>Phone:</strong> +234 803 123 4567</p>
                    <p class="text-gray-700 dark:text-white"><strong>Status:</strong> Active</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Assigned Centers</h4>
                    <ul class="list-disc list-inside text-gray-700 dark:text-white space-y-1">
                        <li>Ikeja Collection Center</li>
                        <li>Yaba Return Center</li>
                    </ul>
                </div>
            </div>

            <!-- Performance Summary -->
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Performance</h4>
                <p class="text-gray-700 dark:text-white"><strong>Farmers Verified:</strong> 127</p>
                <p class="text-gray-700 dark:text-white"><strong>Returns Handled:</strong> 62</p>
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="closeAgentProfile()"
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>

    <!-- agent assign to center modal -->
    <div id="assignCenterModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assign Agent to Center</h3>
            <form id="assignCenterForm" class="space-y-4">
                <select required
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">Select Agent</option>
                    <option value="mike">Mike Wilson</option>
                    <option value="grace">Grace Paul</option>
                </select>
                <select multiple required
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="center1">Ikeja Collection Center</option>
                    <option value="center2">Yaba Return Center</option>
                    <option value="center3">Surulere Center</option>
                </select>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAssignCenterModal()"
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Assign</button>
                </div>
            </form>
        </div>
    </div>
@endsection
