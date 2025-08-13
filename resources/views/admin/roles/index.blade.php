@extends('layouts.layout')

@section('content')
    <div id="settings-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-10">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Roles & Permissions</h3>
                <a href="{{ route('admin.roles.create') }}"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                    + Add New Role
                </a>
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
                        @foreach ($roles as $role)
                            <tr>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $role->name }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $role->permissions->pluck('name')->join(', ') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this role?')" class="text-red-600 dark:text-red-400 hover:underline text-xs">Delete</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Repeat for other roles -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
