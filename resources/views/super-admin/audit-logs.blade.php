@extends('layouts.layout')

@section('content')
    <div id="settings-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Audit Logs</h3>
                <input type="search" placeholder="Search logs..."
                    class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-white w-72 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Target</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Admin (admin@necas.ng)
                            </td>
                            <td class="px-6 py-4 text-sm text-blue-600 dark:text-blue-400">Updated quota for
                                North Central</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">Maize 2024 Dry Season
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">2025-07-20 10:34 AM
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Sarah Johnson</td>
                            <td class="px-6 py-4 text-sm text-red-600 dark:text-red-400">Deleted Zone Agent</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">Agent: Musa A</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">2025-07-19 6:15 PM
                            </td>
                        </tr>
                        <!-- Repeat -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
