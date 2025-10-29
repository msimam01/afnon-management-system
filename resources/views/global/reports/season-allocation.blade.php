@extends('layouts.layout')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
    }
    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        transition: transform 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
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
    .export-btn {
        transition: all 0.3s ease;
    }
    .export-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .progress-bar {
        height: 8px;
        border-radius: 4px;
        transition: width 0.5s ease;
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
                            <span class="text-gray-500 dark:text-gray-400">Season Allocation</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Season Allocation Report</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        <strong>{{ $season->name }}</strong> - Comprehensive allocation overview across all tenants
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

        <!-- Key Metrics -->
        @if($reportData)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">${{ number_format($reportData['budget'] ?? 0, 0) }}</h3>
                        <p class="text-sm opacity-90">Season Budget</p>
                    </div>
                    <div class="text-4xl opacity-75">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($reportData['commodities']) }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Commodities</p>
                    </div>
                    <div class="text-3xl text-blue-600 dark:text-blue-400">
                        <i class="fas fa-cube"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($reportData['tenants']) }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Allocated Tenants</p>
                    </div>
                    <div class="text-3xl text-green-600 dark:text-green-400">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format(collect($reportData['commodities'])->avg('percentage_allocated'), 1) }}%</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Avg Allocation</p>
                    </div>
                    <div class="text-3xl text-purple-600 dark:text-purple-400">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4 mb-8">
            <div class="flex items-center">
                <div class="text-2xl text-red-600 dark:text-red-400 mr-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">Report Data Unavailable</h3>
                    <p class="text-red-700 dark:text-red-300">The season allocation data could not be generated. Please try again later or contact support if the issue persists.</p>
                </div>
            </div>
        </div>
        @endif

        @if($reportData)
        <!-- Chart vs Table Toggle -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-chart-line text-emerald-500 mr-3"></i>
                    Commodities Overview
                </h2>
                <div class="flex gap-2">
                    <button id="viewChartBtn"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                        <i class="fas fa-chart-bar mr-2"></i> Chart View
                    </button>
                    <button id="viewTableBtn"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-table mr-2"></i> Table View
                    </button>
                </div>
            </div>

            <!-- Chart Container -->
            <div id="chartContainer" class="chart-container mb-4">
                <canvas id="seasonAllocationChart"></canvas>
            </div>

            <!-- Table Container -->
            <div id="tableContainer" class="hidden">
                <div class="overflow-x-auto data-table">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Commodity
                                    <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Category
                                    <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Unit
                                    <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Total Stock
                                    <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Allocated
                                    <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Remaining
                                    <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Allocation %
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($reportData['commodities'] as $commodity)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $commodity['name'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $commodity['category'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $commodity['unit'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($commodity['total_stock'] + $commodity['allocated']) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-medium">
                                    {{ number_format($commodity['allocated']) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($commodity['total_stock']) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-2 max-w-[100px]">
                                            <div class="bg-emerald-600 h-2.5 rounded-full progress-bar" style="width: {{ $commodity['percentage_allocated'] }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $commodity['percentage_allocated'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tenants Allocations -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-building text-blue-500 mr-3"></i>
                    Tenant Allocations
                </h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full dark:bg-blue-900 dark:text-blue-300">
                    {{ count($reportData['tenants']) }} tenants
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($reportData['tenants'] as $tenant)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">{{ ucfirst($tenant['tenant_name']) }}</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $tenant['last_sync'] ? \Carbon\Carbon::parse($tenant['last_sync'])->diffForHumans() : 'Never' }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @foreach($tenant['allocations'] as $allocation)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-400">{{ $allocation['commodity'] }}:</span>
                            <span class="font-medium text-blue-600 dark:text-blue-400">{{ number_format($allocation['allocated_stock']) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
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
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') html.classList.add('dark');

    darkModeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        // Recreate chart for theme change
        if (window.allocationChart) {
            window.allocationChart.destroy();
        }
        createChart();
    });

    // View toggle buttons
    const viewChartBtn = document.getElementById('viewChartBtn');
    const viewTableBtn = document.getElementById('viewTableBtn');
    const chartContainer = document.getElementById('chartContainer');
    const tableContainer = document.getElementById('tableContainer');

    viewChartBtn.addEventListener('click', () => {
        chartContainer.classList.remove('hidden');
        tableContainer.classList.add('hidden');
        viewChartBtn.className = 'px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition';
        viewTableBtn.className = 'px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition';
        if (window.allocationChart) window.allocationChart.resize();
    });

    viewTableBtn.addEventListener('click', () => {
        chartContainer.classList.add('hidden');
        tableContainer.classList.remove('hidden');
        viewTableBtn.className = 'px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition';
        viewChartBtn.className = 'px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition';
    });

    // Chart.js implementation
    let allocationChart;

    @if($reportData)
    function createChart() {
        const ctx = document.getElementById('seasonAllocationChart').getContext('2d');
        const isDark = html.classList.contains('dark');

        const commodities = @json($reportData['commodities']);
        const labels = commodities.map(c => c.name);
        const allocatedData = commodities.map(c => c.allocated);
        const remainingData = commodities.map(c => c.remaining);
        const totalData = commodities.map(c => c.total_stock);

        allocationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Stock',
                        data: totalData,
                        backgroundColor: isDark ? 'rgba(156, 163, 175, 0.8)' : 'rgba(209, 213, 219, 0.8)',
                        borderColor: isDark ? 'rgba(156, 163, 175, 1)' : 'rgba(209, 213, 219, 1)',
                        borderWidth: 1,
                        stack: 'Stack 0'
                    },
                    {
                        label: 'Allocated Stock',
                        data: allocatedData,
                        backgroundColor: isDark ? 'rgba(59, 130, 246, 0.8)' : 'rgba(59, 130, 246, 0.8)',
                        borderColor: isDark ? 'rgba(59, 130, 246, 1)' : 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        stack: 'Stack 1'
                    },
                    {
                        label: 'Remaining Stock',
                        data: remainingData,
                        backgroundColor: isDark ? 'rgba(34, 197, 94, 0.8)' : 'rgba(34, 197, 94, 0.8)',
                        borderColor: isDark ? 'rgba(34, 197, 94, 1)' : 'rgba(34, 197, 94, 1)',
                        borderWidth: 1,
                        stack: 'Stack 1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Season Commodity Allocation Overview',
                        color: isDark ? '#ffffff' : '#1f2937',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            color: isDark ? '#d1d5db' : '#374151'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: isDark ? 'rgba(31, 41, 55, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                        titleColor: isDark ? '#ffffff' : '#1f2937',
                        bodyColor: isDark ? '#d1d5db' : '#374151',
                        borderColor: isDark ? '#374151' : '#d1d5db',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            color: isDark ? '#d1d5db' : '#374151'
                        },
                        grid: {
                            color: isDark ? 'rgba(75, 85, 99, 0.2)' : 'rgba(209, 213, 219, 0.2)'
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            color: isDark ? '#d1d5db' : '#374151',
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        },
                        grid: {
                            color: isDark ? 'rgba(75, 85, 99, 0.2)' : 'rgba(209, 213, 219, 0.2)'
                        },
                        title: {
                            display: true,
                            text: 'Quantity',
                            color: isDark ? '#d1d5db' : '#374151'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    // Initialize chart
    document.addEventListener('DOMContentLoaded', createChart);
    @endif

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
                const bVal = b.children[column].textContent.trim();

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
        form.action = `{{ route('global.reports.season-allocation.export') }}`;

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

    // Progress bars animation
    document.addEventListener('DOMContentLoaded', () => {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });
</script>
@endpush
@endsection
