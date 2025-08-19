@extends('layouts.layout')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Activity Log Details</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Log ID: {{ $log->id }}</p>
        </div>
        <a href="{{ route('superadmin.logs.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            ← Back to Logs
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date & Time</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->created_at->format('F j, Y \a\t g:i:s A') }}</p>
                    <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Action Type</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @switch($log->log_name)
                            @case('user_management')
                                bg-blue-100 text-blue-800
                                @break
                            @case('tenant_management')
                                bg-purple-100 text-purple-800
                                @break
                            @case('authentication')
                                bg-green-100 text-green-800
                                @break
                            @case('system')
                                bg-gray-100 text-gray-800
                                @break
                            @default
                                bg-yellow-100 text-yellow-800
                        @endswitch
                    ">
                        {{ ucfirst(str_replace('_', ' ', $log->log_name)) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->description }}</p>
                </div>

                @if(isset($log->properties['tenant_id']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant</label>
                    <span class="mt-1 inline-block font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded text-sm">
                        {{ $log->properties['tenant_id'] }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">User Information</h3>
            @if($log->causer)
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-lg text-emerald-600 font-medium">
                            {{ strtoupper(substr($log->causer->name, 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ $log->causer->name }}</div>
                        <div class="text-sm text-gray-500">{{ $log->causer->email }}</div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">User ID</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->causer->id }}</p>
                    </div>
                    
                    @if($log->causer->hasRole('super-admin'))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Super Admin
                            </span>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">System Action</p>
                    <p class="text-xs text-gray-400">No user associated</p>
                </div>
            @endif
        </div>

        <!-- Technical Details -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Technical Details</h3>
            <div class="space-y-3">
                @if(isset($log->properties['ip_address']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">IP Address</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $log->properties['ip_address'] }}</p>
                </div>
                @endif

                @if(isset($log->properties['user_agent']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">User Agent</label>
                    <p class="mt-1 text-xs text-gray-900 dark:text-white break-all">{{ $log->properties['user_agent'] }}</p>
                </div>
                @endif

                @if($log->subject)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject Type</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ class_basename($log->subject_type) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject ID</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $log->subject_id }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Additional Properties -->
        @if($log->properties && count($log->properties) > 0)
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Additional Properties</h3>
            <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                <pre class="text-sm text-green-400"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
            </div>
        </div>
        @endif
    </div>

    <!-- Changes (if available) -->
    @if($log->changes && count($log->changes) > 0)
    <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Changes Made</h3>
        
        @if(isset($log->changes['old']) || isset($log->changes['attributes']))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(isset($log->changes['old']))
            <div>
                <h4 class="text-sm font-medium text-red-700 dark:text-red-400 mb-2">Before (Old Values)</h4>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                    <pre class="text-sm text-red-800 dark:text-red-200"><code>{{ json_encode($log->changes['old'], JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif

            @if(isset($log->changes['attributes']))
            <div>
                <h4 class="text-sm font-medium text-green-700 dark:text-green-400 mb-2">After (New Values)</h4>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <pre class="text-sm text-green-800 dark:text-green-200"><code>{{ json_encode($log->changes['attributes'], JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="bg-gray-800 rounded-lg p-4">
            <pre class="text-sm text-green-400"><code>{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</code></pre>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
