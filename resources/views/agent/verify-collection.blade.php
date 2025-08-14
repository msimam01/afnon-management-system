@extends('layouts.layout')

@section('content')
<div id="collection-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Assigned Farmers - 2024 Dry Season</h3>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <input type="text" id="farmerFilter" placeholder="Search by Farmer ID or Name"
                    class="w-full sm:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
                <select id="seasonFilter"
                    class="w-full sm:w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="2024-dry" selected>2024 Dry Season</option>
                    <option value="2024-wet">2024 Wet Season</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Farmer ID</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Commodity</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Expected Return</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="collectionTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- AJAX rows injected here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Collection Modal -->
<div id="collectionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center overflow-y-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-5xl mt-16 mx-4 p-6 sm:p-8 relative overflow-y-auto max-h-[90vh]">
        <div class="flex justify-between items-center mb-6 border-b border-gray-200 dark:border-gray-600 pb-4">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Verify Collection</h3>
            <button onclick="closeCollectionModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
        </div>

        <form id="collectionForm" class="space-y-8" enctype="multipart/form-data">
            <input type="hidden" name="application_id" id="application_id" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Farmer Info</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1" id="collection-farmer-info"></ul>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Application Info</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1" id="collection-app-info"></ul>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Commodity Breakdown</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">Commodity</th>
                                <th class="px-4 py-2 text-left">Quantity</th>
                                <th class="px-4 py-2 text-left">Unit Price</th>
                                <th class="px-4 py-2 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody id="collection-breakdown" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <input type="file" name="idCard" required class="border px-2 py-1 rounded"/>
                <input type="file" name="commodityPhoto" required class="border px-2 py-1 rounded"/>
                <button type="submit"
                    class="bg-emerald-600 text-white py-2 px-6 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium transition">
                    Submit Verification
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    fetchAssignedFarmers();
    document.getElementById('farmerFilter').addEventListener('input', fetchAssignedFarmers);
    document.getElementById('seasonFilter').addEventListener('change', fetchAssignedFarmers);
});

async function fetchAssignedFarmers() {
    const filter = document.getElementById('farmerFilter').value;
    const season = document.getElementById('seasonFilter').value;

    try {
        const res = await fetch(`/agent/verify-collection?filter=${filter}&season=${season}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const applications = await res.json();
        const tbody = document.getElementById("collectionTableBody");

        tbody.innerHTML = applications.map(app => {
            const totalExpectedReturn = app.commodity_allocations.reduce((sum, c) => sum + c.allocated_quantity, 0);
            return `
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">${app.farmer.registration_number}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">${app.farmer.full_name}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">${app.commodity_allocations.map(c => c.commodity_name).join(', ')}</td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">Expected: ${totalExpectedReturn}</td>
                    <td class="px-4 py-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${app.status==='approved'?'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200':'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200'}">${app.status}</span>
                    </td>
                    <td class="px-4 py-2">
                        <button onclick='openCollectionModal(${JSON.stringify(app)})' class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 text-sm">Verify Collection</button>
                    </td>
                </tr>
            `;
        }).join('');
    } catch (err) { console.error(err); }
}

// Modal
function openCollectionModal(app) {
    document.getElementById("application_id").value = app.id;

    document.getElementById("collection-farmer-info").innerHTML = `
        <li><strong>Name:</strong> ${app.farmer.full_name}</li>
        <li><strong>Phone:</strong> ${app.farmer.phone}</li>
        <li><strong>State:</strong> ${app.farmer.state}</li>
        <li><strong>LGA:</strong> ${app.farmer.lga}</li>
    `;

    document.getElementById("collection-app-info").innerHTML = `
        <li><strong>Season:</strong> ${app.season.name}</li>
        <li><strong>Farm Size:</strong> ${app.farm.size} ha</li>
    `;

    document.getElementById("collection-breakdown").innerHTML = app.commodity_allocations.map(c => {
        const total = c.allocated_quantity * c.unit_price;
        return `
            <tr>
                <td class="px-4 py-2 border">${c.commodity_name}</td>
                <td class="px-4 py-2 border">${c.allocated_quantity}</td>
                <td class="px-4 py-2 border">₦${c.unit_price.toLocaleString()}</td>
                <td class="px-4 py-2 border">₦${total.toLocaleString()}</td>
            </tr>
        `;
    }).join('');

    document.getElementById("collectionModal").classList.remove("hidden");
}

function closeCollectionModal() {
    document.getElementById("collectionModal").classList.add("hidden");
}

// Form submission
document.getElementById("collectionForm").addEventListener("submit", async function(e){
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const res = await fetch('{{ route("agent.verify.collection.submit") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });
        if(res.ok){
            alert('Collection verified successfully!');
            closeCollectionModal();
            fetchAssignedFarmers();
        } else {
            const err = await res.json();
            alert(err.message || 'Verification failed!');
        }
    } catch(err){ console.error(err); }
});
</script>
@endsection
