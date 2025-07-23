@extends('layouts.layout')

@section('content')
    <div id="settings-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-10">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Roles & Permissions</h3>
                <button onclick="openRoleModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                    + Add New Role
                </button>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Permissions</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Super Admin</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">All Permissions</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-emerald-600 dark:text-emerald-400 hover:underline mr-3">Edit</button>
                                <button class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Zone Agent</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">View, Distribute,
                                Return</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-emerald-600 dark:text-emerald-400 hover:underline mr-3">Edit</button>
                                <button class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                            </td>
                        </tr>
                        <!-- Repeat for other roles -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Add/Edit Role Modal -->
    <div id="roleModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mt-20 mx-4 p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h3 id="roleModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add New Role</h3>
                <button onclick="closeRoleModal()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>
            <form id="roleForm" class="space-y-6">
                <!-- Role Name -->
                <div>
                    <label for="roleName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name
                        *</label>
                    <input type="text" id="roleName" name="roleName" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Permissions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign
                        Permissions</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions" value="view_dashboard"
                                    class="form-checkbox text-emerald-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">View Dashboard</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions" value="manage_users"
                                    class="form-checkbox text-emerald-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Manage Users</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions" value="manage_commodities"
                                    class="form-checkbox text-emerald-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Manage Commodities</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions" value="allocate_quotas"
                                    class="form-checkbox text-emerald-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Allocate Quotas</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions" value="manage_seasons"
                                    class="form-checkbox text-emerald-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Manage Seasons</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions" value="access_settings"
                                    class="form-checkbox text-emerald-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">System Settings</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions" value="view_audit_logs"
                                    class="form-checkbox text-emerald-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">View Audit Logs</span>
                            </label>
                        </div>
                        <!-- Add more as needed -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white py-2 px-6 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium transition-colors">
                        Save Role
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
