@php
    $statusColors = [
        'approved' => 'bg-green-100 text-green-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'verified' => 'bg-blue-100 text-blue-800',
        'returned' => 'bg-purple-100 text-purple-800',
    ];
@endphp
@extends('layouts.layout')

@section('content')
    <div class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Reports</h3>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.reports.applications') }}"
                class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <select name="season_id" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
                    <option value="">All Seasons</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>
                            {{ $season->name }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="reg_number" placeholder="Farmer Reg. No." value="{{ request('reg_number') }}"
                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

                <select name="status" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                </select>

                <input type="date" name="from" value="{{ request('from') }}"
                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
                <input type="date" name="to" value="{{ request('to') }}"
                    class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

                <div class="flex space-x-2">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">Filter</button>
                    <a href="{{ route('admin.reports.export', request()->all()) }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Export CSV</a>
                </div>
            </form>



            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Reg. No</th>
                            <th class="px-4 py-2 text-left">Farmer</th>
                            <th class="px-4 py-2 text-left">Season</th>
                            <th class="px-4 py-2 text-left">Total Loan</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Created</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($applications as $app)
                            <tr>
                                <td class="px-4 py-2">{{ $app->farmer->registration_number }}</td>
                                <td class="px-4 py-2">{{ $app->farmer->full_name }}</td>
                                <td class="px-4 py-2">{{ $app->season->name }}</td>
                                <td class="px-4 py-2">₦{{ number_format($app->total_loan, 2) }}</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $statusColors[$app->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $app->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-sm">No applications found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $applications->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
