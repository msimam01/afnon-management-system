@extends('layouts.layout')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Commodity</h2>

    <form method="POST" action="{{ route('admin.commodities.update', $commodity->uuid) }}"
          class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        @php
            $isGlobal = $commodity->is_global;
            $isSeasonClosed = $commodity->season && $commodity->season->status === 'closed';
            $isReadOnly = $isGlobal || $isSeasonClosed;
        @endphp

        <!-- Season Info -->
        @if($commodity->season)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Season</label>
                <input type="text" value="{{ $commodity->season->name }}" disabled
                       class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-md">
                @if ($isSeasonClosed)
                    <p class="text-xs text-red-500 mt-1">This commodity is linked to a closed season.</p>
                @endif
            </div>
        @endif

        <!-- Name -->
        <div class="mb-4">
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $commodity->name) }}"
                   class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                   @if($isReadOnly) disabled @endif>
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Category</label>
            <input type="text" name="category" value="{{ old('category', $commodity->category) }}"
                   class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                   @if($isReadOnly) disabled @endif>
        </div>

        <!-- Unit -->
        <div class="mb-4">
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Unit</label>
            <input type="text" name="unit" value="{{ old('unit', $commodity->unit) }}"
                   class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                   @if($isReadOnly) disabled @endif>
        </div>

        <!-- Price -->
        <div class="mb-4">
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Price Per Unit (₦)</label>
            <input type="number" step="0.01" name="price_per_unit" value="{{ old('price_per_unit', $commodity->price_per_unit) }}"
                   class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
        </div>

        <!-- Qty per hectare -->
        <div class="mb-4">
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Quantity Per Hectare</label>
            <input type="number" step="0.01" name="quantity_per_hectare" value="{{ old('quantity_per_hectare', $commodity->quantity_per_hectare) }}"
                   class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                   @if($isReadOnly) disabled @endif>
        </div>

        <!-- Stock -->
        <div class="mb-4">
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">
                @if ($isGlobal && isset($commodity->allocated_quantity))
                    Allocated Quantity
                @else
                    Stock
                @endif
            </label>
            <input type="number" name="stock" value="{{ $isGlobal ? $commodity->allocated_quantity : $commodity->stock }}"
                   class="w-full px-3 py-2 rounded-md border bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                   disabled>
        </div>

        <!-- Submit -->
        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('admin.commodities.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:underline">← Back to list</a>

            <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
