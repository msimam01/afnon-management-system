@extends('layouts.layout')

@push('styles')
<style>
    /* Enhanced Card Animations */
    .stat-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.15), 0 10px 20px -5px rgba(0, 0, 0, 0.1);
        border-color: #93c5fd;
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    /* Animated Progress Bar */
    .progress {
        height: 0.5rem;
        border-radius: 9999px;
        background: linear-gradient(90deg, #f3f4f6 0%, #e5e7eb 100%);
        overflow: visible;
        position: relative;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
        border-radius: 9999px;
        position: relative;
        overflow: visible;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.3) 50%,
            rgba(255, 255, 255, 0) 100%);
        animation: shimmer 2s infinite;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        right: -6px;
        top: 50%;
        width: 14px;
        height: 14px;
        background: inherit;
        border-radius: 50%;
        transform: translateY(-50%);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.8), 0 2px 4px rgba(0, 0, 0, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    @keyframes pulse {
        0%, 100% { transform: translateY(-50%) scale(1); }
        50% { transform: translateY(-50%) scale(1.1); }
    }

    /* Enhanced Stats */
    .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: #111827;
        line-height: 1;
        transition: color 0.3s ease;
    }

    .stat-card:hover .stat-value {
        color: #3b82f6;
    }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        margin-top: 0.5rem;
    }

    /* Enhanced Table */
    .table-container {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .table th {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        background: linear-gradient(180deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table th:hover {
        color: #3b82f6;
        transition: color 0.2s ease;
    }

    .table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f3f4f6;
    }

    .table tbody tr:hover {
        background: linear-gradient(90deg, #eff6ff 0%, #f0f9ff 100%);
        transform: scale(1.005);
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
    }

    .table td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
    }

    /* Avatar Enhancement */
    .tenant-avatar {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .tenant-avatar:hover {
        transform: scale(1.1) rotate(3deg);
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* Enhanced Badges */
    .status-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }

    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .badge-success {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #166534;
        border: 1px solid #86efac;
    }

    .badge-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    .badge-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .badge-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    /* Action Buttons */
    .action-btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        border: 1px solid;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .action-btn:active {
        transform: translateY(0);
    }

    .btn-primary-action {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-color: #2563eb;
        color: white;
    }

    .btn-primary-action:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }

    .btn-secondary-action {
        background: white;
        border-color: #d1d5db;
        color: #374151;
    }

    .btn-secondary-action:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    /* Search Bar Enhancement */
    .search-container {
        position: relative;
        transition: all 0.3s ease;
    }

    .search-container:focus-within {
        transform: scale(1.02);
    }

    .search-input {
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem 0.75rem 2.75rem;
    }

    .search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        transition: color 0.3s ease;
    }

    .search-container:focus-within .search-icon {
        color: #3b82f6;
    }

    /* Commodity Tags */
    .commodity-tag {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e40af;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0.125rem;
        border: 1px solid #bfdbfe;
        transition: all 0.2s ease;
    }

    .commodity-tag:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }

    /* Loading States */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    .fade-in-delay-1 { animation-delay: 0.1s; }
    .fade-in-delay-2 { animation-delay: 0.2s; }
    .fade-in-delay-3 { animation-delay: 0.3s; }

    /* Gradient Backgrounds */
    .gradient-bg-blue {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .gradient-bg-green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .gradient-bg-yellow {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .gradient-bg-red {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    /* Sort Indicators */
    .sort-icon {
        opacity: 0.3;
        transition: all 0.2s ease;
    }

    .sort-active .sort-icon {
        opacity: 1;
        color: #3b82f6;
    }

    th:hover .sort-icon {
        opacity: 0.6;
    }

    /* Empty State */
    .empty-state-icon {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Responsive Enhancements */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }

        .table-container {
            border-radius: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 sm:px-6 lg:px-8 py-6">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-blue-50 p-8 rounded-2xl border border-blue-200 mb-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-4xl font-bold leading-tight text-gray-900 flex items-center mb-4">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white mr-4 shadow-xl">
                        <i class="fas fa-boxes text-2xl"></i>
                    </span>
                    <div>
                        <span>Tenant Allocations</span>
                        <p class="text-lg font-normal text-gray-600 mt-1">{{ $season->name }}</p>
                    </div>
                </h1>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ ucfirst($season->type) }}
                    </span>
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold {{ $season->status === 'open' ? 'bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-800 border border-gray-200' }} shadow-sm">
                        <i class="fas fa-{{ $season->status === 'open' ? 'play-circle' : 'pause-circle' }} mr-2"></i>
                        {{ ucfirst($season->status) }}
                    </span>
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-white/50 text-gray-700 border border-gray-200 shadow-sm">
                        <i class="far fa-calendar mr-2"></i>
                        {{ $season->start_date->format('M d, Y') }} - {{ $season->end_date->format('M d, Y') }}
                    </span>
                </div>
            </div>

            <div class="mt-6 md:mt-0 flex flex-col gap-3">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('global.allocations.create', $season->uuid) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i> New Allocation
                    </a>

                    @if($allocations->isNotEmpty())
                    <a href="{{ route('global.allocations.edit-all', $season->uuid) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-lg text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i> Bulk Edit
                    </a>

                    <button onclick="syncAllAllocations()"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        <i class="fas fa-sync mr-2"></i> Sync All
                    </button>
                    @endif
                </div>

                <a href="{{ route('global.seasons.show', $season->uuid) }}"
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Season
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Stock Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($commodityStats as $index => $stat)
        @php
            $percentage = $stat['percentage_allocated'];
            $statusClass = $percentage >= 90 ? 'gradient-bg-red' : ($percentage >= 70 ? 'gradient-bg-yellow' : 'gradient-bg-green');
            $textClass = $percentage >= 90 ? 'text-red-600' : ($percentage >= 70 ? 'text-yellow-600' : 'text-green-600');
            $bgClass = $percentage >= 90 ? 'from-red-50 to-red-100 border-red-200' : ($percentage >= 70 ? 'from-yellow-50 to-yellow-100 border-yellow-200' : 'from-green-50 to-green-100 border-green-200');
            $remainingStock = $stat['total_stock'] - $stat['allocated'];
        @endphp
        <div class="stat-card fade-in fade-in-delay-{{ $index % 4 + 1 }}">
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-cube text-blue-500 mr-2"></i>
                        {{ $stat['name'] }}
                    </h3>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r {{ $bgClass }} {{ $textClass }} border">
                        {{ $percentage }}%
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div class="space-y-2">
                        <p class="stat-value">{{ number_format($stat['total_stock']) }}</p>
                        <p class="stat-label">Total Stock</p>
                    </div>
                    <div class="space-y-2">
                        <p class="stat-value text-blue-600">{{ number_format($stat['allocated']) }}</p>
                        <p class="stat-label">Allocated</p>
                    </div>
                    <div class="space-y-2">
                        <p class="stat-value {{ $remainingStock < 0 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ number_format(abs($remainingStock)) }}
                        </p>
                        <p class="stat-label">{{ $remainingStock < 0 ? 'Overallocated' : 'Remaining' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="stat-value">{{ $stat['tenants_count'] ?? 0 }}</p>
                        <p class="stat-label">Tenants</p>
                    </div>
                </div>

                <div class="progress">
                    <div class="progress-bar {{ $statusClass }}"
                         style="width: {{ min($percentage, 100) }}%">
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Enhanced Tenant Allocations Table -->
    <div class="table-container mb-8 fade-in">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-white mr-3">
                        <i class="fas fa-table"></i>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Tenant Allocations</h3>
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $allocations->count() }} {{ Str::plural('tenant', $allocations->count()) }} allocated
                        </p>
                    </div>
                </div>
                <div class="w-full sm:w-auto search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text"
                           id="searchInput"
                           class="search-input w-full sm:w-64"
                           placeholder="Search tenants...">
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden">
            @if($allocations->isEmpty())
                <div class="text-center py-16 px-4 sm:px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 mb-6 empty-state-icon">
                        <i class="fas fa-inbox text-blue-500 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">No Allocations Found</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">
                        There are no allocations for this season yet. Start by creating a new allocation to assign commodities to tenants.
                    </p>
                    <a href="{{ route('global.allocations.create', $season->uuid) }}"
                       class="action-btn btn-primary-action inline-flex">
                        <i class="fas fa-plus"></i> Create First Allocation
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left cursor-pointer hover:bg-gray-50">
                                    <div class="flex items-center">
                                        <span>Tenant</span>
                                        <i class="sort-icon fas fa-sort ml-2"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left cursor-pointer hover:bg-gray-50">
                                    <div class="flex items-center">
                                        <span>Domain</span>
                                        <i class="sort-icon fas fa-sort ml-2"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left cursor-pointer hover:bg-gray-50">
                                    <div class="flex items-center">
                                        <span>Commodities</span>
                                        <i class="sort-icon fas fa-sort ml-2"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left cursor-pointer hover:bg-gray-50">
                                    <div class="flex items-center">
                                        <span>Total Allocated</span>
                                        <i class="sort-icon fas fa-sort ml-2"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left cursor-pointer hover:bg-gray-50">
                                    <div class="flex items-center">
                                        <span>Last Synced</span>
                                        <i class="sort-icon fas fa-sort ml-2"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-right">
                                    <span>Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($allocations as $allocationData)
                            @php
                                $tenant = $allocationData->tenant;
                                $totalStock = $allocationData->total_stock;
                                $lastSync = $allocationData->last_sync;
                                $commoditiesCount = $allocationData->commodities_count;
                                $isSynced = $lastSync !== null;
                                $tenantAllocations = $allocationData->allocations;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <img class="tenant-avatar"
                                                 src="{{ $tenant->logo_url ?? asset('images/default-tenant.png') }}"
                                                 alt="{{ ucfirst($tenant->id) }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ ucfirst($tenant->id) }}</div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $tenant->domain }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $tenant->domain }}</div>
                                    <span class="status-badge badge-{{ $tenant->is_active ? 'success' : 'danger' }} mt-1">
                                        <i class="fas fa-{{ $tenant->status ? 'check-circle' : 'times-circle' }}"></i>
                                        {{ $tenant->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 mb-2">
                                        {{ $commoditiesCount }} {{ Str::plural('commodity', $commoditiesCount) }}
                                    </div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($tenantAllocations->take(3) as $allocation)
                                            @php
                                                $commodityName = $allocation->commodity->name ?? 'Unknown';
                                                $allocatedStock = $allocation->allocated_stock ?? 0;
                                                $unit = $allocation->commodity->unit ?? '';
                                            @endphp
                                            <span class="commodity-tag" title="{{ $commodityName }}: {{ number_format($allocatedStock) }} {{ $unit }}">
                                                {{ $commodityName }}
                                            </span>
                                        @endforeach
                                        @if($commoditiesCount > 3)
                                            <span class="commodity-tag">
                                                +{{ $commoditiesCount - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ number_format($totalStock) }}</div>
                                    <div class="text-xs text-gray-500">units allocated</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($lastSync)
                                        @php
                                            $lastSyncDate = \Carbon\Carbon::parse($lastSync);
                                        @endphp
                                        <div class="text-sm font-medium text-gray-900">{{ $lastSyncDate->diffForHumans() }}</div>
                                        <div class="text-xs text-gray-500">{{ $lastSyncDate->format('M d, Y h:i A') }}</div>
                                    @else
                                        <span class="status-badge badge-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Never Synced
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        @if(!$isSynced)
                                        <button type="button"
                                                class="action-btn bg-blue-50 border-blue-200 text-blue-700 hover:bg-blue-100"
                                                onclick="syncTenant('{{ $tenantAllocations->first()->id ?? '' }}')"
                                                title="Sync Now">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                        @endif
                                        @if($tenantAllocations->isNotEmpty())
                                        <a href="{{ route('global.allocations.edit', ['seasonUuid' => $season->uuid, 'tenantId' => $tenantAllocations->first()->tenant_id]) }}"
                                           class="action-btn bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn bg-red-50 border-red-200 text-red-700 hover:bg-red-100"
                                                onclick="confirmDelete('{{ $tenantAllocations->first()->tenant_id }}', '{{ $tenant->id }}')"
                                                title="Delete allocation from this tenant">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($paginator->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($paginator->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-400 bg-gray-50 cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $paginator->previousPageUrl() }}" class="action-btn btn-secondary-action">
                                Previous
                            </a>
                        @endif

                        @if($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}" class="action-btn btn-secondary-action">
                                Next
                            </a>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-400 bg-gray-50 cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700 font-medium">
                                Showing
                                <span class="font-bold text-gray-900">{{ $paginator->firstItem() }}</span>
                                to
                                <span class="font-bold text-gray-900">{{ $paginator->lastItem() }}</span>
                                of
                                <span class="font-bold text-gray-900">{{ $paginator->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            {{ $paginator->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>


@endsection

@push('scripts')
<!-- Delete Confirmation Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden" id="deleteModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-3">Delete Allocation</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete the allocation for <span id="tenantName" class="font-medium"></span>?
                    This action cannot be undone and will also remove the allocation from the tenant's database.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="deleteConfirmBtn" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete
                </button>
                <button id="deleteCancelBtn" class="ml-3 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
         // Dark Mode Toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') html.classList.add('dark');

    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            const tableBody = document.querySelector('tbody');
            const rows = Array.from(tableBody.getElementsByTagName('tr'));

            // Debounce search input
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.toLowerCase().trim();

                searchTimeout = setTimeout(() => {
                    rows.forEach((row, index) => {
                        const text = row.textContent.toLowerCase();
                        const isMatch = text.includes(searchTerm);

                        if (isMatch) {
                            row.style.display = '';
                            row.style.animation = `fadeIn 0.3s ease-out ${index * 0.05}s both`;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show empty state if no results
                    const visibleRows = rows.filter(row => row.style.display !== 'none');
                    if (visibleRows.length === 0 && searchTerm) {
                        // Could add a "no results" message here
                        console.log('No results found');
                    }
                }, 300);
            });
        }

        // Enhanced Sort functionality
        let sortColumn = null;
        let sortDirection = 1;
        const tableBody = document.querySelector('tbody');
        const rows = tableBody ? Array.from(tableBody.getElementsByTagName('tr')) : [];

        function sortTable(columnIndex, th) {
            const newDirection = (sortColumn === columnIndex) ? -sortDirection : 1;
            sortDirection = newDirection;
            sortColumn = columnIndex;

            rows.sort((a, b) => {
                const aCell = a.cells[columnIndex];
                const bCell = b.cells[columnIndex];

                if (!aCell || !bCell) return 0;

                const aVal = aCell.textContent.trim();
                const bVal = bCell.textContent.trim();

                // Try numeric comparison
                const aNum = parseFloat(aVal.replace(/,/g, ''));
                const bNum = parseFloat(bVal.replace(/,/g, ''));

                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return (aNum - bNum) * sortDirection;
                }

                // String comparison
                return aVal.localeCompare(bVal) * sortDirection;
            });

            // Re-append with animation
            rows.forEach((row, index) => {
                row.style.animation = `fadeIn 0.3s ease-out ${index * 0.03}s both`;
                tableBody.appendChild(row);
            });

            updateSortIndicators(columnIndex, th);
        }

        function updateSortIndicators(columnIndex, activeTh) {
            // Remove all sort indicators
            document.querySelectorAll('th').forEach(th => {
                th.classList.remove('sort-active');
                const sortIcon = th.querySelector('.sort-icon');
                if (sortIcon) {
                    sortIcon.className = 'sort-icon fas fa-sort ml-2';
                }
            });

            // Add active state
            if (activeTh) {
                activeTh.classList.add('sort-active');
                const sortIcon = activeTh.querySelector('.sort-icon');
                if (sortIcon) {
                    sortIcon.className = `sort-icon fas fa-sort-${sortDirection > 0 ? 'up' : 'down'} ml-2`;
                }
            }
        }

        // Make table headers sortable
        document.querySelectorAll('th').forEach((th, index) => {
            const sortIcon = th.querySelector('.sort-icon');
            if (sortIcon) {
                th.addEventListener('click', () => {
                    sortTable(index, th);
                });
            }
        });

        // Initialize tooltips if Bootstrap is available
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Animate stats on load
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                requestAnimationFrame(() => {
                    card.style.transition = 'all 0.5s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            }, index * 100);
        });

        // Animate progress bars
        const progressBars = document.querySelectorAll('.progress-bar');
        setTimeout(() => {
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        }, 500);
    });

    // Delete Allocation Function
    (function() {
        let currentAllocationId = null;

        window.confirmDelete = function(allocationId, tenantName) {
            currentAllocationId = allocationId;
            document.getElementById('tenantName').textContent = tenantName;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');

            // Set up event listeners for the modal buttons
            document.getElementById('deleteConfirmBtn').onclick = deleteAllocation;
            document.getElementById('deleteCancelBtn').onclick = closeDeleteModal;
        };

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            currentAllocationId = null;
        }

        function deleteAllocation() {
            if (!currentAllocationId) return;

            const deleteBtn = document.getElementById('deleteConfirmBtn');
            const originalText = deleteBtn.innerHTML;

            // Show loading state
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

            // Get the season UUID from the URL
            // URL format: /global/seasons/{seasonUuid}/allocations
            const pathParts = window.location.pathname.split('/');
            // pathParts = ['', 'global', 'seasons', '{seasonUuid}', 'allocations']
            // seasonUuid is at index 3 (0-based)
            const seasonUuid = pathParts[3];

            // Build the correct URL
            const url = `/global/seasons/${seasonUuid}/allocations/tenants/${currentAllocationId}`;

            // Send DELETE request
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast('success', data.message || 'Allocation deleted and synced successfully');

                    // Close the modal
                    closeDeleteModal();

                    // Reload the page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to delete allocation');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', error.message || 'Failed to delete allocation');
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = originalText;
            });
        }
    })();

    // Show toast notification
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Remove toast after 5 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 5000);
    }

    // Enhanced Sync Function
    function syncTenant(allocationId) {
        const modal = document.getElementById('syncModal');
        const progressBar = document.getElementById('syncProgress');
        const statusText = document.getElementById('syncStatus');

        if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modal).modal('show');
        }

        // Simulate sync progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 100) progress = 100;

            progressBar.style.width = progress + '%';

            if (progress < 30) {
                statusText.textContent = 'Connecting to tenant...';
            } else if (progress < 60) {
                statusText.textContent = 'Transferring data...';
            } else if (progress < 90) {
                statusText.textContent = 'Finalizing sync...';
            } else {
                statusText.textContent = 'Sync complete!';
                clearInterval(interval);
                setTimeout(() => {
                    if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modal).modal('hide');
                    }
                    location.reload();
                }, 1000);
            }
        }, 200);

        // Actual sync request would go here
        // fetch('/api/sync-allocation/' + allocationId, { method: 'POST' })...
    }

    // Enhanced Delete Function
    function deleteAllocation(allocationId) {
        if (confirm('Are you sure you want to delete this allocation? This action cannot be undone.')) {
            // Delete request would go here
            console.log('Deleting allocation:', allocationId);
            // fetch('/api/delete-allocation/' + allocationId, { method: 'DELETE' })...
        }
    }

    // Sync All Allocations Function
    function syncAllAllocations() {
        const modal = document.getElementById('syncModal');
        const progressBar = document.getElementById('syncProgress');
        const statusText = document.getElementById('syncStatus');
        const btn = document.querySelector('[onclick="syncAllAllocations()"]');

        if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modal).modal('show');
        }

        // Get the season UUID from the URL
        // URL format: /global/seasons/{seasonUuid}/allocations
        const pathParts = window.location.pathname.split('/');
        // pathParts = ['', 'global', 'seasons', '{seasonUuid}', 'allocations']
        // seasonUuid is at index 3 (0-based)
        const seasonUuid = pathParts[3];

        // Disable the button
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Syncing...';
        }

        // Send sync all request
        fetch(`/global/seasons/${seasonUuid}/allocations/sync-all`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusText.textContent = 'Sync completed successfully! Reloading...';
                progressBar.style.width = '100%';

                setTimeout(() => {
                    if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modal).modal('hide');
                    }
                    location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Sync failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            statusText.textContent = 'Sync failed: ' + error.message;
            progressBar.style.width = '100%';
            progressBar.classList.remove('gradient-bg-blue');
            progressBar.classList.add('bg-red-500');
        })
        .finally(() => {
            // Re-enable the button
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync mr-2"></i> Sync All';
            }
        });
    }
</script>
@endpush
