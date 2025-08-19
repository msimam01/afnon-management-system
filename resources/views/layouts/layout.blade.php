@php
    $centralDomains = ['localhost', '127.0.0.1', 'afnon.com'];
    $host = request()->getHost();
    $isCentral = in_array($host, $centralDomains);
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
    <nav
        class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button id="mobileMenuButton"
                        class="md:hidden mr-3 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="h-8 w-full flex items-center justify-center">
                        {{-- <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg> --}}
                        {{-- <h1 class="ml-3 text-xl font-bold text-gray-900 dark:text-white">AFNON</h1> --}}
                        <img src="{{ asset('images/afnon-logo.png') }}" class="h-12 w-full text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" alt="AFNON logo">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:block">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Welcome, </span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                    </div>
                    <!-- Profile Settings -->
                    <div class="relative">
                        <button id="profileDropdown"
                            class="flex items-center space-x-2 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <div class="h-6 w-6 bg-emerald-600 rounded-full flex items-center justify-center">
                                <span class="text-xs text-white font-medium">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </span>
                            </div>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div id="profileMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                            <a href="{{ $isCentral ? route('superadmin.profile.edit') : route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profile
                                Settings</a>
                            <form action="{{ $isCentral ? route('central.logout') : route('tenant.logout') }}" method="post">
                                @csrf
                                <button type="submit"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                            </form>
                            {{-- <a href="{{ url('logout') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</a> --}}
                        </div>
                    </div>
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
                </div>
            </div>
        </div>
    </nav>
    <div class="flex pt-16">
        <nav id="sidebar"
            class="bg-white dark:bg-gray-800 shadow-lg w-80 h-screen fixed top-16 left-0 z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <div class="p-4 overflow-y-auto h-[calc(100vh-4rem)]">
                <ul class="space-y-2">
                    @role('super-admin')
                        <li>
                            <a href="{{ route('superadmin.dashboard') }}"
                                class="{{ Route::is('superadmin.dashboard') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.users.index') }}"
                                class="{{ Route::is('superadmin.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                User Management
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.tenants.index') }}"
                                class="{{ Route::is('superadmin.tenants.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Manage Tenants
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.logs.index') }}"
                                class="{{ Route::is('superadmin.logs.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Activity Logs
                            </a>
                        </li>
                        <li>
                            <a href="{{route('superadmin.roles.index')}}"
                                class="{{ Route::is('superadmin.roles.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Roles & Permissions
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{route('superadmin.commodities.index')}}"
                                class="{{ Route::is('superadmin.commodities.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Global Commodities
                            </a>
                        </li> --}}
                        {{-- <li>
                            <a href="{{ route('superadmin.seasons.index') }}"
                                class="{{ Route::is('superadmin.seasons.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Seasons
                            </a>
                        </li>  --}}
                        {{-- <li>
                            <a href="{{ route('superadmin.sync.logs') }}"
                                class="{{ Route::is('superadmin.sync.logs') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Sync Logs
                            </a>
                        </li>  --}}
                        {{--

                        <li>
                            <a href="{{route('superadmin.activity-logs')}}"
                                class="{{ Route::is('superadmin.activity-logs') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Audit Logs
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.settings')}}"
                                class="{{ Route::is('superadmin.settings') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                System Settings
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('superadmin.settings')}}"
                                class="{{ Route::is('superadmin.settings') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                System Settings
                            </a>
                        </li>
                    @endrole
                    @role('admin')
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                                class="{{ Route::is('admin.dashboard') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg font-medium">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.index') }}"
                                class="{{ Route::is('admin.users.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg font-medium">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                User Management
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.applications.index') }}"
                                class="{{ Route::is('admin.applications.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Applications
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.seasons.index') }}"
                                class="{{ Route::is('admin.seasons.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Seasons
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.commodities.index') }}"
                                class="{{ Route::is('admin.commodities.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Commodities
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.centers.index') }}"
                                class="{{ Route::is('admin.centers.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                                Manage Centers
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.roles.index') }}"
                                class="{{ Route::is('admin.roles.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Roles & Permissions
                            </a>
                        </li>
                        <li x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="sidebar-link w-full flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-3-3v6m-9 6h16a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="flex-grow text-left">Reports</span>
                                <!-- Dropdown arrow -->
                                <svg class="w-4 h-4 transform transition-transform duration-200"
                                    :class="{'rotate-90': open, 'rotate-0': !open}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <!-- Nested links -->
                            <ul x-show="open" x-collapse.duration.300ms class="mt-2 ml-6 space-y-2 text-sm" x-cloak>
                                <li>
                                    <a href="#"
                                        class="sidebar-link flex items-center px-4 py-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-700 dark:hover:bg-gray-600">
                                        Summary Report
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="sidebar-link flex items-center px-4 py-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-700 dark:hover:bg-gray-600">
                                        Detailed Analytics
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="sidebar-link flex items-center px-4 py-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-700 dark:hover:bg-gray-600">
                                        Financials
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('admin.verifications.index') }}"
                                class="{{ Route::is('admin.verifications.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                                Return Verification
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.receipts') }}"
                                class="{{ Route::is('admin.receipts') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                                Monetary Return Verification
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.applications') }}"
                                class="{{ Route::is('admin.reports.applications') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Reports
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.agents.index') }}"
                                class="{{ Route::is('admin.agents.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Agents
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.logs.index')}}"
                                class="{{ Route::is('admin.logs.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Activity Logs
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.settings')}}"
                                class="{{ Route::is('admin.settings') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </a>
                        </li>
                    @endrole
                    @role('agent')
                        <li>
                            <a href="{{ route('agent.dashboard') }}"
                                class="{{ Route::is('agent.dashboard') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('agent.search') }}"
                                class="{{ Route::is('agent.search') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('agent.verify.collection') }}"
                                class="{{ Route::is('agent.verify.collection') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Verify Collection
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('agent.verify.return') }}"
                                class="{{ Route::is('agent.verify.return') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                                Verify Return
                            </a>
                        </li>
                    @endrole

                </ul>
            </div>
        </nav>
        <main class="ml-0 md:ml-80 w-full min-h-screen px-4 sm:px-6 lg:px-8 py-12 transition-all">
            @yield('content')
        </main>
    </div>
    @include('layouts.footer')
    @stack('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
