@extends('auth.includes.app')
@section('content')
    <div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div
                    class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 transition-colors duration-200">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 dark:text-white">Reset Password</h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">Enter your new password below.</p>
            </div>

            <!-- Enhanced Form -->
            <form class="mt-8 space-y-6" action="{{ route('tenant.password.update') }}" method="POST" id="resetPasswordForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-4">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                        <div class="mt-1 relative">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                value="{{ old('email', $email) }}" readonly>
                            <!-- Email Icon -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
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

                    <!-- New Password Field with Strength Indicator -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 pr-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm transition-all duration-200 @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Enter your new password">
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

                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex items-center space-x-2">
                                <div class="flex-1">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div id="strengthBar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                </div>
                                <span id="strengthText" class="text-xs font-medium text-gray-500 dark:text-gray-400"></span>
                            </div>
                            <div id="passwordRequirements" class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                <div id="req-length" class="flex items-center">
                                    <svg class="h-3 w-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                    At least 8 characters
                                </div>
                                <div id="req-uppercase" class="flex items-center">
                                    <svg class="h-3 w-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                    One uppercase letter
                                </div>
                                <div id="req-lowercase" class="flex items-center">
                                    <svg class="h-3 w-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                    One lowercase letter
                                </div>
                                <div id="req-number" class="flex items-center">
                                    <svg class="h-3 w-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                    One number
                                </div>
                            </div>
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                        <div class="mt-1 relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 pr-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm transition-all duration-200"
                                placeholder="Confirm your new password">
                            <!-- Password Icon -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <!-- Match Indicator -->
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center" id="confirmMatch">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Submit Button -->
                <div>
                    <button type="submit" id="submitBtn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"></path>
                            </svg>
                            Reset Password
                        </span>
                        <span id="loadingSpinner" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Resetting password...
                        </span>
                    </button>
                </div>

                <!-- Security Notice -->
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Your password will be securely encrypted</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
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

            // Password strength checker
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const requirements = {
                length: document.getElementById('req-length'),
                uppercase: document.getElementById('req-uppercase'),
                lowercase: document.getElementById('req-lowercase'),
                number: document.getElementById('req-number')
            };

            function checkPasswordStrength(password) {
                let score = 0;
                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password)
                };

                // Update requirement indicators
                Object.keys(checks).forEach(key => {
                    const element = requirements[key];
                    const svg = element.querySelector('svg');
                    if (checks[key]) {
                        score++;
                        element.classList.add('text-green-600');
                        element.classList.remove('text-gray-600');
                        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                        svg.classList.add('text-green-500');
                        svg.classList.remove('text-gray-400');
                    } else {
                        element.classList.remove('text-green-600');
                        element.classList.add('text-gray-600');
                        svg.innerHTML = '<circle cx="12" cy="12" r="10"></circle>';
                        svg.classList.remove('text-green-500');
                        svg.classList.add('text-gray-400');
                    }
                });

                // Update strength bar
                const percentage = (score / 4) * 100;
                strengthBar.style.width = percentage + '%';

                if (score === 0) {
                    strengthBar.className = 'h-2 rounded-full transition-all duration-300';
                    strengthText.textContent = '';
                } else if (score === 1) {
                    strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-red-500';
                    strengthText.textContent = 'Weak';
                    strengthText.className = 'text-xs font-medium text-red-500';
                } else if (score === 2) {
                    strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-yellow-500';
                    strengthText.textContent = 'Fair';
                    strengthText.className = 'text-xs font-medium text-yellow-500';
                } else if (score === 3) {
                    strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-blue-500';
                    strengthText.textContent = 'Good';
                    strengthText.className = 'text-xs font-medium text-blue-500';
                } else {
                    strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-green-500';
                    strengthText.textContent = 'Strong';
                    strengthText.className = 'text-xs font-medium text-green-500';
                }

                return score;
            }

            passwordField.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Password confirmation matching
            const confirmField = document.getElementById('password_confirmation');
            const confirmMatch = document.getElementById('confirmMatch');

            function checkPasswordMatch() {
                const password = passwordField.value;
                const confirm = confirmField.value;

                if (confirm.length > 0) {
                    if (password === confirm) {
                        confirmMatch.innerHTML = `
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        `;
                        confirmField.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                        confirmField.classList.add('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                    } else {
                        confirmMatch.innerHTML = `
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        `;
                        confirmField.classList.remove('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                        confirmField.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    }
                } else {
                    confirmMatch.innerHTML = '';
                    confirmField.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500', 'border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                }
            }

            confirmField.addEventListener('input', checkPasswordMatch);
            passwordField.addEventListener('input', checkPasswordMatch);

            // Form submission with loading state
            const form = document.getElementById('resetPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');
            });

            // Auto-focus on password field
            passwordField.focus();
        });
    </script>
@endsection
