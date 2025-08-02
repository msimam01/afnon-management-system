@extends('layouts.layout')

@section('content')
    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $season->name }} Overview</h2>
                <form method="POST" action="{{ route('admin.seasons.update', $season->uuid) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="{{ $season->status === 'open' ? 'closed' : 'open' }}">
                    <button type="submit" class="text-sm px-3 py-1 rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                        {{ $season->status === 'open' ? '🔒 Close Season' : '🔓 Reopen Season' }}
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-300">
                <div>
                    <p><strong>Start:</strong> {{ $season->start_date }}</p>
                    <p><strong>End:</strong> {{ $season->end_date }}</p>
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

            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">🧺 Commodities Distribution</h3>
                <div class="flex space-x-2">
                    <a href="#"
                        {{-- class="text-sm bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700">Export PDF</a> --}}
                    <a href="{{ route('admin.seasons.export', $season->uuid) }}"
                        class="text-sm bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">Export
                        Excel</a>
                </div>
            </div>

            @if ($commodities->isEmpty())
                <p class="text-sm text-gray-500">No commodities allocated yet.</p>
            @else
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left">Commodity</th>
                                <th class="px-4 py-2 text-left">Category</th>
                                <th class="px-4 py-2 text-left">Unit</th>
                                <th class="px-4 py-2 text-left">Allocated</th>
                                <th class="px-4 py-2 text-left text-green-600">Distributed</th>
                                <th class="px-4 py-2 text-left text-yellow-600">Remaining</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @foreach ($commodities as $item)
                                @php
                                    $allocated = $item->pivot->allocated_quantity ?? 0;
                                    $distributed = $item->distributed_quantity ?? 0;
                                    $remaining = $allocated - $distributed;
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ $item->name }}</td>
                                    <td class="px-4 py-2">{{ $item->category }}</td>
                                    <td class="px-4 py-2">{{ $item->unit }}</td>
                                    <td class="px-4 py-2">{{ $item->allocated }}</td>
                                    <td class="px-4 py-2 text-green-600 font-medium">{{ $item->distributed }}</td>
                                    <td class="px-4 py-2 text-yellow-600">{{ $item->remaining }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h3 class="text-lg font-semibold mt-8 mb-2 text-gray-800 dark:text-white">Distribution Overview</h3>
                <canvas id="distributionChart" height="120"></canvas>
            @endif
        </div>
    </div>
    <script>
        const labels = @json($commodities->pluck('name'));
        const allocatedData = @json($commodities->pluck('allocated'));
        const distributedData = @json($commodities->pluck('distributed'));

        const data = {
            labels: labels,
            datasets: [{
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
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Commodity Distribution Comparison'
                    }
                }
            }
        };

        new Chart(document.getElementById('distributionChart'), config);
    </script>

@endsection
