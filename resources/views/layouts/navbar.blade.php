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

                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <img src="{{ asset('logo.png') }}" alt="AFNON Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-emerald-800">{{ $isCentral ? $setting->name ?? 'AFNON' : ($tenant->short_name ?? strtoupper($tenant->id)) . ' STATE CHAPTER' }}</h1>
                        <p class="text-xs text-emerald-600 font-medium">Association Of Farmers In The Northeast Of Nigeria</p>
                    </div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div id="profileMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                        <a href="{{ $isCentral ? route('superadmin.profile.edit') : route('profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profile
                            Settings</a>
                        <form action="{{ $isCentral ? route('central.logout') : route('tenant.logout') }}"
                            method="post">
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
