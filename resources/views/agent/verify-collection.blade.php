@extends('layouts.layout')

@section('content')
    <div x-data="collectionApp()" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <!-- Enhanced Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Collection Verification</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Verify farmer commodity collections and documentation</p>
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
                                class="w-full sm:w-64 pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                        </div>
                        <select x-model="season"
                            class="w-full sm:w-64 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">All Seasons</option>
                            @foreach ($seasons as $item)
                                <option value="{{ $item->slug }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <select x-model="status"
                            class="w-full sm:w-64 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
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
                        <thead class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Farmer Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Commodities</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Loan Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="collectionTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="app in applications" :key="app.id">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg flex items-center justify-center mr-3">
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
                                                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2"></div>
                                                    <span class="text-gray-900 dark:text-white" x-text="c.commodity_name"></span>
                                                    <span class="ml-2 text-gray-500 dark:text-gray-400" x-text="`(${c.allocated_quantity})`"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400" x-text="`₦${app.total_loan ? app.total_loan.toLocaleString() : '0'}`"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Total Loan</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="{
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': app.collection_status === 'verified',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': app.collection_status === 'pending'
                                        }" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <svg :class="{
                                                'text-green-400': app.collection_status === 'verified',
                                                'text-yellow-400': app.collection_status === 'pending'
                                            }" class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            <span x-text="app.collection_status === 'verified' ? 'Verified' : 'Pending'"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button x-show="app.collection_status === 'pending'"
                                            @click="openCollectionModal(app)"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Verify
                                        </button>
                                        <span x-show="app.collection_status === 'verified'" class="inline-flex items-center px-3 py-2 text-sm text-green-600 dark:text-green-400">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">No applications found.</p>
                                    </div>
                                </td>
                            </tr>
                            <tr x-show="loading">
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <div class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

        <!-- Enhanced Modal -->
        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center overflow-y-auto p-4">
            <div @click.away="closeCollectionModal()"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-7xl my-8 p-6 sm:p-8 relative max-h-[95vh] overflow-y-auto border border-gray-200 dark:border-gray-700">
                <!-- Enhanced Modal Header -->
                <div class="flex items-center justify-between mb-8 border-b border-gray-200 dark:border-gray-600 pb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Verify Collection</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Confirm farmer identity and commodity collection</p>
                        </div>
                    </div>
                    <button @click="closeCollectionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="collectionForm" class="space-y-8" enctype="multipart/form-data" @submit.prevent="submitCollection">
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
                                <div class="flex items-center justify-between py-2 border-b border-blue-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">BVN</span>
                                    <span class="text-sm font-mono bg-blue-100 dark:bg-gray-600 px-2 py-1 rounded text-blue-800 dark:text-blue-200" x-text="modalData.farmer?.bvn"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Application Information Card -->
                        <div class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-emerald-200 dark:border-gray-600 shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Application Details</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Reference Number</span>
                                    <span class="text-sm font-mono bg-emerald-100 dark:bg-gray-600 px-2 py-1 rounded text-emerald-800 dark:text-emerald-200" x-text="modalData.reference_number"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Season</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="modalData.season?.name"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Farm Size</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400" x-text="`${modalData.farm?.size} hectares`"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Collection Date</span>
                                    <span class="text-sm text-gray-900 dark:text-white" x-text="modalData.application_center?.collection_date"></span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Return Deadline</span>
                                    <span class="text-sm text-gray-900 dark:text-white" x-text="modalData.application_center?.return_date"></span>
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
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- ID Card Upload -->
                        <div class="space-y-4">
                            <label for="idCardInput" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                Upload ID Card Photo *
                            </label>
                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-all duration-200 group">
                                <input type="file" name="idCard" id="idCardInput" accept="image/*" required @change="previewImage($event, 'idCardPreview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/20 transition-colors">
                                        <svg class="w-8 h-8 text-gray-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Drag & drop or click to upload</p>
                                    <p class="text-gray-500 dark:text-gray-500 text-xs">PNG, JPG up to 2MB</p>
                                    <img id="idCardPreview" class="mt-4 w-40 h-40 object-cover rounded-lg hidden border-2 border-gray-300 dark:border-gray-600 shadow-sm" />
                                </div>
                            </div>
                        </div>

                        <!-- Commodity Photo Upload -->
                        <div class="space-y-4">
                            <label for="commodityPhotoInput" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Upload Commodity Photo *
                            </label>
                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-all duration-200 group">
                                <input type="file" name="commodityPhoto" id="commodityPhotoInput" accept="image/*" required @change="previewImage($event, 'commodityPreview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/20 transition-colors">
                                        <svg class="w-8 h-8 text-gray-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Drag & drop or click to upload</p>
                                    <p class="text-gray-500 dark:text-gray-500 text-xs">PNG, JPG up to 2MB</p>
                                    <img id="commodityPreview" class="mt-4 w-40 h-40 object-cover rounded-lg hidden border-2 border-gray-300 dark:border-gray-600 shadow-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Submit Section -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" @click="closeCollectionModal()"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" id="submitCollectionBtn"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="submitCollectionText">Submit Verification</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('collectionApp', () => ({
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

                // Pagination data
                current_page: 1,
                last_page: 1,
                from: 0,
                to: 0,
                total: 0,
                pages: [],

                init() {
                    this.fetchAssignedFarmers();
                    this.$watch('filter', () => this.goToPage(1));
                    this.$watch('season', () => this.goToPage(1));
                    this.$watch('status', () => this.goToPage(1));
                },

                fetchAssignedFarmers() {
                    this.loading = true;
                    this.applications = [];
                    const url = `/agent/verify-collection?page=${this.current_page}&filter=${this.filter}&season=${this.season}&status=${this.status}`;
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
                    this.fetchAssignedFarmers();
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

                openCollectionModal(app) {
                    this.modalData = app;
                    this.form.application_id = app.id;
                    this.showModal = true;
                    // Reset file inputs and previews
                    document.getElementById('idCardInput').value = '';
                    document.getElementById('commodityPhotoInput').value = '';
                    document.getElementById('idCardPreview').classList.add('hidden');
                    document.getElementById('commodityPreview').classList.add('hidden');
                },

                closeCollectionModal() {
                    this.showModal = false;
                    this.modalData = {};
                    this.form.application_id = null;
                },

                submitCollection() {
                    const form = document.getElementById('collectionForm');
                    const formData = new FormData(form);
                    const submitBtn = document.getElementById('submitCollectionBtn');
                    const submitText = document.getElementById('submitCollectionText');

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

                    fetch('{{ route('agent.verify.collection.submit') }}', {
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
                            this.closeCollectionModal();
                            this.fetchAssignedFarmers();
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

        // Enhanced Toast notification function
        function showToast(message, type) {
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
    </script>
@endsection
