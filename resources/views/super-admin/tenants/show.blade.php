@extends('layouts.layout')

@section('content')
<div class="max-w-6xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Tenant Details</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $tenant->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('superadmin.tenants.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                ← Back to Tenants
            </a>
            @if($tenant->isActive())
                <a href="http://{{ $tenant->domain }}:8000" target="_blank"
                   class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                    🌐 Visit Tenant
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant ID</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">{{ $tenant->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $tenant->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Domain</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            <a href="http://{{ $tenant->domain }}:8000" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $tenant->domain }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <div class="mt-1">
                            @switch($tenant->status)
                                @case('active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Active
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-2 h-2 mr-1 animate-spin" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Pending Setup
                                    </span>
                                    @break
                                @case('inactive')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Inactive
                                    </span>
                                    @break
                                @case('suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Suspended
                                    </span>
                                    @break
                                @case('failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Setup Failed
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                @if($tenant->data['description'] ?? null)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $tenant->data['description'] }}</p>
                </div>
                @endif
            </div>

            <!-- Status Information Card -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Status Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Created At</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $tenant->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    @if($tenant->activated_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Activated At</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $tenant->activated_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    @endif
                    @if($tenant->deactivated_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deactivated At</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $tenant->deactivated_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    @endif
                </div>

                @if($tenant->deactivation_reason)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deactivation Reason</label>
                    <div class="mt-1 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                        <p class="text-sm text-red-800 dark:text-red-200">{{ $tenant->deactivation_reason }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Database Information -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Database Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Database Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                            {{ config('tenancy.database.prefix') }}{{ $tenant->id }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Connection</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ config('tenancy.database.central_connection') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($tenant->isActive())
                        <button onclick="openDeactivateModal('{{ $tenant->id }}', '{{ $tenant->name }}')"
                                class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                            🚫 Deactivate Tenant
                        </button>
                        <button onclick="openSuspendModal('{{ $tenant->id }}', '{{ $tenant->name }}')"
                                class="w-full bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 text-sm">
                            ⏸️ Suspend Tenant
                        </button>
                    @elseif($tenant->isInactive() || $tenant->isSuspended())
                        <form action="{{ route('superadmin.tenants.toggle-status', $tenant) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm"
                                    onclick="return confirm('Are you sure you want to activate this tenant?')">
                                ✅ Activate Tenant
                            </button>
                        </form>
                    @endif

                    @if($tenant->isActive())
                        <a href="http://{{ $tenant->domain }}:8000" target="_blank"
                           class="block w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm text-center">
                            🌐 Visit Tenant Site
                        </a>
                        <a href="http://{{ $tenant->domain }}:8000/admin" target="_blank"
                           class="block w-full bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 text-sm text-center">
                            ⚙️ Admin Panel
                        </a>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Days Active</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            @if($tenant->activated_at)
                                {{ $tenant->activated_at->diffInDays(now()) }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Uptime</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            @if($tenant->activated_at)
                                {{ $tenant->activated_at->diffForHumans(now(), true) }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Activity</h3>
                <div class="space-y-3 text-sm">
                    @if($tenant->deactivated_at)
                        <div class="flex items-start space-x-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-gray-900 dark:text-white">Status changed to {{ $tenant->status }}</p>
                                <p class="text-gray-500 text-xs">{{ $tenant->deactivated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                    @if($tenant->activated_at)
                        <div class="flex items-start space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-gray-900 dark:text-white">Tenant activated</p>
                                <p class="text-gray-500 text-xs">{{ $tenant->activated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-start space-x-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-gray-900 dark:text-white">Tenant created</p>
                            <p class="text-gray-500 text-xs">{{ $tenant->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include the same modals from index page -->
@include('super-admin.tenants.partials.modals')

@endsection
