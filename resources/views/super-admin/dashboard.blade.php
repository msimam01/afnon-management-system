@extends('layouts.layout')

@section('content')
    <!-- Dashboard Section -->
    <div id="dashboard-section" class="section">
        <!-- All Zones Summary -->
        <!-- Super Admin Dashboard: Enhanced -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">All Zones Summary</h2>
            <div class="flex flex-wrap gap-4 mb-6">
                <select
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                    <option selected disabled>Filter by Season</option>
                    <option>Dry Season</option>
                    <option>Rainy Season</option>
                </select>
                <select
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                    <option selected disabled>Filter by Year</option>
                    <option>2023</option>
                    <option>2024</option>
                    <option>2025</option>
                </select>
            </div>


            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card Template -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition-all p-6">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Zones</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">6</p>
                        </div>
                    </div>
                </div>

                <!-- Repeat for each card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition-all p-6">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Farmers</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">15,247</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition-all p-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4v10l8 4v-10l8-4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Distributed
                            </p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">12,456 bags</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition-all p-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-10 w-10 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4v10l8 4v-10l8-4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Remaining Stock</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">7,544 bags</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📊 Zone Breakdown -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Zone Breakdown</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Each Zone Card -->
                        <!-- Repeat for each zone -->
                        <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-4 hover:shadow-md transition">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">North Central Zone</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Farmers:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">2,450</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Distributed:</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">1,890
                                        bags</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Remaining:</span>
                                    <span class="font-medium text-yellow-600 dark:text-yellow-400">560
                                        bags</span>
                                </div>
                            </div>
                        </div>
                        <!-- Add the remaining 5 zones similarly -->
                    </div>
                </div>
            </div>
            <!-- Charts Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow mt-10">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Visual Insights</h3>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Line Chart -->
                    <div>
                        <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-2">Distribution Over Time</h4>
                        <canvas id="lineChart" height="220"></canvas>
                    </div>

                    <!-- Doughnut Chart -->
                    <div>
                        <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-2">Commodity Breakdown</h4>
                        <canvas id="doughnutChart" height="220"></canvas>
                    </div>
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
