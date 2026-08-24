@extends('layouts.layout')
@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Commodity</h3>
        </div>
        <div>
            <form action="{{ route('superadmin.commodities.update', $commodity->uuid) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Commodity Name -->
                    <div>
                        <label for="commodityName"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity Name *</label>
                        <input type="text" id="commodityName" name="name" value="{{ old('name', $commodity->name) }}" required placeholder="e.g., Maize Seeds"
                            class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="commodityCategory"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                        <select id="commodityCategory" name="category" required
                            class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <option value="{{ old('category', $commodity->category) }}">Select category</option>
                            <option value="seed">Seed</option>
                            <option value="fertilizer">Fertilizer</option>
                            <option value="herbicide">Herbicide</option>
                            <option value="insecticide">Insecticide</option>
                            <option value="equipment">Equipment</option>
                            <option value="other">Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>

                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit
                            *</label>
                        <input type="text" id="unit" name="unit" value="{{ old('unit', $commodity->unit) }}" required placeholder="e.g., bags, liters"
                            class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>

                    <!-- Price per Unit -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per
                            Unit (₦) *</label>
                        <input type="number" id="price" name="price_per_unit" value="{{ old('price_per_unit', $commodity->price_per_unit) }}" required step="0.01" min="0"
                            class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        <x-input-error :messages="$errors->get('price_per_unit')" class="mt-2" />
                    </div>

                    <!-- Quantity per Hectare -->
                    <div>
                        <label for="qtyPerHectare"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity per Hectare
                            *</label>
                        <input type="number" id="quantity_per_hectare" name="quantity_per_hectare" value="{{ old('quantity_per_hectare', $commodity->quantity_per_hectare) }}" required step="0.1"
                            min="0"
                            class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white"
                            placeholder="e.g., 2, 3.5">
                        <x-input-error :messages="$errors->get('quantity_per_hectare')" class="mt-2" />
                    </div>

                    <!-- Initial Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Initial
                            Stock *</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $commodity->stock) }}" required min="0"
                            class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white"
                            placeholder="e.g., 5000">
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">
                        Update Commodity
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
