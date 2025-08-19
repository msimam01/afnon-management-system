@extends('layouts.layout')

@section('content')
<div id="settings-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-10">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Permissions</h3>
            <a href="{{ route('admin.permissions.create') }}"
                class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                + Add New Permission
            </a>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($permissions as $permission)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $permission->name }}
                            </td>
                            <td class="px-6 py-4 text-sm flex space-x-3">
                                <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                   class="text-emerald-600 dark:text-emerald-400 hover:underline">Edit</a>
                                <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                No permissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
