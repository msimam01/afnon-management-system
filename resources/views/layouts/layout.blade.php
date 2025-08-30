@php
    use App\Models\Setting;

    $centralDomains = ['localhost', '127.0.0.1', 'afnon.com'];
    $host = request()->getHost();
    $isCentral = in_array($host, $centralDomains);

    $tenant = null;
    $setting = null;

    if ($isCentral) {
        // Central settings (still stored in central DB settings table)
        $setting = Setting::first();
    } else {
        $tenant = \App\Models\SuperAdmin\Tenant::whereHas('domains', function ($q) use ($host) {
            $q->where('domain', $host);
        })->first();

        if ($tenant) {
            // Switch to tenant DB
            tenancy()->initialize($tenant);

            // Tenant settings (logo, phone, email, address, etc.)
            $setting = Setting::first();
        }
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'AFNON') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        [x-cloak] { display: none !important; }
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
        .sidebar-link {
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }
    </style>

</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans transition-colors duration-200">
    @include('layouts.navbar');
    @include('layouts.sidebar');
    @include('layouts.footer')
    @stack('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {!! ToastMagic::scripts() !!}
    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileMenuButton = document.getElementById('mobileMenuButton');

        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>



</body>

</html>
