@extends('layouts.layout')

@section('content')
    <div id="farmers-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Farmers</h3>
                <button onclick="exportFarmersToCSV()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 text-sm">Export CSV</button>
            </div>

            <!-- Search & Filter -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" id="farmerSearch" placeholder="Search by name or phone"
                    class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white" />
            </div>

            <!-- Table -->
            <div class="px-6 pb-6 overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead
                        class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-2 text-left">Farmer</th>
                            <th class="px-4 py-2 text-left">Farm Info</th>
                            <th class="px-4 py-2 text-left">Applications</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmerTableBody"
                        class="divide-y divide-gray-200 dark:divide-gray-600 bg-white dark:bg-gray-800">
                        <!-- JS generated rows -->
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4 flex justify-end space-x-2 text-sm" id="paginationControls"></div>
            </div>
        </div>
    </div>

    <!-- Farmer profile modal -->
    <div id="farmerProfileModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl rounded-lg shadow-lg p-6 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Farmer Profile</h3>
                <button onclick="closeFarmerModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Farmer Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Basic Info</h4>
                    <p class="text-gray-700 dark:text-white"><strong>Name:</strong> John Doe</p>
                    <p class="text-gray-700 dark:text-white"><strong>Phone:</strong> +234 803 123 4567</p>
                    <p class="text-gray-700 dark:text-white"><strong>BVN:</strong> 12345678901</p>
                    <p class="text-gray-700 dark:text-white"><strong>NIN:</strong> 98765432109</p>
                    <p class="text-gray-700 dark:text-white"><strong>Cluster:</strong> Cluster A</p>
                    <p class="text-gray-700 dark:text-white"><strong>State:</strong> Lagos</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Farm(s)</h4>
                    <ul class="space-y-2">
                        <li>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-700 dark:text-white"><strong>Farm 1:</strong> 5.2 hectares –
                                        Ikeja</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Current Season</p>
                                </div>
                                <span
                                    class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">Active</span>
                            </div>
                        </li>
                        <li>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-700 dark:text-white"><strong>Farm 2:</strong> 3.7 hectares –
                                        Badagry</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Available for next season</p>
                                </div>
                                <button class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Make
                                    Active</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Application History -->
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Application History</h4>
                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Season</th>
                            <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Commodity</th>
                            <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="px-4 py-2 text-gray-700 dark:text-white">2024 Dry Season</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-white">Maize</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-white"><span
                                    class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">Approved</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-gray-700 dark:text-white">2023 Wet Season</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-white">Fertilizer</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-white"><span
                                    class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full">Collected</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end">
                <button onclick="closeFarmerModal()"
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>
    <script>
    const farmersData = [
        { name: 'John Doe', phone: '+234 803 123 4567', size: '5.2 hectares', location: 'Ikeja, Lagos', cluster: 'Cluster A', applications: 3, status: 'Active' },
        { name: 'Mary Okon', phone: '+234 802 987 6543', size: '3.0 hectares', location: 'Wuse, Abuja', cluster: 'Cluster B', applications: 2, status: 'Inactive' },
        { name: 'Fatima Bello', phone: '+234 701 555 1122', size: '7 hectares', location: 'Zaria, Kaduna', cluster: 'Cluster A', applications: 4, status: 'Active' },
        // ... Add more or load via AJAX
    ];

    let currentPage = 1;
    const perPage = 5;

    function renderFarmersTable() {
        const searchQuery = document.getElementById('farmerSearch').value.toLowerCase();
        const filtered = farmersData.filter(f =>
            f.name.toLowerCase().includes(searchQuery) || f.phone.includes(searchQuery)
        );

        const start = (currentPage - 1) * perPage;
        const paginated = filtered.slice(start, start + perPage);

        const tbody = document.getElementById('farmerTableBody');
        tbody.innerHTML = '';

        if (paginated.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-sm">No results found</td></tr>`;
            renderPagination(0);
            return;
        }

        paginated.forEach(farmer => {
            tbody.innerHTML += `
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-9 h-9 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-sm font-medium text-gray-700 dark:text-white">${farmer.name.split(' ').map(n => n[0]).join('')}</div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${farmer.name}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">${farmer.phone}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                        ${farmer.size}<br><span class="text-xs text-gray-400">${farmer.location}</span><br>
                        <span class="text-xs text-gray-400">${farmer.cluster}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">${farmer.applications} total</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${
                            farmer.status === 'Active'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                        }">${farmer.status}</span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <button class="text-emerald-600 dark:text-emerald-400 hover:underline mr-2" onclick="viewFarmerProfile('${farmer.name}')">View</button>
                        <button class="text-red-600 dark:text-red-400 hover:underline" onclick="confirmDeleteFarmer('${farmer.name}')">Delete</button>
                    </td>
                </tr>`;
        });

        renderPagination(filtered.length);
    }

    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / perPage);
        const container = document.getElementById('paginationControls');
        container.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            container.innerHTML += `
                <button onclick="goToPage(${i})" class="px-3 py-1 rounded ${
                i === currentPage
                    ? 'bg-emerald-600 text-white'
                    : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white'
            }">${i}</button>`;
        }
    }

    function goToPage(page) {
        currentPage = page;
        renderFarmersTable();
    }

    function exportFarmersToCSV() {
        const params = new URLSearchParams({
            search: document.getElementById('farmerSearch').value
        });
        window.open(`/farmers/export?${params.toString()}`, '_blank');
    }

    document.getElementById('farmerSearch').addEventListener('input', () => {
        currentPage = 1;
        renderFarmersTable();
    });

    // Simulated action
    function viewFarmerProfile(name) {
        alert('View farmer profile: ' + name);
    }

    function confirmDeleteFarmer(name) {
        if (confirm(`Delete farmer ${name}?`)) {
            alert('Deleted: ' + name);
        }
    }

    // Init
    renderFarmersTable();
</script>
{{-- our curent season creation ui is a stepper which at first stepp asked for season name. commodities that will be avalable for that season, start date, end date and total budget and in the next step is where we allocate a certain amount of each commodity from our stock to each of our tenants which are states so now our client aggre to this method but regarding return period or date thay have a little concern and ask whether if a returning date is set for a particular season and a notification or sms has been sent to farmer is there a way that after certain period the farmer will be notified again if he didn't return or pay his loan also they spoke about the return or monetary return they said that when the farmer has come to pay to return commodity there are considering the current price of a commodity like maize for example if farmer wanted to return commodity then the amount that has been given to him as a loan will be divide by the current price of the commodity the equal value is the quantity of the commodity bags he will bring and if he chose to pay money then the curent price of the commodity he recieve he will return that for example if he recievs 2o bags of rice then the current price of the bags of rice is the amount that he will pay --}}
@endsection
