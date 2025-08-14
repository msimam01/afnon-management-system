@extends('layouts.layout')

@section('content')
    <div class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div x-data="rolesMatrix()" class="p-6 space-y-6">

                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Roles & Permissions</h1>
                    <div class="flex gap-2 flex-wrap">
                        <button @click="openRoleModal()"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
                            + Role
                        </button>
                        <button @click="openPermissionModal()"
                            class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
                            + Permission
                        </button>
                    </div>
                </div>

                <!-- Permissions Matrix -->
                <div class="overflow-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow max-h-[70vh]">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-800 sticky top-0 z-20">
                            <tr class="dark:text-white">
                                <th class="px-4 py-3 text-left sticky left-0 bg-gray-100 dark:bg-gray-800 z-20">Role</th>
                                @foreach ($permissions as $perm)
                                    <th class="px-4 py-3 text-center">{{ Str::limit($perm->name, 12) }}</th>
                                @endforeach
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($roles as $role)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-4 py-3 sticky left-0 bg-white dark:text-gray-400 dark:bg-gray-900 z-10">
                                        {{ $role->name }}</td>
                                    @foreach ($permissions as $perm)
                                        <td class="px-4 py-3 text-center relative">
                                            <label class="inline-flex relative cursor-pointer items-center">
                                                <input type="checkbox" class="sr-only peer"
                                                    :checked="hasPermission({{ $role->id }}, {{ $perm->id }})"
                                                    @change="togglePermission({{ $role->id }}, {{ $perm->id }}, $event.target.checked)">
                                                <div
                                                    class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:bg-white after:rounded-full peer-checked:after:translate-x-full after:transition-all">
                                                </div>
                                            </label>
                                            <!-- Permission Actions -->
                                            <div
                                                class="absolute top-0 right-0 flex gap-1 opacity-0 hover:opacity-100 transition">
                                                <button @click="editPermission({{ $perm->id }}, '{{ $perm->name }}')"
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-0.5 rounded text-xs">
                                                    Edit
                                                </button>
                                                <button @click="deletePermission({{ $perm->id }})"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-0.5 rounded text-xs">
                                                    Del
                                                </button>
                                            </div>
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center space-x-2 flex justify-center">
                                        <button @click="editRole({{ $role->id }}, '{{ $role->name }}')"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg transition transform hover:scale-105">
                                            Edit
                                        </button>
                                        <button @click="deleteRole({{ $role->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition transform hover:scale-105">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Role Modal -->
                <div x-show="showRoleModal" x-transition.opacity x-cloak
                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                    <div @click.away="closeRoleModal()" x-transition.scale.origin.center
                        class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md shadow-lg transform">
                        <h2 class="text-lg font-bold mb-4 dark:text-white" x-text="roleForm.id ? 'Edit Role' : 'Add Role'">
                        </h2>
                        <form @submit.prevent="saveRole">
                            <input type="text" placeholder="Role name" x-model="roleForm.name"
                                class="w-full p-2 border rounded mb-4 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                            <div class="flex justify-end gap-2">
                                <button type="submit"
                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg transition transform hover:scale-105">
                                    Save
                                </button>
                                <button type="button" @click="closeRoleModal()"
                                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg transition transform hover:scale-105">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Permission Modal -->
                <div x-show="showPermissionModal" x-transition.opacity x-cloak
                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                    <div @click.away="closePermissionModal()" x-transition.scale.origin.center
                        class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md shadow-lg transform">
                        <h2 class="text-lg font-bold mb-4 dark:text-white"
                            x-text="permissionForm.id ? 'Edit Permission' : 'Add Permission'"></h2>
                        <form @submit.prevent="savePermission">
                            <input type="text" placeholder="Permission name" x-model="permissionForm.name"
                                class="w-full p-2 border rounded mb-4 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                            <div class="flex justify-end gap-2">
                                <button type="submit"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg transition transform hover:scale-105">
                                    Save
                                </button>
                                <button type="button" @click="closePermissionModal()"
                                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg transition transform hover:scale-105">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rolesMatrix', () => ({
                rolePermissions: @json($rolePermissions),
                showRoleModal: false,
                showPermissionModal: false,
                roleForm: {
                    id: null,
                    name: ''
                },
                permissionForm: {
                    id: null,
                    name: ''
                },

                // Permission logic
                hasPermission(roleId, permissionId) {
                    return (this.rolePermissions[roleId] || []).includes(permissionId);
                },

                togglePermission(roleId, permissionId, grant) {
                    fetch(`/admin/roles/${roleId}/permissions`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            permission_id: permissionId,
                            grant
                        })
                    }).then(res => res.json()).then(data => {
                        if (data.granted) this.rolePermissions[roleId].push(permissionId)
                        else this.rolePermissions[roleId] = this.rolePermissions[roleId].filter(
                            id => id !== permissionId)
                    });
                },

                // Role actions
                openRoleModal() {
                    this.roleForm = {
                        id: null,
                        name: ''
                    };
                    this.showRoleModal = true
                },
                closeRoleModal() {
                    this.showRoleModal = false
                },
                saveRole() {
                    const method = this.roleForm.id ? 'PUT' : 'POST';
                    const url = this.roleForm.id ? `/admin/roles/${this.roleForm.id}/update` :
                        '/admin/roles/store';
                    fetch(url, {
                        method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.roleForm)
                    }).then(() => location.reload());
                },
                editRole(id, name) {
                    this.roleForm = {
                        id,
                        name
                    };
                    this.showRoleModal = true
                },
                deleteRole(id) {
                    if (confirm('Are you sure?')) fetch(`/admin/roles/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => location.reload())
                },

                // Permission actions
                openPermissionModal() {
                    this.permissionForm = {
                        id: null,
                        name: ''
                    };
                    this.showPermissionModal = true
                },
                closePermissionModal() {
                    this.showPermissionModal = false
                },
                savePermission() {
                    const method = this.permissionForm.id ? 'PUT' : 'POST';
                    const url = this.permissionForm.id ?
                        `/admin/permissions/${this.permissionForm.id}/update` :
                        '/admin/permissions/store';
                    fetch(url, {
                        method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.permissionForm)
                    }).then(() => location.reload());
                },
                editPermission(id, name) {
                    this.permissionForm = {
                        id,
                        name
                    };
                    this.showPermissionModal = true
                },
                deletePermission(id) {
                    if (confirm('Are you sure?')) fetch(`/admin/permissions/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => location.reload())
                }
            }));
        });
    </script>
@endsection
