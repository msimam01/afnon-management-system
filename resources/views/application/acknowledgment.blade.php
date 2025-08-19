<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgement Slip - AFNON</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {!! ToastMagic::styles() !!}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: { 'sans': ['Inter', 'system-ui', 'sans-serif'] }
            }
        }
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

    <!-- HEADER -->
    <div class="bg-emerald-600 dark:bg-emerald-700 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center space-x-4">
            <div>
                <p class="text-emerald-100 text-sm">Acknowledgement Slip</p>
                <p class="text-emerald-100 text-xs">Ref: {{ $application->reference_number }}</p>
            </div>
        </div>

        <div class="flex flex-col items-center mt-4 sm:mt-0">
            {!! QrCode::size(80)->backgroundColor(255,255,255)
                ->generate(url('/verify/'.$application->reference_number)) !!}
            <p class="text-white text-xs mt-2">Scan to Verify</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-5xl border-gray-700 mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mt-6">

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700 dark:text-gray-300">
            <div>
                <p><span class="font-semibold">Application Reference:</span>
                    <span class="text-gray-900 dark:text-white">{{ $application->reference_number }}</span></p>
                <p><span class="font-semibold">Farmer Name:</span>
                    <span class="text-gray-900 dark:text-white">{{ $application->farmer->full_name }}</span></p>
                <p><span class="font-semibold">Registration Number:</span>
                    <span class="text-gray-900 dark:text-white">{{ $application->farmer->registration_number }}</span></p>
            </div>
            <div>
                <p><span class="font-semibold">Season:</span>
                    <span class="text-gray-900 dark:text-white">{{ $application->season->name }}</span></p>
                <p><span class="font-semibold">Farm Size:</span>
                    <span class="text-gray-900 dark:text-white">{{ $application->farm->size }} ha</span></p>
            </div>
        </div>

        <!-- Commodities Table -->
        <div class="mt-6 overflow-x-auto">
            <table class="w-full border border-gray-300 dark:border-gray-700 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                        <th class="border px-4 py-2 text-left">Commodity</th>
                        <th class="border px-4 py-2 text-left">Quantity</th>
                        <th class="border px-4 py-2 text-left">Unit Price</th>
                        <th class="border px-4 py-2 text-left">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($application->commodities as $commodity)
                        <tr class="dark:text-white">
                            <td class="border px-4 py-2">{{ $commodity->name }}</td>
                            <td class="border px-4 py-2">
                                {{ number_format($commodity->pivot->quantity) }} {{ $commodity->unit }}
                            </td>
                            <td class="border px-4 py-2">₦{{ number_format($commodity->price_per_unit, 2) }}</td>
                            <td class="border px-4 py-2">
                                ₦{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr class="dark:text-white">
                        <td colspan="3" class="border px-4 py-2 font-bold">Insurance ({{ $application->insurance_rate }}%)</td>
                        <td class="border px-4 py-2">₦{{ number_format($application->insurance_amount, 2) }}</td>
                    </tr>
                    <tr class="dark:text-white">
                        <td colspan="3" class="border px-4 py-2 font-bold">Total Loan</td>
                        <td class="border px-4 py-2">₦{{ number_format($application->total_loan, 2) }}</td>
                    </tr>
                    <tr class="dark:text-white">
                        <td colspan="3" class="border px-4 py-2 font-bold">Equity Held</td>
                        <td class="border px-4 py-2">₦{{ number_format($application->equity, 2) }}</td>
                    </tr>
                    <tr class="dark:text-white">
                        <td colspan="3" class="border px-4 py-2 font-bold">Disbursed Amount</td>
                        <td class="border px-4 py-2">₦{{ number_format($application->disbursed_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Print Button -->
        <div class="mt-6 text-center">
            <button onclick="window.print()"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow transition">
                Print Slip
            </button>
            <a href="{{ route('applications.slip.pdf', $application->uuid) }}"
                class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">
                Download PDF
             </a>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <div class="fixed bottom-6 right-6">
        <button id="darkModeToggle" class="p-3 rounded-full bg-gray-100 dark:bg-gray-700 shadow-lg">
            <svg id="sunIcon" class="h-6 w-6 hidden dark:block text-yellow-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364
                       6.364l-.707-.707M6.343 6.343l-.707-.707m12.728
                       0l-.707.707M6.343 17.657l-.707.707M16
                       12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="moonIcon" class="h-6 w-6 block dark:hidden text-gray-800" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646
                       3.646 9.003 9.003 0 0012 21a9.003
                       9.003 0 008.354-5.646z"/>
            </svg>
        </button>
    </div>
    {!! ToastMagic::scripts() !!}
    <script>
        const toggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        if (localStorage.getItem('theme') === 'dark') html.classList.add('dark');
        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
    </script>
</body>
</html>
