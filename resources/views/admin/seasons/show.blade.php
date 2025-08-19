@extends('layouts.layout')

@section('content')
<div class="p-4 md:p-6 space-y-6">

    {{-- Application Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-blue-700 dark:text-blue-300 font-semibold">Total Applications</p>
            <p class="text-2xl font-bold text-blue-900 dark:text-white">{{ $totalApplications }}</p>
        </div>
        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-green-700 dark:text-green-300 font-semibold">Approved</p>
            <p class="text-2xl font-bold text-green-900 dark:text-white">{{ $approvedApplications }}</p>
        </div>
        <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-yellow-700 dark:text-yellow-300 font-semibold">Pending</p>
            <p class="text-2xl font-bold text-yellow-900 dark:text-white">{{ $pendingApplications }}</p>
        </div>
    </div>

    {{-- Commodity Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-purple-700 dark:text-purple-300 font-semibold">Total Allocated</p>
            <p class="text-2xl font-bold text-purple-900 dark:text-white">{{ $totalAllocated }}</p>
        </div>
        <div class="bg-indigo-100 dark:bg-indigo-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-indigo-700 dark:text-indigo-300 font-semibold">Distributed</p>
            <p class="text-2xl font-bold text-indigo-900 dark:text-white">{{ $totalDistributed }}</p>
        </div>
        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-xl shadow text-center">
            <p class="text-sm text-red-700 dark:text-red-300 font-semibold">Remaining</p>
            <p class="text-2xl font-bold text-red-900 dark:text-white">{{ $totalRemaining }}</p>
        </div>
    </div>

    {{-- Season Overview --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">🌾 {{ $season->name }} Overview</h2>
            <form method="POST"
                action="{{ $season->status === 'open' ? route('admin.seasons.close', $season->uuid) : route('admin.seasons.reopen', $season->uuid) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="{{ $season->status === 'open' ? 'closed' : 'open' }}">
                <button type="submit" class="text-sm px-3 py-1 rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                    {{ $season->status === 'open' ? '🔒 Close Season' : '🔓 Reopen Season' }}
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-300">
            <div>
                <p><strong>Start:</strong> {{ $season->start_date }}</p>
                <p><strong>End:</strong> {{ $season->end_date }}</p>
            </div>
            <div>
                <p><strong>Collection Start Date:</strong> {{ $season->collection_start_date }}</p>
                <p><strong>Collection End Date:</strong> {{ $season->collection_end_date }}</p>
            </div>
            <div>
                <p><strong>Return Deadline:</strong> {{ $season->return_deadline }}</p>
                <p><strong>Insurance Rate:</strong> {{ $season->insurance_rate }}%</p>
            </div>
            <div>
                <p><strong>Reminder Days:</strong> {{ $season->send_reminder_after_days }}</p>
                <p><strong>Status:</strong>
                    <span class="{{ $season->status === 'open' ? 'text-green-600' : 'text-red-600' }}">
                        {{ ucfirst($season->status) }}
                    </span>
                </p>
            </div>
        </div>

        <hr class="my-6 border-gray-300 dark:border-gray-700">

        {{-- Commodity Distribution Table --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">🧺 Commodities Distribution</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.seasons.export', $season->uuid) }}"
                   class="text-sm bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                   Export Excel
                </a>
            </div>
        </div>

        @if ($commodities->isEmpty())
            <p class="text-sm text-gray-500">No commodities associated to this season yet.</p>
        @else
            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th>Commodity</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Allocated</th>
                            <th class="text-green-600">Distributed</th>
                            <th class="text-yellow-600">Remaining</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                        @foreach ($commodities as $item)
                            <tr>
                                <td class="px-4 py-2 font-medium">{{ $item->name }}</td>
                                <td class="px-4 py-2">{{ $item->category }}</td>
                                <td class="px-4 py-2">{{ $item->unit }}</td>
                                <td class="px-4 py-2">{{ number_format($item->allocated ?? 0) }}</td>
                                <td class="px-4 py-2 text-green-600 font-medium">{{ number_format($item->distributed ?? 0) }}</td>
                                <td class="px-4 py-2 text-yellow-600">{{ number_format($item->remaining ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Chart Container --}}
            <h3 class="text-lg font-semibold mt-8 mb-2 text-gray-800 dark:text-white">Distribution Overview</h3>
            <div class="w-full h-[300px] sm:h-[400px]">
                <canvas id="appStatusChart"></canvas>
            </div>
        @endif
    </div>
</div>

{{-- Chart Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    const labels = @json($commodities->pluck('name'));
    const allocatedData = @json($commodities->pluck('allocated'));
    const distributedData = @json($commodities->pluck('distributed'));

    new Chart(document.getElementById('appStatusChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Allocated',
                    data: allocatedData,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Distributed',
                    data: distributedData,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Commodity Distribution Comparison' },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: '#fff',
                    font: { weight: 'bold', size: 12 },
                    formatter: (value, context) => {
                        let sum = context.dataset.data.reduce((a, b) => a + b, 0);
                        let percentage = (value * 100 / sum).toFixed(1) + "%";
                        return value + " (" + percentage + ")";
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
@endsection
