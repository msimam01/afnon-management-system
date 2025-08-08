<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Seasonal Loan - NECAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {!! ToastMagic::styles() !!}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                }
            }
        }
    </script>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <nav
        class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <button onclick="history.back()"
                        class="mr-4 p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="h-8 w-8 bg-emerald-600 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <h1 class="ml-3 text-xl font-bold text-gray-900 dark:text-white">AFNON</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <svg id="sunIcon" class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <svg id="moonIcon" class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>
                    <a href="/central/login" class="text-emerald-600 hover:text-emerald-500 font-medium">Back to
                        Login</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mt-9 mx-auto py-10 px-4 sm:px-8 lg:px-10">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Apply for Seasonal Loan
            </h1>
            <p class="mt-3 text-lg text-gray-500 dark:text-gray-300">
                Get access to agricultural inputs based on your farm size
            </p>
        </div>

        <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-2xl overflow-hidden">
            <form id="application-form" method="POST" action="{{ route('applications.store') }}"
                class="space-y-10 p-8">
                @csrf
                <!-- Season -->
                <section>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Season</h2>
                    <select id="season-select" name="season" onchange="renderCommoditiesForSeason()" required
                        class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="{{ $season->id }}">{{ $season->name }} Application</option>
                    </select>

                    <input type="hidden" name="season_id" value="{{ $season->id }}">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Only open seasons are shown</p>
                    <x-input-error :messages="$errors->get('season')" class="mt-1" />
                </section>

                <!-- Farmer Details -->
                <section>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Farmer Details</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Full Name *</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required
                                placeholder="Enter full name"
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                placeholder="+234 xxx xxx xxxx"
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">NIN *</label>
                            <input type="text" name="nin" maxlength="11" value="{{ old('nin') }}" required
                                pattern="[0-9]{11}"
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                            <x-input-error :messages="$errors->get('nin')" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">BVN *</label>
                            <input type="text" id="bvn-input" name="bvn" maxlength="11" required
                                pattern="[0-9]{11}" onchange="validateBVN()" value="{{ old('bvn') }}"
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                            <x-input-error :messages="$errors->get('bvn')" class="mt-1" />
                            <div id="bvn-status" class="mt-1 text-sm hidden text-yellow-600 dark:text-yellow-400">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">State *</label>
                            <select name="state" id="state" onchange="selectLGA(this)" required
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">Select State</option>
                            </select>
                            <x-input-error :messages="$errors->get('state')" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">LGA *</label>
                            <select name="lga" id="lga" required
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">Select LGA</option>
                            </select>
                            <x-input-error :messages="$errors->get('lga')" class="mt-1" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Address *</label>
                            <textarea name="address" value="{{ old('address') }}" required rows="3"
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Enter your address"></textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-1" />
                        </div>
                    </div>
                </section>

                <!-- Farm Information -->
                <section>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Farm Information</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Farm Location *</label>
                            <input type="text" name="farm_location" value="{{ old('farm_location') }}" required
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Village/Town" />
                            <x-input-error :messages="$errors->get('farm_location')" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Farm Size (Hectares)
                                *</label>
                            <input type="number" name="farm_size" id="farm-size" step="0.1" min="0.1"
                                required value="{{ old('number') }}"
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="e.g. 2.5" />
                            <x-input-error :messages="$errors->get('farm_size')" class="mt-1" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Cluster Farm
                                Location</label>
                            <input type="text" name="cluster_location" value="{{ old('cluster_location') }}"
                                class="w-full px-4 py-2 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="e.g., Igabi West" />
                            <x-input-error :messages="$errors->get('cluster_location')" class="mt-1" />
                        </div>
                    </div>
                </section>

                <!-- Seed Selection -->
                <section id="seed-selection" class="hidden">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Choose a Seed</h2>
                    <div id="seed-options" class="grid md:grid-cols-2 gap-6"></div>
                    <x-input-error :messages="$errors->get('seed_selected')" class="mt-1" />
                </section>

                <!-- Commodities Breakdown -->
                <section id="other-commodities-section" class="hidden">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Commodities Breakdown</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border rounded-lg overflow-hidden dark:border-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-2 text-left dark:text-white">Commodity</th>
                                    <th class="px-4 py-2 text-left dark:text-white">Quantity</th>
                                    <th class="px-4 py-2 text-left dark:text-white">Unit Price</th>
                                    <th class="px-4 py-2 text-left dark:text-white">Total</th>
                                </tr>
                            </thead>
                            <tbody id="other-commodities-list"
                                class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Loan Summary -->
                <section id="loan-summary" class="hidden">
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-600 rounded-lg">
                            <p id="total-loan" class="font-semibold text-gray-900 dark:text-white"></p>
                        </div>
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-600 rounded-lg">
                            <p id="equity-held" class="font-semibold text-gray-900 dark:text-white"></p>
                        </div>
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/10 border border-blue-600 rounded-lg">
                            <p id="disbursed-amount" class="font-semibold text-gray-900 dark:text-white"></p>
                        </div>
                    </div>
                </section>

                <!-- Note -->
                <div id="equity-note" class="hidden mt-4 text-sm text-yellow-700 dark:text-yellow-300">
                    Note: You will only receive 50% of the loan value. 50% is held as equity.
                </div>

                <!-- BVN Info -->
                <div
                    class="bg-blue-50 dark:bg-blue-900/10 border border-blue-300 dark:border-blue-700 rounded-lg p-4 mt-4">
                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">BVN Verification</p>
                    <p class="text-sm text-blue-700 dark:text-blue-400">
                        Your BVN will be verified automatically before submission.
                    </p>
                </div>

                <!-- Agreement -->
                <div class="space-y-4 pt-6">
                    <div class="flex items-start gap-2">
                        <input type="checkbox" required
                            class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            I agree to the <a href="#" class="text-emerald-600 hover:underline">Terms and
                                Conditions</a> and confirm that all provided information is accurate.
                        </p>
                    </div>
                    <div class="flex items-start gap-2">
                        <input type="checkbox" required
                            class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            I understand that 50% of the loan value will be held as equity by the organization.
                        </p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-6 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.footer')

    <script>
        // Dark Mode
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') html.classList.add('dark');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            });
        }

        //Fetch all States
        fetch('https://nga-states-lga.onrender.com/fetch')
            .then((res) => res.json())
            .then((data) => {
                var x = document.getElementById("state");
                for (let index = 0; index < Object.keys(data).length; index++) {
                    var option = document.createElement("option");
                    option.text = data[index];
                    option.value = data[index];
                    x.add(option);
                }
            });

        //Fetch Local Goverments based on selected state
        function selectLGA(target) {
            var state = target.value;
            fetch('https://nga-states-lga.onrender.com/?state=' + state)
                .then((res) => res.json())
                .then((data) => {
                    var x = document.getElementById("lga");

                    var select = document.getElementById("lga");
                    var length = select.options.length;
                    for (i = length - 1; i >= 0; i--) {
                        select.options[i] = null;
                    }
                    for (let index = 0; index < Object.keys(data).length; index++) {
                        var option = document.createElement("option");
                        option.text = data[index];
                        option.value = data[index];
                        x.add(option);
                    }
                });
        }

        function validateBVN() {
            const bvn = document.getElementById('bvn-input').value;
            const status = document.getElementById('bvn-status');
            if (bvn.length === 11) {
                status.classList.remove('hidden');
                setTimeout(() => {
                    status.innerHTML =
                        `<span class="text-green-600 dark:text-green-400">✅ BVN verified successfully</span>`;
                }, 2000);
            } else {
                status.classList.add('hidden');
            }
        }

        const commodityData = {
            "{{ $season->id }}": {
                seeds: @json($seeds),
                others: @json($others)
            }
        };
        const seedCommodities = @json($seeds);
        const otherCommodities = @json($others);
        const insuranceRate = {{ $season->insurance_rate ?? 1 }};

        function handleFarmSizeChange() {
            const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
            const seedSection = document.getElementById('seed-selection');
            const seedOptions = document.getElementById('seed-options');
            const breakdownSection = document.getElementById('other-commodities-section');

            if (farmSize > 0) {
                renderCommoditiesForSeason(); // re-render
            } else {
                seedOptions.innerHTML = ''; // clear seeds
                seedSection.classList.add('hidden');
                breakdownSection.classList.add('hidden');
            }
        }

        document.getElementById('farm-size').addEventListener('input', handleFarmSizeChange);

        function renderCommoditiesForSeason() {
            const seasonId = document.getElementById('season-select').value;
            const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
            const data = commodityData[seasonId];

            if (!data || farmSize <= 0) return;

            // Render seed options
            const seedHTML = data.seeds.map(seed => `
            <label class="block border rounded-lg p-4 bg-white dark:bg-gray-800 hover:shadow transition">
                <input type="radio" name="selected_seed" value="${seed.id}" data-price="${seed.price_per_unit}" data-qty="${seed.quantity_per_hectare}" data-name="${seed.name}" data-unit="${seed.unit}" class="hidden">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">${seed.name}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${seed.quantity_per_hectare} ${seed.unit}/ha × ₦${seed.price_per_unit.toLocaleString()}</p>
                    </div>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">Select</span>
                </div>
            </label>
            <x-input-error :messages="$errors->get('selected_seed')" class="mt-1" />
        `).join('');

            document.getElementById('seed-options').innerHTML = seedHTML;
            document.getElementById('seed-selection').classList.remove('hidden');

            document.querySelectorAll('input[name="selected_seed"]').forEach(input => {
                input.addEventListener('change', () => {
                    renderCommodityBreakdown(data, farmSize, input);
                });
            });

        }

        function renderCommodityBreakdown(data, farmSize, selectedInput) {
            let total = 0;

            const seedQty = parseFloat(selectedInput.dataset.qty) * farmSize;
            const seedPrice = parseFloat(selectedInput.dataset.price);
            const seedUnit = selectedInput.dataset.unit;
            const seedName = selectedInput.dataset.name;
            const seedTotal = seedQty * seedPrice;
            total += seedTotal;

            let rows = `
            <tr>
                <td class="px-4 py-2 dark:text-white">${seedName}</td>
                <td class="px-4 py-2 dark:text-white">${seedQty.toFixed(1)} ${seedUnit}</td>
                <td class="px-4 py-2 dark:text-white">₦${seedPrice.toLocaleString()}</td>
                <td class="px-4 py-2 font-semibold dark:text-white">₦${seedTotal.toLocaleString()}</td>
            </tr>
        `;

            data.others.forEach(item => {
                const q = item.quantity_per_hectare * farmSize;
                const val = q * item.price_per_unit;
                total += val;
                rows += `
                <tr>
                    <td class="px-4 py-2 dark:text-white">${item.name}</td>
                    <td class="px-4 py-2 dark:text-white">${q.toFixed(1)} ${item.unit}</td>
                    <td class="px-4 py-2 dark:text-white">₦${item.price_per_unit.toLocaleString()}</td>
                    <td class="px-4 py-2 font-semibold dark:text-white">₦${val.toLocaleString()}</td>
                </tr>`;
            });

            const insurance = total * (insuranceRate / 100);
            const finalLoan = total + insurance;
            const equity = finalLoan / 2;

            // Add insurance row
            rows += `
            <tr class="bg-gray-50 dark:bg-gray-700">
                <td class="px-4 py-2 font-semibold text-gray-800 dark:text-white">Insurance (${insuranceRate}%)</td>
                <td class="px-4 py-2 dark:text-white">—</td>
                <td class="px-4 py-2 dark:text-white">—</td>
                <td class="px-4 py-2 font-semibold dark:text-white">₦${insurance.toLocaleString()}</td>
            </tr>
        `;

            // Update table and summary
            document.getElementById('other-commodities-list').innerHTML = rows;
            document.getElementById('total-loan').innerHTML =
                `Total Loan Value: <strong>₦${finalLoan.toLocaleString()}</strong>`;
            document.getElementById('equity-held').innerHTML = `Equity Held: <strong>₦${equity.toLocaleString()}</strong>`;
            document.getElementById('disbursed-amount').innerHTML =
                `Disbursed Amount: <strong>₦${equity.toLocaleString()}</strong>`;

            // Show sections
            document.getElementById('other-commodities-section').classList.remove('hidden');
            document.getElementById('loan-summary').classList.remove('hidden');
            document.getElementById('equity-note').classList.remove('hidden');
        }

        // Trigger calculation when farm size changes
        document.getElementById('farm-size').addEventListener('input', () => {
            const selected = document.querySelector('input[name="selected_seed"]:checked');
            if (selected) {
                const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
                const seasonId = document.getElementById('season-select').value;
                renderCommodityBreakdown(commodityData[seasonId], farmSize, selected);
            }
        });

        // Attach to the form that wraps your application fields
        document.querySelector('form').addEventListener('submit', function(e) {
            const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
            const selectedSeed = document.querySelector('input[name="selected_seed"]:checked');

            // If farm size is > 0 but no seed selected, block submission
            if (farmSize > 0 && !selectedSeed) {
                e.preventDefault();
                showSeedError("Please select a seed before submitting your application.");
                document.getElementById('seed-selection').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });

        function showSeedError(message) {
            let errorDiv = document.getElementById('seed-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'seed-error';
                errorDiv.className = 'text-red-600 text-sm mt-2';
                document.getElementById('seed-selection').appendChild(errorDiv);
            }
            errorDiv.textContent = message;
        }

        function hideSeedError() {
            const errorDiv = document.getElementById('seed-error');
            if (errorDiv) errorDiv.remove();
        }

        // When a seed is selected, remove the error message
        document.addEventListener('change', function(e) {
            if (e.target && e.target.name === 'selected_seed') {
                hideSeedError();
            }
        });

        function downloadAcknowledgment() {
            alert("🔧 Acknowledgment slip generation coming soon...");
        }

        function closeSuccessModal() {
            document.getElementById('success-modal').classList.add('hidden');
        }
        // const form = document.getElementById('application-form');
        // form.addEventListener('submit', async function(e) {
        //     e.preventDefault();

        //     const formData = new FormData(form);

        //     try {
        //         const res = await fetch('/applications', {
        //             method: 'POST',
        //             headers: {
        //                 'Accept': 'application/json' // 🔥 Tells Laravel to return JSON even on error
        //             },
        //             body: formData
        //         });

        //         const contentType = res.headers.get('content-type');
        //         const isJSON = contentType && contentType.includes('application/json');

        //         if (!res.ok) {
        //             const errorResponse = isJSON ? await res.json() : await res.text();
        //             console.error('Validation Error:', errorResponse);

        //             if (isJSON && errorResponse.errors) {
        //                 alert(Object.values(errorResponse.errors).flat().join('\n'));
        //             } else {
        //                 alert('Submission failed. Please try again.');
        //             }
        //             return;
        //         }

        //         const data = await res.json();
        //         console.log('Success:', data);

        //         // ✅ Show success modal with application data
        //         document.getElementById('ref-number').textContent = data.reference;
        //         document.getElementById('success-modal').classList.remove('hidden');

        //     } catch (error) {
        //         console.error("Network or unexpected error:", error);
        //         alert("An unexpected error occurred. Please check your connection or try again.");
        //     }
        // });

        document.getElementById('season-select').addEventListener('change', renderCommoditiesForSeason);
        document.getElementById('farm-size').addEventListener('input', renderCommoditiesForSeason);
        // Initial render on page load
        document.addEventListener('DOMContentLoaded', () => {
            renderCommoditiesForSeason();
        });
    </script>
    {!! ToastMagic::scripts() !!}
</body>
</html>
