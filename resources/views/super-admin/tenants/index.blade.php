@extends('layouts.layout')

@section('content')
<div class="max-w-5xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🌍 All Tenants</h2>
        <a href="{{ route('superadmin.tenants.create') }}"
            class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">➕ New Tenant</a>
    </div>

    <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Domain</th>
                <th class="px-4 py-2">Created At</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white divide-y">
            @foreach ($tenants as $tenant)
                <tr>
                    <td class="px-4 py-2">{{ $tenant->id }}</td>
                    <td class="px-4 py-2">{{ $tenant->name }}</td>
                    <td class="px-4 py-2">{{ $tenant->domain }}</td>
                    <td class="px-4 py-2">{{ $tenant->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
