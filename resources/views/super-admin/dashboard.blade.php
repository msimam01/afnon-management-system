@extends('layouts.layout')

@section('content')
<div id="dashboard-section" class="px-4 py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Page Heading -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Super Admin Dashboard</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Overview of tenants, farmers, and applications</p>
        </div>

        {{-- Filters / Export --}}
        <form id="filterForm" method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
            <input name="q" value="{{ $q ?? '' }}" placeholder="Search tenants..." class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-200 shadow-sm" />
            <select name="per_page" onchange="document.getElementById('filterForm').submit()" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-200">
                <option value="5" {{ ($perPage ?? 10) == 5 ? 'selected' : '' }}>5 / page</option>
                <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10 / page</option>
                <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25 / page</option>
                <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50 / page</option>
            </select>
            <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm shadow hover:bg-blue-700 transition">Filter</button>

            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->query(), ['export' => 'csv'])) }}" class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700 transition">Export CSV</a>
        </form>
    </div>

    <!-- Stats Cards (minimal shape + subtle gradients/shadows) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tenants -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition p-5 ring-1 ring-transparent hover:ring-indigo-100 dark:hover:ring-indigo-900">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-200" fill="currentColor" viewBox="0 0 20 20"><path d="M3 7a1 1 0 011-1h12a1 1 0 011 1v7a1 1 0 01-1 1H4a1 1 0 01-1-1V7z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Tenants</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalTenants ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">Registered tenant accounts</div>
        </div>

        <!-- Total Farmers -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition p-5 ring-1 ring-transparent hover:ring-green-100 dark:hover:ring-green-900">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-200" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a3 3 0 100 6 3 3 0 000-6zM4 14a4 4 0 018 0H4z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Farmers</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalFarmers ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">Across all tenants</div>
        </div>

        <!-- Total Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition p-5 ring-1 ring-transparent hover:ring-blue-100 dark:hover:ring-blue-900">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-200" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2h14V5a2 2 0 00-2-2H5zM3 9v5a2 2 0 002 2h10a2 2 0 002-2V9H3z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Applications</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalApplications ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">Submitted by farmers</div>
        </div>

        <!-- Approved Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition p-5 ring-1 ring-transparent hover:ring-emerald-100 dark:hover:ring-emerald-900">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full flex items-center justify-center bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900 dark:to-emerald-800">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-200" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414L8.414 15l-4.121-4.121a1 1 0 011.414-1.414L8.414 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Approved Applications</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalApproved ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">Approved by centers</div>
        </div>
    </div>

    <!-- Second row: Collected + Returned (aligned with same look) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition p-5 ring-1 ring-transparent hover:ring-yellow-100 dark:hover:ring-yellow-900">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full flex items-center justify-center bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-200" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2H2V6zM2 10h16v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Collected</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalCollected ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">Verified collections</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition p-5 ring-1 ring-transparent hover:ring-red-100 dark:hover:ring-red-900">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-200" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 016 6v5a3 3 0 01-3 3H7a3 3 0 01-3-3V8a6 6 0 016-6z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Returned</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalReturned ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">Verified returns</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="px-2 py-1 border-b border-gray-200 dark:border-gray-700 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Farmers by State</h3>
            </div>
            <canvas id="farmersChart" height="220"></canvas>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="px-2 py-1 border-b border-gray-200 dark:border-gray-700 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Collected vs Returned</h3>
            </div>
            <canvas id="collectedReturnedChart" height="220"></canvas>
        </div>
    </div>

    <!-- Tenant Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Tenants</h4>
            <span class="text-sm text-gray-500 dark:text-gray-400">Showing {{ $tenantPaginator->total() }} tenants</span>
        </div>

        <div class="overflow-x-auto">
            <table id="tenantTable" class="w-full text-left table-auto">
                <thead>
                    <tr class="text-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-4 py-3">Tenant</th>
                        <th class="px-4 py-3">Farmers</th>
                        <th class="px-4 py-3">Applications</th>
                        <th class="px-4 py-3">Approved</th>
                        <th class="px-4 py-3">Collected</th>
                        <th class="px-4 py-3">Returned</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenantPaginator->items() as $row)
                        <tr class="border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $row['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $row['id'] }}</div>
                            </td>
                            <td class="px-4 py-3">{{ number_format($row['farmers']) }}</td>
                            <td class="px-4 py-3">{{ number_format($row['applications']) }}</td>
                            <td class="px-4 py-3 text-green-600 dark:text-green-300">{{ number_format($row['approved']) }}</td>
                            <td class="px-4 py-3 text-yellow-600 dark:text-yellow-300">{{ number_format($row['collected']) }}</td>
                            <td class="px-4 py-3 text-red-600 dark:text-red-300">{{ number_format($row['returned']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="6">No tenants found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Showing <strong>{{ $tenantPaginator->firstItem() ?? 0 }}</strong> to <strong>{{ $tenantPaginator->lastItem() ?? 0 }}</strong> of <strong>{{ $tenantPaginator->total() }}</strong> tenants
            </div>

            <div class="flex items-center space-x-2">
                @if($tenantPaginator->currentPage() > 1)
                    <a href="{{ $tenantPaginator->url($tenantPaginator->currentPage() - 1) }}" class="px-3 py-1 bg-white dark:bg-gray-700 border rounded text-sm">Prev</a>
                @endif

                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded text-sm">{{ $tenantPaginator->currentPage() }}</span>

                @if($tenantPaginator->currentPage() < $tenantPaginator->lastPage())
                    <a href="{{ $tenantPaginator->url($tenantPaginator->currentPage() + 1) }}" class="px-3 py-1 bg-white dark:bg-gray-700 border rounded text-sm">Next</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart data from controller (safe fallbacks)
    const stateLabels = @json($tenantGrowthLabels ?? ['No Data']);
    const stateData = @json($tenantGrowthData ?? [0]);

    // Farmers by State (bar)
    new Chart(document.getElementById('farmersChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: stateLabels,
            datasets: [{
                label: 'Farmers',
                data: stateData,
                backgroundColor: stateData.map(() => 'rgba(37,99,235,0.85)'),
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Collected vs Returned (doughnut)
    const collected = {{ $totalCollected ?? 0 }};
    const returned = {{ $totalReturned ?? 0 }};
    new Chart(document.getElementById('collectedReturnedChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Collected', 'Returned'],
            datasets: [{
                data: [collected, returned],
                backgroundColor: ['rgba(34,197,94,0.9)', 'rgba(220,38,38,0.9)'],
                hoverOffset: 8
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush
