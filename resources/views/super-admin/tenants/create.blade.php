@extends('layouts.layout')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">➕ Create New Tenant</h2>

    <form id="tenantForm" method="POST" action="{{ route('superadmin.tenants.store') }}" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant Name</label>
            <input type="text" name="name" id="name" required
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="e.g. Kano State" value="{{ old('name') }}">
        </div>

        <div>
            <label for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant ID (Slug)</label>
            <input type="text" name="id" id="id" required
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="e.g. kano" value="{{ old('id') }}">
        </div>

        <div>
            <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant Domain</label>
            <input type="url" name="domain" id="domain" required
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="e.g. http://kano.yourapp.test" value="{{ old('domain') }}">
        </div>

        <div class="flex justify-end">
            <button onclick="startLoader()"
                class="bg-emerald-600 text-white px-6 py-2 rounded-md hover:bg-emerald-700 transition">🚀 Create
                Tenant</button>
        </div>
    </form>
</div>
<script>
    function startLoader() {
        ToastMagic.info("Creating tenant... please wait");
        setTimeout(() => {
            document.getElementById('tenantForm').submit();
        }, 500);
    }
</script>
@endsection
