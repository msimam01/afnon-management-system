@extends('layouts.layout')

@section('content')
    <div class="max-w-5xl mx-auto py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Commodity</h3>
            </div>

            <div class="p-6">

                <form method="POST" action="{{ route('admin.commodities.update', $commodity->uuid) }}"
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $commodity->name) }}"
                                class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                            <select name="category" required
                                class="w-full mt-1 px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                                <option value="{{ $commodity->category }}">Select category</option>
                                <option value="seed">Seed</option>
                                <option value="fertilizer">Fertilizer</option>
                                <option value="herbicide">Herbicide</option>
                                <option value="insecticide">Insecticide</option>
                                <option value="equipment">Equipment</option>
                                <option value="other">Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-1" />
                        </div>

                        <!-- Unit -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Unit</label>
                            <input type="text" name="unit" value="{{ old('unit', $commodity->unit) }}"
                                class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Price Per Unit (₦)</label>
                            <input type="number" step="0.01" name="price_per_unit"
                                value="{{ old('price_per_unit', $commodity->price_per_unit) }}"
                                class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        </div>

                        <!-- Qty per hectare -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Quantity Per Hectare</label>
                            <input type="number" step="0.01" name="quantity_per_hectare"
                                value="{{ old('quantity_per_hectare', $commodity->quantity_per_hectare) }}"
                                class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">

                                Stock

                            </label>
                            <input type="number" name="stock" value="{{ $commodity->stock }}"
                                class="w-full px-3 py-2 rounded-md border bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        </div>

                    </div>
                    <!-- Submit -->
                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('admin.commodities.index') }}"
                            class="text-sm text-gray-600 dark:text-gray-300 hover:underline">← Back to list</a>
    
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Update Commodity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
