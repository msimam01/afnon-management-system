<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deactivated - AFNON</title>
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
            <!-- Icon -->
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 mb-6">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                    </path>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Account Deactivated</h1>
            
            <!-- Message -->
            <p class="text-gray-600 mb-6">
                Your AFNON account has been deactivated and is no longer accessible.
            </p>

            @if($reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-red-800 mb-2">Reason for deactivation:</h3>
                <p class="text-sm text-red-700">{{ $reason }}</p>
            </div>
            @endif

            @if($deactivated_at)
            <p class="text-sm text-gray-500 mb-6">
                Deactivated on: {{ $deactivated_at->format('F j, Y \a\t g:i A') }}
            </p>
            @endif

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Need Help?</h3>
                <p class="text-sm text-gray-600 mb-3">
                    If you believe this is an error or would like to reactivate your account, please contact our support team.
                </p>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-600">support@afnon.com</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-gray-600">+234 (0) 123 456 7890</span>
                    </div>
                </div>
            </div>

            <!-- Back to Central -->
            <a href="http://{{ config('app.central_domain', 'afnon.com') }}" 
               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go to AFNON Home
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} AFNON. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
