@extends('layouts.layout')

@section('content')
    <!-- Users Section -->
    <div id="users-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Manage All Users</h3>
                <button onclick="openUserModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    Add New User
                </button>

            </div>

            <!-- User Type Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button
                        class="user-tab active border-emerald-500 text-emerald-600 dark:text-emerald-400 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                        data-tab="admins">
                        Admins
                    </button>
                    <button
                        class="user-tab border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                        data-tab="agents">
                        Agents
                    </button>
                    <button
                        class="user-tab border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                        data-tab="farmers">
                        Farmers
                    </button>
                </nav>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <!-- Controls -->
                <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                    <!-- Search -->
                    <input type="text" placeholder="Search users..."
                        class="w-full md:w-1/3 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">

                    <!-- Filter Dropdown -->
                    <select
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Filter by Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>

                            <!-- Table Head (add checkbox) -->
                            <th class="px-6 py-3">
                                <input type="checkbox" class="form-checkbox rounded text-emerald-600 dark:bg-gray-700">
                            </th>

                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                User</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Zone/Location</th>
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
                            <!-- Table Row (add matching checkbox) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="form-checkbox rounded text-emerald-600 dark:bg-gray-700">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div
                                            class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                            <span class="text-sm font-medium text-purple-700 dark:text-purple-300">SJ</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Sarah
                                            Johnson</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">sarah@necas.gov.ng
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Admin
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">North
                                Central Zone</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 focus:outline-none">
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <button
                                    class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 mr-3">Edit</button>
                                <button
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- User Modal -->
    <div id="userModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Add New User</h3>
                <button onclick="closeUserModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">✕</button>
            </div>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300">Full Name</label>
                    <input type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300">Role</label>
                    <select
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                        <option>Admin</option>
                        <option>Agent</option>
                        <option>Farmer</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
