<div class="flex pt-16">
    <nav id="sidebar"
        class="bg-white dark:bg-gray-800 shadow-lg w-80 h-screen fixed top-16 left-0 z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="p-4 overflow-y-auto h-[calc(100vh-4rem)]">
            <ul class="space-y-2">
                    <li>
                        @can('view_superadmin_dashboard')
                                                    <a href="{{ route('superadmin.dashboard') }}"
                            class="{{ Route::is('superadmin.dashboard') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                        @endcan
                    </li>
                    <li>
                        @can('manage_central_users')
                                                    <a href="{{ route('superadmin.users.index') }}"
                            class="{{ Route::is('superadmin.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            User Management
                        </a>
                        @endcan
                    </li>
                    <li>
                        @can('manage_tenants')
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
                        @endcan
                    </li>
                    <li>
                        @can('view_central_activity_logs')
                                                    <a href="{{ route('superadmin.logs.index') }}"
                            class="{{ Route::is('superadmin.logs.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Activity Logs
                        </a>
                        @endcan
                    </li>
                    <li>
                        @can('manage_central_roles_permissions')
                                                    <a href="{{ route('superadmin.roles.index') }}"
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
                        @endcan
                    </li>
                    <li>
                        @can('manage_central_system_settings')
                                                    <a href="{{ route('superadmin.settings') }}"
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
                        @endcan
                    </li>
                <li>
                    @can('view_admin_dashboard')
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ Route::is('admin.dashboard') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg font-medium">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    @endcan
                </li>
                <li>
                    @can('manage_users')
                        <a href="{{ route('admin.users.index') }}"
                            class="{{ Route::is('admin.users.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg font-medium">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            User Management
                        </a>
                    @endcan
                </li>
                <li>
                    @can('manage_applications')
                        <a href="{{ route('admin.applications.index') }}"
                            class="{{ Route::is('admin.applications.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Applications
                        </a>
                    @endcan
                </li>
                <li>
                    @can('manage_seasons')
                        <a href="{{ route('admin.seasons.index') }}"
                            class="{{ Route::is('admin.seasons.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Seasons
                        </a>
                    @endcan
                </li>
                <li>
                    @can('manage_commodities')
                        <a href="{{ route('admin.commodities.index') }}"
                            class="{{ Route::is('admin.commodities.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Commodities
                        </a>
                    @endcan

                </li>
                <li>
                    @can('manage_centers')
                        <a href="{{ route('admin.centers.index') }}"
                            class="{{ Route::is('admin.centers.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Manage Centers
                        </a>
                    @endcan
                </li>
                <li>
                    @can('manage_roles_permissions')
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
                    @endcan
                </li>
                {{-- <li x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="sidebar-link w-full flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-emerald-700 dark:hover:bg-emerald-600 hover:text-white focus:outline-none">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-3-3v6m-9 6h16a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="flex-grow text-left">Reports</span>
                        <!-- Dropdown arrow -->
                        <svg class="w-4 h-4 transform transition-transform duration-200"
                            :class="{ 'rotate-90': open, 'rotate-0': !open }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
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
                </li> --}}
                <li>
                    @can('manage_verifications')
                        <a href="{{ route('admin.verifications.index') }}"
                            class="{{ Route::is('admin.verifications.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Return Verification
                        </a>
                    @endcan

                </li>
                <li>
                    @can('manage_monetary_returns')
                        <a href="{{ route('admin.monetary-returns') }}"
                            class="{{ Route::is('admin.monetary-returns') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Monetary Return Verification
                        </a>
                    @endcan
                </li>
                <li>
                    @can('manage_reports')
                        <a href="{{ route('admin.reports.applications') }}"
                            class="{{ Route::is('admin.reports.applications') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Reports
                        </a>
                    @endcan

                </li>
                <li>
                    @can('manage_agents')
                        <a href="{{ route('admin.agents.index') }}"
                            class="{{ Route::is('admin.agents.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Agents
                        </a>
                    @endcan
                </li>
                <li>
                    @can('view_activity_logs')
                        <a href="{{ route('admin.logs.index') }}"
                            class="{{ Route::is('admin.logs.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Activity Logs
                        </a>
                    @endcan

                </li>
                <li>
                    @can('manage_settings')
                        <a href="{{ route('admin.settings') }}"
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
                    @endcan

                </li>
                <li>
                    @can('view_agent_dashboard')
                        <a href="{{ route('agent.dashboard') }}"
                            class="{{ Route::is('agent.dashboard') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    @endcan
                </li>
                <li>
                    @can('verify_collection')
                        
                    <a href="{{ route('agent.verify.collection') }}"
                        class="{{ Route::is('agent.verify.collection') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Verify Collection
                    </a>
                    @endcan
                </li>
                <li>
                    @can('verify_return')
                                            <a href="{{ route('agent.verify.return') }}"
                        class="{{ Route::is('agent.verify.return') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        Verify Return
                    </a>                        
                    @endcan

                </li>
                <li>
                    @can('manage_monetary_return')
                        
                    <a href="{{ route('agent.monetary-return') }}"
                        class="{{ Route::is('agent.monetary-return') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        Monetary Return
                    </a>
                    @endcan
                </li>

            </ul>
        </div>
    </nav>
    <main class="ml-0 md:ml-80 w-full min-h-screen px-4 sm:px-6 lg:px-8 py-12 transition-all">
        @yield('content')
    </main>
</div>
