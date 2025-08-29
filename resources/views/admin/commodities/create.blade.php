@extends('layouts.layout')

@section('content')
    <div class="max-w-5xl mx-auto py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Create New Commodity</h3>
            </div>

            <div class="p-6">


                <form action="{{ route('admin.commodities.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Commodity Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity Name
                                *</label>
                            <input type="text" name="name" required placeholder="e.g., Maize Seeds"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                            <select name="category" required
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                                <option value="">Select category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                                
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-1" />
                        </div>

                        <!-- Unit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit *</label>
                            <input type="text" name="unit" required placeholder="e.g., bags, liters"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <x-input-error :messages="$errors->get('unit')" class="mt-1" />
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price Per Unit (₦)
                                *</label>
                            <input type="number" name="price" required step="0.01" min="0"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <x-input-error :messages="$errors->get('price')" class="mt-1" />
                        </div>

                        <!-- Qty Per Hectare -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Qty Per Hectare
                                *</label>
                            <input type="number" name="qtyPerHectare" required step="0.1" min="0"
                                placeholder="e.g., 2.5"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <x-input-error :messages="$errors->get('qtyPerHectare')" class="mt-1" />
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Initial Stock
                                *</label>
                            <input type="number" name="stock" required min="0" placeholder="e.g., 5000"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <x-input-error :messages="$errors->get('stock')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-md shadow-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            Save Commodity
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
@endsection
