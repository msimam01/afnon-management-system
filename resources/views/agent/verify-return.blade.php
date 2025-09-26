@extends('layouts.layout')

@section('content')
    <div x-data="returnApp()" class="w-full px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <!-- Enhanced Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Return Verification</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Verify farmer commodity returns and documentation</p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Filters -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" x-model.debounce.500ms="filter" placeholder="Search Farmer Name or ID"
                                class="w-full sm:w-64 pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                        </div>
                        <select x-model="season"
                            class="w-full sm:w-64 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">All Seasons</option>
                            @foreach ($seasons as $item)
                                <option value="{{ $item->slug }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <select x-model="status"
                            class="w-full sm:w-64 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="verified">Verified</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></div>
                            Pending
                        </span>
                        <span class="flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                            Verified
                        </span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Farmer Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Commodities</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Loan Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="app in applications" :key="app.id">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-500 rounded-lg flex items-center justify-center mr-3">
                                                <span class="text-white text-sm font-bold" x-text="app.farmer.full_name.charAt(0)"></span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="app.farmer.full_name"></div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400" x-text="app.farmer.registration_number"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <template x-for="c in app.commodity_allocations" :key="c.id">
                                                <div class="flex items-center text-sm">
                                                    <div class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></div>
                                                    <span class="text-gray-900 dark:text-white" x-text="c.commodity_name"></span>
                                                    <span class="ml-2 text-gray-500 dark:text-gray-400" x-text="`(${c.allocated_quantity})`"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-indigo-600 dark:text-indigo-400" x-text="`₦${app.total_loan ? app.total_loan.toLocaleString() : '0'}`"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Total Loan</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="{
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': app.return_status === 'verified',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': app.return_status === 'pending'
                                        }" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <svg :class="{
                                                'text-green-400': app.return_status === 'verified',
                                                'text-yellow-400': app.return_status === 'pending'
                                            }" class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            <span x-text="app.return_status === 'verified' ? 'Verified' : 'Pending'"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button x-show="app.return_status === 'pending'"
                                            @click="openReturnModal(app)"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                            Verify Return
                                        </button>
                                        <span x-show="app.return_status === 'verified'" class="inline-flex items-center px-3 py-2 text-sm text-green-600 dark:text-green-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Completed
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="applications.length === 0 && !loading">
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">No applications found.</p>
                                    </div>
                                </td>
                            </tr>
                            <tr x-show="loading">
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <div class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-gray-500 dark:text-gray-400">Loading applications...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-6 py-3 flex flex-col sm:flex-row justify-between items-center" x-show="last_page > 1">
                <div class="dark:text-gray-500 text-sm mb-2 sm:mb-0">
                    Showing <span x-text="from"></span> to <span x-text="to"></span> of <span x-text="total"></span> results
                </div>
                <div class="space-x-1 dark:text-gray-300 flex items-center">
                    <button @click="goToPage(current_page - 1)" :disabled="current_page === 1"
                        class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50">Prev</button>
                    <template x-for="page in pages" :key="page">
                        <button @click="goToPage(page)"
                            :class="{
                                'bg-emerald-500 text-white': current_page === page,
                                'bg-gray-200 dark:bg-gray-700': current_page !== page
                            }"
                            class="px-3 py-1 rounded hover:bg-emerald-400 transition">
                            <span x-text="page"></span>
                        </button>
                    </template>
                    <button @click="goToPage(current_page + 1)" :disabled="current_page === last_page"
                        class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50">Next</button>
                </div>
            </div>
        </div>

        <!-- Enhanced Return Modal -->
        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center overflow-y-auto p-4">
            <div @click.away="closeReturnModal()"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-7xl my-8 p-6 sm:p-8 relative max-h-[95vh] overflow-y-auto border border-gray-200 dark:border-gray-700">
                <!-- Enhanced Modal Header -->
                <div class="flex items-center justify-between mb-8 border-b border-gray-200 dark:border-gray-600 pb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Verify Return</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Confirm farmer identity and commodity return</p>
                        </div>
                    </div>
                    <button @click="closeReturnModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="returnForm" class="space-y-8" enctype="multipart/form-data" @submit.prevent="submitReturn">
                    <input type="hidden" name="application_id" x-model="form.application_id" />

                    <!-- Enhanced Information Cards -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Farmer Information Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-blue-200 dark:border-gray-600 shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Farmer Information</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Full Name</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="modalData.farmer?.full_name"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Registration No.</span>
                                    <span class="text-sm font-mono bg-blue-100 dark:bg-gray-600 px-2 py-1 rounded text-blue-800 dark:text-blue-200" x-text="modalData.farmer?.registration_number"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Phone Number</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="modalData.farmer?.phone"></span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">BVN</span>
                                    <span class="text-sm font-mono bg-blue-100 dark:bg-gray-600 px-2 py-1 rounded text-blue-800 dark:text-blue-200" x-text="modalData.farmer?.bvn"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Application Information Card -->
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-indigo-200 dark:border-gray-600 shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Application Details</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2 border-b border-indigo-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Reference Number</span>
                                    <span class="text-sm font-mono bg-indigo-100 dark:bg-gray-600 px-2 py-1 rounded text-indigo-800 dark:text-indigo-200" x-text="modalData.reference_number"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-indigo-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Season</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="modalData.season?.name"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-indigo-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Farm Size</span>
                                    <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400" x-text="`${modalData.farm?.size} hectares`"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-indigo-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Collection Date</span>
                                    <span class="text-sm text-gray-900 dark:text-white" x-text="modalData.application_center?.collection_date"></span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Return Deadline</span>
                                    <span class="text-sm text-red-600 dark:text-red-400 font-medium" x-text="modalData.application_center?.return_date"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-t border-indigo-200 dark:border-gray-600 mt-2 pt-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Expected Return</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="modalData.expected_return && modalData.expected_return.quantity ? `${modalData.expected_return.quantity} ${modalData.expected_return.unit} of ${modalData.expected_return.commodity}` : '—'"></span>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Price Used</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-300" x-text="modalData.expected_return && modalData.expected_return.price_used ? `₦${Number(modalData.expected_return.price_used).toLocaleString()}` : '—'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Enhanced Commodity Breakdown -->
                    <div class="mb-8">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Commodity Breakdown & Financial Summary</h4>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                                        <tr>
                                            <th class="px-6 py-4 text-left font-semibold">Commodity</th>
                                            <th class="px-6 py-4 text-left font-semibold">Qty/Ha</th>
                                            <th class="px-6 py-4 text-left font-semibold">Farm Size</th>
                                            <th class="px-6 py-4 text-left font-semibold">Allocated Qty</th>
                                            <th class="px-6 py-4 text-left font-semibold">Unit Price</th>
                                            <th class="px-6 py-4 text-left font-semibold">Total Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <template x-for="c in modalData.commodity_allocations" :key="c.id">
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-500 rounded-lg flex items-center justify-center mr-3">
                                                            <span class="text-white text-xs font-bold" x-text="c.commodity_name.charAt(0)"></span>
                                                        </div>
                                                        <span class="font-medium text-gray-900 dark:text-white" x-text="c.commodity_name"></span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300" x-text="c.qty_per_hectare"></td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300" x-text="`${modalData.farm?.size} ha`"></td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200" x-text="c.allocated_quantity"></span>
                                                </td>
                                                <td class="px-6 py-4 font-mono text-gray-700 dark:text-gray-300" x-text="`₦${c.unit_price.toLocaleString()}`"></td>
                                                <td class="px-6 py-4 font-semibold text-emerald-600 dark:text-emerald-400" x-text="`₦${c.total_value.toLocaleString()}`"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Financial Summary Section -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-t border-gray-200 dark:border-gray-600">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 p-6">
                                    <!-- Total Loan -->
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Loan</p>
                                                <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="`₦${modalData.total_loan ? modalData.total_loan.toLocaleString() : '0'}`"></p>
                                            </div>
                                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Insurance Rate -->
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Insurance Rate</p>
                                                <p class="text-lg font-bold text-orange-600 dark:text-orange-400" x-text="`${modalData.insurance_rate || 0}%`"></p>
                                            </div>
                                            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Insurance Amount -->
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Insurance Amount</p>
                                                <p class="text-lg font-bold text-orange-600 dark:text-orange-400" x-text="`₦${modalData.insurance_amount ? modalData.insurance_amount.toLocaleString() : '0'}`"></p>
                                            </div>
                                            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Equity Held -->
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Equity Held</p>
                                                <p class="text-lg font-bold text-purple-600 dark:text-purple-400" x-text="`₦${modalData.equity ? modalData.equity.toLocaleString() : '0'}`"></p>
                                            </div>
                                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Disbursed Amount -->
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Disbursed Amount</p>
                                                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400" x-text="`₦${modalData.disbursed_amount ? modalData.disbursed_amount.toLocaleString() : '0'}`"></p>
                                            </div>
                                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Enhanced File Upload Section -->
                    <div class="grid grid-cols-1 gap-8">
                        <!-- Single Verification Photo Upload -->
                        <div class="space-y-4">
                            <label for="verificationPhotoInput" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Verification Photo *
                            </label>
                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all duration-200 group">
                                <input type="file" name="photo" id="verificationPhotoInput" accept="image/*" required @change="previewImage($event, 'verificationPhotoPreview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/20 transition-colors">
                                        <svg class="w-8 h-8 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Click to capture or upload photo</p>
                                    <p class="text-gray-500 dark:text-gray-500 text-xs">PNG, JPG up to 2MB</p>
                                    <img id="verificationPhotoPreview" class="mt-4 w-40 h-40 object-cover rounded-lg hidden border-2 border-gray-300 dark:border-gray-600 shadow-sm" />
                                </div>
                            </div>

                            <!-- Camera Capture Button -->
                            <button type="button" id="captureVerificationBtn"
                                class="flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                                <i class="fas fa-camera mr-2"></i>
                                Capture Photo
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Submit Section -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" @click="closeReturnModal()"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" id="submitReturnBtn"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                            <span id="submitReturnText">Submit Verification</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('returnApp', () => ({
                filter: '',
                season: '',
                status: '',
                loading: true,
                applications: [],
                showModal: false,
                modalData: {},
                form: {
                    application_id: null
                },
                isMonetaryReturn: false,

                // Pagination data
                current_page: 1,
                last_page: 1,
                from: 0,
                to: 0,
                total: 0,
                pages: [],

                init() {
                    this.fetchAssignedReturns();
                    this.$watch('filter', () => this.goToPage(1));
                    this.$watch('season', () => this.goToPage(1));
                    this.$watch('status', () => this.goToPage(1));
                },

                fetchAssignedReturns() {
                    this.loading = true;
                    this.applications = [];
                    const url = `/agent/verify-return?page=${this.current_page}&filter=${this.filter}&season=${this.season}&status=${this.status}`;
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.json())
                        .then(data => {
                            this.applications = data.data;
                            this.current_page = data.current_page;
                            this.last_page = data.last_page;
                            this.from = data.from;
                            this.to = data.to;
                            this.total = data.total;
                            this.generatePages();
                        })
                        .catch(err => {
                            toastr.error('Failed to load farmers');
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                goToPage(page) {
                    if (page < 1 || page > this.last_page) return;
                    this.current_page = page;
                    this.fetchAssignedReturns();
                },

                generatePages() {
                    this.pages = [];
                    const maxPages = 5;
                    let startPage = Math.max(1, this.current_page - Math.floor(maxPages / 2));
                    let endPage = Math.min(this.last_page, startPage + maxPages - 1);
                    if (endPage - startPage + 1 < maxPages) {
                        startPage = Math.max(1, endPage - maxPages + 1);
                    }
                    for (let i = startPage; i <= endPage; i++) {
                        this.pages.push(i);
                    }
                },

                openReturnModal(app) {
                    this.modalData = app;
                    this.form.application_id = app.id;
                    this.showModal = true;

                    // Reset all file inputs and previews
                    const verificationPhotoInput = document.getElementById('verificationPhotoInput');
                    if (verificationPhotoInput) verificationPhotoInput.value = '';
                    const verificationPhotoPreview = document.getElementById('verificationPhotoPreview');
                    if (verificationPhotoPreview) verificationPhotoPreview.classList.add('hidden');
                },

                closeReturnModal() {
                    this.showModal = false;
                    this.modalData = {};
                    this.form.application_id = null;
                },

                submitReturn() {
                    const form = document.getElementById('returnForm');
                    const formData = new FormData(form);
                    const submitBtn = document.getElementById('submitReturnBtn');
                    const submitText = document.getElementById('submitReturnText');

                    // Add loading state
                    submitBtn.disabled = true;
                    submitText.textContent = 'Processing...';
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    `;

                    fetch('{{ route('agent.verify.return.submit') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.message) {
                            showToast(data.message, 'success');
                            this.closeReturnModal();
                            this.fetchAssignedReturns();
                        } else {
                            showToast('Verification failed!', 'error');
                        }
                    })
                    .catch(err => {
                        showToast('Network error occurred', 'error');
                        console.error('Error:', err);
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = `
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                            <span>Submit Verification</span>
                        `;
                    });
                },

                previewImage(event, previewId) {
                    const file = event.target.files[0];
                    const preview = document.getElementById(previewId);
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = '';
                        preview.classList.add('hidden');
                    }
                }
            }));
        });

        // Enhanced Toast notification function with fallback
        function showToast(message, type) {
            // Fallback if jQuery is not available
            if (typeof $ === 'undefined') {
                // Use native JavaScript fallback
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 translate-x-full`;
                toast.innerHTML = `
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-5 h-5 ${type === 'success' ? 'text-green-400' : 'text-red-400'}">
                                    ${type === 'success' ?
                                        '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' :
                                        '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                                    }
                                </div>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${message}</p>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.style.transform = 'translateX(0)';
                }, 100);

                // Remove after 5 seconds
                setTimeout(() => {
                    toast.style.transform = 'translateX(full)';
                    setTimeout(() => {
                        if (document.body.contains(toast)) {
                            document.body.removeChild(toast);
                        }
                    }, 300);
                }, 5000);
                return;
            }

            const toast = $(`
                <div class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 translate-x-full">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                ${type === 'success'
                                    ? '<svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                                    : '<svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                                }
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${message}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button class="toast-close bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            $('body').append(toast);

            // Slide in
            setTimeout(() => {
                toast.removeClass('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.addClass('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 5000);

            // Manual close
            toast.find('.toast-close').on('click', function() {
                toast.addClass('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            });
        }

        // Camera capture functionality for return form
        document.addEventListener('DOMContentLoaded', function() {
            const captureVerificationBtn = document.getElementById('captureVerificationBtn');
            const verificationPhotoInput = document.getElementById('verificationPhotoInput');
            const verificationPhotoPreview = document.getElementById('verificationPhotoPreview');

            if (captureVerificationBtn) {
                captureVerificationBtn.addEventListener('click', function() {
                    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                        const constraints = {
                            video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }
                        };
                        navigator.mediaDevices.getUserMedia(constraints)
                            .then(function(stream) {
                                const cameraModal = document.createElement('div');
                                cameraModal.className = 'fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center';
                                cameraModal.innerHTML = `
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-lg w-full mx-4">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Capture Verification Photo</h3>
                                            <button id="closeCamera" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>
                                        <div class="relative">
                                            <video id="cameraVideo" autoplay playsinline class="w-full h-80 bg-gray-200 dark:bg-gray-700 rounded-lg object-cover"></video>
                                            <canvas id="cameraCanvas" class="hidden"></canvas>
                                            <div class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-sm">
                                                <i class="fas fa-camera mr-1"></i>Live Camera
                                            </div>
                                        </div>
                                        <div class="flex justify-center mt-4 space-x-4">
                                            <button id="captureBtn" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg">
                                                <i class="fas fa-camera mr-2"></i>Capture Photo
                                            </button>
                                            <button id="retakeBtn" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors hidden">
                                                <i class="fas fa-redo mr-2"></i>Retake
                                            </button>
                                        </div>
                                        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            Position the returned commodity clearly in the camera view
                                        </p>
                                    </div>
                                `;
                                document.body.appendChild(cameraModal);
                                const video = document.getElementById('cameraVideo');
                                const canvas = document.getElementById('cameraCanvas');
                                const captureBtn = document.getElementById('captureBtn');
                                const closeCamera = document.getElementById('closeCamera');
                                video.srcObject = stream;
                                captureBtn.addEventListener('click', function() {
                                    canvas.width = video.videoWidth;
                                    canvas.height = video.videoHeight;
                                    const ctx = canvas.getContext('2d');
                                    ctx.drawImage(video, 0, 0);
                                    canvas.toBlob(function(blob) {
                                        const file = new File([blob], 'captured-photo.jpg', { type: 'image/jpeg' });
                                        const dataTransfer = new DataTransfer();
                                        dataTransfer.items.add(file);
                                        verificationPhotoInput.files = dataTransfer.files;
                                        const event = new Event('change', { bubbles: true });
                                        verificationPhotoInput.dispatchEvent(event);
                                        const url = URL.createObjectURL(blob);
                                        verificationPhotoPreview.src = url;
                                        verificationPhotoPreview.classList.remove('hidden');
                                        stream.getTracks().forEach(track => track.stop());
                                        document.body.removeChild(cameraModal);
                                    }, 'image/jpeg', 0.8);
                                });
                                closeCamera.addEventListener('click', function() {
                                    stream.getTracks().forEach(track => track.stop());
                                    document.body.removeChild(cameraModal);
                                });
                                cameraModal.addEventListener('click', function(e) {
                                    if (e.target === cameraModal) {
                                        stream.getTracks().forEach(track => track.stop());
                                        document.body.removeChild(cameraModal);
                                    }
                                });
                            })
                            .catch(function(error) {
                                console.error('Error accessing camera:', error);
                                alert('Unable to access camera. Please check permissions or use file upload instead.');
                            });
                    } else {
                        alert('Camera not supported on this device. Please use file upload instead.');
                    }
                });
            }
        });
    </script>
@endsection
