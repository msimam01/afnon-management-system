@extends('layouts.layout')

@section('content')
    <div x-data="returnApp()" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <div class="px-4 py-3 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Return Verification</h1>
            </div>

            <div class="flex px-4 py-3 mt-2 flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <input type="text" x-model.debounce.500ms="filter" placeholder="Search Farmer Name or ID"
                        class="w-full sm:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
                    <select x-model="season"
                        class="w-full sm:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">All Seasons</option>
                        @foreach ($seasons as $item)
                            <option value="{{ $item->slug }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <select x-model="status"
                        class="w-full sm:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto px-6 py-4">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Farmer ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Commodities</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white text-gray-300 dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="app in applications" :key="app.id">
                            <tr>
                                <td class="px-4 py-2 text-sm" x-text="app.farmer.registration_number"></td>
                                <td class="px-4 py-2 text-sm" x-text="app.farmer.full_name"></td>
                                <td class="px-4 py-2 text-sm">
                                    <ul class="list-disc list-inside">
                                        <template x-for="c in app.commodity_allocations" :key="c.id">
                                            <li x-text="`${c.commodity_name}: ${c.allocated_quantity}`"></li>
                                        </template>
                                    </ul>
                                </td>
                                <td class="px-4 py-2">
                                    <span :class="{'bg-green-100 text-green-800': app.return_status === 'verified', 'bg-yellow-100 text-yellow-800': app.return_status === 'pending'}"
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        <span x-text="app.return_status"></span>
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <button x-show="app.return_status === 'pending'"
                                        @click="openReturnModal(app)"
                                        class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 text-sm">
                                        Verify
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="applications.length === 0 && !loading">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No applications found.</td>
                        </tr>
                        <tr x-show="loading">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Loading...</td>
                        </tr>
                    </tbody>
                </table>
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

        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto p-4">
            <div @click.away="closeReturnModal()"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-5xl my-16 p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6 border-b border-gray-200 dark:border-gray-600 pb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Verify Return</h3>
                    <button @click="closeReturnModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
                </div>
                <form id="returnForm" class="space-y-8" enctype="multipart/form-data" @submit.prevent="submitReturn">
                    <input type="hidden" name="application_id" x-model="form.application_id" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Farmer Info</h4>
                            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                <li><strong>Name:</strong> <span x-text="modalData.farmer?.full_name"></span></li>
                                <li><strong>Phone:</strong> <span x-text="modalData.farmer?.phone"></span></li>
                                <li><strong>State:</strong> <span x-text="modalData.farmer?.state"></span></li>
                                <li><strong>LGA:</strong> <span x-text="modalData.farmer?.lga"></span></li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Application Info</h4>
                            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                <li><strong>Season:</strong> <span x-text="modalData.season?.name"></span></li>
                                <li><strong>Farm Size:</strong> <span x-text="modalData.farm?.size"></span> ha</li>
                                <li><strong>Collection Date:</strong> <span x-text="modalData.application_center?.collection_date"></span></li>
                                <li><strong>Return Deadline:</strong> <span x-text="modalData.application_center?.return_date"></span></li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Commodity Breakdown</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Commodity</th>
                                        <th class="px-4 py-2 text-left">Quantity</th>
                                        <th class="px-4 py-2 text-left">Unit Price</th>
                                        <th class="px-4 py-2 text-left">Total Value</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                    <template x-for="c in modalData.commodity_allocations" :key="c.id">
                                        <tr>
                                            <td class="px-4 py-2 border" x-text="c.commodity_name"></td>
                                            <td class="px-4 py-2 border" x-text="c.allocated_quantity"></td>
                                            <td class="px-4 py-2 border" x-text="`₦${c.unit_price.toLocaleString()}`"></td>
                                            <td class="px-4 py-2 border" x-text="`₦${(c.allocated_quantity * c.unit_price).toLocaleString()}`"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="flex-1">
                            <label for="idCardInput" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Upload ID Card Photo</label>
                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-emerald-500 transition-colors">
                                <input type="file" name="idCard" id="idCardInput" accept="image/*" @change="previewImage($event, 'idCardPreview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="mt-2 text-gray-600 dark:text-gray-400 text-sm">Drag & drop or click to upload ID (Optional)</span>
                                    <img id="idCardPreview" class="mt-2 w-32 h-32 object-cover rounded hidden border border-gray-300 dark:border-gray-600" />
                                </div>
                            </div>
                        </div>
                        <div class="flex-1" x-show="!isMonetaryReturn">
                            <label for="returnedCommodityPhotoInput" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Upload Returned Commodity Photo</label>
                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-emerald-500 transition-colors">
                                <input type="file" name="returnedCommodityPhoto" id="returnedCommodityPhotoInput" accept="image/*" x-bind:required="!isMonetaryReturn" @change="previewImage($event, 'returnedCommodityPreview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="mt-2 text-gray-600 dark:text-gray-400 text-sm">Drag & drop or click to upload commodity photo</span>
                                    <img id="returnedCommodityPreview" class="mt-2 w-32 h-32 object-cover rounded hidden border border-gray-300 dark:border-gray-600" />
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-emerald-600 text-white py-2 px-6 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium transition">
                            Submit Verification
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
                    document.getElementById('idCardInput').value = '';
                    document.getElementById('returnedCommodityPhotoInput').value = '';
                    document.getElementById('idCardPreview').classList.add('hidden');
                    document.getElementById('returnedCommodityPreview').classList.add('hidden');
                },

                closeReturnModal() {
                    this.showModal = false;
                    this.modalData = {};
                    this.form.application_id = null;
                },

                submitReturn() {
                    const form = document.getElementById('returnForm');
                    const formData = new FormData(form);
                    
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
                            toastr.success(data.message);
                            this.closeReturnModal();
                            this.fetchAssignedReturns();
                        } else {
                            toastr.error('Verification failed!');
                        }
                    })
                    .catch(err => {
                        toastr.error('Network error occurred');
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
    </script>
@endsection