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
<!-- 500 Internal Server Error -->
            <div id="error-500" class="error-page hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">500 - Internal Server Error</h1>
                    <p class="text-gray-600 mb-6">Something went wrong on our end. Our team has been notified and is working to fix the issue.</p>

                    <div class="bg-red-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-red-900 mb-3">Error details:</h3>
                        <div class="space-y-2 text-sm text-red-800">
                            <div class="flex items-center justify-between">
                                <span>Error ID:</span>
                                <span class="font-mono text-xs">#ERR-500-AF123456</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Timestamp:</span>
                                <span class="font-mono text-xs" id="error-timestamp"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Status:</span>
                                <span class="font-medium">Team notified</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Try Again
                        </button>
                        <button class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 1.26a2 2 0 001.73-.49L21 3.5M12 19l-7-7 7-7m5 12v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6c0-1.1.9-2 2-2h6a2 2 0 012 2z"></path>
                            </svg>
                            Report Issue
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
