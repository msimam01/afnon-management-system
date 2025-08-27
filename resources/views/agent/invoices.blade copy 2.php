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
    <!-- Navigation for Demo -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900 mb-4">HTTP Error Pages - AFNON Design</h1>
            <div class="flex flex-wrap gap-2">
                <button onclick="showError('400')"
                    class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm hover:bg-red-200">400</button>
                <button onclick="showError('401')"
                    class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm hover:bg-red-200">401</button>
                <button onclick="showError('403')"
                    class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm hover:bg-red-200">403</button>
                <button onclick="showError('404')"
                    class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm hover:bg-red-200">404</button>
                <button onclick="showError('409')"
                    class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md text-sm hover:bg-yellow-200">409</button>
                <button onclick="showError('429')"
                    class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md text-sm hover:bg-yellow-200">429</button>
                <button onclick="showError('500')"
                    class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm hover:bg-red-200">500</button>
                <button onclick="showError('502')"
                    class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm hover:bg-red-200">502</button>
                <button onclick="showError('503')"
                    class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md text-sm hover:bg-yellow-200">503</button>
            </div>
        </div>
    </div>

    <!-- Error Pages Container -->
    <div class="flex items-center justify-center min-h-screen py-8">
        <div class="max-w-md w-full mx-4">

            <!-- 400 Bad Request -->
            <div id="error-400" class="error-page hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">400 - Bad Request</h1>
                    <p class="text-gray-600 mb-6">The request you sent was invalid or malformed. Please check your
                        request and try again.</p>

                    <div class="bg-red-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-red-900 mb-3">Common Causes:</h3>
                        <div class="space-y-2 text-sm text-red-800">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Version mismatch
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button onclick="window.location.reload()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Try Again
                        </button>
                        <button onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </button>
                    </div>

                    <div class="mt-6">
                        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to
                            AFNON Home</a>
                    </div>
                </div>
            </div>

            <!-- 429 Too Many Requests -->
            <div id="error-429" class="error-page hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-orange-100 mb-6">
                        <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">429 - Too Many Requests</h1>
                    <p class="text-gray-600 mb-6">You've made too many requests in a short period. Please wait a moment
                        before trying again.</p>

                    <div class="bg-orange-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-orange-900 mb-3">Rate Limit Information:</h3>
                        <div class="space-y-2 text-sm text-orange-800">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Rate limit exceeded
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Please wait before retrying
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Limit resets automatically
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-600">
                            <span id="countdown">60</span> seconds until you can try again.
                        </p>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button id="retry-btn" disabled
                            class="inline-flex items-center px-4 py-2 bg-gray-400 text-white text-sm font-medium rounded-lg cursor-not-allowed">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Retry
                        </button>
                        <button onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </button>
                    </div>

                    <div class="mt-6">
                        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back
                            to AFNON Home</a>
                    </div>
                </div>
            </div>

            <!-- 500 Internal Server Error -->
            <div id="error-500" class="error-page hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">500 - Internal Server Error</h1>
                    <p class="text-gray-600 mb-6">Something went wrong on our end. Our team has been notified and is
                        working to fix the issue.</p>

                    <div class="bg-red-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-red-900 mb-3">What happened:</h3>
                        <div class="space-y-2 text-sm text-red-800">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Server encountered an error
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Technical team notified
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Working on resolution
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button onclick="window.location.reload()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Try Again
                        </button>
                        <button onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </button>
                    </div>

                    <div class="mt-6">
                        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back
                            to AFNON Home</a>
                    </div>
                </div>
            </div>

            <!-- 502 Bad Gateway -->
            <div id="error-502" class="error-page hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">502 - Bad Gateway</h1>
                    <p class="text-gray-600 mb-6">There's a communication problem between our servers. This is usually
                        temporary.</p>

                    <div class="bg-red-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-red-900 mb-3">Technical details:</h3>
                        <div class="space-y-2 text-sm text-red-800">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Gateway communication error
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Upstream server unavailable
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Usually resolves quickly
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button onclick="window.location.reload()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Try Again
                        </button>
                        <button onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </button>
                    </div>

                    <div class="mt-6">
                        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back
                            to AFNON Home</a>
                    </div>
                </div>
            </div>

            <!-- 503 Service Unavailable -->
            <div id="error-503" class="error-page hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-yellow-100 mb-6">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">503 - Service Unavailable</h1>
                    <p class="text-gray-600 mb-6">Our service is temporarily unavailable due to maintenance or high
                        traffic. Please try again in a few minutes.</p>

                    <div class="bg-yellow-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-yellow-900 mb-3">Service Status:</h3>
                        <div class="space-y-2 text-sm text-yellow-800">
                            <div class="flex items-center">
                                <div
                                    class="h-4 w-4 border-2 border-yellow-500 border-t-transparent rounded-full animate-spin mr-2">
                                </div>
                                Performing maintenance
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Service will resume shortly
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                No data has been lost
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-600">
                            This page will automatically refresh every 60 seconds to check if the service is back
                            online.
                        </p>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button onclick="window.location.reload()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Check Status
                        </button>
                        <button onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </button>
                    </div>

                    <div class="mt-6">
                        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back
                            to AFNON Home</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">
                © 2025 AFNON. All rights reserved.
            </p>
        </div>
    </div>

    <script>
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
        }

        // Countdown timer for 429 error
        let countdown = 60;
        const countdownElement = document.getElementById('countdown');
        const retryButton = document.getElementById('retry-btn');

        function updateCountdown() {
            if (countdownElement && countdown > 0) {
                countdownElement.textContent = countdown;
                countdown--;
                setTimeout(updateCountdown, 1000);
            } else if (retryButton && countdown <= 0) {
                retryButton.disabled = false;
                retryButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                retryButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                retryButton.onclick = () => window.location.reload();
            }
        }

        // Start countdown if 429 page is visible
        if (document.getElementById('error-429') && !document.getElementById('error-429').classList.contains('hidden')) {
            updateCountdown();
        }

        // Auto-refresh for 503 error
        function autoRefresh503() {
            if (document.getElementById('error-503') && !document.getElementById('error-503').classList.contains(
                'hidden')) {
                setTimeout(() => {
                    window.location.reload();
                }, 60000); // 60 seconds
            }
        }
        autoRefresh503();

        // Show 404 by default
        document.addEventListener('DOMContentLoaded', function() {
            showError('404');
        });
    </script>
