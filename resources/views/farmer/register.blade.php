<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registration - NECAS Grant & Loan Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans transition-colors duration-200">
    <!-- Fixed Navbar -->
    <nav
        class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <button onclick="history.back()"
                        class="mr-4 p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="h-8 w-8 bg-emerald-600 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <h1 class="ml-3 text-xl font-bold text-gray-900 dark:text-white">North East Commodity Distribution Associations (NECAS)</h1>
                </div>
                <div class="flex items-center space-x-4">
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
                    <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-500 font-medium">Back to Login</a>
                </div>
            </div>
        </div>
    </nav>

    
    <main class="max-w-4xl mx-auto py-24 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Farmer Registration</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Please fill in all required information to register
                    your account.</p>
            </div>

            <form class="space-y-6" id="registrationForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label for="fullName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full
                            Name *</label>
                        <input type="text" id="fullName" name="fullName" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone
                            Number *</label>
                        <input type="tel" id="phone" name="phone" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- BVN -->
                    <div>
                        <label for="bvn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">BVN
                            *</label>
                        <input type="text" id="bvn" name="bvn" required maxlength="11"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- NIN -->
                    <div>
                        <label for="nin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIN
                            *</label>
                        <input type="text" id="nin" name="nin" required maxlength="11"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State
                            *</label>
                        <select id="state" name="state" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select State</option>
                            <option value="Lagos">Lagos</option>
                            <option value="Kano">Kano</option>
                            <option value="Rivers">Rivers</option>
                            <option value="Kaduna">Kaduna</option>
                            <option value="Ogun">Ogun</option>
                            <option value="Oyo">Oyo</option>
                        </select>
                    </div>

                    <!-- LGA -->
                    <div>
                        <label for="lga" class="block text-sm font-medium text-gray-700 dark:text-gray-300">LGA
                            *</label>
                        <select id="lga" name="lga" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select LGA</option>
                            <option value="Ikeja">Ikeja</option>
                            <option value="Victoria Island">Victoria Island</option>
                            <option value="Surulere">Surulere</option>
                        </select>
                    </div>

                    <!-- Cluster -->
                    <div>
                        <label for="cluster"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cluster *</label>
                        <input name="cluster" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="eg, Bama"/>
                    </div>

                    <!-- Farm Size -->
                    <div>
                        <label for="farmSize" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Farm
                            Size (hectares) *</label>
                        <input type="number" id="farmSize" name="farmSize" required min="0" step="0.1"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>
                <div>
                        <label for="farm-location"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Farm Location</label>
                        <input id="farm-location" name="address" rows="3" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                    </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address
                        *</label>
                    <textarea id="address" name="address" rows="3" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white py-2 px-6 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 font-medium transition-colors">
                        Register Account
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('layouts.footer')

    <script>
        // Dark mode functionality
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            html.classList.add('dark');
        }

        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        // Form submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Registration successful! You can now login to your account.');
            window.location.href = 'index.html';
        });

        function setupCameraButton(buttonId, inputId) {
            const button = document.getElementById(buttonId);
            const input = document.getElementById(inputId);
            if (button && input) {
                button.addEventListener("click", () => {
                    input.setAttribute("capture", "environment");
                    input.click();
                });
            }
        }

        setupCameraButton("passportCameraBtn", "passportPhoto");
        setupCameraButton("signatureCameraBtn", "signature");
    </script>
</body>

</html>
