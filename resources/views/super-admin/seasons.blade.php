@extends('layouts.layout')

@section('content')
    <div id="seasons-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Global Season Management</h3>
                <button onclick="openSeasonModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Create New Season
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-medium text-gray-900 dark:text-white">2024 Dry Season</h4>
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Open</span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            <p>Start Date: January 1, 2024</p>
                            <p>End Date: June 30, 2024</p>
                            <p>Commodity: Maize Seeds</p>
                            <p>Total Budget: ₦2.5B</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="openEditSeasonModal()"
                                class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 text-sm">Edit</button>
                            <button
                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm">Close</button>
                        </div>
                    </div>
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-medium text-gray-900 dark:text-white">2024 Wet Season</h4>
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">Closed</span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            <p>Start Date: July 1, 2024</p>
                            <p>End Date: December 31, 2024</p>
                            <p>Commodity: Rice Seeds</p>
                            <p>Total Budget: ₦1.8B</p>
                        </div>
                        <div class="flex space-x-2">
                            <button
                                class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 text-sm">Edit</button>
                            <button
                                class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 text-sm">Open</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-10">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tenant Quota Distribution</h3>
                <select id="seasonSelect"
                    class="text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="2024_dry_maize">2024 Dry Season - Maize</option>
                    <option value="2024_wet_rice">2024 Wet Season - Rice</option>
                </select>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Tenant Card -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-2">North Central Zone
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Allocated:</span>
                                <span class="font-medium text-blue-600 dark:text-blue-400">7,500 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Distributed:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">5,500 bags</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Remaining:</span>
                                <span class="font-medium text-yellow-600 dark:text-yellow-400">2,000 bags</span>
                            </div>
                        </div>
                        <button class="mt-4 w-full text-sm text-emerald-600 dark:text-emerald-400 hover:underline"
                            onclick="adjustQuota('north-central')">Adjust Allocation</button>
                    </div>

                    <!-- Repeat cards for other tenants -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-2">North East Zone
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span
                                    class="text-gray-600 dark:text-gray-400">Allocated:</span><span
                                    class="font-medium text-blue-600 dark:text-blue-400">9,000 bags</span></div>
                            <div class="flex justify-between"><span
                                    class="text-gray-600 dark:text-gray-400">Distributed:</span><span
                                    class="font-medium text-green-600 dark:text-green-400">6,400 bags</span>
                            </div>
                            <div class="flex justify-between"><span
                                    class="text-gray-600 dark:text-gray-400">Remaining:</span><span
                                    class="font-medium text-yellow-600 dark:text-yellow-400">2,600 bags</span>
                            </div>
                        </div>
                        <button class="mt-4 w-full text-sm text-emerald-600 dark:text-emerald-400 hover:underline"
                            onclick="adjustQuota('north-east')">Adjust Allocation</button>
                    </div>

                    <!-- Add more tenant cards as needed -->

                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-10">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Distribution Tracking (By Tenant)
                </h3>
                <select id="seasonSelectTrack"
                    class="text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="2024_dry">2024 Dry Season</option>
                    <option value="2024_wet">2024 Wet Season</option>
                </select>
            </div>

            <div class="p-6 space-y-8">
                <!-- One section per tenant -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">North Central Zone</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Maize Seeds:</span>
                            <span class="font-medium text-green-600 dark:text-green-400">Distributed 5,500 /
                                Allocated 7,500</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Rice Seeds:</span>
                            <span class="font-medium text-green-600 dark:text-green-400">Distributed 2,200 /
                                Allocated 3,000</span>
                        </div>
                    </div>
                </div>

                <!-- Repeat for other tenants -->
                <!-- ... -->
            </div>
        </div>


    </div>
    <!-- Add Season Modal -->
    <div id="seasonModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-5xl mt-20 mx-4 p-6 relative">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Season</h3>
                <button onclick="closeSeasonModal()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>

            <form id="seasonForm" class="space-y-6">
                <!-- Step 1: General Info -->
                <div id="step1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="seasonName"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season Name *</label>
                            <input type="text" id="seasonName" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="commodities"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commodities *</label>
                            <select id="commodities" multiple required
                                class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                                <!-- Dynamically filled -->
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hold Ctrl or Cmd to select multiple
                            </p>
                        </div>
                        <div>
                            <label for="startDate"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date *</label>
                            <input type="date" id="startDate" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                                Date *</label>
                            <input type="date" id="endDate" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total
                                Budget (₦)</label>
                            <input type="number" id="budget" required
                                class="mt-1 w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Quota Allocation -->
                <div id="step2" class="hidden">
                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Allocate Commodity Quotas per
                        Tenant</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Available stock is shown beside each
                        commodity.</div>
                    <div id="allocationContainer" class="space-y-6 max-h-[60vh] overflow-y-auto pr-2">
                        <!-- Dynamically filled via JS -->
                    </div>
                </div>

                <!-- Step Buttons -->
                <div class="flex justify-between pt-4">
                    <button type="button" id="backBtn"
                        class="hidden px-4 py-2 rounded-md text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600"
                        onclick="goToStep(1)">← Back</button>
                    <button type="button" id="nextBtn"
                        class="ml-auto bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium"
                        onclick="handleNextStep()">Next →</button>
                    <button type="submit" id="submitBtn"
                        class="hidden bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">Create
                        Season</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Season Modal -->
    <div id="editSeasonModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center px-4">
        <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="seasonModalTitle" class="text-xl font-bold text-gray-900 dark:text-white">Edit Season</h3>
                <button onclick="closeEditSeasonModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="seasonForm" class="space-y-5">
                <!-- Season Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="seasonName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season
                            Name</label>
                        <input type="text" id="seasonName"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="e.g. 2024 Dry Season">
                    </div>
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Budget
                            (₦)</label>
                        <input type="number" id="budget"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="e.g. 2500000000">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                            Date</label>
                        <input type="date" id="startDate"
                            class="mt-1 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                            Date</label>
                        <input type="date" id="endDate"
                            class="mt-1 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>

                <!-- Return Date Mode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Return Date
                        Mode</label>
                    <select id="returnMode" onchange="toggleReturnMode()"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md">
                        <option value="global">Global Return Date (all commodities)</option>
                        <option value="per-commodity">Custom Return Dates per Commodity</option>
                    </select>
                </div>

                <!-- Global Return Date -->
                <div id="globalReturnDateGroup" class="mt-2">
                    <label for="returnDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Return
                        Date</label>
                    <input type="date" id="returnDate"
                        class="mt-1 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Per-Commodity Return Dates -->
                <div id="perCommodityDates" class="hidden mt-2 space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="text-sm text-gray-700 dark:text-gray-300">Maize Seeds Return Date</label>
                        <input type="date"
                            class="w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
                    </div>
                    <div class="flex justify-between items-center">
                        <label class="text-sm text-gray-700 dark:text-gray-300">Rice Seeds Return Date</label>
                        <input type="date"
                            class="w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
                    </div>
                    <!-- You can dynamically add more based on season commodity list -->
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeSeasonModal()"
                        class="bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">
                        Save Season
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="quotaModal"
        class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-start justify-center overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mt-24 mx-4 p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Adjust Quota for <span
                        id="tenantName">Tenant</span></h3>
                <button onclick="closeQuotaModal()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>

            <form id="adjustQuotaForm" class="space-y-4">
                <div>
                    <label for="newQuota" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Quota
                        *</label>
                    <input type="number" id="newQuota" required min="0"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeQuotaModal()"
                        class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancel</button>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-5 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function toggleReturnMode() {
            const mode = document.getElementById('returnMode').value;
            document.getElementById('globalReturnDateGroup').classList.toggle('hidden', mode !== 'global');
            document.getElementById('perCommodityDates').classList.toggle('hidden', mode !== 'per-commodity');
        }

        function openEditSeasonModal() {
            document.getElementById('editSeasonModal').classList.remove('hidden');
            document.getElementById('seasonModalTitle').innerText = 'Edit Season';
            toggleReturnMode(); // initialize return mode visibility
        }

        function closeEditSeasonModal() {
            document.getElementById('editSeasonModal').classList.add('hidden');
        }

        function adjustQuota(zoneId) {
            document.getElementById('quotaModal').classList.remove('hidden');
            document.getElementById('tenantName').innerText = zoneId.replace('-', ' ').toUpperCase();
        }

        function closeQuotaModal() {
            document.getElementById('quotaModal').classList.add('hidden');
        }

        document.getElementById('adjustQuotaForm').addEventListener('submit', function (e) {
            e.preventDefault();
            // save quota to backend
            alert("Quota updated!");
            closeQuotaModal();
        });
        const availableCommodities = {
            maize: { name: "Maize Seeds", stock: 12000 },
            rice: { name: "Rice Seeds", stock: 8000 }
        };

        const tenants = [
            "North Central", "North East", "North West", "South South", "South East", "South West"
        ];

        let allocationMap = {}; // { commodity: remainingStock }

        function openSeasonModal() {
            document.getElementById('seasonModal').classList.remove('hidden');
            goToStep(1);

            const commoditySelect = document.getElementById('commodities');
            commoditySelect.innerHTML = "";
            for (const key in availableCommodities) {
                const opt = document.createElement("option");
                opt.value = key;
                opt.textContent = availableCommodities[key].name;
                commoditySelect.appendChild(opt);
            }
        }

        function closeSeasonModal() {
            document.getElementById('seasonModal').classList.add('hidden');
        }

        function goToStep(step) {
            document.getElementById('step1').classList.toggle('hidden', step !== 1);
            document.getElementById('step2').classList.toggle('hidden', step !== 2);
            document.getElementById('backBtn').classList.toggle('hidden', step !== 2);
            document.getElementById('nextBtn').classList.toggle('hidden', step !== 1);
            document.getElementById('submitBtn').classList.toggle('hidden', step !== 2);
        }

        function handleNextStep() {
            const selectedCommodities = Array.from(document.getElementById('commodities').selectedOptions).map(opt => opt.value);
            if (!selectedCommodities.length) {
                alert("Please select at least one commodity.");
                return;
            }

            allocationMap = {};
            selectedCommodities.forEach(c => allocationMap[c] = availableCommodities[c].stock);

            const container = document.getElementById("allocationContainer");
            container.innerHTML = "";

            tenants.forEach(tenant => {
                const block = document.createElement("div");
                block.className = "border-b border-gray-200 dark:border-gray-700 pb-4";

                const title = `<h5 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">${tenant}</h5>`;
                const rows = selectedCommodities.map(commodityKey => {
                    const commodity = availableCommodities[commodityKey];
                    return `
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-2 items-center">
          <div><span class="text-sm text-gray-700 dark:text-gray-300">${commodity.name}</span></div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Stock: <span id="stock-${commodityKey}" class="font-medium">${allocationMap[commodityKey]}</span> bags</div>
          <div>
            <input type="number" min="0" value="0"
              data-tenant="${tenant}" data-commodity="${commodityKey}"
              class="allocationInput w-full px-2 py-1 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              oninput="updateStock(this)">
          </div>
        </div>
      `;
                }).join("");

                block.innerHTML = title + rows;
                container.appendChild(block);
            });

            goToStep(2);
        }

        function updateStock(input) {
            const commodity = input.dataset.commodity;
            const allInputs = document.querySelectorAll(`input[data-commodity="${commodity}"]`);
            let totalAllocated = 0;

            allInputs.forEach(inp => {
                totalAllocated += parseInt(inp.value || "0");
            });

            const remaining = Math.max(0, availableCommodities[commodity].stock - totalAllocated);
            allocationMap[commodity] = remaining;
            document.getElementById(`stock-${commodity}`).textContent = remaining;
        }

        // Profile dropdown
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');

        profileDropdown.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Dark mode functionality
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            html.classList.add('dark');
        }

        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });


    </script>
@endsection
