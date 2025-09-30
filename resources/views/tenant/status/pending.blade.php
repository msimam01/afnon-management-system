<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Setup in Progress - AFNEN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Animated Icon -->
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-blue-100 mb-6">
                <svg class="h-8 w-8 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Setting Up Your Account</h1>
            
            <!-- Message -->
            <p class="text-gray-600 mb-6">
                {{ $message ?? 'We are currently setting up your AFNEN account. This usually takes just a few minutes.' }}
            </p>

            <!-- Progress Steps -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-blue-900 mb-3">Setup Progress:</h3>
                <div class="space-y-2 text-sm text-blue-800">
                    <div class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Account created
                    </div>
                    <div class="flex items-center">
                        <div class="h-4 w-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin mr-2"></div>
                        Setting up database
                    </div>
                    <div class="flex items-center text-gray-500">
                        <div class="h-4 w-4 border-2 border-gray-300 rounded-full mr-2"></div>
                        Creating default users
                    </div>
                    <div class="flex items-center text-gray-500">
                        <div class="h-4 w-4 border-2 border-gray-300 rounded-full mr-2"></div>
                        Finalizing setup
                    </div>
                </div>
            </div>

            <!-- Auto-refresh notice -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600">
                    This page will automatically refresh every 30 seconds to check if your account is ready.
                </p>
            </div>

            <!-- Manual refresh button -->
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors mb-4">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Check Status
            </button>

            <!-- Back to Central -->
            <div>
                <a href="http://{{ config('app.central_domain', 'afnon.com') }}" 
                   class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    ← Back to AFNEN Home
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} AFNEN. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Auto-refresh script -->
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>
