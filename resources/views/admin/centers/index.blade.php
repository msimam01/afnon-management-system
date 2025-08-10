@extends('layouts.layout')

@section('content')
    <div id="commodities-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Available Centers</h3>
                <a href="{{ route('admin.centers.create') }}"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                    + Add Center
                </a>
            </div>
            <form method="GET" action="{{ route('admin.centers.index') }}"
                class="px-6 py-4 flex flex-col md:flex-row gap-4 border-b border-gray-200 dark:border-gray-700">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name"
                        class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                </div>

                <div class="self-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">Search</button>
                </div>
            </form>

            <!-- centers Table -->
            <div class="px-6 pb-6 pt-3">
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    State</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Lga</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Address</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($centers as $item)
                                <tr>
                                    <!-- Name -->
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</div>
                                        <div class="flex items-center gap-2 mt-1 text-xs">
                                            <span
                                                class="text-gray-400 dark:text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->type }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->state }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->lga }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->address }}</td>

                                    <!-- Actions -->
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.centers.edit', $item->uuid) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>


                                            <form action="{{ route('admin.centers.destroy', $item->uuid) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 dark:text-red-400 hover:underline text-xs">Delete</button>
                                            </form>



                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No
                                        center found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                    <div class="px-6 mt-4">
                        {{ $centers->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
