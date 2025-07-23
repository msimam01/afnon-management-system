<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Seasonal Loan - NECAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                }
            }
        }
    </script>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <nav
        class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <button onclick="history.back()"
                        class="mr-4 p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="h-8 w-8 bg-emerald-600 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <h1 class="ml-3 text-xl font-bold text-gray-900 dark:text-white">North East Commodity Distribution Associations (NECAS)</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <svg id="sunIcon" class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <svg id="moonIcon" class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>
                    <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-500 font-medium">Back to Login</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pt-16">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl pt-4 font-bold text-gray-900 dark:text-white">Apply for Seasonal Loan</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Submit your application for agricultural commodity support
            </p>
        </div>

        <!-- Application Form -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
            <div class="px-6 py-8">
                <form id="application-form" onsubmit="handleSubmission(event)" class="space-y-8">
                    <!-- Season Selection -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Season Selection</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Season *
                            </label>
                            <select id="season-select" required onchange="updateCommodities()"
                                class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Choose season...</option>
                                <option value="dry-2024">2024 Dry Season (Open - Ends March 31)</option>
                                <option value="wet-2024">2024 Wet Season (Open - Ends September 30)</option>
                                <option value="dry-2025" disabled>2025 Dry Season (Closed)</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Applications are processed on a
                                first-come, first-served basis</p>
                        </div>
                    </div>

                    <!-- Farmer Details -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Farmer Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Full Name *
                                </label>
                                <input type="text" required
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Phone Number *
                                </label>
                                <input type="tel" required pattern="[0-9+\-\s]+"
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="+234 xxx xxx xxxx">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    NIN (National Identification Number) *
                                </label>
                                <input type="text" required pattern="[0-9]{11}" maxlength="11"
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="12345678901">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    BVN (Bank Verification Number) *
                                </label>
                                <input type="text" id="bvn-input" required pattern="[0-9]{11}" maxlength="11"
                                    onchange="validateBVN()"
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="12345678901">
                                <div id="bvn-status" class="mt-1 text-sm hidden">
                                    <span class="text-yellow-600 dark:text-yellow-400">
                                        <svg class="inline w-4 h-4 mr-1 animate-spin" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        Verifying BVN details...
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    State *
                                </label>
                                <select required onchange="updateLGAs()"
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Select State</option>
                                    <option value="kaduna">Kaduna</option>
                                    <option value="niger">Niger</option>
                                    <option value="kano">Kano</option>
                                    <option value="katsina">Katsina</option>
                                    <option value="sokoto">Sokoto</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    LGA (Local Government Area) *
                                </label>
                                <select id="lga-select" required
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Select LGA</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Address *
                                </label>
                                <textarea required rows="3"
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Enter your full address"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Farm Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Farm Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Farm Location *
                                </label>
                                <input type="text" required
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Village/Town where farm is located">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Farm Size (Hectares) *
                                </label>
                                <input type="number" id="farm-size" required min="0.1" step="0.1"
                                    class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="e.g., 2.5">
                            </div>
                        </div>
                        <div class="md:col-span-2 pt-5">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Cluster Farm Location
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="e.g., Igabi West">
                        </div>
                    </div>

                    <!-- Commodities Section -->
                    <div id="commodities-section" class="hidden">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Available Commodities</h3>
                        <div id="commodities-list" class="space-y-4">
                            <!-- Commodities will be populated here -->
                        </div>
                        {{-- <div id="commodity-calculation"
                            class="hidden mt-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-lg">
                            <h4 class="font-medium text-emerald-800 dark:text-emerald-200 mb-2">Calculated Allocation
                            </h4>
                            <div id="calculation-details" class="text-sm text-emerald-700 dark:text-emerald-300">
                                <!-- Calculation details will be shown here -->
                            </div>
                        </div> --}}
                    </div>

                    <!-- BVN Validation Note -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-blue-800 dark:text-blue-200">BVN Verification</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                    Your BVN will be verified against your provided details to ensure authenticity. This
                                    process may take a few minutes after submission.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" required
                                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                I agree to the <a href="#"
                                    class="text-emerald-600 hover:text-emerald-500 dark:text-emerald-400">Terms and
                                    Conditions</a> and confirm that all information provided is accurate
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-8 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            Apply Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Application Submitted
                    Successfully!</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Your application has been received and is being processed. You will receive an SMS notification
                        with your application reference number shortly.
                    </p>
                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Application Reference: <span
                                id="ref-number"
                                class="text-emerald-600 dark:text-emerald-400">NECAS-2024-001234</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Keep this reference number for
                            tracking your application</p>
                    </div>
                </div>
                <div class="items-center px-4 py-3 space-y-2">
                    <button onclick="downloadAcknowledgment()"
                        class="px-4 py-2 bg-emerald-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        Download Acknowledgment Slip
                    </button>
                    <button onclick="closeSuccessModal()"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <script>
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

        // Commodity data based on seasons
        const commodityData = {
            'dry-2024': {
                'maize': {
                    name: 'Maize Seeds',
                    // available: 1000,
                    // ratePerHectare: 20,
                    // unit: 'bags'
                },
                'fertilizer': {
                    name: 'NPK Fertilizer',
                    // available: 800,
                    // ratePerHectare: 15,
                    // unit: 'bags'
                }
            },
            'wet-2024': {
                'rice': {
                    name: 'Rice Seeds',
                    // available: 600,
                    // ratePerHectare: 18,
                    // unit: 'bags'
                },
                'yam': {
                    name: 'Yam Seedlings',
                    // available: 400,
                    // ratePerHectare: 12,
                    // unit: 'bundles'
                }
            }
        };

        // LGA data
        const lgaData = {
            'kaduna': ['Igabi', 'Kaduna North', 'Kaduna South', 'Chikun', 'Kajuru'],
            'niger': ['Minna', 'Suleja', 'Kontagora', 'Bida', 'Mokwa'],
            'kano': ['Kano Municipal', 'Fagge', 'Dala', 'Gwale', 'Tarauni'],
            'katsina': ['Katsina', 'Funtua', 'Daura', 'Malumfashi', 'Dutsin-Ma'],
            'sokoto': ['Sokoto North', 'Sokoto South', 'Wamako', 'Dange Shuni', 'Gudu']
        };

        function updateLGAs() {
            const stateSelect = event.target;
            const lgaSelect = document.getElementById('lga-select');
            const selectedState = stateSelect.value;

            lgaSelect.innerHTML = '<option value="">Select LGA</option>';

            if (selectedState && lgaData[selectedState]) {
                lgaData[selectedState].forEach(lga => {
                    const option = document.createElement('option');
                    option.value = lga.toLowerCase().replace(/\s+/g, '-');
                    option.textContent = lga;
                    lgaSelect.appendChild(option);
                });
            }
        }

        function updateCommodities() {
            const seasonSelect = document.getElementById('season-select');
            const commoditiesSection = document.getElementById('commodities-section');
            const commoditiesList = document.getElementById('commodities-list');

            if (seasonSelect.value) {
                const commodities = commodityData[seasonSelect.value];

                commoditiesList.innerHTML = '';

                Object.keys(commodities).forEach(key => {
                    const commodity = commodities[key];

                    const commodityHTML = `
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="commodity-${key}" name="commodities" value="${key}"
                                           onchange="calculateCommodities()"
                                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">${commodity.name}</h4>
                                    </div>
                                </div>
                                <div class="text-right">

                                </div>
                            </div>
                        </div>
                    `;

                    commoditiesList.innerHTML += commodityHTML;
                });

                commoditiesSection.classList.remove('hidden');
                calculateCommodities();
            } else {
                commoditiesSection.classList.add('hidden');
            }
        }

        function calculateCommodities() {
            const farmSize = parseFloat(document.getElementById('farm-size').value) || 0;
            const seasonSelect = document.getElementById('season-select');
            const selectedCommodities = document.querySelectorAll('input[name="commodities"]:checked');
            const calculationSection = document.getElementById('commodity-calculation');
            const calculationDetails = document.getElementById('calculation-details');

            if (farmSize > 0 && seasonSelect.value) {
                const commodities = commodityData[seasonSelect.value];

                // Update individual quantities
                Object.keys(commodities).forEach(key => {
                    const commodity = commodities[key];
                    const quantity = Math.floor(farmSize * commodity.ratePerHectare);
                    const quantityElement = document.getElementById(`quantity-${key}`);
                    if (quantityElement) {
                        quantityElement.textContent = `${quantity} ${commodity.unit}`;
                    }
                });

                // Show calculation details for selected commodities
                if (selectedCommodities.length > 0) {
                    let calculationHTML = '<div class="space-y-2">';

                    selectedCommodities.forEach(checkbox => {
                        const commodity = commodities[checkbox.value];
                        const quantity = Math.floor(farmSize * commodity.ratePerHectare);

                        calculationHTML += `
                            <div class="flex justify-between items-center">
                                <span>${commodity.name}:</span>
                                <span class="font-medium">${quantity} ${commodity.unit}</span>
                            </div>
                        `;
                    });

                    calculationHTML += `
                        <div class="border-t border-emerald-200 dark:border-emerald-600 mt-2 pt-2">
                            <div class="flex justify-between items-center text-sm">
                                <span>Farm Size:</span>
                                <span>${farmSize} hectares</span>
                            </div>
                        </div>
                    </div>`;

                    calculationDetails.innerHTML = calculationHTML;
                    calculationSection.classList.remove('hidden');
                } else {
                    calculationSection.classList.add('hidden');
                }
            }
        }

        function validateBVN() {
            const bvnInput = document.getElementById('bvn-input');
            const bvnStatus = document.getElementById('bvn-status');

            if (bvnInput.value.length === 11) {
                bvnStatus.classList.remove('hidden');

                // Simulate BVN verification
                setTimeout(() => {
                    bvnStatus.innerHTML = `
                        <span class="text-green-600 dark:text-green-400">
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            BVN verified successfully
                        </span>
                    `;
                }, 2000);
            } else {
                bvnStatus.classList.add('hidden');
            }
        }

        function handleSubmission(event) {
            event.preventDefault();

            // Generate reference number
            const refNumber = 'NECAS-2024-' + Math.floor(Math.random() * 900000 + 100000);
            document.getElementById('ref-number').textContent = refNumber;

            // Show success modal
            document.getElementById('success-modal').classList.remove('hidden');
        }

        function closeSuccessModal() {
            document.getElementById('success-modal').classList.add('hidden');
            // Reset form
            document.getElementById('application-form').reset();
            document.getElementById('commodities-section').classList.add('hidden');
            document.getElementById('commodity-calculation').classList.add('hidden');
            document.getElementById('bvn-status').classList.add('hidden');
        }

        function downloadAcknowledgment() {
            // Simulate download
            alert('Acknowledgment slip download started. The file will be saved to your downloads folder.');
        }
    </script>
    {{-- <p class="text-sm text-gray-500 dark:text-gray-400">Available: ${commodity.available} ${commodity.unit}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Rate: ${commodity.ratePerHectare} ${commodity.unit} per hectare</p> --}}
    {{-- // <p class="text-sm font-medium text-gray-900 dark:text-white" id="quantity-${key}">-</p> --}}
    {{-- // <p class="text-xs text-gray-500 dark:text-gray-400">Calculated quantity</p> --}}
</body>

</html>
