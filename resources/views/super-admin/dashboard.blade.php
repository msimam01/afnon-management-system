@extends('layouts.layout')

@section('content')
    <!-- Dashboard Section -->
    <div id="dashboard-section" class="section px-4 py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">

  <!-- Filters -->
  <div class="flex flex-wrap gap-4 mb-6 items-center justify-between">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Super Admin Dashboard</h2>
    <div class="flex gap-4">
      <select class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
        <option disabled selected>Filter by Season</option>
        <option>Dry Season</option>
        <option>Rainy Season</option>
      </select>
      <select class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
        <option disabled selected>Filter by Year</option>
        <option>2023</option>
        <option>2024</option>
        <option>2025</option>
      </select>
    </div>
  </div>

  <!-- Metric Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition p-6">
      <div class="flex items-center gap-4">
        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
          <i class="fas fa-map-marker-alt text-blue-600 dark:text-blue-400"></i>
        </div>
        <div>
          <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total States</p>
          <p class="text-xl font-semibold text-gray-900 dark:text-white">36</p>
        </div>
      </div>
    </div>

    <!-- Farmers -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition p-6">
      <div class="flex items-center gap-4">
        <div class="h-10 w-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
          <i class="fas fa-users text-green-600 dark:text-green-400"></i>
        </div>
        <div>
          <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Registered Farmers</p>
          <p class="text-xl font-semibold text-gray-900 dark:text-white">15,247</p>
        </div>
      </div>
    </div>

    <!-- Distributed -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition p-6">
      <div class="flex items-center gap-4">
        <div class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
          <i class="fas fa-truck text-purple-600 dark:text-purple-400"></i>
        </div>
        <div>
          <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Distributed</p>
          <p class="text-xl font-semibold text-gray-900 dark:text-white">12,456 bags</p>
        </div>
      </div>
    </div>

    <!-- Remaining -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition p-6">
      <div class="flex items-center gap-4">
        <div class="h-10 w-10 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
          <i class="fas fa-warehouse text-yellow-600 dark:text-yellow-400"></i>
        </div>
        <div>
          <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Remaining Stock</p>
          <p class="text-xl font-semibold text-gray-900 dark:text-white">7,544 bags</p>
        </div>
      </div>
    </div>
  </div>

  <!-- State Breakdown -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow mb-10">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">State Distribution Summary</h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- State Card -->
      <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-4 bg-white dark:bg-gray-800 hover:shadow transition">
        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Kano State</h4>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Farmers:</span>
            <span class="font-medium text-gray-900 dark:text-white">3,240</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Distributed:</span>
            <span class="font-medium text-green-600 dark:text-green-400">2,890 bags</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Remaining:</span>
            <span class="font-medium text-yellow-600 dark:text-yellow-400">350 bags</span>
          </div>
        </div>
      </div>
      <!-- Add more state cards as needed -->
    </div>
  </div>

  <!-- Charts Section -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Visual Insights</h3>
    </div>
    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Chart 1 -->
      <div>
        <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-2">Distribution Over Time</h4>
        <canvas id="lineChart" height="220"></canvas>
      </div>

      <!-- Chart 2 -->
      <div>
        <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-2">Commodity Type Breakdown</h4>
        <canvas id="doughnutChart" height="220"></canvas>
      </div>
    </div>
  </div>
</div>

    <script>
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Bags Distributed',
                    data: [800, 1200, 1500, 1300, 1700],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Maize', 'Rice', 'Beans', 'Fertilizer'],
                datasets: [{
                    data: [3000, 2000, 1500, 1000],
                    backgroundColor: ['#FBBF24', '#60A5FA', '#34D399', '#A78BFA']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
@endsection
