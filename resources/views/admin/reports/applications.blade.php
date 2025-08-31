@php
    $statusColors = [
        'approved' =>
            'bg-gradient-to-r from-emerald-50 to-green-50 text-emerald-700 border border-emerald-200 dark:from-emerald-900/20 dark:to-green-900/20 dark:text-emerald-300 dark:border-emerald-700',
        'pending' =>
            'bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-700 border border-amber-200 dark:from-amber-900/20 dark:to-yellow-900/20 dark:text-amber-300 dark:border-amber-700',
        'distributed' =>
            'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 border border-blue-200 dark:from-blue-900/20 dark:to-indigo-900/20 dark:text-blue-300 dark:border-blue-700',
        'rejected' =>
            'bg-gradient-to-r from-red-50 to-rose-50 text-red-700 border border-red-200 dark:from-red-900/20 dark:to-rose-900/20 dark:text-red-300 dark:border-red-700',
    ];

    $statusIcons = [
        'approved' =>
            '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
        'pending' =>
            '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>',
        'distributed' =>
            '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path></svg>',
        'rejected' =>
            '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>',
    ];
@endphp

@extends('layouts.layout')

@section('content')
    <div
        class="w-75 min-h-screen px-4 py-6 space-y-8 bg-gradient-to-br from-slate-50 via-emerald-50/30 to-green-50/20 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Enhanced Header Section with Glassmorphism -->

        <div class="relative">
            <div class="absolute inset-0 rounded-2xl blur-xl"></div>
            <div
                class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 p-8 shadow-xl">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-green-400 rounded-xl shadow-lg">
                                <svg class="w-8 h-8 text-white dark:bg-green-400 dark:text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-emerald-900 to-emerald-900 bg-clip-text text-transparent dark:from-white dark:via-emerald-100 dark:to-emerald-100">
                                    Application Reports
                                </h1>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">Comprehensive insights into farmer
                                    applications and analytics</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="toggleAdvancedFilters()"
                            class="group inline-flex bg-green-400 items-center px-6 py-3 text-white  dark:text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-180 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                                </path>
                            </svg>
                            Advanced Filters
                        </button>
                        <a href="{{ route('admin.reports.export', request()->all()) }}"
                            class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Export Data
                            <span class="ml-2 px-2 py-1 bg-white/20 rounded-lg text-xs">CSV</span>
                        </a>
                        <a href="{{ route('admin.reports.exportExcel', request()->all()) }}"
                            class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ml-2">
                            <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Export Data
                            <span class="ml-2 px-2 py-1 bg-white/20 rounded-lg text-xs">Excel</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Cards with Reduced Animation -->
        @if (isset($statistics))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-green-600/10 rounded-2xl blur-lg">
                    </div>
                    <div
                        class="relative bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm p-6 rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Total Applications</p>
                                <p
                                    class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">
                                    {{ number_format($statistics['total'] ?? $applications->total()) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">All time records</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-green-600/10 rounded-2xl blur-lg">
                    </div>
                    <div
                        class="relative bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm p-6 rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Approved</p>
                                <p
                                    class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">
                                    {{ number_format($statistics['approved'] ?? 0) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ready for distribution</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-yellow-600/10 rounded-2xl blur-lg">
                    </div>
                    <div
                        class="relative bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm p-6 rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Pending Review</p>
                                <p
                                    class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">
                                    {{ number_format($statistics['pending'] ?? 0) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Awaiting approval</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-600/10 rounded-2xl blur-lg">
                    </div>
                    <div
                        class="relative bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm p-6 rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Total Loan Value</p>
                                <p
                                    class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">
                                    ₦{{ number_format($statistics['total_loan'] ?? 0, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Cumulative amount</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Enhanced Filters Section with Collapsible Design -->
        <div id="filtersSection" class="relative transition-all duration-500 ease-in-out">
            <div
                class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 via-green-500/10 to-pink-500/10 rounded-2xl blur-xl">
            </div>
            <div
                class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Smart Filters & Search</h3>
                        </div>
                        <button onclick="toggleFilters()"
                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg id="filterToggleIcon" class="w-5 h-5 text-gray-500 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="filterContent" class="p-6">
                    <form method="GET" action="{{ route('admin.reports.applications') }}" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Season
                                    </span>
                                </label>
                                <select name="season_id"
                                    class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 text-gray-900 dark:text-white transition-all duration-300 hover:shadow-md">
                                    <option value="">All Seasons</option>
                                    @foreach ($seasons as $season)
                                        <option value="{{ $season->id }}"
                                            {{ request('season_id') == $season->id ? 'selected' : '' }}>
                                            {{ $season->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Registration Number
                                    </span>
                                </label>
                                <input type="text" name="reg_number" placeholder="Enter farmer registration number..."
                                    value="{{ request('reg_number') }}"
                                    class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 text-gray-900 dark:text-white placeholder-gray-500 transition-all duration-300 hover:shadow-md">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Status
                                    </span>
                                </label>
                                <select name="status"
                                    class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 text-gray-900 dark:text-white transition-all duration-300 hover:shadow-md">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="distributed"
                                        {{ request('status') == 'distributed' ? 'selected' : '' }}>Distributed</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        From Date
                                    </span>
                                </label>
                                <input type="date" name="from" value="{{ request('from') }}"
                                    class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 text-gray-900 dark:text-white transition-all duration-300 hover:shadow-md">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        To Date
                                    </span>
                                </label>
                                <input type="date" name="to" value="{{ request('to') }}"
                                    class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 text-gray-900 dark:text-white transition-all duration-300 hover:shadow-md">
                            </div>
                        </div>

                        <div
                            class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-gray-200/50 dark:border-gray-700/50">
                            <button type="submit"
                                class="group inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.reports.applications') }}"
                                class="group inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                                <svg class="w-5 h-5 mr-2 group-hover:rotate-180 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Clear All Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Enhanced Results Section with Modern Table -->
        <div class="relative">
            <div
                class="absolute inset-0 bg-gradient-to-r from-slate-500/10 via-gray-500/10 to-zinc-500/10 rounded-2xl blur-xl">
            </div>
            <div
                class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden">
                <div
                    class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-white/50 to-gray-50/50 dark:from-gray-800/50 dark:to-gray-700/50">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Application Results</h3>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="px-4 py-2 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-700">
                                <span class="text-sm font-semibold text-emerald-700 dark:text-green-300">
                                    Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }}
                                    of {{ $applications->total() }} results
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-gray-50 via-emerald-50/30 to-green-50/30 dark:from-gray-700 dark:via-gray-600/30 dark:to-gray-500/30 border-b border-gray-200/50 dark:border-gray-600/50">
                                <th
                                    class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                        Registration Number
                                    </div>
                                </th>
                                <th
                                    class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Farmer Details
                                    </div>
                                </th>
                                <th
                                    class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Season
                                    </div>
                                </th>
                                <th
                                    class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        Loan Amount
                                    </div>
                                </th>
                                <th
                                    class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Status
                                    </div>
                                </th>
                                <th
                                    class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Application Date
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                            @forelse($applications as $app)
                                <tr
                                    class="group hover:bg-gradient-to-r hover:from-emerald-50/50 hover:to-green-50/30 dark:hover:from-emerald-900/10 dark:hover:to-green-900/5 transition-all duration-300">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="p-2 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-lg group-hover:scale-105 transition-transform duration-300">
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                </svg>
                                            </div>
                                            <span
                                                class="font-mono text-sm font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-lg">
                                                {{ $app->farmer->registration_number }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <div
                                                    class="h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                    <span class="text-sm font-bold text-white">
                                                        {{ substr($app->farmer->full_name, 0, 2) }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="absolute -bottom-1 -right-1 h-4 w-4 bg-green-400 rounded-full border-2 border-white dark:border-gray-800">
                                                </div>
                                            </div>
                                            <div class="space-y-1">
                                                <div class="font-bold text-gray-900 dark:text-white text-lg">
                                                    {{ $app->farmer->full_name }}
                                                </div>
                                                <div
                                                    class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                        </path>
                                                    </svg>
                                                    {{ $app->farmer->phone ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="space-y-2">
                                            <div
                                                class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 text-indigo-700 dark:text-indigo-300 rounded-lg border border-indigo-200 dark:border-indigo-700">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm font-semibold">{{ $app->season->name }}</span>
                                            </div>
                                            @if ($app->season->type)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                                    {{ $app->season->type }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="space-y-1">
                                            <div
                                                class="text-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                                ₦{{ number_format($app->total_loan, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Total Amount
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-2 text-xs font-bold rounded-xl {{ $statusColors[$app->status] ?? 'bg-gray-100 text-gray-800 border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600' }} group-hover:scale-105 transition-transform duration-300">
                                            {!! $statusIcons[$app->status] ?? '' !!}
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="space-y-1">
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $app->created_at->format('M d, Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $app->created_at->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center space-y-4">
                                            <div
                                                class="p-6 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-3xl">
                                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">No applications
                                                    found</h3>
                                                <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                                                    Try adjusting your search criteria or filters to find the applications
                                                    you're looking for.
                                                </p>
                                            </div>
                                            <button onclick="clearFilters()"
                                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                                Clear All Filters
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Enhanced Pagination -->
                @if ($applications->hasPages())
                    <div
                        class="px-8 py-6 border-t border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-gray-50/50 to-white/50 dark:from-gray-700/50 dark:to-gray-800/50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Page {{ $applications->currentPage() }} of {{ $applications->lastPage() }}
                            </div>
                            <div class="pagination-wrapper">
                                {{ $applications->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Enhanced JavaScript for Interactions -->
    <script>
        // Filter Toggle Functionality
        let filtersExpanded = true;

        function toggleFilters() {
            const filterContent = document.getElementById('filterContent');
            const toggleIcon = document.getElementById('filterToggleIcon');

            filtersExpanded = !filtersExpanded;

            if (filtersExpanded) {
                filterContent.style.maxHeight = filterContent.scrollHeight + 'px';
                filterContent.style.opacity = '1';
                toggleIcon.style.transform = 'rotate(0deg)';
            } else {
                filterContent.style.maxHeight = '0px';
                filterContent.style.opacity = '0';
                toggleIcon.style.transform = 'rotate(-90deg)';
            }
        }

        // Advanced Filters Toggle
        function toggleAdvancedFilters() {
            const filtersSection = document.getElementById('filtersSection');
            filtersSection.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Add a subtle highlight effect
            filtersSection.classList.add('ring-4', 'ring-indigo-200', 'dark:ring-indigo-700');
            setTimeout(() => {
                filtersSection.classList.remove('ring-4', 'ring-indigo-200', 'dark:ring-indigo-700');
            }, 2000);
        }

        // Clear Filters Function
        function clearFilters() {
            window.location.href = "{{ route('admin.reports.applications') }}";
        }

        // Enhanced Form Interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to buttons
            const submitButton = document.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.addEventListener('click', function() {
                    this.innerHTML = `
                        <svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Processing...
                    `;
                });
            }

            // Enhanced hover effects for table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(4px)';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Animate statistics cards on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay =
                            `${Array.from(entry.target.parentNode.children).indexOf(entry.target) * 0.1}s`;
                        entry.target.classList.add('animate-fade-in-up');
                    }
                });
            });

            document.querySelectorAll('.grid > div').forEach(card => {
                observer.observe(card);
            });
        });

        // Add CSS animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fade-in-up {
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
                animation: fade-in-up 0.6s ease-out forwards;
            }

            .pagination-wrapper .pagination {
                display: flex;
                gap: 0.5rem;
                align-items: center;
            }

            .pagination-wrapper .pagination a,
            .pagination-wrapper .pagination span {
                padding: 0.5rem 1rem;
                border-radius: 0.75rem;
                font-weight: 600;
                transition: all 0.3s ease;
                border: 1px solid transparent;
            }

            .pagination-wrapper .pagination a {
                background: linear-gradient(to right, #f8fafc, #f1f5f9);
                color: #64748b;
                border-color: #e2e8f0;
            }

            .pagination-wrapper .pagination a:hover {
                background: linear-gradient(to right, #3b82f6, #6366f1);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }

            .pagination-wrapper .pagination .active span {
                background: linear-gradient(to right, #3b82f6, #6366f1);
                color: white;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }

            .dark .pagination-wrapper .pagination a {
                background: linear-gradient(to right, #374151, #4b5563);
                color: #d1d5db;
                border-color: #4b5563;
            }

            .dark .pagination-wrapper .pagination a:hover {
                background: linear-gradient(to right, #3b82f6, #6366f1);
                color: white;
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
