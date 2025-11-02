@extends('layouts.layout')

@section('content')
    <!-- Include ToastMagic notifications -->
    <!--  -->
    <div x-data="verificationApp()" class="w-full min-h-screen px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Verification Management</h3>
            </div>
            <div class="p-6">
                <!-- Filters -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Verification
                            Type</label>
                        <select x-model="type" @change="goToPage(1)"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">All</option>
                            <option value="collection">Collection</option>
                            <option value="return">Return</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Season</label>
                        <select x-model="season" @change="goToPage(1)"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">All Seasons</option>
                            @foreach ($seasons as $seasonOption)
                                <option value="{{ $seasonOption->name }}">{{ $seasonOption->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select x-model="status" @change="goToPage(1)"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Verified</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" x-model.debounce.500ms="filter" @input="goToPage(1)"
                            placeholder="Search farmer name, registration number, or application reference..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <!-- Verified Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">✅ Verified</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" x-text="summary.approved || 0"></p>
                            </div>
                            <div class="p-2 rounded-full bg-green-100 dark:bg-green-900 bg-opacity-50">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">🕓 Pending</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" x-text="summary.pending || 0"></p>
                            </div>
                            <div class="p-2 rounded-full bg-yellow-100 dark:bg-yellow-900 bg-opacity-50">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">❌ Rejected</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" x-text="summary.rejected || 0"></p>
                            </div>
                            <div class="p-2 rounded-full bg-red-100 dark:bg-red-900 bg-opacity-50">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Collections Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">📦 Collections</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" x-text="summary.collections || 0"></p>
                            </div>
                            <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900 bg-opacity-50">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Returns Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">🔁 Returns</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" x-text="summary.returns || 0"></p>
                            </div>
                            <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900 bg-opacity-50">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Action and Export -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div class="flex items-center gap-4">
                        <button @click="bulkApprove" :disabled="selectedItems.length === 0"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 disabled:opacity-50">
                            Approve Selected
                        </button>
                        <span x-show="selectedItems.length > 0" class="text-gray-600 dark:text-gray-300 text-sm">
                            <span x-text="selectedItems.length"></span> item(s) selected
                        </span>
                    </div>

                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export Data
                        </button>
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                            <div class="py-1">
                                <button @click="exportData('excel')" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-excel text-green-600 mr-2 w-5 text-center"></i> Excel (.xlsx)
                                </button>
                                <button @click="exportData('csv')" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-csv text-blue-600 mr-2 w-5 text-center"></i> CSV (.csv)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <input type="checkbox" @change="toggleSelectAll($event)">
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Farmer Details</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Application</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Payment Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="item in verifications" :key="item.id">
                                <tr :class="{ 'bg-gray-100 dark:bg-gray-700': selectedItems.includes(item.id) }">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" :value="item.id" x-model="selectedItems">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <span x-text="item.type"
                                            :class="{
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': item
                                                    .type === 'collection',
                                                'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200': item
                                                    .type === 'return'
                                            }"
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full capitalize">
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                        x-text="item.application?.farmer?.full_name?.substring(0, 2).toUpperCase() || 'N/A'">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white"
                                                    x-text="item.application?.farmer?.full_name || 'N/A'"></div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400"
                                                    x-text="item.application?.farmer?.phone || 'N/A'"></div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    <span
                                                        x-text="item.application?.farmer?.registration_number || 'N/A'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white"
                                            x-text="item.application?.season?.name || 'N/A'"></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">Ref: </span><span x-text="item.application?.reference_number || 'N/A'"></span>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <template x-for="c in item.application?.commodity_allocations"
                                                :key="c.id">
                                                <span
                                                    x-text="`${c.commodity_name}: ${c.allocated_quantity} bags`"></span><br>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <!-- Payment Status for Co-funded Collection Verifications -->
                                        <div x-show="item.application?.season?.loan_type === 'co-funded' && item.type === 'collection'">
                                            <span x-text="item.application?.payment_status || 'N/A'"
                                                :class="{
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': item.application?.payment_status === 'paid',
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': item.application?.payment_status === 'pending',
                                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': item.application?.payment_status === 'failed'
                                                }"
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full capitalize">
                                            </span>
                                        </div>
                                        <!-- Show loan type for non-co-funded or return verifications -->
                                        <div x-show="!(item.application?.season?.loan_type === 'co-funded' && item.type === 'collection')">
                                            <span x-text="item.application?.season?.loan_type === 'co-funded' ? 'Co-funded' : 'Complete Loan'"
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span x-text="item.status"
                                            :class="{
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': item
                                                    .status === 'pending',
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': item
                                                    .status === 'approved',
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': item
                                                    .status === 'rejected',
                                            }"
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full capitalize">
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white space-x-2">
                                        <!-- PDF Download Button -->
                                        <a :href="`/admin/verifications/${item.type}/${item.id}/download`"
                                           class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                           title="Download PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            PDF
                                        </a>

                                        <!-- View & Verify Button (only for pending) -->
                                        <button @click="openModal(item)" x-show="item.status === 'pending'"
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Verify
                                        </button>

                                        <span x-show="item.status !== 'pending' && !selectedItems.includes(item.id)" class="text-gray-500 text-xs">
                                            Verified
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="verifications.length === 0 && !loading">
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No verifications found.
                                </td>
                            </tr>
                            <tr x-show="loading">
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-3 flex flex-col sm:flex-row justify-between items-center">
                    <div class="dark:text-gray-500 text-sm mb-4 sm:mb-0">
                        Showing <span x-text="from"></span> to <span x-text="to"></span> of <span x-text="total"></span> results
                    </div>
                    <div class="flex items-center space-x-1">
                        <button @click="goToPage(current_page - 1)"
                                :disabled="current_page === 1"
                                class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50">
                            Previous
                        </button>
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
                        <button @click="goToPage(current_page + 1)"
                                :disabled="current_page === last_page"
                                class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50">
                            Next
                        </button>
                    </div>
                </div>
                </div>

                <!-- Modal -->
                <!-- Enhanced Verification Modal -->
                <div x-show="modalOpen" x-cloak x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-600/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center z-50 p-4">

                    <div @click.away="closeModal"
                        class="relative mx-auto w-full max-w-5xl bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transform transition-all">

                        <!-- Modal Header -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <span x-text="selectedItem?.type === 'collection' ? 'Collection' : 'Return' + ' Verification'"></span>
                                    <span class="ml-2 text-sm px-2.5 py-0.5 rounded-full"
                                          :class="{
                                              'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': selectedItem?.status === 'approved',
                                              'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': selectedItem?.status === 'rejected',
                                              'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': !selectedItem?.status || selectedItem?.status === 'pending'
                                          }">
                                        <span x-text="selectedItem?.status ? selectedItem.status.charAt(0).toUpperCase() + selectedItem.status.slice(1) : 'Pending'"></span>
                                    </span>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    <span x-text="selectedItem?.created_at ? 'Created: ' + new Date(selectedItem.created_at).toLocaleString() : ''"></span>
                                    <span x-show="selectedItem?.verified_date" class="ml-3">
                                          <span x-text="selectedItem && selectedItem.verified_date ? new Date(selectedItem.verified_date).toLocaleString() : 'N/A'"></span>
                                    </span>
                                </p>
                            </div>
                            <button @click="closeModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div x-show="selectedItem" x-cloak class="p-6 overflow-y-auto max-h-[calc(100vh-200px)]" x-init="selectedItem = selectedItem || {}">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Left Column: Farmer & Season Info -->
                                <div class="space-y-6">
                                    <!-- Farmer Card -->
                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                Farmer Information
                                            </h4>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                <span x-text="selectedItem?.application?.farmer?.registration_number || 'N/A'"></span>
                                            </span>
                                        </div>
                                        <div class="space-y-2 text-sm">
                                            <p class="flex items-center">
                                                <span class="text-gray-500 dark:text-gray-400 w-24">Name:</span>
                                                <span class="font-medium text-gray-900 dark:text-white" x-text="selectedItem?.application?.farmer?.full_name || 'N/A'"></span>
                                            </p>
                                            <p class="flex items-center">
                                                <span class="text-gray-500 dark:text-gray-400 w-24">Phone:</span>
                                                <span class="font-medium" x-text="selectedItem?.application?.farmer?.phone || 'N/A'"></span>
                                            </p>
                                            <p class="flex items-center">
                                                <span class="text-gray-500 dark:text-gray-400 w-24">Center:</span>
                                                <span class="font-medium" x-text="selectedItem?.application?.center?.name || selectedItem?.center?.name || 'N/A'"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Season Card -->
                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
                                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Season Information
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <p class="flex items-center">
                                                <span class="text-gray-500 dark:text-gray-400 w-24">Season:</span>
                                                <span class="font-medium text-gray-900 dark:text-white" x-text="selectedItem?.application?.season?.name || 'N/A'"></span>
                                            </p>
                                            <p class="flex items-center">
                                                <span class="text-gray-500 dark:text-gray-400 w-24">Loan Type:</span>
                                                <span class="font-medium" x-text="selectedItem?.application?.season?.loan_type ? selectedItem.application.season.loan_type.charAt(0).toUpperCase() + selectedItem.application.season.loan_type.slice(1) : 'N/A'"></span>
                                            </p>
                                            <p class="flex items-center">
                                                <span class="text-gray-500 dark:text-gray-400 w-24">Period:</span>
                                                <span class="font-medium" x-text="selectedItem?.application?.season?.start_date ?
                                                    new Date(selectedItem.application.season.start_date).toLocaleDateString() + ' - ' +
                                                    (selectedItem.application.season.end_date ? new Date(selectedItem.application.season.end_date).toLocaleDateString() : 'Present') : 'N/A'">
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Payment Status Card (Conditional) -->
                                    <div x-show="selectedItem && selectedItem.application && selectedItem.application.season && selectedItem.application.season.loan_type === 'co-funded' && selectedItem.type === 'collection'"
                                         class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
                                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Payment Details
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <p class="flex items-center justify-between">
                                                <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                                <span x-text="selectedItem?.application?.payment_status ? selectedItem.application.payment_status.charAt(0).toUpperCase() + selectedItem.application.payment_status.slice(1) : 'N/A'"
                                                    :class="{
                                                        'text-green-600 font-semibold': selectedItem?.application?.payment_status === 'completed',
                                                        'text-yellow-600 font-semibold': selectedItem?.application?.payment_status === 'pending',
                                                        'text-red-600 font-semibold': selectedItem?.application?.payment_status === 'failed',
                                                        'text-gray-600': !selectedItem?.application?.payment_status
                                                    }">
                                                </span>
                                            </p>
                                            <p x-show="selectedItem?.application?.monetary_return" class="flex items-center justify-between">
                                                <span class="text-gray-500 dark:text-gray-400">Amount:</span>
                                                <span class="font-semibold"
                                                      x-text="selectedItem?.application?.monetary_return?.amount ? '₦' + new Intl.NumberFormat().format(selectedItem.application.monetary_return.amount) : 'N/A'">
                                                </span>
                                            </p>
                                            <p x-show="selectedItem?.application?.monetary_return?.payment_date" class="flex items-center justify-between">
                                                <span class="text-gray-500 dark:text-gray-400">Paid On:</span>
                                                <span x-text="selectedItem && selectedItem.application && selectedItem.application.monetary_return ? new Date(selectedItem.application.monetary_return.payment_date).toLocaleDateString() : 'N/A'"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Middle Column: Commodities -->
                                <div class="lg:col-span-2 space-y-6">
                                    <!-- Commodities Card -->
                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-600">
                                        <div class="bg-gray-50 dark:bg-gray-600 px-4 py-3 flex justify-between items-center">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                Commodity Details
                                            </h4>
                                            <span class="text-xs bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-100 px-2 py-1 rounded-full">
                                                <span x-text="selectedItem?.application?.commodity_allocations?.length || '0'"></span> items
                                            </span>
                                        </div>
                                        <div class="p-4">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                                    <thead class="bg-gray-50 dark:bg-gray-600">
                                                        <tr>
                                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Commodity</th>
                                                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Allocated</th>
                                                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                                <span x-text="selectedItem?.type === 'collection' ? 'Collected' : 'Returned'"></span>
                                                            </th>
                                                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Variance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                                        <template x-for="(c, index) in (selectedItem && selectedItem.application && selectedItem.application.commodity_allocations) ? selectedItem.application.commodity_allocations : []" :key="c.id">
                                                            <tr>
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                                    <span x-text="index + 1"></span>
                                                                </td>
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                                    <span x-text="c.commodity_name || 'N/A'"></span>
                                                                </td>
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">
                                                                    <span x-text="c.allocated_quantity ? c.allocated_quantity + ' bags' : 'N/A'"></span>
                                                                </td>
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">
                                                                    <template x-if="selectedItem?.type === 'collection'">
                                                                        <span x-text="selectedItem?.collected_details && selectedItem.collected_details[c.id] ? selectedItem.collected_details[c.id].collected_quantity + ' bags' : '0 bags'"></span>
                                                                    </template>
                                                                    <template x-if="selectedItem?.type === 'return' && selectedItem?.returned_quantity !== undefined">
                                                                        <span x-text="selectedItem.returned_quantity + ' bags'"></span>
                                                                    </template>
                                                                    <template x-if="selectedItem?.type === 'return' && selectedItem?.returned_quantity === undefined">
                                                                        <span>0 bags</span>
                                                                    </template>
                                                                </td>
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-right"
                                                                    :class="{
                                                                        'text-green-600': (selectedItem?.type === 'collection' ? (selectedItem?.collected_details && selectedItem.collected_details[c.id] ? selectedItem.collected_details[c.id].collected_quantity : 0) : (selectedItem?.returned_quantity || 0)) - c.allocated_quantity > 0,
                                                                        'text-red-600': (selectedItem?.type === 'collection' ? (selectedItem?.collected_details && selectedItem.collected_details[c.id] ? selectedItem.collected_details[c.id].collected_quantity : 0) : (selectedItem?.returned_quantity || 0)) - c.allocated_quantity < 0,
                                                                        'text-gray-600': (selectedItem?.type === 'collection' ? (selectedItem?.collected_details && selectedItem.collected_details[c.id] ? selectedItem.collected_details[c.id].collected_quantity : 0) : (selectedItem?.returned_quantity || 0)) - c.allocated_quantity === 0
                                                                    }">
                                                                    <template x-if="selectedItem?.type === 'collection'">
                                                                        <span>
                                                                            <span x-text="((selectedItem?.collected_details && selectedItem.collected_details[c.id] ? selectedItem.collected_details[c.id].collected_quantity : 0) - c.allocated_quantity) > 0 ? '+' : ''"></span>
                                                                            <span x-text="(selectedItem?.collected_details && selectedItem.collected_details[c.id] ? selectedItem.collected_details[c.id].collected_quantity : 0) - c.allocated_quantity"></span> bags
                                                                        </span>
                                                                    </template>
                                                                    <template x-if="selectedItem?.type === 'return' && selectedItem?.returned_quantity !== undefined">
                                                                        <span>
                                                                            <span x-text="(selectedItem.returned_quantity - c.allocated_quantity) > 0 ? '+' : ''"></span>
                                                                            <span x-text="selectedItem.returned_quantity - c.allocated_quantity"></span> bags
                                                                        </span>
                                                                    </template>
                                                                    <template x-if="selectedItem?.type === 'return' && selectedItem?.returned_quantity === undefined">
                                                                        <span>
                                                                            <span>-</span>
                                                                            <span x-text="c.allocated_quantity"></span> bags
                                                                        </span>
                                                                    </template>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        <tr x-show="!selectedItem || !selectedItem.application || !selectedItem.application.commodity_allocations || selectedItem.application.commodity_allocations.length === 0">
                                                            <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                                No commodities found for this application.
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Verification Photos -->
                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-600">
                                        <div class="bg-gray-50 dark:bg-gray-600 px-4 py-3">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Verification Photos
                                                <span class="ml-2 text-xs bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100 px-2 py-0.5 rounded-full"
                                                      x-text="selectedItem?.image_paths?.length ? selectedItem.image_paths.length + ' photos' : 'No photos'">
                                                </span>
                                            </h4>
                                        </div>
                                        <div class="p-4">
                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                                <template x-for="(img, index) in selectedItem?.image_paths || []" :key="index">
                                                    <div class="relative group aspect-square rounded-md overflow-hidden border border-gray-200 dark:border-gray-600">
                                                        <img :src="img" :alt="'Verification Image ' + (index + 1)"
                                                            class="w-full h-full object-cover cursor-pointer transition-transform duration-200 group-hover:scale-105"
                                                            @click="openImageInModal(img, 'Verification Image ' + (index + 1))">
                                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                            <span class="text-white bg-black/70 px-2 py-1 rounded text-xs backdrop-blur-sm">
                                                                Click to Enlarge
                                                            </span>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div x-show="!selectedItem?.image_paths?.length"
                                                     class="col-span-3 py-8 text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No verification photos available</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Admin Remarks / Verification Notes -->
                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-600">
                                        <div class="bg-gray-50 dark:bg-gray-600 px-4 py-3">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span x-text="selectedItem?.status === 'pending' ? 'Admin Remarks' : 'Verification Notes'"></span>
                                            </h4>
                                        </div>
                                        <div class="p-4">
                                            <div x-show="selectedItem?.status === 'pending'">
                                                <textarea x-model="adminRemarks"
                                                    placeholder="Enter verification notes or remarks..."
                                                    rows="3"
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"></textarea>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    Add any notes or remarks about this verification. These will be visible to other administrators.
                                                </p>
                                            </div>
                                            <div x-show="selectedItem && selectedItem.status !== 'pending' && selectedItem.verification_notes" class="p-3 bg-gray-50 dark:bg-gray-600 rounded-md">
                                                <p class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line" x-text="selectedItem?.verification_notes || 'No notes available'"></p>
                                                <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-500 text-xs text-gray-500 dark:text-gray-400">
                                                    Verified by <span class="font-medium" x-text="selectedItem && selectedItem.approved_by_name ? selectedItem.approved_by_name : 'System'"></span>
                                                    on <span x-text="selectedItem && selectedItem.verified_date ? new Date(selectedItem.verified_date).toLocaleString() : 'N/A'"></span>
                                                </div>
                                            </div>
                                            <div x-show="selectedItem?.status !== 'pending' && !selectedItem?.verification_notes" class="text-center py-4">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">No verification notes available.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <span x-show="selectedItem && selectedItem.verified_date">
                                    Last updated: <span x-text="selectedItem && selectedItem.updated_at ? new Date(selectedItem.updated_at).toLocaleString() : 'N/A'"></span>
                                </span>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" @click="closeModal"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    Close
                                </button>
                                <div x-show="selectedItem?.status === 'pending'" class="flex space-x-3">
                                    <button type="button" @click="updateStatus('rejected')"
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Reject
                                    </button>
                                    <button type="button" @click="updateStatus('approved')"
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 flex items-center">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        function verificationApp() {
            return {
                verifications: [],
                loading: true,
                selectedItems: [],
                selectedItem: null,
                type: '',
                summary: {
                    approved: 0,
                    pending: 0,
                    rejected: 0,
                    collections: 0,
                    returns: 0
                },
                season: '',
                status: '',
                filter: '',
                current_page: 1,
                last_page: 1,
                from: 0,
                to: 0,
                total: 0,
                modalOpen: false,
                adminRemarks: '',
                imageModal: {
                    open: false,
                    src: '',
                    alt: ''
                },
                selectedItem: null,

                get pages() {
                    const pages = [];
                    const maxPages = 5;
                    const startPage = Math.max(1, this.current_page - Math.floor(maxPages / 2));
                    const endPage = Math.min(this.last_page, startPage + maxPages - 1);

                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }
                    return pages;
                },

                async init() {
                    await this.fetchVerifications();
                },

                async fetchVerifications() {
                    try {
                        this.loading = true;
                        const params = new URLSearchParams({
                            page: this.current_page,
                            filter: this.filter,
                            season: this.season,
                            status: this.status,
                            type: this.type
                        });

                        const [verificationsResponse, summaryResponse] = await Promise.all([
                            fetch(`/admin/api/verifications?${params.toString()}`),
                            fetch(`/admin/api/verifications/summary?${params.toString()}`)
                        ]);

                        const data = await verificationsResponse.json();
                        const summaryData = await summaryResponse.json();

                        this.verifications = data.data;
                        this.total = data.total;
                        this.last_page = data.last_page;
                        this.from = data.from;
                        this.to = data.to;
                        this.current_page = data.current_page;

                        // Update summary data
                        this.summary = {
                            approved: summaryData.approved || 0,
                            pending: summaryData.pending || 0,
                            rejected: summaryData.rejected || 0,
                            collections: summaryData.collections || 0,
                            returns: summaryData.returns || 0
                        };
                        this.last_page = data.last_page;
                        this.from = data.from;
                        this.to = data.to;
                        this.total = data.total;

                    } catch (error) {
                        this.showToast('Error fetching verifications: ' + error.message, 'error');

                    } finally {
                        this.loading = false;
                    }
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.last_page) {
                        this.current_page = page;
                        this.fetchVerifications();
                    }
                },

                toggleSelectAll(event) {
                    if (event.target.checked) {
                        this.selectedItems = this.verifications.map(item => item.id);
                    } else {
                        this.selectedItems = [];
                    }
                },

                async bulkApprove() {
                    if (this.selectedItems.length === 0) {
                        this.showToast('Please select at least one item to approve.', 'error');
                        return;
                    }

                    this.loading = true;
                    try {
                        const response = await fetch('/admin/verifications/bulk-approve', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                ids: this.selectedItems,
                                type: this.type
                            })
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Failed to perform bulk approval.');
                        }

                        this.showToast(data.message, 'success');
                        this.selectedItems = [];
                        await this.fetchVerifications();

                    } catch (error) {
                        // Handle JSON parsing errors and other errors
                        let errorMessage = 'An unexpected error occurred.';

                        if (error.message.includes('JSON.parse')) {
                            errorMessage = 'Server returned an invalid response. Please check your permissions and try again.';
                        } else if (error.message) {
                            errorMessage = error.message;
                        }

                        this.showToast(errorMessage, 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                openModal(item) {
                    this.selectedItem = item;
                    this.adminRemarks = item.verification_notes || '';
                    this.modalOpen = true;
                },

                openImageInModal(src, alt) {
                    this.imageModal = {
                        open: true,
                        src: src,
                        alt: alt
                    };
                },

                closeImageModal() {
                    this.imageModal = {
                        open: false,
                        src: '',
                        alt: ''
                    };
                },

                closeModal() {
                    this.selectedItem = null;
                    this.modalOpen = false;
                    this.adminRemarks = '';
                },

                async updateStatus(status) {
                    if (!this.selectedItem) return;

                    try {
                        const response = await fetch('/admin/verifications/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                id: this.selectedItem.id,
                                type: this.selectedItem.type,
                                status: status,
                                remarks: this.adminRemarks
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Update the selected item with the response data
                            if (data.verification) {
                                const index = this.verifications.findIndex(v => v.id === this.selectedItem.id && v.type === this.selectedItem.type);
                                if (index !== -1) {
                                    this.verifications[index] = {
                                        ...this.verifications[index],
                                        ...data.verification,
                                        // Ensure we don't lose the type
                                        type: this.verifications[index].type,
                                        // Ensure we keep the image paths
                                        image_paths: this.verifications[index].image_paths
                                    };
                                }
                                this.selectedItem = this.verifications[index];
                            }

                            this.showToast(data.message, 'success');
                            this.closeModal();
                        } else {
                            throw new Error(data.message || 'Failed to update status');
                        }
                    } catch (error) {
                        this.showToast(error.message || 'An error occurred', 'error');
                        console.error('Error updating status:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                showToast(message, type) {
                    // Create and show a simple toast notification
                    const toast = document.createElement('div');
                    toast.className =
                        `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transition-all duration-300 transform translate-x-full`;

                    if (type === 'success') {
                        toast.className += ' bg-green-500';
                        toast.innerHTML =
                            `<div class="flex items-center"><svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>${message}</div>`;
                    } else {
                        toast.className += ' bg-red-500';
                        toast.innerHTML =
                            `<div class="flex items-center"><svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>${message}</div>`;
                    }

                    document.body.appendChild(toast);

                    // Animate in
                    setTimeout(() => {
                        toast.classList.remove('translate-x-full');
                    }, 100);

                    // Auto remove after 4 seconds
                    setTimeout(() => {
                        toast.classList.add('translate-x-full');
                        setTimeout(() => {
                            if (document.body.contains(toast)) {
                                document.body.removeChild(toast);
                            }
                        }, 300);
                    }, 4000);
                },

                async exportData(format) {
                    try {
                        this.showToast('Preparing export, please wait...', 'info');

                        const params = new URLSearchParams({
                            format: format,
                            type: this.type,
                            season: this.season,
                            status: this.status,
                            filter: this.filter
                        });

                        const response = await fetch(`/admin/verifications/export?${params.toString()}`);

                        if (!response.ok) {
                            throw new Error(`Export failed: ${response.statusText}`);
                        }

                        // Get the filename from the Content-Disposition header
                        const contentDisposition = response.headers.get('Content-Disposition');
                        let filename = `verifications_${new Date().toISOString().split('T')[0]}.${format}`;

                        if (contentDisposition) {
                            const filenameMatch = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                            if (filenameMatch && filenameMatch[1]) {
                                filename = filenameMatch[1].replace(/['"]/g, '');
                            }
                        }

                        // Create a blob from the response and trigger download
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        a.remove();

                        this.showToast('Export completed successfully!', 'success');
                    } catch (error) {
                        console.error('Export error:', error);
                        this.showToast(`Export failed: ${error.message}`, 'error');
                    }
                }
            }
        }
    </script>

    <!-- Image Preview Modal -->
    <div x-show="imageModal.open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="imageModal.open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="closeImageModal()"
                 aria-hidden="true">
            </div>

            <!-- Modal content -->
            <div x-show="imageModal.open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title" x-text="imageModal.alt"></h3>
                                <button @click="closeImageModal()" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2">
                                <img :src="imageModal.src" :alt="imageModal.alt" class="w-full h-auto max-h-[70vh] object-contain">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <a :href="imageModal.src" download class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm" @click.stop>
                        Download
                    </a>
                    <button type="button" @click="closeImageModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
