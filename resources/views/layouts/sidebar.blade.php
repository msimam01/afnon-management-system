<nav id="sidebar" class="bg-white dark:bg-gray-800 shadow-lg w-80 h-screen fixed top-16 left-0 z-30 hidden md:block">
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
                {{-- <li>
                    <a href="{{ route('superadmin.users') }}"
                        class="{{ Route::is('superadmin.users') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        User Management
                    </a>
                </li> --}}
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
                    <a href="{{route('superadmin.commodities.index')}}"
                        class="{{ Route::is('superadmin.commodities.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Global Commodities
                    </a>
                </li>
                <li>
                    <a href="{{ route('superadmin.seasons.index') }}"
                        class="{{ Route::is('superadmin.seasons.index') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Seasons
                    </a>
                </li> 
                {{--
                <li>
                    <a href="{{route('superadmin.roles')}}"
                        class="{{ Route::is('superadmin.roles') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
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
                    <a href="{{ route('admin.applications') }}"
                        class="{{ Route::is('admin.applications') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
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
                    <a href="{{ route('admin.farmers') }}"
                        class="{{ Route::is('admin.farmers') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        Farmers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.agents') }}"
                        class="{{ Route::is('admin.agents') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Agents
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.returns') }}"
                        class="{{ Route::is('admin.returns') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
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
                    <a href="{{ route('admin.centers') }}"
                        class="{{ Route::is('admin.centers') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        Manage Centers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports') }}"
                        class="{{ Route::is('admin.reports') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Reports
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
                <li>
                    <a href="{{ route('agent.search') }}"
                        class="{{ Route::is('agent.search') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </a>
                </li>
                <li>
                    <a href="{{ route('agent.verify-collection') }}"
                        class="{{ Route::is('agent.verify-collection') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Verify Collection
                    </a>
                </li>
                <li>
                    <a href="{{ route('agent.verify-return') }}"
                        class="{{ Route::is('agent.verify-return') ? 'bg-emerald-700 text-emerald-50' : 'text-gray-700 dark:text-gray-300' }} sidebar-link flex items-center px-4 py-2 rounded-lg">
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
