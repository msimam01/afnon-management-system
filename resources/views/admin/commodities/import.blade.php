@extends('layouts.layout')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Import From Global Commodities</h2>


        <form action="{{ route('admin.commodities.importBulk') }}" method="POST">
            @csrf
            <div class="px-6 pb-6 pt-3">
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300"><input type="checkbox" onclick="toggleAll(this)"></th>
                                <th class="px-4 py-2 px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Name</th>
                                <th class="px-4 py-2 px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Unit</th>
                                <th class="px-4 py-2 px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($globalCommodities as $item)
                                <tr>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-400">
                                        <input type="checkbox" name="commodity_ids[]" value="{{ $item->id }}">
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-400">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-400">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-400">₦{{ number_format($item->price_per_unit) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4 flex justify-end py-3 pr-3">
                        <button type="submit"
                            class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">Import
                            Selected</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script>
        function toggleAll(source) {
            checkboxes = document.getElementsByName('commodity_ids[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = source.checked;
            }
        }
    </script>
@endsection
