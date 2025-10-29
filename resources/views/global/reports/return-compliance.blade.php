@extends('layouts.layout')

@push('styles')
<style>
    .compliance-card {
        transition: transform 0.3s ease;
    }
    .compliance-card:hover {
        transform: translateY(-2px);
    }
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .status-good { background-color: #10b981; }
    .status-warning { background-color: #f59e0b; }
    .status-bad { background-color: #ef4444; }
    .data-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .sortable th {
        cursor: pointer;
        user-select: none;
    }
    .sortable th:hover {
        background-color: #f8fafc;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Breadcrumb -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('global.reports.index') }}" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">
                            <i class="fas fa-home mr-2"></i>Reports
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-500 dark:text-gray-400">Return Compliance</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Return Compliance Report</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        <strong>{{ $season->name }}</strong> - Return compliance analysis across all tenants
                    </p>
                </div>
                <div class="flex gap-3">
                    
                    <a href="{{ route('global.reports.index') }}"
                       class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Reports
                    </a>
                </div>
            </div>
        </div>

        @if($reportData && !empty($reportData))
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($reportData) }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tenants Evaluated</p>
                    </div>
                    <div class="text-3xl text-blue-600 dark:text-blue-400">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ collect($reportData)->where('variance', '<=', 0)->count() }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">On/Past Target</p>
                    </div>
                    <div class="text-3xl text-green-600 dark:text-green-400">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ collect($reportData)->where('variance', '>', 0)->count() }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Shortfall</p>
                    </div>
                    <div class="text-3xl text-red-600 dark:text-red-400">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ collect($reportData)->sum('overdue_applications') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Overdue Applications</p>
                    </div>
                    <div class="text-3xl text-orange-600 dark:text-orange-400">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance Table -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-chart-line text-emerald-500 mr-3"></i>
                    Tenant Compliance Details
                </h2>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs rounded-full dark:bg-emerald-900 dark:text-emerald-300">
                    {{ count($reportData) }} tenants
                </span>
            </div>

            <div class="overflow-x-auto data-table">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tenant
                                <i class="fas fa-sort ml-2"></i>
                            </th>
                            <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Expected Returns
                                <i class="fas fa-sort ml-2"></i>
                            </th>
                            <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actual Returns
                                <i class="fas fa-sort ml-2"></i>
                            </th>
                            <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Variance
                                <i class="fas fa-sort ml-2"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Overdue Apps
                                <i class="fas fa-sort ml-2"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Shortfall Reasons
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($reportData as $tenant)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <span class="status-indicator
                                    @if($tenant['variance'] <= 0) status-good
                                     @elseif($tenant['variance'] < $tenant['total_expected_returns'] * 0.2) status-warning
                                     @else status-bad @endif">
                                </span>
                                {{ ucfirst($tenant['tenant_name']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                ${{ number_format($tenant['total_expected_returns'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-medium">
                                ${{ number_format($tenant['total_returned'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($tenant['variance'] > 0)
                                    <span class="text-red-600 dark:text-red-400">
                                        -${{ number_format($tenant['variance'], 2) }}
                                    </span>
                                @elseif($tenant['variance'] < 0)
                                    <span class="text-green-600 dark:text-green-400">
                                        +${{ number_format(abs($tenant['variance']), 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-600 dark:text-gray-400">
                                        $0.00
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tenant['variance'] <= 0)
                                    <span class="status-badge px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full dark:bg-green-900 dark:text-green-300">
                                        <i class="fas fa-check-circle mr-1"></i>On Target
                                    </span>
                                @elseif($tenant['variance'] < $tenant['total_expected_returns'] * 0.5)
                                    <span class="status-badge px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Moderate Gap
                                    </span>
                                @else
                                    <span class="status-badge px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full dark:bg-red-900 dark:text-red-300">
                                        <i class="fas fa-times-circle mr-1"></i>Critical Gap
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $tenant['overdue_applications'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                @if(!empty($tenant['shortfall_reasons']))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($tenant['shortfall_reasons'] as $reason)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded dark:bg-gray-700 dark:text-gray-300">
                                                {{ $reason }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">No shortfalls reported</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Section -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Export Report</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Download this report in various formats for offline analysis</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportReport('csv')"
                            class="export-btn bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 font-medium flex items-center">
                        <i class="fas fa-file-csv mr-2"></i> Export as CSV
                    </button>
                    <button onclick="exportReport('pdf')"
                            class="export-btn bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-medium flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> Export as PDF
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6 text-center">
            <div class="text-4xl text-blue-600 dark:text-blue-400 mb-4">
                <i class="fas fa-info-circle"></i>
            </div>
            <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">No Compliance Data</h3>
            <p class="text-blue-700 dark:text-blue-300">
                No return compliance data is available for this season.
                This could mean no tenants have been allocated to this season yet.
            </p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') html.classList.add('dark');

    darkModeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    });

    // Table sorting
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const column = Array.from(header.parentNode.children).indexOf(header);
            const sortIcon = header.querySelector('i');

            // Toggle sort direction
            const isAscending = sortIcon.classList.contains('fa-sort') || sortIcon.classList.contains('fa-sort-up');
            document.querySelectorAll('.sortable i').forEach(icon => {
                icon.className = 'fas fa-sort ml-2';
            });

            if (isAscending) {
                sortIcon.className = 'fas fa-sort-down ml-2';
            } else {
                sortIcon.className = 'fas fa-sort-up ml-2';
            }

            rows.sort((a, b) => {
                const aVal = a.children[column].textContent.trim();
                const bVal = b.children[column].textContent.trim().replace(/[$,\-+]/g, '');

                // Handle numeric sorting
                const aNum = parseFloat(aVal.replace(/,/g, ''));
                const bNum = parseFloat(bVal.replace(/,/g, ''));

                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return isAscending ? aNum - bNum : bNum - aNum;
                }

                // Handle string sorting
                return isAscending ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            });

            rows.forEach(row => tbody.appendChild(row));
        });
    });

    // Export functionality
    function exportReport(format) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('global.reports.return-compliance.export') }}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const seasonUuid = document.createElement('input');
        seasonUuid.type = 'hidden';
        seasonUuid.name = 'season_uuid';
        seasonUuid.value = '{{ $season->uuid }}';

        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = format;

        form.appendChild(csrfToken);
        form.appendChild(seasonUuid);
        form.appendChild(formatInput);

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    // Progress bars or status indicators animation
    document.addEventListener('DOMContentLoaded', () => {
        const elements = document.querySelectorAll('.status-indicator, .animate-fade-in');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush
@endsection
