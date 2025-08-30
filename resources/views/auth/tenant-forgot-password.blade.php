@extends('auth.includes.app')
@section('content')
    <div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div
                    class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900 transition-colors duration-200">
                    <svg class="h-8 w-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 dark:text-white">Forgot Password
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Enhanced Form -->
            <form class="mt-8 space-y-6" action="{{ route('tenant.password.email') }}" method="POST" id="forgotPasswordForm">
                @csrf
                <div class="space-y-4">
                    <!-- Email Field with Enhanced Validation -->
                    <div>
                        <label for="email-address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                        <div class="mt-1 relative">
                            <input id="email-address" name="email" type="email" autocomplete="email" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                value="{{ old('email') }}" placeholder="Enter your email address">
                            <!-- Email Icon -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <!-- Validation Check Icon -->
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center" id="email-validation">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Enhanced Submit Button with Loading State -->
                <div>
                    <button type="submit" id="submitBtn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Password Reset Link
                        </span>
                        <span id="loadingSpinner" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending reset link...
                        </span>
                    </button>
                </div>

                <!-- Back to Login Link -->
                <div class="text-center">
                    <a href="{{ route('tenant.login') }}"
                        class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-500 transition-colors duration-200 focus:outline-none focus:underline">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to login
                    </a>
                </div>

                <!-- Security Notice -->
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Reset links expire after 60 minutes for security</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced JavaScript for Better UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission with loading state
            const form = document.getElementById('forgotPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');
            });

            // Email validation feedback
            const emailField = document.getElementById('email-address');
            const emailValidation = document.getElementById('email-validation');

            emailField.addEventListener('input', function() {
                const email = this.value;
                const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

                if (email.length > 0) {
                    if (isValid) {
                        emailValidation.innerHTML = `
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        `;
                        this.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                        this.classList.add('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                    } else {
                        emailValidation.innerHTML = `
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        `;
                        this.classList.remove('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                        this.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    }
                } else {
                    emailValidation.innerHTML = '';
                    this.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500', 'border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                }
            });

            // Auto-focus on email field if no errors
            @if(!$errors->any())
                emailField.focus();
            @endif

            // Auto-hide success message after 10 seconds
            const successMessage = document.querySelector('.bg-green-50');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease-out';
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.remove();
                    }, 500);
                }, 10000);
            }
        });
    </script>

@endsection