</body>

</html>
<svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
    <path fill-rule="evenodd"
        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
        clip-rule="evenodd"></path>
</svg>
Invalid form data
</div>
<div class="flex items-center">
    <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
            clip-rule="evenodd"></path>
    </svg>
    Missing required fields
</div>
<div class="flex items-center">
    <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
            clip-rule="evenodd"></path>
    </svg>
    Malformed URL parameters
</div>
</div>
</div>

<div class="flex gap-3 justify-center">
    <button onclick="window.history.back()"
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Go Back
    </button>
    <button onclick="window.location.reload()"
        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
            </path>
        </svg>
        Retry
    </button>
</div>

<div class="mt-6">
    <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to AFNON Home</a>
</div>
</div>
</div>

<!-- 401 Unauthorized -->
<div id="error-401" class="error-page hidden">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-yellow-100 mb-6">
            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 0h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                </path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">401 - Unauthorized</h1>
        <p class="text-gray-600 mb-6">You need to be authenticated to access this resource. Please log in and try
            again.</p>

        <div class="bg-yellow-50 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-yellow-900 mb-3">What you can do:</h3>
            <div class="space-y-2 text-sm text-yellow-800">
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Sign in to your account
                </div>
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Create a new account
                </div>
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Contact support if needed
                </div>
            </div>
        </div>

        <div class="flex gap-3 justify-center">
            <button
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
                Sign In
            </button>
            <button
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Create Account
            </button>
        </div>

        <div class="mt-6">
            <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to AFNON
                Home</a>
        </div>
    </div>
</div>

<!-- 403 Forbidden -->
<div id="error-403" class="error-page hidden">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 mb-6">
            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                </path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">403 - Access Forbidden</h1>
        <p class="text-gray-600 mb-6">You don't have permission to access this resource. Your account may lack the
            necessary privileges.</p>

        <div class="bg-red-50 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-red-900 mb-3">Possible reasons:</h3>
            <div class="space-y-2 text-sm text-red-800">
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Insufficient user privileges
                </div>
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Account restrictions
                </div>
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Resource access limitations
                </div>
            </div>
        </div>

        <div class="flex gap-3 justify-center">
            <button onclick="window.history.back()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go Back
            </button>
            <button
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 1.26a2 2 0 001.73-.49L21 3.5M12 19l-7-7 7-7m5 12v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6c0-1.1.9-2 2-2h6a2 2 0 012 2z">
                    </path>
                </svg>
                Contact Support
            </button>
        </div>

        <div class="mt-6">
            <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to AFNON
                Home</a>
        </div>
    </div>
</div>

<!-- 404 Not Found -->
<div id="error-404" class="error-page">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-blue-100 mb-6">
            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">404 - Page Not Found</h1>
        <p class="text-gray-600 mb-6">The page you're looking for doesn't exist or has been moved to a different
            location.</p>

        <div class="bg-blue-50 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-blue-900 mb-3">What you can do:</h3>
            <div class="space-y-2 text-sm text-blue-800">
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Check the URL for typos
                </div>
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Go back to the previous page
                </div>
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Visit our homepage
                </div>
            </div>
        </div>

        <div class="flex gap-3 justify-center">
            <button onclick="window.history.back()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go Back
            </button>
            <a href="/"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Go Home
            </a>
        </div>

        <div class="mt-6">
            <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Back to AFNON
                Home</a>
        </div>
    </div>
</div>

<!-- 409 Conflict -->
<div id="error-409" class="error-page hidden">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-yellow-100 mb-6">
            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">409 - Conflict</h1>
        <p class="text-gray-600 mb-6">There was a conflict with your request. This usually happens when trying to
            create something that already exists.</p>

        <div class="bg-yellow-50 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-yellow-900 mb-3">Common causes:</h3>
            <div class="space-y-2 text-sm text-yellow-800">
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Duplicate entry detected
                </div>
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Resource already exists
                </div>
                <div class="flex items-center">
                    </body>

                    </html>
