// application.js (final working version based on your requirements)
document.addEventListener('DOMContentLoaded', function () {
    const farmSizeInput = document.getElementById('farm-size');
    const seedSection = document.getElementById('seed-selection');
    const seedList = document.getElementById('seed-options');
    const otherSection = document.getElementById('other-commodities-section');
    const otherList = document.getElementById('other-commodities-list');
    const totalText = document.getElementById('total-loan');
    const equityText = document.getElementById('equity-held');
    const disbursedText = document.getElementById('disbursed-amount');
    const summaryBox = document.getElementById('loan-summary');
    const equityNote = document.getElementById('equity-note');

    const insuranceRate = window.insuranceRate || 1;

    let selectedSeedId = null;

    function renderSeedOptions() {
        seedList.innerHTML = '';
        seedCommodities.forEach(seed => {
            const option = `
            <label class="block cursor-pointer border rounded-lg p-4 bg-white dark:bg-gray-800 shadow hover:shadow-md transition">
                <input type="radio" name="selected-seed" required value="${seed.id}" data-name="${seed.name}" data-price="${seed.price_per_unit}" data-qty="${seed.quantity_per_hectare}" data-unit="${seed.unit}" class="hidden">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">${seed.name}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${seed.quantity_per_hectare} ${seed.unit}/ha × ₦${seed.price_per_unit.toLocaleString()}</p>
                    </div>
                    <div class="text-emerald-600 dark:text-emerald-400 font-bold">Select</div>
                </div>
            </label>`;
            seedList.innerHTML += option;
        });

        seedSection.classList.remove('hidden');
    }

    function calculateBreakdown() {
        const farmSize = parseFloat(farmSizeInput.value || 0);
        const selected = document.querySelector('input[name="selected-seed"]:checked');

        if (!selected || farmSize <= 0) {
            otherSection.classList.add('hidden');
            summaryBox.classList.add('hidden');
            equityNote.classList.add('hidden');
            return;
        }

        otherList.innerHTML = '';
        let total = 0;

        // Seed Row
        const seedQty = parseFloat(selected.dataset.qty) * farmSize;
        const seedPrice = parseFloat(selected.dataset.price);
        const seedUnit = selected.dataset.unit;
        const seedName = selected.dataset.name;
        const seedTotal = seedQty * seedPrice;
        total += seedTotal;

        otherList.innerHTML += `
            <tr>
                <td class="px-4 py-2 text-gray-900 dark:text-white font-medium">${seedName}</td>
                <td class="px-4 py-2 text-gray-700 dark:text-gray-300">${seedQty.toFixed(1)} ${seedUnit}</td>
                <td class="px-4 py-2 text-gray-700 dark:text-gray-300">₦${seedPrice.toLocaleString()}</td>
                <td class="px-4 py-2 font-semibold text-gray-900 dark:text-white">₦${seedTotal.toLocaleString()}</td>
            </tr>`;

        // Other Commodities
        otherCommodities.forEach(item => {
            const quantity = item.quantity_per_hectare * farmSize;
            const value = quantity * item.price_per_unit;
            total += value;

            otherList.innerHTML += `
                <tr>
                    <td class="px-4 py-2 text-gray-900 dark:text-white">${item.name}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">${quantity.toFixed(1)} ${item.unit}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-300">₦${item.price_per_unit.toLocaleString()}</td>
                    <td class="px-4 py-2 font-semibold text-gray-900 dark:text-white">₦${value.toLocaleString()}</td>
                </tr>`;
        });

        // Insurance
        const insuranceAmount = total * (insuranceRate / 100);
        const finalLoan = total + insuranceAmount;
        const equity = finalLoan / 2;

        otherList.innerHTML += `
            <tr class="bg-gray-50 dark:bg-gray-700">
                <td class="px-4 py-2 font-semibold text-gray-800 dark:text-white">Insurance (${insuranceRate}%)</td>
                <td class="px-4 py-2">-</td>
                <td class="px-4 py-2">-</td>
                <td class="px-4 py-2 font-semibold text-gray-800 dark:text-white">₦${insuranceAmount.toLocaleString()}</td>
            </tr>`;

        // Summary Updates
        totalText.innerHTML = `Total Loan Value: <strong>₦${finalLoan.toLocaleString()}</strong>`;
        equityText.innerHTML = `Equity Held by Organization: <strong>₦${equity.toLocaleString()}</strong>`;
        disbursedText.innerHTML = `Amount Disbursed to Farmer: <strong>₦${equity.toLocaleString()}</strong>`;

        otherSection.classList.remove('hidden');
        summaryBox.classList.remove('hidden');
        equityNote.classList.remove('hidden');
    }

    renderSeedOptions();

    farmSizeInput.addEventListener('input', calculateBreakdown);
    document.addEventListener('change', function (e) {
        if (e.target.name === 'selected-seed') {
            selectedSeedId = e.target.value;
            calculateBreakdown();
        }
    });
});
