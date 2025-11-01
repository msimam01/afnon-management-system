@extends('layouts.layout')

@push('styles')
    <style>
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .progress-bar-custom {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            transition: width 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-8">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('global.reports.index') }}"
                                class="text-gray-700 hover:text-blue-600 dark:text-gray-300">
                                <i class="fas fa-home mr-2"></i>Reports
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-gray-500 dark:text-gray-400">Tenant Distribution</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Tenant Distribution Report</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            <strong>{{ $season->name }}</strong> - Distribution details for
                            <strong>{{ ucfirst($tenant->id) }}</strong>
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

            @if ($reportData)
                <!-- Loan Type Badge -->
                <div class="mb-6">
                    <span
                        class="px-4 py-2 rounded-full text-sm font-medium
                {{ $reportData['loan_type'] === 'co-funded' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300' : 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300' }}">
                        {{ ucfirst(str_replace('-', ' ', $reportData['loan_type'])) }} Season
                    </span>
                </div>

                <!-- Summary Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $reportData['summary']['total_farmers'] }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Farmers</p>
                            </div>
                            <div class="text-3xl text-blue-600 dark:text-blue-400">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $reportData['summary']['farmers_collected'] }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Collected</p>
                            </div>
                            <div class="text-3xl text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                    @if ($reportData['loan_type'] === 'complete-loan')
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $reportData['summary']['farmers_returned'] ?? 0 }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Returned</p>
                                </div>
                                <div class="text-3xl text-purple-600 dark:text-purple-400">
                                    <i class="fas fa-undo-alt"></i>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ count($reportData['commodity_distribution']) }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Commodities</p>
                            </div>
                            <div class="text-3xl text-amber-600 dark:text-amber-400">
                                <i class="fas fa-boxes"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commodity Distribution Table -->
                @if ($reportData['commodity_distribution'])
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center">
                                <i class="fas fa-chart-bar text-emerald-500 mr-3"></i>
                                Commodity Distribution Summary
                            </h2>
                        </div>

                        <div class="overflow-x-auto data-table">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Commodity
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Remaining
                                        </th>
                                        @if ($reportData['loan_type'] === 'complete-loan')
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Returned
                                            </th>
                                        @endif
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($reportData['commodity_distribution'] as $commodity)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $commodity['commodity_name'] }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    Unit: {{ $commodity['unit'] }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                                    {{ number_format($commodity['original_allocated'], 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ number_format($commodity['approved_quantity'], 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="text-sm font-medium text-purple-600 dark:text-purple-400">
                                                    {{ number_format($commodity['distributed_quantity'], 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                                    {{ number_format($commodity['collected_quantity'], 2) }}
                                                </span>
                                                @if ($commodity['collection_variance'] != 0)
                                                    <div
                                                        class="text-xs {{ $commodity['collection_variance'] > 0 ? 'text-orange-500' : 'text-red-500' }}">
                                                        Variance: {{ number_format($commodity['collection_variance'], 2) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    {{ number_format($commodity['remaining_stock'], 2) }}
                                                </span>
                                            </td>
                                            @if ($reportData['loan_type'] === 'complete-loan')
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    @if (isset($commodity['return_metrics']))
                                                        <div
                                                            class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                            {{ number_format($commodity['return_metrics']['actual_returned'], 2) }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            Expected:
                                                            {{ number_format($commodity['return_metrics']['expected_return'], 2) }}
                                                        </div>
                                                        @if ($commodity['return_metrics']['return_variance'] != 0)
                                                            <div
                                                                class="text-xs {{ $commodity['return_metrics']['return_variance'] > 0 ? 'text-red-500' : 'text-green-500' }}">
                                                                Variance:
                                                                {{ number_format(abs($commodity['return_metrics']['return_variance']), 2) }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span class="text-xs text-gray-400">N/A</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $collectionRate =
                                                        $commodity['distributed_quantity'] > 0
                                                            ? ($commodity['collected_quantity'] /
                                                                    $commodity['distributed_quantity']) *
                                                                100
                                                            : 0;
                                                @endphp
                                                <div class="progress-bar-custom w-24 mx-auto">
                                                    <div class="progress-fill"
                                                        style="width: {{ min($collectionRate, 100) }}%"></div>
                                                </div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ number_format($collectionRate, 1) }}%
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Farmer Distribution Table -->
                <!-- Farmer Distribution Table -->
                <!-- Farmer Distribution Table -->
                @if ($reportData['farmer_distributions'])
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center">
                                <i class="fas fa-users text-blue-500 mr-3"></i>
                                Farmer-Level Distribution
                            </h2>
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full dark:bg-blue-900 dark:text-blue-300">
                                {{ count($reportData['farmer_distributions']) }} farmers
                            </span>
                        </div>

                        <div class="overflow-x-auto data-table">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Farmer
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Commodity
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Payment
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Allocated Qty
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Collected Qty
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Collection Rate
                                        </th>
                                        @if ($reportData['loan_type'] === 'complete-loan')
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Expected Return
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Returned Qty
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Variance
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($reportData['farmer_distributions'] as $farmer)
                                        @foreach ($farmer['commodities'] as $index => $commodity)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                @if ($index === 0)
                                                    <td class="px-6 py-4 whitespace-nowrap"
                                                        rowspan="{{ count($farmer['commodities']) + 1 }}">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $farmer['farmer_name'] }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $farmer['registration_number'] }}
                                                        </div>
                                                        @if (
                                                            $reportData['loan_type'] === 'complete-loan' &&
                                                                isset($farmer['return_shortfall_reason']) &&
                                                                $farmer['return_shortfall_reason']
                                                        )
                                                            <div class="mt-2">
                                                                <span
                                                                    class="px-2 py-1 text-xs rounded bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">
                                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                                    {{ $farmer['return_partial_return'] ? 'Partial Return' : 'Shortfall' }}
                                                                </span>
                                                                <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                                    Reason: {{ $farmer['return_shortfall_reason'] }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endif

                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $commodity['name'] }}
                                                    </span>
                                                </td>

                                                @if ($index === 0)
                                                    <td class="px-6 py-4 whitespace-nowrap"
                                                        rowspan="{{ count($farmer['commodities']) + 1 }}">
                                                        @if ($reportData['loan_type'] === 'complete-loan')
                                                            <span
                                                                class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                                Not Required
                                                            </span>
                                                        @else
                                                            <span
                                                                class="px-2 py-1 text-xs rounded-full
                                {{ $farmer['payment_status'] === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                                                {{ ucfirst($farmer['payment_status']) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                @endif

                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                                        {{ number_format($commodity['allocated'], 2) }}
                                                    </span>
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                                        {{ number_format($commodity['collected'], 2) }}
                                                    </span>
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    @php
                                                        $collectionRate =
                                                            $commodity['allocated'] > 0
                                                                ? ($commodity['collected'] / $commodity['allocated']) *
                                                                    100
                                                                : 0;
                                                    @endphp
                                                    <div class="flex items-center justify-end gap-2">
                                                        <div class="progress-bar-custom w-16">
                                                            <div class="progress-fill"
                                                                style="width: {{ min($collectionRate, 100) }}%"></div>
                                                        </div>
                                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                                            {{ number_format($collectionRate, 1) }}%
                                                        </span>
                                                    </div>
                                                </td>

                                                @if ($reportData['loan_type'] === 'complete-loan')
                                                    @if ($index === 0)
                                                        <td class="px-6 py-4 whitespace-nowrap text-right"
                                                            rowspan="{{ count($farmer['commodities']) + 1 }}">
                                                            <span
                                                                class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                                {{ number_format($farmer['expected_return'] ?? 0, 2) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right"
                                                            rowspan="{{ count($farmer['commodities']) + 1 }}">
                                                            <span
                                                                class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                                {{ number_format($farmer['total_returned'] ?? 0, 2) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right"
                                                            rowspan="{{ count($farmer['commodities']) + 1 }}">
                                                            @php
                                                                $variance = $farmer['return_variance'] ?? 0;
                                                            @endphp
                                                            <span
                                                                class="text-sm font-medium {{ $variance < 0 ? 'text-red-600 dark:text-red-400' : ($variance > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400') }}">
                                                                {{ $variance < 0 ? '' : '+' }}{{ number_format($variance, 2) }}
                                                            </span>
                                                            @if ($variance < 0)
                                                                <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                                    <i class="fas fa-exclamation-triangle"></i> Shortfall
                                                                </div>
                                                            @elseif($variance > 0)
                                                                <div
                                                                    class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                                                    <i class="fas fa-info-circle"></i> Excess
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endif
                                                @endif
                                            </tr>
                                        @endforeach

                                        <!-- Summary row for farmer -->
                                        <tr class="bg-gray-50 dark:bg-gray-700 font-semibold">
                                            <td class="px-6 py-3 text-right" colspan="2">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    Total for {{ $farmer['farmer_name'] }}:
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-right">
                                                <span class="text-sm text-blue-700 dark:text-blue-300">
                                                    {{ number_format($farmer['total_allocated'], 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-right">
                                                <span class="text-sm text-green-700 dark:text-green-300">
                                                    {{ number_format($farmer['total_collected'], 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-right">
                                                @php
                                                    $totalRate =
                                                        $farmer['total_allocated'] > 0
                                                            ? ($farmer['total_collected'] /
                                                                    $farmer['total_allocated']) *
                                                                100
                                                            : 0;
                                                @endphp
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ number_format($totalRate, 1) }}%
                                                </span>
                                            </td>
                                            @if ($reportData['loan_type'] === 'complete-loan')
                                                <!-- These cells are already spanned from above, no need to add them here -->
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Export Section -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Export Report</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Download this report in various formats for
                                offline analysis</p>
                        </div>
                        <div class="flex gap-3">
                            <button onclick="exportReport('csv')"
                                class="bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 font-medium flex items-center transition">
                                <i class="fas fa-file-csv mr-2"></i> Export as CSV
                            </button>
                            <button onclick="exportReport('pdf')"
                                class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-medium flex items-center transition">
                                <i class="fas fa-file-pdf mr-2"></i> Export as PDF
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-6 text-center">
                    <div class="text-4xl text-red-600 dark:text-red-400 mb-4">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">No Report Data Available</h3>
                    <p class="text-red-700 dark:text-red-300">
                        The tenant distribution data could not be generated. Please check the season and tenant selection
                        and try again.
                    </p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function exportReport(format) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('global.reports.tenant-distribution.export') }}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const seasonUuid = document.createElement('input');
                seasonUuid.type = 'hidden';
                seasonUuid.name = 'season_uuid';
                seasonUuid.value = '{{ $season->uuid }}';

                const tenantId = document.createElement('input');
                tenantId.type = 'hidden';
                tenantId.name = 'tenant_id';
                tenantId.value = '{{ $tenant->id }}';

                const formatInput = document.createElement('input');
                formatInput.type = 'hidden';
                formatInput.name = 'format';
                formatInput.value = format;

                form.appendChild(csrfToken);
                form.appendChild(seasonUuid);
                form.appendChild(tenantId);
                form.appendChild(formatInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }
        </script>
    @endpush
@endsection
