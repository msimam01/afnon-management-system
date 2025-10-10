@extends('layouts.layout')

@push('jquery')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">👥 User Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage system users, roles, and permissions</p>
            </div>
            <div class="flex gap-2">
                <button onclick="openUserModal()"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New User
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($users) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Users</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ collect($users)->where('status', 'active')->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inactive Users</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                            {{ collect($users)->where('status', '!=', 'active')->count() }}</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Roles</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ count($roles) }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🔍 Search & Filters</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Users</label>
                    <input type="text" id="user-search" placeholder="Search by name, email..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by Status</label>
                    <select id="status-filter"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                <div class="flex flex-wrap gap-2">
                    <button
                        class="user-tab px-4 py-2 text-sm font-medium rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300"
                        data-tab="">
                        All Users
                    </button>
                    @foreach ($roles as $role)
                        <button
                            class="user-tab px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                            data-tab="{{ $role->name }}">
                            {{ ucfirst($role->name) }}
                        </button>
                    @endforeach
                </div>

                <div class="flex gap-2">
                    <button id="bulk-activate"
                        class="px-3 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50"
                        disabled>
                        Activate Selected
                    </button>
                    <button id="bulk-deactivate"
                        class="px-3 py-2 text-sm bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors disabled:opacity-50"
                        disabled>
                        Deactivate Selected
                    </button>
                    <button id="bulk-delete"
                        class="px-3 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
                        disabled>
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Table Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">👤 System Users</h3>
            </div>

            <div class="overflow-x-auto">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all"
                                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    User Details
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Role
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                            class="user-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $user->roles->first()->name ?? 'No Role' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                ✓ Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                ✗ Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button type="button"
                                                class="edit-user-btn text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300"
                                                data-user-uuid="{{ $user->uuid }}" title="Edit User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            @if ($user->status === 'active')
                                                <button type="button"
                                                    class="toggle-status-btn text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                    data-user-id="{{ $user->id }}" data-action="deactivate"
                                                    title="Deactivate User">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="toggle-status-btn text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    data-user-id="{{ $user->id }}" data-action="activate"
                                                    title="Activate User">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            <button type="button"
                                                class="delete-user-btn text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                data-user-uuid="{{ $user->uuid }}" title="Delete User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
            <form class="space-y-4" method="POST" action="{{ route('superadmin.users.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Full Name</label>
                        <input type="text" name="name"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Roles</label>
                        <select name="roles[]" multiple
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                            @foreach ($roles as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-gray-500">Hold Ctrl (Cmd on Mac) to select multiple</small>
                    </div>

                    <div class="flex justify-end col-span-2">
                        <button type="submit"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Save</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
    <div id="editUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit User</h3>
                <button onclick="closeEditUserModal()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400">✕</button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Full Name</label>
                        <input type="text" name="name" id="edit_name"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="edit_email"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Roles</label>
                        <select name="roles[]" id="edit_roles" multiple
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2"></select>
                    </div>


                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditUserModal(uuid) {
            // Show loading state
            $('#editUserForm').html('<div class="flex justify-center items-center h-64"><div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-emerald-500"></div></div>');
            $('#editUserModal').removeClass('hidden');
            
            // Construct the URL properly with the UUID in the route
            const editUrl = '{{ url('') }}/super-admin/users/' + uuid + '/edit';
            
            $.ajax({
                url: editUrl,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Fill form fields
                    const formHtml = `
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="name" id="edit_name" value="${data.user.name}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="edit_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="edit_email" value="${data.user.email}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="edit_roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Roles</label>
                                <select name="roles[]" id="edit_roles" multiple 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    ${data.all_roles.map(role => 
                                        `<option value="${role}" ${data.user.roles.includes(role) ? 'selected' : ''}>${role}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-md shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    Update User
                                </button>
                            </div>
                        </div>
                    `;
                    
                    $('#editUserForm').html(formHtml);
                    $('#editUserForm').attr('action', '{{ url('') }}/super-admin/users/' + uuid + '/update');
                },
                error: function(xhr, status, error) {
                    console.error('Error loading user data:', status, error);
                    showToast('error', 'Failed to load user data. Please try again.');
                    $('#editUserModal').addClass('hidden');
                }
            });
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }
        $(document).ready(function() {
            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Simple search functionality
            $('#user-search').on('keyup', function() {
                const searchText = $(this).val().toLowerCase();
                $('tbody tr').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.indexOf(searchText) > -1);
                });
            });

            // Status filter
            $('#status-filter').on('change', function() {
                const status = $(this).val();
                $('tbody tr').each(function() {
                    if (status === '') {
                        $(this).show();
                    } else {
                        const statusText = $(this).find('td:nth-child(4)').text().trim();
                        const showRow = (status === 'active' && statusText === '✓ Active') || 
                                      (status === 'inactive' && statusText === '✗ Inactive');
                        $(this).toggle(showRow);
                    }
                });
            });

            // Role tabs filter
            $('.user-tab').on('click', function() {
                const role = $(this).data('tab');
                $('.user-tab').removeClass(
                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300'
                ).addClass('bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300');
                
                $(this).removeClass('bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300')
                    .addClass('bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300');
                
                if (!role) {
                    $('tbody tr').show();
                    return;
                }
                
                $('tbody tr').each(function() {
                    const rowRole = $(this).find('td:nth-child(3)').text().trim();
                    $(this).toggle(rowRole === role);
                });
            });

            // Update bulk action buttons based on selection
            function updateBulkButtons() {
                const selectedCount = $('.user-checkbox:checked').length;
                const bulkButtons = $('#bulk-activate, #bulk-deactivate, #bulk-delete');
                bulkButtons.prop('disabled', selectedCount === 0);
            }

            // Handle checkbox changes
            $(document).on('change', '.user-checkbox', updateBulkButtons);
            
            $('#select-all').on('change', function() {
                $('.user-checkbox').prop('checked', this.checked);
                updateBulkButtons();
            });

            // Handle bulk actions
            $('#bulk-activate, #bulk-deactivate, #bulk-delete').on('click', function() {
                const action = $(this).attr('id').replace('bulk-', '');
                const selectedIds = [];
                $('.user-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    alert('Please select users to ' + action + '.');
                    return;
                }

                const confirmMessage = action === 'delete' ?
                    `Are you sure you want to delete ${selectedIds.length} user(s)? This action cannot be undone.` :
                    `Are you sure you want to ${action} ${selectedIds.length} user(s)?`;

                if (confirm(confirmMessage)) {
                    performBulkAction(action, selectedIds);
                }
            });
        });

        // Handle Edit button clicks (uses UUID)
        $(document).on('click', '.edit-user-btn', function(e) {
            console.log('Edit button clicked');
            const userUuid = $(this).data('user-uuid');
            openEditUserModal(userUuid);
        });

        // Handle status toggle
        $(document).on('click', '.toggle-status-btn', function(e) {
            console.log('Toggle status clicked');
            const userId = $(this).data('user-id');
            const action = $(this).data('action');
            const element = this;
            const confirmMessage = action === 'activate' ?
                'Are you sure you want to activate this user?' :
                'Are you sure you want to deactivate this user?';

            if (confirm(confirmMessage)) {
                toggleUserStatus(userId, action, element);
            }
        });

        // Handle delete button
        $(document).on('click', '.delete-user-btn', function(e) {
            console.log('Delete button clicked');
            const userUuid = $(this).data('user-uuid');
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                deleteUser(userUuid);
            }
        });

        // Function to perform bulk actions
        function performBulkAction(action, userIds) {
            $.ajax({
                url: '{{ route('superadmin.users.bulk-action') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: action,
                    user_ids: userIds
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');
                        location.reload();
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function() {
                    showToast('An error occurred while processing the request.', 'error');
                }
            });
        }

        // Function to toggle user status
        function toggleUserStatus(userId, action, element) {
            console.log('Toogle status clicked');
            $.ajax({
                url: '{{ route('superadmin.users.toggle-status') }}',
                method: 'POST',
                dataType: 'json',
                headers: {
                    'Accept': 'application/json'
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: userId,
                    action: action
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');

                        // Find the parent row to update the status text and button
                        const row = $(element).closest('tr');
                        const statusCell = row.find('td:eq(3)'); // Assuming status is the 4th column (index 3)
                        const toggleBtnContainer = $(element).closest('.flex.items-center.space-x-2');

                        if (action === 'activate') {
                            // Change status text to active
                            statusCell.html(`
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            ✓ Active
                        </span>
                    `);
                            // Change button to deactivate
                            toggleBtnContainer.find('.toggle-status-btn').remove(); // Remove old button
                            toggleBtnContainer.prepend(`
                        <button type="button" class="toggle-status-btn text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" data-user-id="${userId}" data-action="deactivate" title="Deactivate User">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    `);
                        } else {
                            // Change status text to inactive
                            statusCell.html(`
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            ✗ Inactive
                        </span>
                    `);
                            // Change button to activate
                            toggleBtnContainer.find('.toggle-status-btn').remove(); // Remove old button
                            toggleBtnContainer.prepend(`
                        <button type="button" class="toggle-status-btn text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" data-user-id="${userId}" data-action="activate" title="Activate User">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    `);
                        }
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        const errorMessage = Object.values(errors).flat().join(' ');
                        showToast(errorMessage, 'error');
                    } else {
                        showToast('An error occurred while toggling the status.', 'error');
                    }
                }
            });
        }

        // Function to delete user
        function deleteUser(uuid) {
            $.ajax({
                url: `{{ route('superadmin.users.destroy', '') }}/${uuid}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');
                        location.reload();
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function() {
                    showToast('An error occurred while deleting the user.', 'error');
                }
            });
        }

        // Toast notification function
        function showToast(message, type) {
            const toast = $(`
                <div class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 translate-x-full">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                ${type === 'success'
                                    ? '<svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                                    : '<svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                                }
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${message}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button class="toast-close bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            $('body').append(toast);

            // Slide in
            setTimeout(() => {
                toast.removeClass('translate-x-full');
            }, 100);

            // Auto dismiss after 4 seconds
            setTimeout(() => {
                toast.addClass('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 4000);

            // Handle close button
            toast.find('.toast-close').on('click', function() {
                toast.addClass('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            });
        }
    </script>
@endsection
