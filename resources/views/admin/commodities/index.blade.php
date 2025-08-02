@extends('layouts.layout')

@section('content')
    <div id="commodities-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Available Commodities</h3>
                <a href="{{ route('admin.commodities.create') }}"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                    + Add Commodity
                </a>
            </div>
            <div class="flex justify-end items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.commodities.importForm') }}"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                    + Import From Global Commodities
                </a>
            </div>
            <!-- Commodities Table -->
            <div class="px-6 pb-6 pt-3">
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Commodity
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Category</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Unit</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Price (₦)
                                </th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Qty/ha</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Stock</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($commodities as $item)
                                <tr>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-400">{{ $item->name }}@if ($item->is_global)
                                            <span
                                                class="ml-2 text-xs px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded">Synced</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->category }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                        ₦{{ number_format($item->price_per_unit) }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                        {{ number_format($item->quantity_per_hectare) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->stock }}</td>
                                    <td class="px-4 py-4 flex space-x-3">
                                        <a href="{{ route('admin.commodities.edit', $item->uuid) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>
                                        <form action="{{ route('admin.commodities.destroy', $item->uuid) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="text-red-600 px-2 py-0 dark:text-red-400 hover:underline text-xs">Delete</button>
                                        </form>

                                        @if ($item->is_global)
                                            <form action="{{ route('admin.commodities.sync', $item->uuid) }}"
                                                method="POST">
                                                @csrf
                                                <button class="text-xs text-blue-500 hover:underline">Sync</button>
                                            </form>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach

                            <!-- More rows... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Trigger -->

    </div>
@endsection
