@extends('layouts.layout')

@section('content')
    <div id="reports-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Reports</h3>
            <button id="exportBtn"
                class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition-all">
                Export to CSV
            </button>
        </div>

        <!-- Filters -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Season</label>
                <select id="filter-season"
                    class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">All Seasons</option>
                    <option value="2024-dry">2024 Dry Season</option>
                    <option value="2024-wet">2024 Wet Season</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Cluster</label>
                <select id="filter-cluster"
                    class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">All Clusters</option>
                    <option value="Cluster A">Cluster A</option>
                    <option value="Cluster B">Cluster B</option>
                </select>
            </div>
            <div class="flex items-end">
                <input type="text" id="searchInput"
                    placeholder="Search by farmer name..." class="w-full px-3 py-2 rounded-md border dark:bg-gray-700 dark:text-white">
            </div>
        </div>

        <!-- Table -->
        <div class="p-6 overflow-x-auto">
            <table id="reportsTable" class="min-w-full table-auto">
                <thead class="bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                    <tr>
                        <th class="px-4 py-2">Farmer</th>
                        <th class="px-4 py-2">Season</th>
                        <th class="px-4 py-2">Commodity</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody id="reportBody" class="text-sm text-gray-700 dark:text-gray-200">
                    <!-- Filled via JS -->
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-6 flex justify-end space-x-2" id="pagination"></div>
        </div>
    </div>
</div>
<script>
    const reportsData = [
        { farmer: 'John Doe', season: '2024-dry', cluster: 'Cluster A', commodity: 'Maize Seeds', quantity: '5 bags', status: 'Distributed', date: '2024-03-15' },
        { farmer: 'Mary Okon', season: '2024-wet', cluster: 'Cluster B', commodity: 'Rice Seeds', quantity: '4 bags', status: 'Returned', date: '2024-06-12' },
        { farmer: 'Fatima Bello', season: '2024-dry', cluster: 'Cluster A', commodity: 'Urea', quantity: '3 bags', status: 'Rejected', date: '2024-04-10' },
        // Add more dummy data or load via AJAX
    ];

    let currentPage = 1;
    const rowsPerPage = 5;

    function filterReports() {
        const season = document.getElementById('filter-season').value;
        const cluster = document.getElementById('filter-cluster').value;
        const search = document.getElementById('searchInput').value.toLowerCase();

        return reportsData.filter(item =>
            (!season || item.season === season) &&
            (!cluster || item.cluster === cluster) &&
            item.farmer.toLowerCase().includes(search)
        );
    }

    function paginateData(data) {
        const start = (currentPage - 1) * rowsPerPage;
        return data.slice(start, start + rowsPerPage);
    }

    function renderTable() {
        const filtered = filterReports();
        const paginated = paginateData(filtered);
        const tbody = document.getElementById('reportBody');
        tbody.innerHTML = '';

        if (paginated.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4">No records found</td></tr>`;
        }

        paginated.forEach(item => {
            tbody.innerHTML += `
                <tr>
                    <td class="px-4 py-2">${item.farmer}</td>
                    <td class="px-4 py-2">${item.season.replace('-', ' ').toUpperCase()}</td>
                    <td class="px-4 py-2">${item.commodity}</td>
                    <td class="px-4 py-2">${item.quantity}</td>
                    <td class="px-4 py-2">${item.status}</td>
                    <td class="px-4 py-2">${item.date}</td>
                </tr>
            `;
        });

        renderPagination(filtered.length);
    }

    function renderPagination(total) {
        const totalPages = Math.ceil(total / rowsPerPage);
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <button onclick="goToPage(${i})" class="px-3 py-1 rounded ${i === currentPage ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white'}">
                    ${i}
                </button>
            `;
        }
    }

    function goToPage(page) {
        currentPage = page;
        renderTable();
    }

    // Export via Laravel route
    document.getElementById('exportBtn').addEventListener('click', () => {
        const season = document.getElementById('filter-season').value;
        const cluster = document.getElementById('filter-cluster').value;
        const query = new URLSearchParams({ season, cluster }).toString();

        window.open(`/export-reports?${query}`, '_blank');
    });

    // Bind filters
    document.getElementById('filter-season').addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });
    document.getElementById('filter-cluster').addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });
    document.getElementById('searchInput').addEventListener('input', () => {
        currentPage = 1;
        renderTable();
    });

    // Initial render
    renderTable();
</script>

@endsection
