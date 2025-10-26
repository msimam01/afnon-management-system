        <!-- Enhanced Modal -->
        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-2 sm:p-4">
            <div class="relative w-full max-w-2xl sm:max-w-4xl lg:max-w-6xl xl:max-w-7xl max-h-[95vh] flex flex-col">
                <div @click.away="closeCollectionModal()" class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-2xl flex flex-col h-full overflow-hidden">
                    <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
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
                                <div class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Loan Type</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="modalData.season?.loan_type === 'co-funded' ? 'Co-funded (50% upfront)' : 'Complete Loan (commodity return)'"></span>
                                </div>
                                <!-- Payment Status for Co-funded Applications -->
                                <div x-show="modalData.season?.loan_type === 'co-funded'" class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Payment Status</span>
                                    <span x-text="modalData.payment_status || 'N/A'"
                                          :class="{
                                              'text-green-600 font-semibold': modalData.payment_status === 'paid',
                                              'text-yellow-600 font-semibold': modalData.payment_status === 'pending',
                                              'text-red-600 font-semibold': modalData.payment_status === 'failed'
                                          }"
                                          class="text-sm capitalize"></span>
                                </div>
                                <div x-show="modalData.monetary_return && modalData.season?.loan_type === 'co-funded'" class="flex items-center justify-between py-2 border-b border-emerald-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Payment Amount</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400" x-text="modalData.monetary_return?.amount ? '₦' + new Intl.NumberFormat().format(modalData.monetary_return.amount) : 'N/A'"></span>
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

                    <!-- Collection Details Section -->
                    <div class="mb-8">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Collection Details per Commodity</h4>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                            <div class="space-y-6">
                                <template x-for="(allocation, index) in modalData.commodity_allocations" :key="`commodity-${allocation.id}`">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <!-- Commodity Info -->
                                        <div class="md:col-span-2">
                                            <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-2" x-text="allocation.commodity_name"></h5>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200" x-text="`Allocated: ${allocation.allocated_quantity}`"></span>
                                        </div>

                                        <!-- Collected Quantity for this Commodity -->
                                        <div>
                                            <label :for="`collected_quantity_${allocation.id}`" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Collected Quantity *
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                </div>
                                                <input type="number" :id="`collected_quantity_${allocation.id}`" :name="`collected_quantities[${allocation.id}]`" min="0" :max="allocation.allocated_quantity" step="0.01" required
                                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                                    :placeholder="`Enter collected for ${allocation.commodity_name}`" />
                                            </div>
                                        </div>

                                        <!-- Potential Adjustment Display -->
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Shortage: </span>
                                            <span id="shortage_${allocation.id}" class="text-sm font-semibold text-red-600 dark:text-red-400 ml-1"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Hidden allocated_quantities for validation -->
                            <template x-for="(allocation, index) in modalData.commodity_allocations" :key="`hidden-${allocation.id}`">
                                <input type="hidden" :name="`allocated_quantities[${allocation.id}]`" :value="allocation.allocated_quantity" />
                            </template>

                            <!-- Collection Notes -->
                            <div class="mt-6">
                                <label for="collection_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Overall Collection Notes
                                </label>
                                <textarea id="collection_notes" name="collection_notes" rows="4"
                                    class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                    placeholder="Add any overall notes about the collection..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced File Upload Section -->
                    <div class="space-y-4">
                        <label for="verificationPhotoInput" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Commodity Photo *
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-all duration-200 group">
                            <input type="file" name="photo" id="verificationPhotoInput" accept="image/*" required @change="previewImage($event, 'verificationPhotoPreview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/20 transition-colors">
                                    <svg class="w-8 h-8 text-gray-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Click to capture or upload commodity photo</p>
                                <p class="text-gray-500 dark:text-gray-500 text-xs">PNG, JPG up to 4MB</p>
                                <img id="verificationPhotoPreview" class="mt-4 w-full h-64 object-contain rounded-lg hidden border-2 border-gray-300 dark:border-gray-600 shadow-sm" />
                            </div>
                        </div>

                        <!-- Camera Capture Button -->
                        <button type="button" id="captureVerificationBtn"
                            class="flex items-center justify-center w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                            <i class="fas fa-camera mr-2"></i>
                            Capture Photo
                        </button>
                        </div>
                    </div>

                    <!-- Farmer Signature Section -->
                    <div class="mb-8">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Farmer's Signature</h4>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                            <div class="space-y-6">
                                <!-- Signature Pad -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Draw Signature *
                                    </label>
                                    <div class="border-2 border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-gray-50 dark:bg-gray-700">
                                        <canvas id="signatureCanvas" class="w-full h-40 bg-white border border-gray-200 dark:border-gray-600 rounded cursor-crosshair touch-none" style="touch-action: none;"></canvas>
                                        <div class="flex justify-between items-center mt-2">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Use mouse, touch, or stylus to sign</p>
                                            <button type="button" id="clearSignatureBtn" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 underline">
                                                Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alternative: Upload Signature Image -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Or Upload Signature Image *
                                    </label>
                                    <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all duration-200">
                                        <input type="file" name="signature_image" id="signatureImageInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="flex flex-col items-center">
                                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-2">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Click to upload signed image</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, PDF up to 2MB</p>
                                            <img id="signatureImagePreview" class="mt-2 max-w-full h-20 object-contain hidden border border-gray-300 dark:border-gray-600 rounded" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden Signature Data -->
                                <input type="hidden" name="signature_data" id="signatureDataInput" />
                                <input type="hidden" name="signature_type" id="signatureTypeInput" />

                                <!-- Validation Message -->
                                <div id="signatureValidationMessage" class="hidden bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-sm text-red-700 dark:text-red-300">Please provide a signature before completing verification.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Submit Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-6 sm:p-8 -mx-6 sm:-mx-8 -mb-6 sm:-mb-8">
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
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
                    </div>
                    </form>
                </div>
            </div>
        </div>