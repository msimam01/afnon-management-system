@extends('layouts.layout')
@section('content')
    <!-- Add Role -->
    <div class="p-6">
        <div class="max-w-5xl mx-auto py-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 id="roleModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add New Role</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <!-- Role Name -->
                        <div>
                            <label for="roleName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role
                                Name
                                *</label>
                            <input type="text" id="roleName" name="name" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Permissions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign
                                Permissions</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($permissions as $item)
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $item->name }}"
                                                class="form-checkbox text-emerald-600 dark:bg-gray-700 dark:border-gray-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $item->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
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
        </div>
    </div>
@endsection
