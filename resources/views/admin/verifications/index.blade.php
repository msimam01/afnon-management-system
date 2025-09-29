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

                <!-- Bulk Action -->
                <div class="flex items-center gap-4 mb-4">
                    <button @click="bulkApprove" :disabled="selectedItems.length === 0"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 disabled:opacity-50">
                        Approve Selected
                    </button>
                    <span x-show="selectedItems.length > 0" class="text-gray-600 dark:text-gray-300 text-sm">
                        <span x-text="selectedItems.length"></span> item(s) selected
                    </span>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <button @click="openModal(item)" x-show="item.status === 'pending'"
                                            class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300">
                                            View & Verify
                                        </button>
                                        <span x-show="item.status !== 'pending'" class="text-gray-500">
                                            No action
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
                <div class="px-6 py-3 flex flex-col sm:flex-row justify-between items-center" x-show="last_page > 1">
                    <div class="dark:text-gray-500 text-sm mb-2 sm:mb-0">
                        Showing <span x-text="from"></span> to <span x-text="to"></span> of <span
                            x-text="total"></span>
                        results
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

                <!-- Modal -->
                <div x-show="modalOpen" x-cloak
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
                    <div @click.away="closeModal"
                        class="relative mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
                        <!-- Modal header -->
                        <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Verification Details</h3>
                            <button @click="closeModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" class="h-6 w-6"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div x-if="selectedItem" class="mt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Farmer Information
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-bold">Name:</span> <span
                                            x-text="selectedItem?.application?.farmer?.full_name || 'N/A'"></span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-bold">Phone:</span> <span
                                            x-text="selectedItem?.application?.farmer?.phone || 'N/A'"></span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-bold">Registration #:</span> <span
                                            x-text="selectedItem?.application?.farmer?.registration_number || 'N/A'"></span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-bold">Type:</span> <span x-text="selectedItem?.type || 'N/A'"
                                            class="capitalize"></span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-bold">Status:</span> <span
                                            x-text="selectedItem?.status || 'N/A'" class="capitalize"></span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-bold">Application Ref:</span> <span
                                            x-text="selectedItem?.application?.reference_number || 'N/A'"></span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-bold">Loan Type:</span> <span
                                            x-text="selectedItem?.application?.season?.loan_type === 'co-funded' ? 'Co-funded (50% upfront)' : 'Complete Loan (commodity return)'" class="capitalize"></span>
                                    </p>
                                    <!-- Payment Status for Co-funded Applications -->
                                    <div x-show="selectedItem?.application?.season?.loan_type === 'co-funded' && selectedItem?.type === 'collection'">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-bold">Payment Status:</span> 
                                            <span x-text="selectedItem?.application?.payment_status || 'N/A'" 
                                                  :class="{
                                                      'text-green-600 font-semibold': selectedItem?.application?.payment_status === 'paid',
                                                      'text-yellow-600 font-semibold': selectedItem?.application?.payment_status === 'pending',
                                                      'text-red-600 font-semibold': selectedItem?.application?.payment_status === 'failed'
                                                  }"
                                                  class="capitalize"></span>
                                        </p>
                                        <div x-show="selectedItem?.application?.monetary_return">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-bold">Payment Amount:</span> 
                                                <span x-text="selectedItem?.application?.monetary_return?.amount ? '₦' + new Intl.NumberFormat().format(selectedItem.application.monetary_return.amount) : 'N/A'"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mt-4 mb-2">Commodities
                                    </h4>
                                    <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                                        <template x-for="c in selectedItem?.application?.commodity_allocations"
                                            :key="c.id">
                                            <li><span x-text="`${c.commodity_name}: ${c.allocated_quantity} bags`"></span>
                                            </li>
                                        </template>
                                        <li x-show="!selectedItem?.application?.commodity_allocations?.length">No
                                            commodities found.</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Verification Photos
                                    </h4>
                                    <!-- filepath: /home/msimam/afnon-management-system/resources/views/admin/verifications/index.blade.php -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <template x-for="img in selectedItem?.image_paths" :key="img">
                                            <img :src="img" alt="Verification Image"
                                                class="w-full h-32 object-cover rounded" />
                                        </template>
                                        <p x-show="!selectedItem?.image_paths?.length"
                                            class="text-gray-500 dark:text-gray-400 text-sm">No photos available.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="flex justify-end items-center gap-4 mt-6">
                            <button @click="updateStatus('rejected')"
                                class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                Reject
                            </button>
                            <button @click="updateStatus('approved')"
                                class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">
                                Approve
                            </button>
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
                type: '',
                season: '',
                status: '',
                filter: '',
                current_page: 1,
                last_page: 1,
                from: 0,
                to: 0,
                total: 0,
                modalOpen: false,
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
                    this.loading = true;
                    this.selectedItems = [];

                    const params = new URLSearchParams({
                        page: this.current_page,
                        type: this.type,
                        season: this.season,
                        status: this.status,
                        filter: this.filter
                    });

                    try {
                        const response = await fetch(`/admin/api/verifications?${params.toString()}`);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        this.verifications = data.data;
                        this.current_page = data.current_page;
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
                    this.modalOpen = true;
                },

                closeModal() {
                    this.modalOpen = false;
                    this.selectedItem = null;
                },

                async updateStatus(status) {
                    if (!this.selectedItem) return;

                    this.loading = true;
                    try {
                        const response = await fetch('/admin/verifications/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                id: this.selectedItem.id,
                                type: this.selectedItem.type,
                                status: status
                            })
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Failed to update status.');
                        }

                        this.showToast(data.message, 'success');
                        await this.fetchVerifications();
                        this.closeModal();

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
                }
            }
        }
    </script>
@endsection
