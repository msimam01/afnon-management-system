<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Pages Demo - AFNON</title>
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
<!-- 403 Forbidden -->
            <div id="error-403" class="error-page hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">403 - Access Forbidden</h1>
                    <p class="text-gray-600 mb-6">You don't have permission to access this resource. Your account may lack the necessary privileges.</p>

                    <div class="bg-red-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-red-900 mb-3">Possible reasons:</h3>
                        <div class="space-y-2 text-sm text-red-800">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Insufficient user privileges
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Account restrictions
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Resource access limitations
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </button>
                        <button class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 1.26a2 2 0 001.73-.49L21 3.5M12 19l-7-7 7-7m5 12v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6c0-1.1.9-2 2-2h6a2 2 0 012 2z"></path>
                            </svg>
                            Contact Support
                        </button>
                    </div>

                    <div class="mt-6">
                        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to AFNON Home</a>
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
