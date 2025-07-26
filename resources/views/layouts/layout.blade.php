<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AFNON</title>
    {{-- <link rel="stylesheet" href="{{asset('css/style.css')}}"> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- include DataTables & Buttons CSS/JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

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
    <style>
        /* Match Tailwind dark mode for DataTables */
        body.dark .dataTables_wrapper {
            color: #e5e7eb;
            /* text-gray-200 */
        }

        body.dark .dataTables_wrapper table {
            background-color: #1f2937;
            /* bg-gray-800 */
            color: #e5e7eb;
        }

        body.dark .dataTables_wrapper .dataTables_length label,
        body.dark .dataTables_wrapper .dataTables_filter label,
        body.dark .dataTables_wrapper .dataTables_info,
        body.dark .dataTables_wrapper .dataTables_paginate {
            color: #d1d5db;
            /* text-gray-300 */
        }

        body.dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #d1d5db !important;
            background-color: transparent !important;
        }

        body.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #10b981 !important;
            /* emerald-500 */
            color: white !important;
            border-radius: 4px;
        }

        body.dark .dataTables_wrapper .dataTables_filter input,
        body.dark .dataTables_wrapper .dataTables_length select {
            background-color: #374151;
            /* bg-gray-700 */
            border-color: #4b5563;
            /* border-gray-600 */
            color: #f9fafb;
        }

        body.dark .dataTables_wrapper table thead {
            background-color: #374151;
            color: #f9fafb;
        }

        body.dark .dataTables_wrapper table tbody tr {
            background-color: #1f2937;
        }

        body.dark .dataTables_wrapper table tbody tr:hover {
            background-color: #374151;
        }
    </style>

</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans transition-colors duration-200">
    @include('layouts.navbar')
    <div class="flex pt-16">
        @include('layouts.sidebar')
        <main class="ml-0 md:ml-80 w-full min-h-screen px-4 sm:px-6 lg:px-8 py-12 transition-all">
            @yield('content')
        </main>
    </div>
    @include('layouts.footer')
    <script src="{{ asset('js/script.js') }}"></script>
    {!! ToastMagic::scripts() !!}

</body>

</html>
