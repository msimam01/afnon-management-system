@extends('layouts.layout')

@section('content')
<div id="dashboard-section" class="px-4 py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Super Admin Dashboard</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Overview of tenants, farmers, and applications</p>
        </div>
        <div class="flex items-center gap-3">
            <input id="tenantSearch" type="text" placeholder="Search tenants..." class="px-3 py-2 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-200 shadow-sm" />
            <button onclick="window.print()" class="px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">Export / Print</button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Tenants -->
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-wide opacity-90">Total Tenants</p>
                    <h3 class="text-3xl font-extrabold mt-1">{{ number_format($totalTenants ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <!-- simple svg icon -->
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a4 4 0 004 4h10"/></svg>
                </div>
            </div>
            <div class="absolute right-4 bottom-2 opacity-30 text-xs">Tenants</div>
        </div>

        <!-- Total Farmers -->
        <div class="relative overflow-hidden rounded-2xl p-6 bg-white dark:bg-gray-800 shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Farmers</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($totalFarmers ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-400">Across all tenants</p>
        </div>

        <!-- Applications -->
        <div class="relative overflow-hidden rounded-2xl p-6 bg-white dark:bg-gray-800 shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Applications</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($totalApplications ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m4-4H8"/></svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-400">Submitted by farmers</p>
        </div>

        <!-- Approved -->
        <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-wide opacity-90">Approved Applications</p>
                    <h3 class="text-3xl font-extrabold mt-1">{{ number_format($totalApproved ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                </div>
            </div>
            <div class="absolute right-4 bottom-2 opacity-30 text-xs">Approved</div>
        </div>

        <!-- Collected -->
        <div class="relative overflow-hidden rounded-2xl p-6 bg-white dark:bg-gray-800 shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Collected</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($totalCollected ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-yellow-50 dark:bg-yellow-900 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18"/></svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-400">Verified collections</p>
        </div>

        <!-- Returned -->
        <div class="relative overflow-hidden rounded-2xl p-6 bg-white dark:bg-gray-800 shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Returned</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($totalReturned ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-900 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12H3"/></svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-400">Verified returns</p>
        </div>
    </div>

    <!-- Charts & Tenant Table -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Top States by Farmers</h4>
            <canvas id="farmersChart" height="220"></canvas>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Collected vs Returned</h4>
            <canvas id="collectedReturnedChart" height="220"></canvas>
        </div>

        <!-- Full width tenant table below charts -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tenants</h4>
                <span class="text-sm text-gray-500 dark:text-gray-400">Showing {{ count($tenantRows) }} tenants</span>
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
                        @foreach($tenantRows as $row)
                        <tr class="border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800 dark:text-gray-100">{{ $row['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $row['id'] }}</div>
                            </td>
                            <td class="px-4 py-3">{{ number_format($row['farmers']) }}</td>
                            <td class="px-4 py-3">{{ number_format($row['applications']) }}</td>
                            <td class="px-4 py-3 text-green-600 dark:text-green-300">{{ number_format($row['approved']) }}</td>
                            <td class="px-4 py-3 text-yellow-600 dark:text-yellow-300">{{ number_format($row['collected']) }}</td>
                            <td class="px-4 py-3 text-red-600 dark:text-red-300">{{ number_format($row['returned']) }}</td>
                        </tr>
                        @endforeach
                        @if(count($tenantRows) === 0)
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="6">No tenants found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Simple client-side tenant search
    document.getElementById('tenantSearch').addEventListener('input', function(e) {
        const q = e.target.value.toLowerCase();
        const rows = Array.from(document.querySelectorAll('#tenantTable tbody tr'));
        rows.forEach(r => {
            const name = r.querySelector('td')?.innerText.toLowerCase() || '';
            r.style.display = name.includes(q) ? '' : 'none';
        });
    });

    // Charts data (passed from controller)
    const stateLabels = @json($tenantGrowthLabels ?? ['No Data']);
    const stateData = @json($tenantGrowthData ?? [0]);

    // Farmers by State (bar)
    const farmersCtx = document.getElementById('farmersChart').getContext('2d');
    new Chart(farmersCtx, {
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

    // Collected vs Returned (pie)
    const collected = {{ $totalCollected ?? 0 }};
    const returned = {{ $totalReturned ?? 0 }};
    const crCtx = document.getElementById('collectedReturnedChart').getContext('2d');
    new Chart(crCtx, {
        type: 'doughnut',
        data: {
            labels: ['Collected', 'Returned'],
            datasets: [{
                data: [collected, returned],
                backgroundColor: ['rgba(34,197,94,0.9)', 'rgba(220,38,38,0.9)'],
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
