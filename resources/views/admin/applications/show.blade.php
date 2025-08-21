@extends('layouts.layout')

@section('content')
    <!-- Updated Application Approval Modal -->
    <div class="">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full  p-6 sm:p-8 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 dark:border-gray-600">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Approve Application</h3>
            </div>

            <!-- Farmer & Application Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Farmer Info</h4>
                    <ul class="text-sm space-y-1 text-gray-700 dark:text-gray-300">
                        <li><strong>Name:</strong> {{ $application->farmer->full_name }}</li>
                        <li><strong>Reg No:</strong> {{ $application->farmer->registration_number }}</li>
                        <li><strong>Phone:</strong> {{ $application->farmer->phone }}</li>
                        <li><strong>NIN:</strong> {{ $application->farmer->nin }}</li>
                        <li><strong>BVN:</strong> {{ $application->farmer->bvn }}</li>
                        <li><strong>Address:</strong> {{ $application->farmer->address }}</li>
                    </ul>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Application Info</h4>
                    <ul class="text-sm space-y-1 text-gray-700 dark:text-gray-300">
                        <li><strong>Reference Number:</strong> {{ $application->reference_number }}</li>
                        <li><strong>Season:</strong> {{ $application->season->name }}</li>
                        <li><strong>Farm Location:</strong> {{ $application->farm->location }}</li>
                        <li><strong>Farm Size:</strong> {{ $application->farm->size }} ha</li>
                        <li><strong>Status:</strong><span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                            {{ ucfirst($application->status) }}
                        </span></li>
                    </ul>
                </div>
            </div>

            <!-- Commodity Breakdown -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Commodity Breakdown</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">Commodity</th>
                                <th class="px-4 py-2 text-left">Qty/Ha</th>
                                <th class="px-4 py-2 text-left">Farm Size</th>
                                <th class="px-4 py-2 text-left">Allocated Qty</th>
                                <th class="px-4 py-2 text-left">Unit Price</th>
                                <th class="px-4 py-2 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                            @foreach ($allocations as $alloc)
                                <tr>
                                    <td class="px-4 py-2">{{ $alloc['commodity'] }}</td>
                                    <td class="px-4 py-2">{{ $alloc['qty_per_hectare'] }}</td>
                                    <td class="px-4 py-2">{{ $alloc['farm_size'] }}</td>
                                    <td class="px-4 py-2">{{ $alloc['allocated_quantity'] }}</td>
                                    <td class="px-4 py-2">{{ number_format($alloc['unit_price'], 2) }}</td>
                                    <td class="px-4 py-2">{{ number_format($alloc['total_value'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700 font-medium text-gray-900 dark:text-white">
                            <tr>
                                <td colspan="5" class="px-4 py-2">Total Loan</td>
                                <td class="px-4 py-2">₦{{ number_format($total_loan) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="px-4 py-2">Insurance Rate</td>
                                <td class="px-4 py-2">{{ $insurance_rate }}%</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="px-4 py-2">Insurance Amount</td>
                                <td class="px-4 py-2">₦{{ number_format($insurance_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="px-4 py-2">Equity Held</td>
                                <td class="px-4 py-2">₦{{ number_format($equity_held, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="px-4 py-2">Disbursed Amount</td>
                                <td class="px-4 py-2">₦{{ number_format($disbursed_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            @if($application->status === 'pending')
                <form action="{{ route('admin.applications.approve', $application->uuid) }}" method="POST">
                    @csrf
                    @method('PUT')
                
                    <!-- Existing Farmer Info and Commodity Breakdown -->
                
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Collection Center *</label>
                        <select name="collection_center_id" id="collectionCenter" required class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select</option>
                            @foreach ($collectionCenters as $center)
                                <option value="{{ $center->id }}" data-type="{{ $center->type }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                        
                
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Return Center *</label>
                        <select name="return_center_id" id="returnCenter" required class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select</option>
                            @foreach ($returnCenters as $center)
                                <option value="{{ $center->id }}" data-type="{{ $center->type }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                
                    <div class="flex justify-end space-x-3">
                        <button id="approveBtn" type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-md opacity-50 cursor-not-allowed" disabled>Approve</button>
                        <button type="button" id="rejectBtn" class="px-5 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Reject</button>
                    </div>
                </form>

                <!-- Rejection Modal -->
                <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Application</h3>
                            <form action="{{ route('admin.applications.reject', $application->uuid) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason (Optional)</label>
                                    <textarea name="rejection_note" rows="4" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        placeholder="Enter reason for rejection..."></textarea>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" id="cancelReject" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Confirm Reject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-200">
                                This application has already been <strong>{{ $application->status }}</strong> and cannot be modified.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const collectionSelect = document.getElementById("collectionCenter");
            const returnSelect = document.getElementById("returnCenter");
            const approveBtn = document.getElementById("approveBtn");
            const rejectBtn = document.getElementById("rejectBtn");
            const rejectModal = document.getElementById("rejectModal");
            const cancelReject = document.getElementById("cancelReject");

            // Only run if form elements exist (application is pending)
            if (!collectionSelect || !returnSelect || !approveBtn) {
                return;
            }

            // Rejection modal handlers
            if (rejectBtn && rejectModal) {
                rejectBtn.addEventListener("click", function() {
                    rejectModal.classList.remove("hidden");
                });

                cancelReject.addEventListener("click", function() {
                    rejectModal.classList.add("hidden");
                });

                // Close modal when clicking outside
                rejectModal.addEventListener("click", function(e) {
                    if (e.target === rejectModal) {
                        rejectModal.classList.add("hidden");
                    }
                });
            }

            function handleCenterSelection(changedSelect, otherSelect) {
                const selectedOption = changedSelect.options[changedSelect.selectedIndex];
                const centerType = selectedOption.getAttribute("data-type");

                if (centerType === "both") {
                    otherSelect.value = changedSelect.value;
                    otherSelect.disabled = true;
                } else {
                    if (otherSelect.disabled) {
                        otherSelect.disabled = false;
                        otherSelect.value = "";
                    }
                }
                toggleApprove();
            }

            function toggleApprove() {
                const hasCollection = !!collectionSelect.value;
                const hasReturn = !!returnSelect.value;
                const canApprove = hasCollection && hasReturn;
                approveBtn.disabled = !canApprove;
                approveBtn.classList.toggle('opacity-50', !canApprove);
                approveBtn.classList.toggle('cursor-not-allowed', !canApprove);
            }

            collectionSelect.addEventListener("change", function() {
                handleCenterSelection(collectionSelect, returnSelect);
            });

            returnSelect.addEventListener("change", function() {
                handleCenterSelection(returnSelect, collectionSelect);
            });

            // Initialize state on load
            toggleApprove();
        });
    </script>
@endsection
