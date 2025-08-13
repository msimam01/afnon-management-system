@extends('layouts.layout')

@section('content')
<div class="p-6">
    <div class="max-w-lg mx-auto py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New Permission</h3>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.permissions.store') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Permission Name *</label>
                        <input type="text" id="name" name="name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-emerald-600 text-white py-2 px-6 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium transition-colors">
                            Save Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
