<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Pages Demo - AFNEN</title>
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
<body class="bg-gray-50 font-sans min-h-screen">
    <!-- Error Pages Container -->
    <div class="flex items-center justify-center min-h-screen py-8">
        <div class="max-w-md w-full mx-4">
<!-- 401 Unauthorized -->
            <div id="error-401" class="error-page hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-yellow-100 mb-6">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 0h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">401 - Unauthorized</h1>
                    <p class="text-gray-600 mb-6">You need to be authenticated to access this resource. Please log in and try again.</p>

                    <div class="bg-yellow-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-yellow-900 mb-3">What you can do:</h3>
                        <div class="space-y-2 text-sm text-yellow-800">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Sign in to your account
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Create a new account
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Contact support if needed
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign In
                        </button>
                        <button class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Create Account
                        </button>
                    </div>

                    <div class="mt-6">
                        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to AFNEN Home</a>
                    </div>
                </div>
            </div>
</div>

    </div>

    <script>
        // Set current year
        document.getElementById('current-year').textContent = new Date().getFullYear();

        // Set error timestamp for 500 error
        if (document.getElementById('error-timestamp')) {
            document.getElementById('error-timestamp').textContent = new Date().toISOString().slice(0, 19) + 'Z';
        }

        // Show specific error page
        function showError(code) {
            // Hide all error pages
            document.querySelectorAll('.error-page').forEach(page => {
                page.classList.add('hidden');
            });

            // Show selected error page
            const errorPage = document.getElementById('error-' + code);
            if (errorPage) {
                errorPage.classList.remove('hidden');
            }

            // Start specific timers for certain errors
            if (code === '429') {
                startRateLimitTimer();
            } else if (code === '502') {
                startRetryTimer();
            }
        }

        // Rate limit timer for 429 error
        function startRateLimitTimer() {
            let timeLeft = 60;
            const timerElement = document.getElementById('reset-timer');
            const progressBar = document.getElementById('progress-bar');

            const timer = setInterval(() => {
                timeLeft--;
                if (timerElement) {
                    timerElement.textContent = timeLeft + ' seconds';
                }
                if (progressBar) {
                    progressBar.style.width = ((60 - timeLeft) / 60 * 100) + '%';
                }

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    if (timerElement) {
                        timerElement.textContent = 'Ready to retry';
                    }
                    if (progressBar) {
                        progressBar.style.width = '100%';
                        progressBar.classList.remove('bg-orange-600');
                        progressBar.classList.add('bg-green-600');
                    }
                }
            }, 1000);
        }

        // Retry timer for 502 error
        function startRetryTimer() {
            let timeLeft = 30;
            const timerElement = document.getElementById('retry-timer');

            const timer = setInterval(() => {
                timeLeft--;
                if (timerElement) {
                    timerElement.textContent = timeLeft;
                }

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    window.location.reload();
                }
            }, 1000);
        }

        // Auto-refresh for 503 error
        if (window.location.hash === '#503' || document.getElementById('error-503').style.display !== 'none') {
            setTimeout(() => {
                window.location.reload();
            }, 60000); // Refresh after 1 minute
        }

        // Show 404 by default
        showError('404');
    </script>
</body>
</html>
