@extends('layouts.layout')

@section('content')
    <div x-data="collectionApp()" class="w-full px-4 py-6">
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
                            <input type="text" x-model.debounce.500ms="filter" placeholder="Search Farmer Name, ID, or Application Ref"
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
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Application</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Commodities</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Payment Status</th>
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
                                        <div class="text-sm text-gray-900 dark:text-white" x-text="app.season?.name || 'N/A'"></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">Ref: </span><span x-text="app.reference_number || 'N/A'"></span>
                                        </div>
                                        <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400" x-text="`₦${app.total_loan ? app.total_loan.toLocaleString() : '0'}`"></div>
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
                                        <!-- Payment Status for Co-funded Applications -->
                                        <div x-show="app.season?.loan_type === 'co-funded'">
                                            <span x-text="app.payment_status || 'N/A'"
                                                :class="{
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': app.payment_status === 'paid',
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': app.payment_status === 'pending',
                                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': app.payment_status === 'failed'
                                                }"
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full capitalize">
                                            </span>
                                        </div>
                                        <!-- Show loan type for Complete Loan applications -->
                                        <div x-show="app.season?.loan_type !== 'co-funded'">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                Complete Loan
                                            </span>
                                        </div>
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
                                        <a x-show="app.collection_status === 'pending'"
                                           :href="`/agent/collections/${app.encrypted_id}/verify`"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Verify
                                        </a>
                                        <div x-show="app.collection_status === 'verified'" class="flex flex-col space-y-1">
                                            <span class="inline-flex items-center px-3 py-2 text-sm text-green-600 dark:text-green-400">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Completed
                                            </span>
<a
    x-show="app.collection_status === 'verified'"
    :href="`/agent/collections/${app.id}/pdf`"
    target="_blank"
    class="inline-flex items-center px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors duration-200">
    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    Download PDF
