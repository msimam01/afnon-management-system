@extends('layouts.layout')

@section('content')
<!-- Background gradient -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-slate-800 dark:to-gray-900">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Enhanced Header with breadcrumbs and actions -->
        <div class="mb-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('admin.logs.index') }}" class="ml-1 text-gray-500 hover:text-indigo-600 transition-colors duration-200 md:ml-2">Activity Logs</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-700 dark:text-gray-300 md:ml-2">Log Details</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Enhanced Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <!-- Status Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Activity Log Details</h1>
                                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        Log ID: #{{ $log->id }}
                                    </span>
                                    <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $log->created_at->format('M j, Y \a\t g:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3">
                            <button onclick="exportLog()" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export
                            </button>
                            <a href="{{ route('admin.logs.index') }}" 
                               class="inline-flex items-center px-6 py-2 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg border border-gray-300 dark:border-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Main Content - Left Column (2/3 width) -->
            <div class="xl:col-span-2 space-y-6">
                
                <!-- Basic Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Basic Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- DateTime Info -->
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date & Time</label>
                                <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 p-4 rounded-xl border border-gray-200 dark:border-gray-600 group-hover:shadow-md transition-all duration-200">
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $log->created_at->format('F j, Y \a\t g:i:s A') }}</p>
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium mt-1">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Action Type -->
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Action Type</label>
                                <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 p-4 rounded-xl border border-gray-200 dark:border-gray-600 group-hover:shadow-md transition-all duration-200">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold shadow-sm
                                        @switch($log->log_name)
                                            @case('user_management')
                                                bg-gradient-to-r from-blue-500 to-blue-600 text-white
                                                @break
                                            @case('loan_management')
                                                bg-gradient-to-r from-emerald-500 to-emerald-600 text-white
                                                @break
                                            @case('authentication')
                                                bg-gradient-to-r from-purple-500 to-purple-600 text-white
                                                @break
                                            @case('system')
                                                bg-gradient-to-r from-gray-500 to-gray-600 text-white
                                                @break
                                            @default
                                                bg-gradient-to-r from-amber-500 to-amber-600 text-white
                                        @endswitch
                                    ">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $log->log_name)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 p-4 rounded-xl border border-gray-200 dark:border-gray-600">
                                <p class="text-base text-gray-900 dark:text-white leading-relaxed">{{ $log->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Technical Details</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($log->properties['ip_address']))
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">IP Address</label>
                                <div class="bg-gradient-to-r from-gray-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 p-4 rounded-xl border border-gray-200 dark:border-gray-600 group-hover:shadow-md transition-all duration-200">
                                    <p class="text-base font-mono font-semibold text-gray-900 dark:text-white">{{ $log->properties['ip_address'] }}</p>
                                    <button onclick="copyToClipboard('{{ $log->properties['ip_address'] }}')" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 font-medium mt-1">Click to copy</button>
                                </div>
                            </div>
                            @endif

                            @if($log->subject)
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                                <div class="bg-gradient-to-r from-gray-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 p-4 rounded-xl border border-gray-200 dark:border-gray-600 group-hover:shadow-md transition-all duration-200">
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ class_basename($log->subject_type) }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">ID: {{ $log->subject_id }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if(isset($log->properties['user_agent']))
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">User Agent</label>
                            <div class="bg-gradient-to-r from-gray-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 p-4 rounded-xl border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-700 dark:text-gray-300 break-all font-mono leading-relaxed">{{ $log->properties['user_agent'] }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar - Right Column (1/3 width) -->
            <div class="xl:col-span-1 space-y-6">
                
                <!-- User Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm overflow-hidden sticky top-6">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">User Information</h3>
                        </div>

                        @if($log->causer)
                            <!-- User Profile -->
                            <div class="text-center mb-6">
                                <div class="relative inline-block">
                                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 via-teal-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg mx-auto mb-4">
                                        <span class="text-2xl text-white font-bold">
                                            {{ strtoupper(substr($log->causer->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-white shadow-md"></div>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $log->causer->name }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $log->causer->email }}</p>
                                    <p class="text-xs text-gray-500">User ID: {{ $log->causer->id }}</p>
                                </div>
                            </div>

                            @if($log->causer->roles->count() > 0)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">User Roles</label>
                                <div class="flex flex-wrap gap-2 justify-center">
                                    @foreach($log->causer->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-md transform hover:scale-105 transition-all duration-200
                                            @if($role->name === 'admin')
                                                bg-gradient-to-r from-purple-500 to-pink-600 text-white
                                            @elseif($role->name === 'agent')
                                                bg-gradient-to-r from-blue-500 to-indigo-600 text-white
                                            @elseif($role->name === 'farmer')
                                                bg-gradient-to-r from-green-500 to-emerald-600 text-white
                                            @else
                                                bg-gradient-to-r from-gray-500 to-gray-600 text-white
                                            @endif
                                        ">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @else
                            <!-- System Action -->
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">System Action</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Automated system operation</p>
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full">
                                        No user associated
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Properties Section -->
        @if($log->properties && count($log->properties) > 0)
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Additional Properties</h3>
                    </div>
                    <button onclick="toggleProperties()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg id="toggle-icon" class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                <div id="properties-content" class="bg-gray-900 rounded-xl p-4 overflow-x-auto border border-gray-700">
                    <pre class="text-sm text-emerald-400"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
        </div>
        @endif

        <!-- Changes Section -->
        @if($log->changes && count($log->changes) > 0)
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Changes Made</h3>
                </div>
                
                @if(isset($log->changes['old']) || isset($log->changes['attributes']))
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if(isset($log->changes['old']))
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <h4 class="text-sm font-bold text-red-700 dark:text-red-400">Before (Old Values)</h4>
                        </div>
                        <div class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <pre class="text-sm text-red-800 dark:text-red-200 overflow-x-auto"><code>{{ json_encode($log->changes['old'], JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    </div>
                    @endif

                    @if(isset($log->changes['attributes']))
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <h4 class="text-sm font-bold text-green-700 dark:text-green-400">After (New Values)</h4>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                            <pre class="text-sm text-green-800 dark:text-green-200 overflow-x-auto"><code>{{ json_encode($log->changes['attributes'], JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="bg-gray-900 rounded-xl p-4 border border-gray-700">
                    <pre class="text-sm text-emerald-400 overflow-x-auto"><code>{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Enhanced JavaScript for interactivity -->
<script>
function toggleProperties() {
    const content = document.getElementById('properties-content');
    const icon = document.getElementById('toggle-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(-90deg)';
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Create and show toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Copied to clipboard!</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Slide in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Slide out and remove
        setTimeout(() => {
            toast.style.transform = 'translateX(full)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 2000);
    });
}

function exportLog() {
    // Create export data
    const exportData = {
        log_id: '{{ $log->id }}',
        timestamp: '{{ $log->created_at->toISOString() }}',
        action_type: '{{ $log->log_name }}',
        description: `{{ $log->description }}`,
        @if($log->causer)
        user: {
            id: '{{ $log->causer->id }}',
            name: `{{ $log->causer->name }}`,
            email: '{{ $log->causer->email }}',
            @if($log->causer->roles->count() > 0)
            roles: [
                @foreach($log->causer->roles as $role)
                '{{ $role->name }}',
                @endforeach
            ]
            @endif
        },
        @endif
        @if($log->properties)
        properties: {!! json_encode($log->properties) !!},
        @endif
        @if($log->changes)
        changes: {!! json_encode($log->changes) !!},
        @endif
        @if($log->subject)
        subject: {
            type: '{{ class_basename($log->subject_type) }}',
            id: '{{ $log->subject_id }}'
        }
        @endif
    };
    
    // Create and download file
    const dataStr = JSON.stringify(exportData, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    
    const exportFileDefaultName = `activity-log-${exportData.log_id}-${new Date().toISOString().split('T')[0]}.json`;
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
    
    // Show success message
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    toast.innerHTML = `
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Log exported successfully!</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(full)';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Add smooth scrolling and entrance animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on load
    const cards = document.querySelectorAll('.bg-white, .dark\\:bg-gray-800');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });
    
    // Add hover effects for interactive elements
    const interactiveElements = document.querySelectorAll('.group');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + E for export
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        exportLog();
    }
    
    // Escape to go back
    if (e.key === 'Escape') {
        window.location.href = '{{ route("admin.logs.index") }}';
    }
});

// Add real-time clock update
function updateRelativeTime() {
    const timeElement = document.querySelector('.text-indigo-600.dark\\:text-indigo-400');
    if (timeElement) {
        // This would need to be calculated server-side in a real implementation
        // For demo purposes, we'll keep the original time
    }
}

// Update every minute
setInterval(updateRelativeTime, 60000);

// Add print functionality
function printLog() {
    window.print();
}

// Add search functionality (if there were multiple logs)
function searchLogs(query) {
    // This would be implemented with a search API
    console.log('Searching for:', query);
}

// Add theme toggle functionality
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.classList.toggle('dark');
    localStorage.setItem('theme', newTheme);
}

// Initialize theme from localStorage
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
});
</script>

<!-- Enhanced CSS for additional styling -->
<style>
/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
}

/* Dark mode scrollbar */
.dark ::-webkit-scrollbar-track {
    background: #374151;
}

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Enhanced focus styles for accessibility */
button:focus,
a:focus {
    outline: 2px solid #6366f1;
    outline-offset: 2px;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .bg-gradient-to-br,
    .bg-gradient-to-r {
        background: #f8fafc !important;
        -webkit-print-color-adjust: exact;
    }
    
    .shadow-xl,
    .shadow-lg,
    .shadow-md {
        box-shadow: none !important;
        border: 1px solid #e2e8f0 !important;
    }
}

/* Animation classes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Enhanced gradient backgrounds */
.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

/* Custom backdrop blur for better browser support */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Enhanced hover effects */
.group:hover .group-hover\:shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Responsive text scaling */
@media (max-width: 640px) {
    .text-3xl {
        font-size: 1.875rem;
        line-height: 2.25rem;
    }
    
    .text-2xl {
        font-size: 1.5rem;
        line-height: 2rem;
    }
}
</style>
@endsection