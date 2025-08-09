<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Application - AFNON</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    keyframes: {
                        fadeSlideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        bounceOnce: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-8px)'
                            }
                        }
                    },
                    animation: {
                        fadeSlideUp: 'fadeSlideUp 0.8s ease-out forwards',
                        bounceOnce: 'bounceOnce 1s ease-in-out infinite'
                    }
                }
            }
        }
    </script>
</head>

<body
    class="h-full bg-gradient-to-br from-green-50 to-emerald-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4">

    <!-- Dark Mode Toggle -->
    <div class="fixed bottom-6 right-6">
        <button id="darkModeToggle"
            class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            <svg id="sunIcon" class="h-6 w-6 hidden dark:block text-yellow-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364
                       6.364l-.707-.707M6.343 6.343l-.707-.707m12.728
                       0l-.707.707M6.343 17.657l-.707.707M16
                       12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg id="moonIcon" class="h-6 w-6 block dark:hidden text-gray-800" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646
                       3.646 9.003 9.003 0 0012 21a9.003
                       9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <!-- Card -->
    <div class="max-w-4xl w-full bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 animate-fadeSlideUp">
        <div
            class="mx-auto w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-900 flex items-center justify-center animate-bounceOnce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-600 dark:text-emerald-400"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2l4 -4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-2xl text-center font-bold text-gray-900 dark:text-white mt-4">Application Verified</h1>
        <p class="mb-6 text-gray-600 text-center dark:text-gray-300">This application is valid and recorded in the AFNON
            system.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700 dark:text-gray-300">
            <div>
                <p><strong>Reference:</strong> {{ $application->reference_number }}</p>
                <p><strong>Farmer Name:</strong> {{ $application->farmer->full_name }}</p>
                <p><strong>Registration No.:</strong> {{ $application->farmer->registration_number }}</p>
            </div>
            <div>
                <p><strong>Season:</strong> {{ $application->season->name }}</p>
                <p><strong>Farm Size:</strong> {{ $application->farm->size }} ha</p>
            </div>
        </div>

        <h2 class="mt-6 mb-2 font-semibold text-gray-900 dark:text-white">Commodities</h2>
        <table class="w-full border border-gray-300 dark:border-gray-700 text-sm">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                    <th class="border px-4 py-2">Commodity</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Unit Price</th>
                    <th class="border px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($application->commodities as $commodity)
                    <tr>
                        <td class="border px-4 py-2 dark:text-white">{{ $commodity->name }}</td>
                        <td class="border px-4 py-2 dark:text-white">{{ number_format($commodity->pivot->quantity) }}
                            {{ $commodity->unit }}</td>
                        <td class="border px-4 py-2 dark:text-white">₦{{ number_format($commodity->price_per_unit, 2) }}
                        </td>
                        <td class="border px-4 py-2 dark:text-white">
                            ₦{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Financial Summary -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700 dark:text-gray-300">
            <div>
                <p><strong>Insurance Rate:</strong> {{ $application->insurance_rate }}%</p>
                <p><strong>Equity Held:</strong> ₦{{ number_format($application->equity, 2) }}</p>
            </div>
            <div>
                <p><strong>Total Loan:</strong> ₦{{ number_format($application->total_loan, 2) }}</p>
                <p><strong>Disbursed Amount:</strong> ₦{{ number_format($application->disbursed_amount, 2) }}</p>
            </div>
        </div>


        <div class="mt-6 text-center">
            <a href="{{ route('applications.verify.pdf', $application->reference_number) }}"
                class="px-4 py-2 mr-3 bg-emerald-600 text-white rounded hover:bg-emerald-700">
                Download Verification PDF
            </a>
            <a href="/" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Go Home</a>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const saved = localStorage.getItem('theme') || 'light';
        if (saved === 'dark') html.classList.add('dark');
        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
    </script>
</body>

</html>
