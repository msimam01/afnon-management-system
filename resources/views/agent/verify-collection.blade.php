@extends('layouts.layout')
@section('content')
    <!-- Collection Section -->
    <div id="collection-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Assigned Farmers - 2024 Dry Season
                </h3>

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
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Farmer ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Commodity</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Expected Return</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody id="collectionTableBody"
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Dynamic Rows -->
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">NEC001234</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">John Doe</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">Maize Seeds (5 bags)
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">Expected: 4 Bags
                                Maize</td>
                            <td class="px-4 py-2">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Pending</span>
                            </td>
                            <td class="px-4 py-2">
                                <button <button
                                    onclick="openCollectionModal({
    farmer: {
        name: 'John Doe',
        phone: '08012345678',
        state: 'Kano',
        lga: 'Gwale'
    },
    season: {
        name: '2024 Dry Season'
    },
    farmSize: 2.5,
    seed: {
        name: 'Maize Seeds'
    },
    expectedReturn: '5 bags (25kg each)',
    commodities: [
        { name: 'Maize Seeds', quantity: 5, unit: 'bags', unitPrice: 10000 },
        { name: 'NPK Fertilizer', quantity: 3, unit: 'bags', unitPrice: 8000 }
    ]
})"
                                    class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 text-sm">
                                    Verify Collection
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div id="collectionModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center overflow-y-auto">
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-5xl mt-16 mx-4 p-6 sm:p-8 relative overflow-y-auto max-h-[90vh]">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6 border-b border-gray-200 dark:border-gray-600 pb-4">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Verify Collection</h3>
                <button onclick="closeCollectionModal()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>

            <form id="collectionForm" class="space-y-8">

                <!-- Farmer + Application Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Farmer Info</h4>
                        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1" id="collection-farmer-info">
                            <!-- Populated via JS -->
                        </ul>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Application Info</h4>
                        <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1" id="collection-app-info">
                            <!-- Populated via JS -->
                        </ul>
                    </div>
                </div>

                <!-- Commodity Breakdown -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Commodity Breakdown</h4>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                                <tr>
                                    <th class="px-4 py-2 text-left">Commodity</th>
                                    <th class="px-4 py-2 text-left">Quantity</th>
                                    <th class="px-4 py-2 text-left">Unit Price</th>
                                    <th class="px-4 py-2 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody id="collection-breakdown"
                                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <!-- Injected via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Image Uploads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- ID Card Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID Card Photo
                            *</label>
                        <div class="relative flex flex-col items-center justify-center w-full h-40 px-4 border-2 border-dashed rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 cursor-pointer hover:border-emerald-500 transition"
                            onclick="document.getElementById('idCard').click()">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4M17 8v8m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Click or drop an image here</p>
                            <input type="file" id="idCard" name="idCard" accept="image/*" required
                                class="absolute inset-0 opacity-0 cursor-pointer"
                                onchange="previewImage(event, 'idCardPreview')" />
                        </div>
                        <div id="idCardPreview" class="mt-2 hidden">
                            <img class="h-24 w-24 object-cover border rounded-lg" alt="ID Card Preview" />
                        </div>
                    </div>

                    <!-- Commodity Photo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Commodity Photo
                            *</label>
                        <div class="relative flex flex-col items-center justify-center w-full h-40 px-4 border-2 border-dashed rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 cursor-pointer hover:border-emerald-500 transition"
                            onclick="document.getElementById('commodityPhoto').click()">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4M17 8v8m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Click or drop an image here</p>
                            <input type="file" id="commodityPhoto" name="commodityPhoto" accept="image/*" required
                                class="absolute inset-0 opacity-0 cursor-pointer"
                                onchange="previewImage(event, 'commodityPreview')" />
                        </div>
                        <div id="commodityPreview" class="mt-2 hidden">
                            <img class="h-24 w-24 object-cover border rounded-lg" alt="Commodity Preview" />
                        </div>
                    </div>
                </div>


                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="block w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md text-gray-900 dark:text-white p-2"
                        placeholder="Any additional notes or remarks..."></textarea>
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white py-2 px-6 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium transition">
                        Submit Verification
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview image handlers
        document.getElementById('idCard').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const preview = document.getElementById('idCardPreview');
                preview.querySelector('img').src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });

        document.getElementById('commodityPhoto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const preview = document.getElementById('commodityPreview');
                preview.querySelector('img').src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });
        function previewImage(event, previewId) {
        const input = event.target;
        const file = input.files[0];
        const previewContainer = document.getElementById(previewId);

        if (file) {
            const img = previewContainer.querySelector("img");
            img.src = URL.createObjectURL(file);
            previewContainer.classList.remove("hidden");
        }
    }

        // Populate modal with data
        function openCollectionModal(application) {
            const farmerUl = document.getElementById("collection-farmer-info");
            const appUl = document.getElementById("collection-app-info");
            const breakdown = document.getElementById("collection-breakdown");

            farmerUl.innerHTML = `
        <li><strong>Name:</strong> ${application.farmer.name}</li>
        <li><strong>Phone:</strong> ${application.farmer.phone}</li>
        <li><strong>State:</strong> ${application.farmer.state}</li>
        <li><strong>LGA:</strong> ${application.farmer.lga}</li>
    `;

            appUl.innerHTML = `
        <li><strong>Season:</strong> ${application.season.name}</li>
        <li><strong>Farm Size:</strong> ${application.farmSize} ha</li>
        <li><strong>Seed:</strong> ${application.seed.name}</li>
        <li><strong>Expected Return:</strong> ${application.expectedReturn}</li>
    `;

            let rows = '';
            application.commodities.forEach(item => {
                const total = item.quantity * item.unitPrice;
                rows += `
            <tr>
                <td class="px-4 py-2 border">${item.name}</td>
                <td class="px-4 py-2 border">${item.quantity} ${item.unit}</td>
                <td class="px-4 py-2 border">₦${item.unitPrice.toLocaleString()}</td>
                <td class="px-4 py-2 border">₦${total.toLocaleString()}</td>
            </tr>
        `;
            });
            breakdown.innerHTML = rows;

            // Show modal
            document.getElementById("collectionModal").classList.remove("hidden");
        }

        // Close modal
        function closeCollectionModal() {
            document.getElementById("collectionModal").classList.add("hidden");
        }
    </script>
@endsection
