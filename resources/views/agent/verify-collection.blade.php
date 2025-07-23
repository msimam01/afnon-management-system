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
                                <button onclick="openCollectionModal('NEC001234')"
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
    <!-- Modal Wrapper -->
    <div id="collectionModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl mt-20 mx-4 p-6 relative">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Verify Application Collection</h3>
                <button onclick="closeCollectionModal()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>

            <form class="space-y-6" id="collectionForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="farmerId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Farmer
                            ID *</label>
                        <input type="text" id="farmerId" name="farmerId" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <div>
                        <label for="seasonInfo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season
                            *</label>
                        <input type="text" id="seasonInfo" name="seasonInfo" value="2024 Dry Season" readonly
                            class="mt-1 block w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md shadow-sm sm:text-sm bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <div>
                        <label for="commodityType"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity Type *</label>
                        <select id="commodityType" name="commodityType" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select commodity</option>
                            <option value="maize-seeds">Maize Seeds</option>
                        </select>
                    </div>

                    <div>
                        <label for="expectedQuantity"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Return *</label>
                        <input type="text" id="expectedQuantity" name="expectedQuantity" value="5 bags (25kg each)"
                            readonly
                            class="mt-1 block w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md shadow-sm sm:text-sm bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>

                <!-- Image Uploads with Preview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- ID Card -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Card Photo
                            *</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md bg-white dark:bg-gray-700">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor"
                                    fill="none" viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28l4 4"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex justify-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <button type="button" id="idCameraBtn"
                                        class="cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-emerald-600 hover:text-emerald-500">Use
                                        Camera</button>
                                    <label for="idCard"
                                        class="cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-emerald-600 hover:text-emerald-500">
                                        Upload
                                        <input id="idCard" name="idCard" type="file" accept="image/*"
                                            capture="environment" class="sr-only" required>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                        <div id="idCardPreview" class="mt-2 hidden">
                            <img class="h-24 w-24 object-cover border rounded-lg" alt="ID Card preview">
                        </div>
                    </div>


                    <!-- Commodity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodity Photo
                            *</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md bg-white dark:bg-gray-700">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor"
                                    fill="none" viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28l4 4"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex justify-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <button type="button" id="commodityCameraBtn"
                                        class="cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-emerald-600 hover:text-emerald-500">Use
                                        Camera</button>
                                    <label for="commodityPhoto"
                                        class="cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-emerald-600 hover:text-emerald-500">
                                        Upload
                                        <input id="commodityPhoto" name="commodityPhoto" type="file" accept="image/*"
                                            capture="environment" class="sr-only" required>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                        <div id="commodityPreview" class="mt-2 hidden">
                            <img class="h-24 w-24 object-cover border rounded-lg" alt="Commodity preview">
                        </div>
                    </div>

                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="Any additional notes or observations..."></textarea>
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white py-2 px-6 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium transition-colors">
                        Submit Collection Verification
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage(input, previewId) {
            const file = input.files[0];
            const previewWrapper = document.getElementById(previewId);
            const img = previewWrapper.querySelector('img');

            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    previewWrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
            // ========== Modal Functions ==========
            window.openCollectionModal = function (farmerId) {
                const modal = document.getElementById("collectionModal");
                const farmerIdInput = document.getElementById("farmerId");

                if (modal && farmerIdInput) {
                    modal.classList.remove("hidden");
                    farmerIdInput.value = farmerId;
                }
            };

            window.closeCollectionModal = function () {
                const modal = document.getElementById("collectionModal");
                const form = document.getElementById("collectionForm");

                if (modal) modal.classList.add("hidden");
                if (form) form.reset();
            };

            // ========== Setup Camera Buttons (Safe) ==========
            function setupCameraButton(buttonId) {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.addEventListener("click", function () {
                        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                            navigator.mediaDevices.getUserMedia({ video: true })
                                .then(function (stream) {
                                    alert("Camera access granted!");
                                    stream.getTracks().forEach((track) => track.stop());
                                })
                                .catch(function (err) {
                                    alert("Camera access denied or not available.");
                                });
                        } else {
                            alert("Camera not supported on this device.");
                        }
                    });
                }
            }

            ["farmerCameraBtn", "idCameraBtn", "commodityCameraBtn", "returnCameraBtn"].forEach(setupCameraButton);

            // ========== Form Submission Handling ==========
            const collectionForm = document.getElementById("collectionForm");
            if (collectionForm) {
                collectionForm.addEventListener("submit", function (e) {
                    e.preventDefault();
                    alert("Collection verification submitted successfully!");
                });
            }

            const returnForm = document.getElementById("returnForm");
            if (returnForm) {
                returnForm.addEventListener("submit", function (e) {
                    e.preventDefault();
                    alert("Return verification submitted successfully!");
                });
            }

            window.openReturnModal = function (farmerId) {
                const modal = document.getElementById("returnModal");
                const input = document.getElementById("returnFarmerId");
                document.getElementById("returnModal").classList.remove("hidden");
                document.getElementById("returnFarmerId").value = farmerId;

                if (modal && input) {
                    modal.classList.remove("hidden");
                    input.value = farmerId;
                }
            };

            window.closeReturnModal = function () {
                const modal = document.getElementById("returnModal");
                const form = document.getElementById("returnForm");
                const preview = document.getElementById("returnPreview");

                if (modal) modal.classList.add("hidden");
                if (form) form.reset();
                if (preview) preview.classList.add("hidden");
            };

        });
    </script>
@endsection
