@extends('layouts.layout')

@section('content')
<div id="seasons-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Season Management</h3>
            {{-- <button onclick="openSeasonModal()"
                class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                Create New Season
            </button> --}}
        </div>

        <!-- Season Cards -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Example Season Card -->
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-medium text-gray-900 dark:text-white">2024 Dry Season</h4>
                    <span
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Open</span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1 mb-3">
                    <p><strong>Start:</strong> Jan 1, 2024</p>
                    <p><strong>End:</strong> June 30, 2024</p>
                    <p><strong>Commodity:</strong> Maize Seeds</p>
                    <p><strong>Allocation:</strong> 20,000 bags</p>
                </div>
                <div class="flex space-x-2">
                    {{-- <button onclick="openSeasonModal(true)"
                        class="text-emerald-600 dark:text-emerald-400 text-sm hover:underline">Edit</button> --}}
                    <button onclick="toggleSeasonStatus(this)"
                        class="text-red-600 dark:text-red-400 text-sm hover:underline">Close</button>
                </div>
            </div>

            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-medium text-gray-900 dark:text-white">2024 Wet Season</h4>
                    <span
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100">Closed</span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1 mb-3">
                    <p><strong>Start:</strong> July 1, 2024</p>
                    <p><strong>End:</strong> Dec 31, 2024</p>
                    <p><strong>Commodity:</strong> Rice Seeds</p>
                    <p><strong>Allocation:</strong> 15,000 bags</p>
                </div>
                {{-- <div class="flex space-x-2">
                    <button onclick="openSeasonModal(true)"
                        class="text-emerald-600 dark:text-emerald-400 text-sm hover:underline">Edit</button> --}}
                    <button onclick="toggleSeasonStatus(this)"
                        class="text-emerald-600 dark:text-emerald-400 text-sm hover:underline">Open</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seasons Modal -->
<div id="seasonModal"
    class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center px-4">
    <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4" id="seasonModalTitle">Create New Season</h3>
        <form id="seasonForm" class="space-y-4">
            <input type="text" placeholder="Season Name"
                class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                required>
            <div class="grid grid-cols-2 gap-4">
                <input type="date"
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>
                <input type="date"
                    class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>
            </div>
            <input type="text" placeholder="Primary Commodity (e.g., Maize)"
                class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                required>
            <input type="number" placeholder="Total Allocation (bags)"
                class="w-full px-3 py-2 rounded-md border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                required>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeSeasonModal()"
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-md">Cancel</button>
                <button type="submit"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Save Season</button>
            </div>
        </form>
    </div>
</div>
@endsection
