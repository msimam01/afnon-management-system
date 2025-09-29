@extends('layouts.layout')

@section('content')
<div class="w-full px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Season Reports & Analytics</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Comprehensive insights and tracking for each season</p>
                </div>
            </div>
        </div>

        <!-- Seasons Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($seasons as $season)
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Season Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 {{ $season->loan_type === 'co-funded' ? 'bg-blue-500' : 'bg-green-500' }} rounded-lg flex items-center justify-center">
                                    @if($season->loan_type === 'co-funded')
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $season->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('-', ' ', $season->loan_type)) }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($season->status === 'open') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($season->status === 'closed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                {{ ucfirst($season->status) }}
                            </span>
                        </div>

                        <!-- Season Statistics -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Applications</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($season->applications_count) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Approved Applications</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($season->approved_applications_count) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Approval Rate</span>
                                <span class="text-sm font-semibold {{ $season->applications_count > 0 && ($season->approved_applications_count / $season->applications_count) > 0.7 ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $season->applications_count > 0 ? number_format(($season->approved_applications_count / $season->applications_count) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>

                        <!-- Season Dates -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Season Period</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($season->start_date)->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($season->end_date)->format('M d, Y') }}
                            </div>
                            @if($season->loan_type === 'complete-loan' && $season->return_deadline)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    Return Deadline: {{ \Carbon\Carbon::parse($season->return_deadline)->format('M d, Y') }}
                                </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('admin.reports.seasons.show', $season) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            View Detailed Report
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No seasons found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new season.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection