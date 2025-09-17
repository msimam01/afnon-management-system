@extends('auth.includes.app')
@section('content')
    <div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div
                    class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 transition-colors duration-200">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <img src="{{ asset('logo.png') }}" alt="AFNON Logo" class="w-full h-full object-contain">
                    </div>
                </div>
                <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 dark:text-white">Sign in to your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    Access the AFNON Loan Management System
                </p>
            </div>

            <!-- Enhanced Form with Loading State -->
            <form class="mt-8 space-y-6" action="/central/login" method="POST" id="loginForm">
                @csrf
                <div class="space-y-4">
                    <!-- Email Field with Enhanced Validation -->
                    <div>
                        <label for="email-address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                        <div class="mt-1 relative">
                            <input id="email-address" name="email" type="email" autocomplete="email" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                value="{{ old('email') }}" placeholder="Enter your email">
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
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password Field with Visibility Toggle -->
                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 pr-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm transition-all duration-200 @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Enter your password">
                            <!-- Password Icon -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <!-- Password Visibility Toggle -->
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <svg id="eyeOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeClosed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>

                <!-- Remember Me and Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-gray-600 rounded transition-colors duration-200">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('central.forgot.password') }}"
                            class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors duration-200 focus:outline-none focus:underline">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <!-- Enhanced Submit Button with Loading State -->
                <div>
                    <button type="submit" id="submitBtn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">Sign in</span>
                        <span id="loadingSpinner" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Signing in...
                        </span>
                    </button>
                </div>

                <!-- Security Notice -->
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mb-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Your connection is secure and encrypted</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Don't have an account?
                        <span class="font-medium text-emerald-600 hover:text-emerald-500">
                            Please contact administrator
                        </span>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced JavaScript for Better UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            togglePassword.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');
            });

            // Form submission with loading state
            const form = document.getElementById('loginForm');
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

            // Enhanced keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                    e.preventDefault();
                    form.submit();
                }
            });

            // Auto-focus on first input if no errors
            @if(!$errors->any())
                emailField.focus();
            @endif
        });
    </script>
@endsection
