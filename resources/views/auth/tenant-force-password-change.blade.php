@extends('auth.includes.app')
@section('content')
    <div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div
                    class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 transition-colors duration-200">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 dark:text-white">Change Your Password</h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    For security reasons, please change your password to continue
                </p>
            </div>

            <!-- Enhanced Form with Loading State -->
            <form class="mt-8 space-y-6" action="{{ route('tenant.password.force.change.update') }}" method="POST" id="tenantPasswordChangeForm">
                @csrf
                <div class="space-y-4">
                    <!-- Current Password Field -->
                    <div>
                        <label for="current-password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                        <div class="mt-1 relative">
                            <input id="current-password" name="current_password" type="password" autocomplete="current-password" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 pr-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-200 @error('current_password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Enter your current password">
                            <!-- Password Icon -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <!-- Password Visibility Toggle -->
                            <button type="button" id="toggleCurrentPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <svg id="currentEyeOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="currentEyeClosed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                    </div>

                    <!-- New Password Field -->
                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 pr-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-200 @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Enter your new password">
                            <!-- Password Icon -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <!-- Password Visibility Toggle -->
                            <button type="button" id="toggleNewPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <svg id="newEyeOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="newEyeClosed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <!-- Password Strength Indicator -->
                        <div id="passwordStrength" class="mt-2 hidden">
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div id="strengthBar" class="h-2 rounded-full transition-all duration-300"></div>
                                </div>
                                <span id="strengthText" class="text-xs text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm New Password Field -->
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                        <div class="mt-1 relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                class="appearance-none relative block w-full px-3 py-3 pl-10 pr-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-200 @error('password_confirmation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Confirm your new password">
                            <!-- Password Icon -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <!-- Password Visibility Toggle -->
                            <button type="button" id="toggleConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <svg id="confirmEyeOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="confirmEyeClosed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                        <!-- Password Match Indicator -->
                        <div id="passwordMatch" class="mt-2 hidden">
                            <div class="flex items-center space-x-2">
                                <svg id="matchIcon" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span id="matchText" class="text-xs"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Submit Button with Loading State -->
                <div>
                    <button type="submit" id="submitBtn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">Change Password</span>
                        <span id="loadingSpinner" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Changing Password...
                        </span>
                    </button>
                </div>

                <!-- Security Notice -->
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mb-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Your password will be encrypted and secure</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced JavaScript for Better UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggles
            const passwordFields = [
                { field: 'current-password', toggle: 'toggleCurrentPassword', eyeOpen: 'currentEyeOpen', eyeClosed: 'currentEyeClosed' },
                { field: 'password', toggle: 'toggleNewPassword', eyeOpen: 'newEyeOpen', eyeClosed: 'newEyeClosed' },
                { field: 'password_confirmation', toggle: 'toggleConfirmPassword', eyeOpen: 'confirmEyeOpen', eyeClosed: 'confirmEyeClosed' }
            ];

            passwordFields.forEach(({ field, toggle, eyeOpen, eyeClosed }) => {
                const toggleBtn = document.getElementById(toggle);
                const passwordField = document.getElementById(field);
                const eyeOpenIcon = document.getElementById(eyeOpen);
                const eyeClosedIcon = document.getElementById(eyeClosed);

                toggleBtn.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    eyeOpenIcon.classList.toggle('hidden');
                    eyeClosedIcon.classList.toggle('hidden');
                });
            });

            // Password strength checker
            const passwordField = document.getElementById('password');
            const passwordStrength = document.getElementById('passwordStrength');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            passwordField.addEventListener('input', function() {
                const password = this.value;
                const strength = checkPasswordStrength(password);

                if (password.length > 0) {
                    passwordStrength.classList.remove('hidden');
                    strengthBar.className = `h-2 rounded-full transition-all duration-300 ${strength.color}`;
                    strengthBar.style.width = `${strength.percentage}%`;
                    strengthText.textContent = strength.text;
                    strengthText.className = `text-xs ${strength.textColor}`;
                } else {
                    passwordStrength.classList.add('hidden');
                }
            });

            function checkPasswordStrength(password) {
                let strength = 0;
                let feedback = [];

                if (password.length >= 8) strength += 25;
                else feedback.push('At least 8 characters');

                if (/[a-z]/.test(password)) strength += 25;
                else feedback.push('Lowercase letter');

                if (/[A-Z]/.test(password)) strength += 25;
                else feedback.push('Uppercase letter');

                if (/[0-9]/.test(password)) strength += 25;
                else feedback.push('Number');

                if (strength === 100) {
                    return {
                        percentage: strength,
                        text: 'Strong password',
                        color: 'bg-green-500',
                        textColor: 'text-green-600 dark:text-green-400'
                    };
                } else if (strength >= 75) {
                    return {
                        percentage: strength,
                        text: 'Good password',
                        color: 'bg-blue-500',
                        textColor: 'text-blue-600 dark:text-blue-400'
                    };
                } else if (strength >= 50) {
                    return {
                        percentage: strength,
                        text: 'Fair password',
                        color: 'bg-yellow-500',
                        textColor: 'text-yellow-600 dark:text-yellow-400'
                    };
                } else {
                    return {
                        percentage: strength,
                        text: 'Weak password',
                        color: 'bg-red-500',
                        textColor: 'text-red-600 dark:text-red-400'
                    };
                }
            }

            // Password confirmation matching
            const confirmPasswordField = document.getElementById('password_confirmation');
            const passwordMatch = document.getElementById('passwordMatch');
            const matchIcon = document.getElementById('matchIcon');
            const matchText = document.getElementById('matchText');

            confirmPasswordField.addEventListener('input', function() {
                const password = passwordField.value;
                const confirmPassword = this.value;

                if (confirmPassword.length > 0) {
                    passwordMatch.classList.remove('hidden');

                    if (password === confirmPassword) {
                        matchIcon.classList.remove('hidden');
                        matchIcon.className = 'h-4 w-4 text-green-500';
                        matchText.textContent = 'Passwords match';
                        matchText.className = 'text-xs text-green-600 dark:text-green-400';
                        this.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                        this.classList.add('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                    } else {
                        matchIcon.classList.remove('hidden');
                        matchIcon.className = 'h-4 w-4 text-red-500';
                        matchText.textContent = 'Passwords do not match';
                        matchText.className = 'text-xs text-red-600 dark:text-red-400';
                        this.classList.remove('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                        this.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    }
                } else {
                    passwordMatch.classList.add('hidden');
                    this.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500', 'border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                }
            });

            // Form submission with loading state
            const form = document.getElementById('tenantPasswordChangeForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');
            });

            // Enhanced keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                    e.preventDefault();
                    form.submit();
                }
            });

            // Auto-focus on first input
            document.getElementById('current-password').focus();
        });
    </script>
@endsection
