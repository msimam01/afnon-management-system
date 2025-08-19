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
            
                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-md">Approve</button>
                </div>
            </form>
            
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const collectionSelect = document.getElementById("collectionCenter");
            const returnSelect = document.getElementById("returnCenter");

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
            }

            collectionSelect.addEventListener("change", function() {
                handleCenterSelection(collectionSelect, returnSelect);
            });

            returnSelect.addEventListener("change", function() {
                handleCenterSelection(returnSelect, collectionSelect);
            });
        });
    </script>
@endsection
