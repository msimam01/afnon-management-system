<nav id="sidebar"
        class="bg-white dark:bg-gray-800 shadow-lg w-80 h-screen fixed top-16 left-0 z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="p-4 overflow-y-auto h-[calc(100vh-4rem)]">
            <ul class="space-y-2">
                {{-- SUPER ADMIN SIDEBAR (Central Domain) --}}
                @if(auth()->guard('web')->check() && auth()->guard('web')->user()->hasRole('super-admin'))
                    {{-- Dashboard - Most Important --}}
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

                    {{-- Tenant Management - High Priority --}}
                    <li>
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
                    </li>

                    {{-- User Management - High Priority --}}
                    <li>
                        <a href="{{ route('superadmin.users.index') }}"
                            class="{{ Route::is('superadmin.users.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            User Management
                        </a>
                    </li>

                    {{-- Roles & Permissions - Medium Priority --}}
                    <li>
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
                    </li>

                    {{-- Activity Logs - Medium Priority --}}
                    <!-- Global Admin Section -->
                    <li class="mt-4 mb-2 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Global Management
                    </li>

                    <!-- Global Seasons -->
                    <li>
                        <a href="{{ route('global.seasons.index') }}"
                            class="{{ Route::is('global.seasons.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Global Seasons
                        </a>
                    </li>

                    <!-- Global Commodities -->
                    <li>
                        <a href="{{ route('global.commodities.index') }}"
                            class="{{ Route::is('global.commodities.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Global Commodities
                        </a>
                    </li>

                    <!-- Global Categories -->
                    <li>
                        <a href="{{ route('global.commodity-categories.index') }}"
                            class="{{ Route::is('global.commodity-categories.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Commodity Categories
                        </a>
                    </li>

                    <!-- Season Allocations -->
                    @php
                        // Get the first season to link to, or null if no seasons exist
                        $firstSeason = \App\Models\GlobalSeason::first();
                        $allocationsRoute = $firstSeason ? route('global.allocations.index', $firstSeason->uuid) : '#';
                    @endphp
                    <li>
                        <a href="{{ $allocationsRoute }}"
                            class="{{ Route::is('global.allocations.*') || Route::is('global.seasons.allocations.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Season Allocations
                        </a>
                    </li>

                    <!-- Global Market Prices -->
                    {{-- <li>
                        <a href="{{ route('global.commodity-market-prices.index') }}"
                            class="{{ Route::is('global.commodity-market-prices.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Market Prices
                        </a>
                    </li> --}}

                    <li class="border-t border-gray-200 dark:border-gray-700 my-2"></li>

                    <!-- System Logs -->
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

                    {{-- Enquiries - Medium Priority --}}
                    <li>
                        <a href="{{ route('superadmin.enquiries.index') }}"
                            class="{{ Route::is('superadmin.enquiries.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            Enquiries
                        </a>
                    </li>

                    {{-- System Settings - Low Priority --}}
                    <li>
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
                    </li>
                @endif

                {{-- TENANT ADMIN SIDEBAR --}}
                @if(auth()->guard('tenant')->check() && auth()->guard('tenant')->user()->hasRole('admin'))
                    {{-- Dashboard - Most Important --}}
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

                    {{-- Applications - High Priority (Core Business Function) --}}
                    <li>
                        <a href="{{ route('admin.applications.index') }}"
                            class="{{ Route::is('admin.applications.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Applications
                        </a>
                    </li>

                    {{-- Verifications - High Priority (Core Business Function) --}}
                    <li>
                        <a href="{{ route('admin.verifications.index') }}"
                            class="{{ Route::is('admin.verifications.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Verifications
                        </a>
                    </li>

                    {{-- User Management - High Priority --}}
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="{{ Route::is('admin.users.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            User Management
                        </a>
                    </li>

                    {{-- Agents - High Priority --}}
                    <li>
                        <a href="{{ route('admin.agents.index') }}"
                            class="{{ Route::is('admin.agents.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                </path>
                            </svg>
                            Agents
                        </a>
                    </li>

                    {{-- Seasons - Medium Priority --}}
                    <li>
                        <a href="{{ route('admin.seasons.index') }}"
                            class="{{ Route::is('admin.seasons.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Seasons
                        </a>
                    </li>

                    {{-- Commodities - Medium Priority --}}
                    <li>
                        <a href="{{ route('admin.commodities.index') }}"
                            class="{{ Route::is('admin.commodities.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                </path>
                            </svg>
                            Commodities
                        </a>
                    </li>

                    {{-- Centers - Medium Priority --}}
                    <li>
                        <a href="{{ route('admin.centers.index') }}"
                            class="{{ Route::is('admin.centers.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Centers
                        </a>
                    </li>

                    {{-- Monetary Returns - Medium Priority --}}
                    <li>
                        <a href="{{ route('admin.monetary-returns') }}"
                            class="{{ Route::is('admin.monetary-returns') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                            Payments
                        </a>
                    </li>

                    {{-- Reports - Medium Priority --}}
                    <li>
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
                                <a href="{{ route('admin.reports.applications') }}"
                                    class="{{ Route::is('admin.reports.applications') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                    Applications
                                </a>
                                <a href="{{ route('admin.reports.collections') }}"
                                    class="{{ Route::is('admin.reports.collections') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                    Collections
                                </a>
                                <a href="{{ route('admin.reports.returns') }}"
                                    class="{{ Route::is('admin.reports.returns') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                    Returns
                                </a>
                                <a href="{{ route('admin.reports.monetary-returns') }}"
                                    class="{{ Route::is('admin.reports.monetary-returns') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                    Payments
                                </a>
                                <a href="{{ route('admin.reports.seasons.index') }}"
                                    class="{{ Route::is('admin.reports.seasons.*') ? 'bg-emerald-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} block px-4 py-2 text-sm rounded-lg transition-colors">
                                    Season Analytics
                                </a>
                            </div>
                        </div>
                    </li>

                    {{-- Roles & Permissions - Low Priority --}}
                    {{-- <li>
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
                    </li> --}}

                    {{-- Activity Logs - Low Priority --}}
                    <li>
                        <a href="{{ route('admin.logs.index') }}"
                            class="{{ Route::is('admin.logs.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Activity Logs
                        </a>
                    </li>

                    {{-- Enquiries - Medium Priority --}}
                    <li>
                        <a href="{{ route('admin.enquiries.index') }}"
                            class="{{ Route::is('admin.enquiries.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            Enquiries
                        </a>
                    </li>

                    {{-- Settings - Low Priority --}}
                    <li>
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
                    </li>
                @endif

                {{-- TENANT AGENT SIDEBAR --}}
                @if(auth()->guard('tenant')->check() && auth()->guard('tenant')->user()->hasRole('agent'))
                    {{-- Dashboard - Most Important --}}
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

                    {{-- Verify Collection - High Priority (Primary Function) --}}
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

                    {{-- Verify Return - High Priority (Primary Function) --}}
                    <li>
                        <a href="{{ route('agent.verify.return') }}"
                            class="{{ Route::is('agent.verify.return') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Verify Return
                        </a>
                    </li>

                    {{-- Monetary Return - Medium Priority --}}
                    <li>
                        <a href="{{ route('agent.monetary-return') }}"
                            class="{{ Route::is('agent.monetary-return') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                            Payments
                        </a>
                    </li>
                @endif

                {{-- TENANT FARMER SIDEBAR --}}
                @if(auth()->guard('tenant')->check() && auth()->guard('tenant')->user()->hasRole('farmer'))
                    {{-- Dashboard - Most Important --}}
                    <li>
                        <a href="{{ route('farmer.dashboard') }}"
                            class="{{ Route::is('farmer.dashboard') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    {{-- Submit Application - High Priority (Primary Function) --}}
                    <li>
                        <a href="{{ route('applications.create') }}"
                            class="{{ Route::is('applications.create') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                </path>
                            </svg>
                            Submit Application
                        </a>
                    </li>

                    {{-- View Applications - Medium Priority --}}
                    <li>
                        <a href="{{ route('admin.applications.index') }}"
                            class="{{ Route::is('admin.applications.*') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            My Applications
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
