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
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif']
                },
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#059669',
                            light: '#10b981',
                            dark: '#047857'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Left -->
                <div class="flex items-center">
                    <button onclick="history.back()"
                        class="mr-4 p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="h-8 w-8 bg-primary rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <h1 class="ml-3 text-lg sm:text-xl font-bold text-gray-900 dark:text-white">AFNON</h1>
                </div>

                <!-- Right -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
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
                    <a href="/central/login" class="text-primary hover:text-primary-light font-medium">Back to Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Slip Container -->
    <main class="pt-20 pb-10 px-4">
        <!-- Acknowledgement Slip -->
        <div
            class="bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">

            <!-- Header with Logo & Title -->
            <div class="bg-emerald-600 dark:bg-emerald-700 p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/afnon-logo.png') }}" alt="Afnon Logo"
                        class="h-12 w-12 rounded-full bg-white p-1">
                    <div>
                        <h1 class="text-2xl font-bold text-white">AFNON</h1>
                        <p class="text-emerald-100 text-sm">Acknowledgement Slip</p>
                    </div>
                </div>
                <div class="text-white text-sm text-right">
                    <p>{{ now()->format('F j, Y') }}</p>
                    <p>{{ now()->format('g:i A') }}</p>
                </div>
            </div>

            <!-- Application Details -->
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Application Details</h2>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 sm:gap-y-6 sm:gap-x-10 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">

                    <div
                        class="border-b sm:border-b-0 sm:border-r border-gray-200 dark:border-gray-700 pb-4 sm:pb-0 pr-0 sm:pr-6">
                        <p class="mb-1 text-gray-500 dark:text-gray-400 text-sm">Application Reference</p>
                        <strong
                            class="text-lg text-gray-900 dark:text-white">{{ $application->reference_number }}</strong>
                    </div>

                    <div>
                        <p class="mb-1 text-gray-500 dark:text-gray-400 text-sm">Farmer Name</p>
                        <strong
                            class="text-lg text-gray-900 dark:text-white">{{ $application->farmer->full_name }}</strong>
                    </div>

                    <div
                        class="border-b sm:border-b-0 sm:border-r border-gray-200 dark:border-gray-700 pb-4 sm:pb-0 pr-0 sm:pr-6">
                        <p class="mb-1 text-gray-500 dark:text-gray-400 text-sm">Registration Number</p>
                        <strong
                            class="text-lg text-gray-900 dark:text-white">{{ $application->farmer->registration_number }}</strong>
                    </div>

                    <div>
                        <p class="mb-1 text-gray-500 dark:text-gray-400 text-sm">Season</p>
                        <strong class="text-lg text-gray-900 dark:text-white">{{ $application->season->name }}</strong>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="mb-1 text-gray-500 dark:text-gray-400 text-sm">Farm Size</p>
                        <strong class="text-lg text-gray-900 dark:text-white">{{ $application->farm->size }} ha</strong>
                    </div>
                </div>

                <!-- Commodities Table -->
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-8 mb-4">Commodity Breakdown</h2>
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-700 text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            <th class="border px-4 py-2 text-left">Commodity</th>
                            <th class="border px-4 py-2 text-left">Quantity</th>
                            <th class="border px-4 py-2 text-left">Unit Price</th>
                            <th class="border px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($application->commodities as $commodity)
                            <tr class="hover:bg-gray-50 dark:text-white dark:hover:bg-gray-800">
                                <td class="border px-4 py-2">{{ $commodity->name }}</td>
                                <td class="border px-4 py-2">{{ number_format($commodity->pivot->quantity) }}
                                    {{ $commodity->unit }}</td>
                                <td class="border px-4 py-2">₦{{ number_format($commodity->price_per_unit, 2) }}</td>
                                <td class="border px-4 py-2">
                                    ₦{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-800">
                        <tr class="dark:text-white">
                            <td colspan="3" class="border px-4 py-2 font-bold">Insurance
                                ({{ $application->insurance_rate }}%)</td>
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

                <!-- Print Button -->
                <div class="mt-6 text-center">
                    <button onclick="window.print()"
                        class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg shadow">
                        Print Slip
                    </button>
                </div>
            </div>
        </div>

    </main>

    @include('layouts.footer')

    <!-- Dark Mode Script -->
    <script>
        const toggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const saved = localStorage.getItem('theme') || 'light';
        if (saved === 'dark') html.classList.add('dark');

        toggle?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
    </script>
</body>

</html>
