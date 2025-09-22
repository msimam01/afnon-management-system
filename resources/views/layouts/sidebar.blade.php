<nav id="sidebar"
        class="bg-white dark:bg-gray-800 shadow-lg w-80 h-screen fixed top-16 left-0 z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="p-4 overflow-y-auto h-[calc(100vh-4rem)]">
            <ul class="space-y-2">
                {{-- SUPER ADMIN SIDEBAR (Central Domain) --}}
                @if(auth()->guard('web')->check() && auth()->guard('web')->user()->hasRole('super-admin'))
                    {{-- Dashboard - Most Important --}}
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

                    {{-- Tenant Management - High Priority --}}
                    <li>
                        @can('manage_tenants')
                            <a href="{{ route('superadmin.tenants.index') }}"
                                class="{{ Route::is('superadmin.tenants.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
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

                    {{-- User Management - High Priority --}}
                    <li>
                        @can('manage_central_users')
                            <a href="{{ route('superadmin.users.index') }}"
                                class="{{ Route::is('superadmin.users.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                User Management
                            </a>
                        @endcan
                    </li>

                    {{-- Roles & Permissions - Medium Priority --}}
                    <li>
                        @can('manage_central_roles_permissions')
                            <a href="{{ route('superadmin.roles.index') }}"
                                class="{{ Route::is('superadmin.roles.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
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

                    {{-- Activity Logs - Medium Priority --}}
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

                    {{-- Enquiries - Medium Priority --}}
                    <li>
                        @can('manage_central_enquiries')
                            <a href="{{ route('superadmin.enquiries.index') }}"
                                class="{{ Route::is('superadmin.enquiries.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                Enquiries
                            </a>
                        @endcan
                    </li>

                    {{-- System Settings - Low Priority --}}
                    <li>
                        @can('manage_central_system_settings')
                            <a href="{{ route('superadmin.settings.index') }}"
                                class="{{ Route::is('superadmin.settings.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
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
                @endif

                {{-- TENANT ADMIN SIDEBAR --}}
                @if(auth()->guard('tenant')->check() && auth()->guard('tenant')->user()->hasRole('admin'))
                    {{-- Dashboard - Most Important --}}
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

                    {{-- Applications - High Priority (Core Business Function) --}}
                    <li>
                        @can('manage_applications')
                            <a href="{{ route('admin.applications.index') }}"
                                class="{{ Route::is('admin.applications.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Applications
                            </a>
                        @endcan
                    </li>

                    {{-- Verifications - High Priority (Core Business Function) --}}
                    <li>
                        @can('manage_verifications')
                            <a href="{{ route('admin.verifications.index') }}"
                                class="{{ Route::is('admin.verifications.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Verifications
                            </a>
                        @endcan
                    </li>

                    {{-- User Management - High Priority --}}
                    <li>
                        @can('manage_users')
                            <a href="{{ route('admin.users.index') }}"
                                class="{{ Route::is('admin.users.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                User Management
                            </a>
                        @endcan
                    </li>

                    {{-- Agents - High Priority --}}
                    <li>
                        @can('manage_agents')
                            <a href="{{ route('admin.agents.index') }}"
                                class="{{ Route::is('admin.agents.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Agents
                            </a>
                        @endcan
                    </li>

                    {{-- Seasons - Medium Priority --}}
                    <li>
                        @can('manage_seasons')
                            <a href="{{ route('admin.seasons.index') }}"
                                class="{{ Route::is('admin.seasons.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Seasons
                            </a>
                        @endcan
                    </li>

                    {{-- Commodities - Medium Priority --}}
                    <li>
                        @can('manage_commodities')
                            <a href="{{ route('admin.commodities.index') }}"
                                class="{{ Route::is('admin.commodities.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                    </path>
                                </svg>
                                Commodities
                            </a>
                        @endcan
                    </li>

                    {{-- Centers - Medium Priority --}}
                    <li>
                        @can('manage_centers')
                            <a href="{{ route('admin.centers.index') }}"
                                class="{{ Route::is('admin.centers.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Centers
                            </a>
                        @endcan
                    </li>

                    {{-- Monetary Returns - Medium Priority --}}
                    <li>
                        @can('manage_monetary_returns')
                            <a href="{{ route('admin.monetary-returns') }}"
                                class="{{ Route::is('admin.monetary-returns') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                                Monetary Returns
                            </a>
                        @endcan
                    </li>

                    {{-- Reports - Medium Priority --}}
                    <li>
                        @can('manage_reports')
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                    class="{{ Route::is('admin.reports.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center justify-between w-full px-4 py-2 rounded-lg hover:bg-emerald-600 hover:text-white transition-colors">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Reports
                                    </div>
                                    <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                                    @can('view_application_reports')
                                        <a href="{{ route('admin.reports.applications') }}"
                                            class="{{ Route::is('admin.reports.applications') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                            Applications
                                        </a>
                                    @endcan
                                    @can('view_verification_reports')
                                        <a href="{{ route('admin.reports.collections') }}"
                                            class="{{ Route::is('admin.reports.collections') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                            Collections
                                        </a>
                                        <a href="{{ route('admin.reports.returns') }}"
                                            class="{{ Route::is('admin.reports.returns') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                            Returns
                                        </a>
                                    @endcan
                                    @can('view_application_reports')
                                        <a href="{{ route('admin.reports.monetary-returns') }}"
                                            class="{{ Route::is('admin.reports.monetary-returns') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                            Monetary Returns
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endcan
                    </li>

                    {{-- Roles & Permissions - Low Priority --}}
                    <li>
                        @can('manage_roles_permissions')
                            <a href="{{ route('admin.roles.index') }}"
                                class="{{ Route::is('admin.roles.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
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

                    {{-- Activity Logs - Low Priority --}}
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

                    {{-- Enquiries - Medium Priority --}}
                    <li>
                        @can('manage_enquiries')
                            <a href="{{ route('admin.enquiries.index') }}"
                                class="{{ Route::is('admin.enquiries.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                Enquiries
                            </a>
                        @endcan
                    </li>

                    {{-- Settings - Low Priority --}}
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
                @endif

                {{-- TENANT AGENT SIDEBAR --}}
                @if(auth()->guard('tenant')->check() && auth()->guard('tenant')->user()->hasRole('agent'))
                    {{-- Dashboard - Most Important --}}
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

                    {{-- Verify Collection - High Priority (Primary Function) --}}
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

                    {{-- Verify Return - High Priority (Primary Function) --}}
                    <li>
                        @can('verify_return')
                            <a href="{{ route('agent.verify.return') }}"
                                class="{{ Route::is('agent.verify.return') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Verify Return
                            </a>
                        @endcan
                    </li>

                    {{-- Monetary Return - Medium Priority --}}
                    <li>
                        @can('manage_monetary_return')
                            <a href="{{ route('agent.monetary-return') }}"
                                class="{{ Route::is('agent.monetary-return') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                                Monetary Return
                            </a>
                        @endcan
                    </li>
                @endif

                {{-- TENANT FARMER SIDEBAR --}}
                @if(auth()->guard('tenant')->check() && auth()->guard('tenant')->user()->hasRole('farmer'))
                    {{-- Dashboard - Most Important --}}
                    <li>
                        @can('view_farmer_dashboard')
                            <a href="{{ route('farmer.dashboard') }}"
                                class="{{ Route::is('farmer.dashboard') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Dashboard
                            </a>
                        @endcan
                    </li>

                    {{-- Submit Application - High Priority (Primary Function) --}}
                    <li>
                        @can('create_application')
                            <a href="{{ route('applications.create') }}"
                                class="{{ Route::is('applications.create') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                    </path>
                                </svg>
                                Submit Application
                            </a>
                        @endcan
                    </li>

                    {{-- View Applications - Medium Priority --}}
                    <li>
                        @can('read_application')
                            <a href="{{ route('admin.applications.index') }}"
                                class="{{ Route::is('admin.applications.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                My Applications
                            </a>
                        @endcan
                    </li>
                @endif
            </ul>
        </div>
    </nav>
