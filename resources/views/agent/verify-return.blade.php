@extends('layouts.layout')
@section('content')
    <!-- Return Section -->
    <div id="return-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Farmers with Return Obligations
            </h3>

            <!-- Filter & Search -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <select id="returnSeasonFilter"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="2024 Dry Season">2024 Dry Season</option>
                    <option value="2023 Wet Season">2023 Wet Season</option>
                </select>
                <input type="text" id="returnSearch" placeholder="Search Farmer ID or Name"
                    class="w-full md:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Farmer ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Commodity</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Expected Qty</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">NEC001234</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">John Doe</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">Maize (harvested)</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">80kg</td>
                            <td class="px-4 py-2">
                                <button onclick="openReturnModal('NEC001234')"
                                    class="bg-emerald-600 text-white text-xs px-3 py-1 rounded hover:bg-emerald-700">Verify
                                    Return</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Return Modal -->
    <div id="returnModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center overflow-y-auto">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-5xl mt-20 mx-4 p-6 sm:p-8 max-h-[90vh] overflow-y-auto">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 dark:border-gray-600">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Return Verification</h3>
                <button onclick="closeReturnModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">✕</button>
            </div>

            <form id="returnForm" class="space-y-8">

                <!-- Farmer & Application Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border dark:border-gray-600">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">📋 Farmer Info</h4>
                        <ul id="return-farmer-info" class="text-sm text-gray-700 dark:text-gray-300 space-y-1"></ul>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border dark:border-gray-600">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">📁 Application Info</h4>
                        <ul id="return-app-info" class="text-sm text-gray-700 dark:text-gray-300 space-y-1"></ul>
                    </div>
                </div>

                <!-- Commodity Breakdown -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">🧾 Commodity Breakdown</h4>
                    <table
                        class="w-full text-sm border-collapse border rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">Commodity</th>
                                <th class="px-4 py-2 text-left">Qty</th>
                                <th class="px-4 py-2 text-left">Price</th>
                                <th class="px-4 py-2 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody id="return-commodity-table" class="text-gray-900 dark:text-white"></tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700 font-medium">
                            <tr>
                                <td colspan="3" class="px-4 py-2 dark:text-white">Expected Return Value</td>
                                <td id="expectedReturnValue" class="px-4 py-2 text-emerald-600 dark:text-emerald-400">₦0.00
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- 🔁 Return Method -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">🔄 Choose Return Method</h4>

                    <!-- Modern Toggle Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label for="returnModeCommodity"
                            class="group border border-gray-300 dark:border-gray-600 rounded-lg p-4 flex items-center gap-3 cursor-pointer hover:border-emerald-500 transition">
                            <input type="radio" name="returnMode" id="returnModeCommodity" value="commodity"
                                class="sr-only" onchange="toggleReturnMode()" checked>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Return Commodity</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Upload photo proof of physical return
                                </div>
                            </div>
                        </label>

                        <label for="returnModeMoney"
                            class="group border border-gray-300 dark:border-gray-600 rounded-lg p-4 flex items-center gap-3 cursor-pointer hover:border-emerald-500 transition">
                            <input type="radio" name="returnMode" id="returnModeMoney" value="money" class="sr-only"
                                onchange="toggleReturnMode()">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Pay Money Equivalent</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Upload bank payment receipt</div>
                            </div>
                        </label>
                    </div>

                    <!-- Invoice Button (conditional) -->
                    <div id="invoiceBtnWrap" class="mt-4 hidden">
                        <button type="button" onclick="openInvoiceModal()" class="text-sm text-emerald-600 underline">📄
                            View Invoice</button>
                    </div>
                </div>

                <!-- 📸 Commodity Return Photo Upload -->
                <div id="commodityReturnGroup" class="mt-4 transition-all duration-300">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Farmer + Commodity
                        Photo *</label>
                    <label for="returnPhoto"
                        class="flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 cursor-pointer">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4a4 4 0 014-4h2a4 4 0 014 4v12m1 4H6a2 2 0 00-2 2v0a2 2 0 002 2h12a2 2 0 002-2v0a2 2 0 00-2-2z" />
                        </svg>
                        <span class="mt-2 text-sm text-gray-600 dark:text-gray-400">Click to upload or use camera</span>
                        <input type="file" id="returnPhoto" accept="image/*" class="sr-only" required />
                    </label>
                    <div id="returnPhotoPreview" class="mt-2 hidden">
                        <img class="h-24 w-24 object-cover border rounded-md" />
                    </div>
                </div>

                <!-- 💳 Payment Receipt Upload (if money) -->
                <div id="moneyReturnGroup" class="hidden mt-6 space-y-3 transition-all duration-300">
                    <div>
                        <label for="paymentProof"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Payment Receipt
                            *</label>
                        <label for="paymentProof"
                            class="flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 cursor-pointer">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c0-2.21 2-4 4-4s4 1.79 4 4-2 4-4 4-4 1.79-4 4m-8 0h8m-4-4h.01" />
                            </svg>
                            <span class="mt-2 text-sm text-gray-600 dark:text-gray-400">Upload or take a picture of
                                receipt</span>
                            <input type="file" id="paymentProof" accept="image/*,application/pdf" class="sr-only" />
                        </label>
                    </div>
                    <div
                        class="text-sm text-yellow-800 dark:text-yellow-300 bg-yellow-100 dark:bg-yellow-900 rounded-md px-4 py-3">
                        <strong>Invoice #:</strong> <span id="generatedInvoice">INV-00000000</span><br />
                        Please pay at the bank and upload your receipt for verification.
                    </div>
                </div>


                <!-- Notes -->
                <div>
                    <label for="returnNote" class="block text-sm font-medium text-gray-700 dark:text-gray-300">📝
                        Verification Notes</label>
                    <textarea id="returnNote" rows="3"
                        class="block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                        placeholder="Add any remarks about the verification..."></textarea>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
                    <button type="submit"
                        class="bg-emerald-600 text-white px-6 py-2 rounded-md hover:bg-emerald-700 font-medium transition">
                        ✅ Submit Return Verification
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Invoice Modal -->
    <div id="invoiceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl mx-4 p-6 sm:p-8">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Invoice Preview</h3>
                <button onclick="closeInvoiceModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">✕</button>
            </div>
            <div id="invoiceContent" class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                <p><strong>Invoice Number:</strong> INV-20250725-8552</p>
                <p><strong>Farmer:</strong> John Doe</p>
                <p><strong>Total Amount:</strong> <span class="text-emerald-600 font-semibold">₦150,000.00</span></p>
                <p>Payment should be made to the designated bank account. This invoice will be verified upon return.</p>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="downloadInvoice()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 font-medium transition">
                    ⬇ Download Invoice
                </button>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const returnForm = document.getElementById('returnForm');
            const returnPhoto = document.getElementById('returnPhoto');
            const paymentProof = document.getElementById('paymentProof');
            const commodityGroup = document.getElementById('commodityReturnGroup');
            const moneyGroup = document.getElementById('moneyReturnGroup');
            const invoiceEl = document.getElementById('generatedInvoice');

            function toggleReturnMode() {
    const mode = document.querySelector('input[name="returnMode"]:checked').value;
    const showMoney = mode === 'money';

    document.getElementById('commodityReturnGroup').classList.toggle('hidden', showMoney);
    document.getElementById('moneyReturnGroup').classList.toggle('hidden', !showMoney);
    document.getElementById('invoiceBtnWrap').classList.toggle('hidden', !showMoney);

    document.getElementById('returnPhoto').required = !showMoney;
    document.getElementById('paymentProof').required = showMoney;
}



            // Bind toggle event
            document.querySelectorAll('input[name="returnMode"]').forEach(radio => {
                radio.addEventListener('change', toggleReturnMode);
            });

            // Preview uploaded photo
            returnPhoto.addEventListener('change', e => {
                const file = e.target.files[0];
                const preview = document.getElementById('returnPhotoPreview');
                const img = preview.querySelector('img');
                if (file) {
                    img.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
            });

            // Modal open handler
            window.openReturnModal = function(farmerId) {
                document.getElementById('returnModal').classList.remove('hidden');

                const farmer = {
                    name: "John Doe",
                    phone: "+2348123456789",
                    state: "Kano",
                    lga: "Tarauni"
                };
                const app = {
                    season: "2024 Dry Season",
                    seed: "Maize",
                    farmSize: "2.5ha"
                };
                const commodities = [{
                        name: "Maize",
                        quantity: 5,
                        unit: "bags",
                        price: 10000
                    },
                    {
                        name: "NPK",
                        quantity: 3,
                        unit: "bags",
                        price: 8000
                    }
                ];

                const farmerInfo = `
            <li><strong>Name:</strong> ${farmer.name}</li>
            <li><strong>Phone:</strong> ${farmer.phone}</li>
            <li><strong>State:</strong> ${farmer.state}</li>
            <li><strong>LGA:</strong> ${farmer.lga}</li>`;
                const appInfo = `
            <li><strong>Season:</strong> ${app.season}</li>
            <li><strong>Seed:</strong> ${app.seed}</li>
            <li><strong>Farm Size:</strong> ${app.farmSize}</li>`;

                document.getElementById('return-farmer-info').innerHTML = farmerInfo;
                document.getElementById('return-app-info').innerHTML = appInfo;

                // Commodity Table
                const table = document.getElementById('return-commodity-table');
                let total = 0;
                table.innerHTML = '';
                commodities.forEach(c => {
                    const lineTotal = c.quantity * c.price;
                    total += lineTotal;
                    table.innerHTML += `
                <tr>
                    <td class="px-4 py-2 border">${c.name}</td>
                    <td class="px-4 py-2 border">${c.quantity} ${c.unit}</td>
                    <td class="px-4 py-2 border">₦${c.price.toLocaleString()}</td>
                    <td class="px-4 py-2 border">₦${lineTotal.toLocaleString()}</td>
                </tr>`;
                });

                document.getElementById('expectedReturnValue').innerText = `₦${total.toLocaleString()}`;
                invoiceEl.innerText = 'INV-' + new Date().getTime();
                toggleReturnMode(); // Apply initial mode
            }

            window.closeReturnModal = function() {
                document.getElementById('returnModal').classList.add('hidden');
                returnForm.reset();
                document.getElementById('returnPhotoPreview').classList.add('hidden');
                toggleReturnMode();
            }

            returnForm.addEventListener('submit', function(e) {
                e.preventDefault();
                alert("✅ Return Verification Submitted!");
                closeReturnModal();
            });
        });

        function toggleReturnMode() {
            const selected = document.querySelector('input[name="returnMode"]:checked').value;
            document.getElementById('commodityReturnGroup').classList.toggle('hidden', selected !== 'commodity');
            document.getElementById('moneyReturnGroup').classList.toggle('hidden', selected !== 'money');
            document.getElementById('returnPhoto').required = selected === 'commodity';
            document.getElementById('paymentProof').required = selected === 'money';
        }

        document.getElementById('returnPhoto').addEventListener('change', (e) => {
            const file = e.target.files[0];
            const preview = document.getElementById('returnPhotoPreview');
            const img = preview.querySelector('img');
            if (file) {
                img.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });

        function openInvoiceModal() {
            document.getElementById('invoiceModal').classList.remove('hidden');
        }

        function closeInvoiceModal() {
            document.getElementById('invoiceModal').classList.add('hidden');
        }

        function downloadInvoice() {
            // Fake download for now
            const invoiceText = document.getElementById('invoiceContent').innerText;
            const blob = new Blob([invoiceText], {
                type: 'text/plain'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'invoice.txt';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
@endsection
