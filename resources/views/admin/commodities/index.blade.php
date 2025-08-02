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
            <form method="GET" action="{{ route('admin.commodities.index') }}"
                class="px-6 py-4 flex flex-col md:flex-row gap-4 border-b border-gray-200 dark:border-gray-700">

                <!-- Season Filter -->
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Season</label>
                    <select name="season_id" class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        <option value="">All Seasons</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}" @selected(request('season_id') == $season->id)>
                                {{ $season->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Type</label>
                    <select name="is_global" class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        <option value="">All Types</option>
                        <option value="1" @selected(request('is_global') === '1')>Global</option>
                        <option value="0" @selected(request('is_global') === '0')>Local</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or category"
                        class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                </div>

                <div class="self-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">Apply</button>
                </div>
            </form>


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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Commodity</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Season</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Price (₦)</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Qty/ha</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Stock / Allocation</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($commodities as $item)
                                <tr>
                                    <!-- Commodity + Badge + Timestamp -->
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</div>
                                        <div class="flex items-center gap-2 mt-1 text-xs">
                                            @if ($item->is_global)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded-full">Global</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full">Local</span>
                                            @endif
                        
                                            <span class="text-gray-400 dark:text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                        
                                    <!-- Season Name -->
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ optional($item->season)->name ?? '—' }}
                                    </td>
                        
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->category }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">₦{{ number_format($item->price_per_unit) }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ number_format($item->quantity_per_hectare) }}</td>
                        
                                    <!-- Stock / Allocated -->
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                        @if ($item->is_global && isset($item->allocated_quantity))
                                            {{ $item->allocated_quantity }} <span class="text-xs text-gray-400">(Allocated)</span>
                                        @else
                                            {{ $item->stock }}
                                        @endif
                                    </td>
                        
                                    <!-- Actions -->
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.commodities.edit', $item->uuid) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>
                        
                                            @unless ($item->is_global)
                                                <form action="{{ route('admin.commodities.destroy', $item->uuid) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:underline text-xs">Delete</button>
                                                </form>
                                            @endunless
                        
                                            @if ($item->is_global)
                                                <form action="{{ route('admin.commodities.sync', $item->uuid) }}" method="POST">
                                                    @csrf
                                                    <button class="text-xs text-blue-500 hover:underline">Sync</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No commodities found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                                              
                    </table>
                    <div class="px-6 mt-4">
                        {{ $commodities->links() }}
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- Trigger -->

    </div>
@endsection