</a>

                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="applications.length === 0 && !loading">
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400">No applications found.</p>
                                    </div>
                                </td>
                            </tr>
                            <tr x-show="loading">
                                <td colspan="6" class="px-6 py-8 text-center">
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
    </div>

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
                    const verificationInput = document.getElementById('verificationPhotoInput');
                    const verificationPreview = document.getElementById('verificationPhotoPreview');
                    if (verificationInput) verificationInput.value = '';
                    if (verificationPreview) verificationPreview.classList.add('hidden');
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

        // Camera capture functionality
        document.addEventListener('DOMContentLoaded', function() {
            const captureVerificationBtn = document.getElementById('captureVerificationBtn');
            const verificationPhotoInput = document.getElementById('verificationPhotoInput');
            const verificationPhotoPreview = document.getElementById('verificationPhotoPreview');

            // Camera capture for verification photo
            if (captureVerificationBtn && verificationPhotoInput && verificationPhotoPreview) {
                captureVerificationBtn.addEventListener('click', function() {
                    // Check if getUserMedia is supported
                    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                        // Request camera access - try back camera first, fallback to any camera
                        const constraints = {
                            video: {
                                facingMode: { ideal: 'environment' }, // Prefer back camera
                                width: { ideal: 1280 },
                                height: { ideal: 720 }
                            }
                        };

                        navigator.mediaDevices.getUserMedia(constraints)
                        .then(function(stream) {
                            // Create camera modal
                            const cameraModal = document.createElement('div');
                            cameraModal.className = 'fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center';
                            cameraModal.innerHTML = `
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-lg w-full mx-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Capture Commodity Photo</h3>
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
                                        <button id="captureBtn" class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors shadow-lg">
                                            <i class="fas fa-camera mr-2"></i>Capture Photo
                                        </button>
                                        <button id="retakeBtn" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors hidden">
                                            <i class="fas fa-redo mr-2"></i>Retake
                                        </button>
                                    </div>
                                    <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        Position the commodity clearly in the camera view
                                    </p>
                                </div>
                            `;

                            document.body.appendChild(cameraModal);

                            const video = document.getElementById('cameraVideo');
                            const canvas = document.getElementById('cameraCanvas');
                            const captureBtn = document.getElementById('captureBtn');
                            const retakeBtn = document.getElementById('retakeBtn');
                            const closeCamera = document.getElementById('closeCamera');

                            video.srcObject = stream;

                            // Capture photo
                            captureBtn.addEventListener('click', function() {
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(video, 0, 0);

                                // Convert to blob and create file
                                canvas.toBlob(function(blob) {
                                    const file = new File([blob], 'captured-photo.jpg', { type: 'image/jpeg' });

                                    // Create a new FileList-like object
                                    const dataTransfer = new DataTransfer();
                                    dataTransfer.items.add(file);
                                    verificationPhotoInput.files = dataTransfer.files;

                                    // Trigger change event
                                    const event = new Event('change', { bubbles: true });
                                    verificationPhotoInput.dispatchEvent(event);

                                    // Show preview
                                    const url = URL.createObjectURL(blob);
                                    verificationPhotoPreview.src = url;
                                    verificationPhotoPreview.classList.remove('hidden');

                                    // Stop camera and close modal
                                    stream.getTracks().forEach(track => track.stop());
                                    document.body.removeChild(cameraModal);
                                }, 'image/jpeg', 0.8);
                            });

                            // Close camera
                            closeCamera.addEventListener('click', function() {
                                stream.getTracks().forEach(track => track.stop());
                                document.body.removeChild(cameraModal);
                            });

                            // Close on outside click
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

        // Signature pad functionality
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signatureCanvas');
            const clearBtn = document.getElementById('clearSignatureBtn');
            const signatureDataInput = document.getElementById('signatureDataInput');
            const signatureTypeInput = document.getElementById('signatureTypeInput');
            const signatureImageInput = document.getElementById('signatureImageInput');
            const signatureImagePreview = document.getElementById('signatureImagePreview');
            const validationMessage = document.getElementById('signatureValidationMessage');
            const submitBtn = document.getElementById('submitCollectionBtn');

            if (canvas && clearBtn) {
                const ctx = canvas.getContext('2d');
                let isDrawing = false;
                let hasSignature = false;

                // Set canvas size for crisp rendering
                function resizeCanvas() {
                    const rect = canvas.getBoundingClientRect();
                    const dpr = window.devicePixelRatio || 1;
                    canvas.width = rect.width * dpr;
                    canvas.height = rect.height * dpr;
                    ctx.scale(dpr, dpr);
                    canvas.style.width = rect.width + 'px';
                    canvas.style.height = rect.height + 'px';
                }

                // Initialize canvas
                resizeCanvas();
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';

                // Drawing functions
                function startDrawing(e) {
                    isDrawing = true;
                    ctx.beginPath();
                    const rect = canvas.getBoundingClientRect();
                    const scaleX = canvas.width / rect.width;
                    const scaleY = canvas.height / rect.height;
                    const x = (e.clientX - rect.left) * scaleX;
                    const y = (e.clientY - rect.top) * scaleY;
                    ctx.moveTo(x, y);
                    hasSignature = true;
                }

                function draw(e) {
                    if (!isDrawing) return;
                    e.preventDefault();
                    const rect = canvas.getBoundingClientRect();
                    const scaleX = canvas.width / rect.width;
                    const scaleY = canvas.height / rect.height;
                    const x = (e.clientX - rect.left) * scaleX;
                    const y = (e.clientY - rect.top) * scaleY;
                    ctx.lineTo(x, y);
                    ctx.stroke();
                }

                function stopDrawing() {
                    isDrawing = false;
                }

                // Touch events for mobile
                function handleTouchStart(e) {
                    e.preventDefault();
                    const touch = e.touches[0];
                    const mouseEvent = new MouseEvent('mousedown', {
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    canvas.dispatchEvent(mouseEvent);
                }

                function handleTouchMove(e) {
                    e.preventDefault();
                    const touch = e.touches[0];
                    const mouseEvent = new MouseEvent('mousemove', {
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    canvas.dispatchEvent(mouseEvent);
                }

                function handleTouchEnd(e) {
                    e.preventDefault();
                    const mouseEvent = new MouseEvent('mouseup');
                    canvas.dispatchEvent(mouseEvent);
                }

                // Event listeners
                canvas.addEventListener('mousedown', startDrawing);
                canvas.addEventListener('mousemove', draw);
                canvas.addEventListener('mouseup', stopDrawing);
                canvas.addEventListener('mouseout', stopDrawing);

                canvas.addEventListener('touchstart', handleTouchStart, { passive: false });
                canvas.addEventListener('touchmove', handleTouchMove, { passive: false });
                canvas.addEventListener('touchend', handleTouchEnd);

                // Clear signature
                clearBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    hasSignature = false;
                    signatureDataInput.value = '';
                    signatureTypeInput.value = '';
                });

                // Handle signature image upload
                if (signatureImageInput && signatureImagePreview) {
                    signatureImageInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                signatureImagePreview.src = e.target.result;
                                signatureImagePreview.classList.remove('hidden');
                                hasSignature = true;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            signatureImagePreview.src = '';
                            signatureImagePreview.classList.add('hidden');
                        }
                    });
                }

                // Form submission validation
                if (submitBtn) {
                    submitBtn.addEventListener('click', function(e) {
                        // Check if at least one signature method is provided
                        if (!hasSignature && (!signatureImageInput || !signatureImageInput.files.length)) {
                            e.preventDefault();
                            validationMessage.classList.remove('hidden');
                            // Scroll to signature section
                            const signatureSection = document.querySelector('.mb-8');
                            if (signatureSection) {
                                signatureSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                            return false;
                        }

                        // Hide validation message
                        validationMessage.classList.add('hidden');

                        // Convert canvas signature to base64 if canvas has signature
                        if (hasSignature && !signatureImageInput.files.length) {
                            const signatureDataURL = canvas.toDataURL('image/png');
                            signatureDataInput.value = signatureDataURL;
                            signatureTypeInput.value = 'canvas';
                        } else if (signatureImageInput.files.length > 0) {
                            signatureTypeInput.value = 'upload';
                        }
                    });
                }

                // Reset when modal opens
                const modal = canvas.closest('[x-show="showModal"]');
                if (modal) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'style') {
                                const display = window.getComputedStyle(modal).display;
                                if (display !== 'none') {
                                    // Modal opened, reset signature
                                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                                    ctx.fillStyle = 'white';
                                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                                    hasSignature = false;
                                    signatureDataInput.value = '';
                                    signatureTypeInput.value = '';
                                    validationMessage.classList.add('hidden');
                                    if (signatureImagePreview) {
                                        signatureImagePreview.src = '';
                                        signatureImagePreview.classList.add('hidden');
                                    }
                                    if (signatureImageInput) {
                                        signatureImageInput.value = '';
                                    }
                                }
                            }
                        });
                    });
                    observer.observe(modal, { attributes: true, attributeFilter: ['style'] });
                }

                // Handle window resize
                window.addEventListener('resize', resizeCanvas);
            }
        });
    </script>
@endsection
