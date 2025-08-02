@extends('layouts.layout')
@php
    $openSeason = \App\Models\Season::where('status', 'open')->first();
@endphp
@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Create New Commodity</h3>
        </div>
        <div>
            @if (!$openSeason)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">
                    <strong>Notice:</strong> You cannot add or update commodities. All seasons are currently closed.
                </div>
            @else
                <form action="{{ route('admin.commodities.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Commodity Name -->
                        <div>
                            <label for="commodityName"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity Name *</label>
                            <input type="text" id="commodityName" name="name" required placeholder="e.g., Maize Seeds"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="commodityCategory"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                            <select id="commodityCategory" name="category" required
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                                <option value="">Select category</option>
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
                            <input type="text" id="unit" name="unit" required placeholder="e.g., bags, liters"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <!-- Price per Unit -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price
                                per
                                Unit (₦) *</label>
                            <input type="number" id="price" name="price" required step="0.01" min="0"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <!-- Quantity per Hectare -->
                        <div>
                            <label for="qtyPerHectare"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity per Hectare
                                *</label>
                            <input type="number" id="qtyPerHectare" name="qtyPerHectare" required step="0.1"
                                min="0"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white"
                                placeholder="e.g., 2, 3.5">
                            <x-input-error :messages="$errors->get('qtyPerHectare')" class="mt-2" />
                        </div>

                        <!-- Initial Stock -->
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Initial
                                Stock *</label>
                            <input type="number" id="stock" name="stock" required min="0"
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white"
                                placeholder="e.g., 5000">
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="submit"
                            class="bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">
                            Save Commodity
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
