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
                    <h1 class="ml-3 text-xl font-bold text-gray-900 dark:text-white">AFNON</h1>
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
                    <a href="/central/login" class="text-emerald-600 hover:text-emerald-500 font-medium">Back to
                        Login</a>
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
                    <!-- Seed Selection (Card Layout) -->
                    <div id="seed-selection" class="hidden mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Select a Seed</h3>
                        <div id="seed-options" class="grid md:grid-cols-2 gap-4"></div>
                    </div>

                    <!-- Commodities Breakdown Table -->
                    <div id="other-commodities-section" class="hidden mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Commodities Breakdown</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm border rounded-lg overflow-hidden dark:border-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Commodity</th>
                                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Quantity</th>
                                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Unit Price
                                        </th>
                                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="other-commodities-list"
                                    class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Loan Summary -->
                    <div id="loan-summary" class="hidden mb-6">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div
                                class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border dark:border-emerald-700">
                                <p id="total-loan" class="font-semibold text-gray-900 dark:text-white"></p>
                            </div>
                            <div
                                class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border dark:border-yellow-700">
                                <p id="equity-held" class="font-semibold text-gray-900 dark:text-white"></p>
                            </div>
                            <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border dark:border-blue-700">
                                <p id="disbursed-amount" class="font-semibold text-gray-900 dark:text-white"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Equity Note -->
                    <div id="equity-note" class="hidden mb-6 text-sm text-yellow-700 dark:text-yellow-300">
                        Note: You will only receive 50% of the loan value. The organization holds 50% as equity.
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
                    <!-- Equity Acknowledgment -->
                    <div id="equity-note" class="flex items-center">
                        <input type="checkbox" required
                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            <span>I understand that 50% of the total value of commodities will be held as equity by the
                                organization and I will receive the remaining 50%.</span>
                        </label>
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
    <div id="success-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 ease-out">
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
                        shortly.
                    </p>
                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Application Reference: <span
                                id="ref-number"
                                class="text-emerald-600 dark:text-emerald-400">NECAS-2024-001234</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Keep this reference number for
                            tracking your application</p>

                        <!-- Commodity Summary Table -->
                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full text-sm border-collapse border border-gray-300 dark:border-gray-600">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 border text-left dark:text-white">Commodity</th>
                                        <th class="px-4 py-2 border text-left dark:text-white">Quantity</th>
                                        <th class="px-4 py-2 border text-left dark:text-white">Unit Price</th>
                                        <th class="px-4 py-2 border text-left dark:text-white">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="summary-table-body"
                                    class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                    <!-- Injected rows -->
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 font-semibold">Insurance (1%)</td>
                                        <td id="summary-insurance" class="px-4 py-2 font-semibold"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 font-semibold">Total Loan</td>
                                        <td id="summary-total" class="px-4 py-2 font-semibold"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 font-semibold">Equity Held</td>
                                        <td id="summary-equity" class="px-4 py-2 font-semibold"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 font-semibold">Disbursed Amount</td>
                                        <td id="summary-disbursed" class="px-4 py-2 font-semibold"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
        // Dark Mode
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') html.classList.add('dark');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            });
        }

        // Commodity Dataset
        const commodityData = {
            'dry-2024': {
                seeds: [{
                    id: 'maize',
                    name: 'Maize Seeds',
                    unit: 'bags',
                    qtyPerHectare: 2,
                    price: 10000
                }],
                others: [{
                        name: 'NPK Fertilizer',
                        unit: 'bags',
                        qtyPerHectare: 3,
                        price: 8000
                    },
                    {
                        name: 'Urea Fertilizer',
                        unit: 'bags',
                        qtyPerHectare: 2,
                        price: 9000
                    },
                    {
                        name: 'Herbicide',
                        unit: 'litres',
                        qtyPerHectare: 3,
                        price: 3000
                    },
                    {
                        name: 'Insecticide',
                        unit: 'litres',
                        qtyPerHectare: 3,
                        price: 10000
                    },
                    {
                        name: 'Water Generator',
                        unit: 'unit',
                        qtyPerHectare: 1,
                        price: 50000
                    }
                ]
            },
            'wet-2024': {
                seeds: [{
                        id: 'rice',
                        name: 'Rice Seeds',
                        unit: 'bags',
                        qtyPerHectare: 2.5,
                        price: 11000
                    },
                    {
                        id: 'millet',
                        name: 'Millet Seeds',
                        unit: 'bags',
                        qtyPerHectare: 2,
                        price: 9500
                    }
                ],
                others: [{
                        name: 'NPK Fertilizer',
                        unit: 'bags',
                        qtyPerHectare: 3,
                        price: 8000
                    },
                    {
                        name: 'Urea Fertilizer',
                        unit: 'bags',
                        qtyPerHectare: 2,
                        price: 9000
                    },
                    {
                        name: 'Herbicide',
                        unit: 'litres',
                        qtyPerHectare: 3,
                        price: 3000
                    },
                    {
                        name: 'Insecticide',
                        unit: 'litres',
                        qtyPerHectare: 3,
                        price: 10000
                    }
                ]
            }
        };

        function renderCommoditiesForSeason() {
            const season = document.getElementById('season-select').value;
            const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
            const seedSection = document.getElementById('seed-selection');
            const seedList = document.getElementById('seed-options');
            const otherSection = document.getElementById('other-commodities-section');
            const otherList = document.getElementById('other-commodities-list');
            const totalText = document.getElementById('total-loan');
            const equityText = document.getElementById('equity-held');
            const disbursedText = document.getElementById('disbursed-amount');
            const summaryBox = document.getElementById('loan-summary');
            const equityNote = document.getElementById('equity-note');

            if (!season || farmSize <= 0) {
                seedSection.classList.add('hidden');
                otherSection.classList.add('hidden');
                summaryBox.classList.add('hidden');
                equityNote.classList.add('hidden');
                return;
            }

            const data = commodityData[season];
            let total = 0;

            // Render seeds
            seedList.innerHTML = '';
            data.seeds.forEach(seed => {
                const html = `
                <label class="block cursor-pointer border rounded-lg p-4 bg-white dark:bg-gray-800 shadow hover:shadow-md transition">
                    <input type="radio" name="selected-seed" required value="${seed.id}" data-price="${seed.price}" data-qty="${seed.qtyPerHectare}" class="hidden">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white">${seed.name}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${seed.qtyPerHectare} ${seed.unit}/ha × ₦${seed.price.toLocaleString()}</p>
                        </div>
                        <div class="text-emerald-600 dark:text-emerald-400 font-bold">Select</div>
                    </div>
                </label>
                    `;
                seedList.innerHTML += html;
            });


            seedSection.classList.remove('hidden');
            otherSection.classList.remove('hidden');
            equityNote.classList.remove('hidden');
            summaryBox.classList.remove('hidden');
            otherList.innerHTML = '';

            document.querySelectorAll('input[name="selected-seed"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    const qty = parseFloat(radio.dataset.qty);
                    const price = parseFloat(radio.dataset.price);
                    total = qty * price * farmSize;

                    otherList.innerHTML = '';
                    data.others.forEach(commodity => {
                        const quantity = commodity.qtyPerHectare * farmSize;
                        const value = quantity * commodity.price;
                        total += value;
                        const row = `
                    <tr>
                        <td class="px-4 py-2 text-gray-900 dark:text-white">${commodity.name}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">${quantity} ${commodity.unit}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">₦${commodity.price.toLocaleString()}</td>
                        <td class="px-4 py-2 font-semibold text-gray-900 dark:text-white">₦${value.toLocaleString()}</td>
                    </tr>`;
                        otherList.innerHTML += row;

                    });

                    const equity = total / 2;
                    totalText.innerHTML = `Total Loan Value: <strong>₦${total.toLocaleString()}</strong>`;
                    equityText.innerHTML =
                        `Equity Held by Organization: <strong>₦${equity.toLocaleString()}</strong>`;
                    disbursedText.innerHTML =
                        `Amount Disbursed to Farmer: <strong>₦${equity.toLocaleString()}</strong>`;
                });
            });
        }

        document.getElementById('season-select').addEventListener('change', renderCommoditiesForSeason);
        document.getElementById('farm-size').addEventListener('input', renderCommoditiesForSeason);

        // Update LGAs
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

        // BVN Simulation
        function validateBVN() {
            const bvnInput = document.getElementById('bvn-input');
            const bvnStatus = document.getElementById('bvn-status');
            if (bvnInput.value.length === 11) {
                bvnStatus.classList.remove('hidden');
                setTimeout(() => {
                    bvnStatus.innerHTML = `
                    <span class="text-green-600 dark:text-green-400">
                        <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        BVN verified successfully
                    </span>`;
                }, 2000);
            } else {
                bvnStatus.classList.add('hidden');
            }
        }

        function handleSubmission(e) {
            e.preventDefault();

            const season = document.getElementById('season-select').value;
            const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
            const selectedSeed = document.querySelector('input[name="selected-seed"]:checked');

            if (!season || !selectedSeed || farmSize <= 0) {
                alert("Please fill in all required information before submitting.");
                return;
            }

            const data = commodityData[season];
            const seedData = data.seeds.find(seed => seed.id === selectedSeed.value);
            const seedQty = seedData.qtyPerHectare * farmSize;
            const seedTotal = seedQty * seedData.price;

            let total = seedTotal;
            let rows = `
        <tr>
            <td class="px-4 py-2 border">${seedData.name}</td>
            <td class="px-4 py-2 border">${seedQty} ${seedData.unit}</td>
            <td class="px-4 py-2 border">₦${seedData.price.toLocaleString()}</td>
            <td class="px-4 py-2 border font-medium">₦${seedTotal.toLocaleString()}</td>
        </tr>
    `;

            // Other Commodities
            data.others.forEach(item => {
                const qty = item.qtyPerHectare * farmSize;
                const val = qty * item.price;
                total += val;
                rows += `
            <tr>
                <td class="px-4 py-2 border">${item.name}</td>
                <td class="px-4 py-2 border">${qty} ${item.unit}</td>
                <td class="px-4 py-2 border">₦${item.price.toLocaleString()}</td>
                <td class="px-4 py-2 border font-medium">₦${val.toLocaleString()}</td>
            </tr>
        `;
            });

            const insuranceFee = total * 0.01;
            total += insuranceFee;
            const equity = total / 2;

            // Inject table rows and summary
            document.getElementById('summary-table-body').innerHTML = rows;
            document.getElementById('summary-insurance').textContent = `₦${insuranceFee.toLocaleString()}`;
            document.getElementById('summary-total').textContent = `₦${total.toLocaleString()}`;
            document.getElementById('summary-equity').textContent = `₦${equity.toLocaleString()}`;
            document.getElementById('summary-disbursed').textContent = `₦${equity.toLocaleString()}`;

            const ref = 'NECAS-' + Math.floor(Math.random() * 900000 + 100000);
            document.getElementById('ref-number').textContent = ref;

            // Show modal and reset form
            document.getElementById('success-modal').classList.remove('hidden');
            window.scrollTo(0, 0);

            document.getElementById('application-form').reset();
            document.getElementById('seed-selection').classList.add('hidden');
            document.getElementById('other-commodities-section').classList.add('hidden');
            document.getElementById('loan-summary').classList.add('hidden');
            document.getElementById('equity-note').classList.add('hidden');
        }

        function closeSuccessModal() {
            document.getElementById('success-modal').classList.add('hidden');
        }

        function downloadAcknowledgment() {
            alert("Downloading acknowledgment slip... (feature coming soon)");
        }
    </script>

    {{-- <p class="text-sm text-gray-500 dark:text-gray-400">Available: ${commodity.available} ${commodity.unit}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Rate: ${commodity.ratePerHectare} ${commodity.unit} per hectare</p> --}}
    {{-- // <p class="text-sm font-medium text-gray-900 dark:text-white" id="quantity-${key}">-</p> --}}
    {{-- // <p class="text-xs text-gray-500 dark:text-gray-400">Calculated quantity</p> --}}
</body>

</html>
