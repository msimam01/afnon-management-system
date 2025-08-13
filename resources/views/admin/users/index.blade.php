@extends('layouts.layout')

@section('content')
    <!-- Users Section -->
    <div id="users-section" class="...">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium ... font-semibold text-gray-600 dark:text-gray-300">Manage All Users</h3>
                <button onclick="openUserModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">Add
                    New User</button>
            </div>

            <table id="usersTable"
                class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm"
                style="width:100%">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">User</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Email</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Role</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Action</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($users as $item)
                        <tr class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['email'] }}</td>
                            <td>{{ $item['role'] }}</td>
                            <!-- Actions -->
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href=""
                                                class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>


                                            <form action=""
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 dark:text-red-400 hover:underline text-xs">Delete</button>
                                            </form>



                                        </div>
                                    </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- User Modal -->
    <div id="userModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Add New User</h3>
                <button onclick="closeUserModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">✕</button>
            </div>
            <form class="space-y-4" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
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
                    <label class="block text-sm text-gray-700 dark:text-gray-300">Role</label>
                    <select name="role"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">
                        @foreach ($roles as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Save</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const table = $('#usersTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csvHtml5',
                        text: 'Export CSV',
                        exportOptions: {
                            modifier: {
                                selected: true
                            }
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        exportOptions: {
                            modifier: {
                                selected: true
                            }
                        }
                    }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input'
                },
                columnDefs: [{
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0
                    },
                    {
                        targets: 5,
                        orderable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });

            $('#select-all').on('click', function() {
                const rows = table.rows({
                    page: 'current'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                if (this.checked) table.rows({
                    page: 'current'
                }).select();
                else table.rows({
                    page: 'current'
                }).deselect();
            });

            $('#usersTable tbody').on('change', 'input[type="checkbox"]', function() {
                const $row = $(this).closest('tr');
                if (this.checked) {
                    table.row($row).select();
                } else {
                    table.row($row).deselect();
                }
            });

            // Bulk actions
            $('#bulk-activate, #bulk-deactivate, #bulk-delete').on('click', function() {
                const action = this.id.split('-')[1];
                const rows = table.rows({
                    selected: true
                }).data();
                if (!rows.length) return alert('No users selected.');
                if (!confirm(`Confirm ${action} ${rows.length} user(s)?`)) return;
                // call AJAX to backend with action & list of user IDs
                // then table.rows({ selected: true }).remove().draw(false);
            });

            // Tabs filter
            $('.user-tab').on('click', function() {
                $('.user-tab').removeClass('active');
                $(this).addClass('active');
                const role = $(this).data('tab');
                table.column(2).search(role).draw();
            });
        });
    </script>
@endsection
