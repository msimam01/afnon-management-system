@extends('layouts.layout')

@section('content')
<div id="dashboard-section" class="section">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Assigned Farmers</h2>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $totalFarmers }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Assigned</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $collectionsVerified }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Collections Verified</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $returnsVerified }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Returns Verified</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $todayTasks }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Today's Tasks</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <canvas id="tasksChart" height="120"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('tasksChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Count',
                data: @json($chartData['values']),
                backgroundColor: ['#3b82f6', '#facc15'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
