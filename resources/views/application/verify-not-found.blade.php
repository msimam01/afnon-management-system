<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Not Found - AFNEN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 1s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'shake': 'shake 0.5s ease-in-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'error-pulse': 'errorPulse 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.8)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        shake: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '10%, 30%, 50%, 70%, 90%': { transform: 'translateX(-5px)' },
                            '20%, 40%, 60%, 80%': { transform: 'translateX(5px)' }
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-8px)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-15px)' }
                        },
                        errorPulse: {
                            '0%, 100%': { transform: 'scale(1)', opacity: '1' },
                            '50%': { transform: 'scale(1.05)', opacity: '0.8' }
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-red-50 via-pink-50 to-orange-50 dark:from-gray-900 dark:via-red-900 dark:to-gray-800 flex items-center justify-center px-4 py-8">

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-red-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-40 left-1/2 w-80 h-80 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 4s;"></div>
    </div>

    <!-- Enhanced Dark Mode Toggle -->
    <div class="fixed bottom-6 right-6 z-50">
        <button id="darkModeToggle" class="group p-4 rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-2xl border border-white/20 dark:border-gray-700/20 hover:scale-110 transition-all duration-300">
            <svg id="sunIcon" class="h-6 w-6 hidden dark:block text-yellow-400 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg id="moonIcon" class="h-6 w-6 block dark:hidden text-gray-800 group-hover:rotate-12 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <!-- Enhanced Not Found Card -->
    <div class="relative max-w-2xl w-full">
        <!-- Error Animation Container -->
        <div class="text-center mb-8 animate-fade-in">
            <!-- Animated Error Icon -->
            <div class="relative mx-auto w-32 h-32 mb-6">
                <!-- Outer Ring with Error Pulse -->
                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-red-400 to-pink-500 animate-error-pulse"></div>
                <!-- Inner Circle -->
                <div class="absolute inset-2 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center">
                    <!-- Error Icon with Shake Animation -->
                    <div class="animate-shake">
                        <i class="fas fa-exclamation-triangle text-6xl text-red-500"></i>
                    </div>
                </div>
                <!-- Floating warning elements -->
                <div class="absolute -top-2 -right-2 w-4 h-4 bg-yellow-400 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
                <div class="absolute -bottom-2 -left-2 w-3 h-3 bg-orange-400 rounded-full animate-bounce" style="animation-delay: 1.5s;"></div>
            </div>

            <!-- Error Message -->
            <h1 class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent mb-4 animate-slide-up" style="animation-delay: 0.3s;">
                ❌ Application Not Found
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-xl mx-auto animate-slide-up" style="animation-delay: 0.6s;">
                😔 Sorry! The reference number you're looking for doesn't exist in our system.
            </p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 overflow-hidden animate-scale-in" style="animation-delay: 0.8s;">
            <div class="p-8 lg:p-12 text-center">
                <!-- Reference Number Display -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-center">
                        <i class="fas fa-search text-red-500 mr-3"></i>
                        Searched Reference
                    </h2>

                    <div class="inline-flex items-center space-x-3 p-4 bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-red-100 dark:border-gray-600">
                        <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-hashtag text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Reference Number</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $reference }}</p>
                        </div>
                    </div>
                </div>

                <!-- Helpful Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        What you can do:
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-blue-100 dark:border-gray-600">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mt-1">
                                    <i class="fas fa-spell-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Check the Reference</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Verify the reference number is correct and try again</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-green-100 dark:border-gray-600">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mt-1">
                                    <i class="fas fa-phone text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Contact Support</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Reach out to our support team for assistance</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-purple-100 dark:border-gray-600">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mt-1">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Wait & Retry</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">The application might be processing</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl border border-orange-100 dark:border-gray-600">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mt-1">
                                    <i class="fas fa-file-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Check Documents</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Review your application documents</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-8 border-t border-gray-200 dark:border-gray-700">
                    <!-- Try Again Button -->
                    <button onclick="tryAgain()"
                            class="group relative px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-redo group-hover:animate-spin"></i>
                        <span>Try Again</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </button>

                    <!-- Contact Support Button -->
                    <a href="mailto:support@afnon.com"
                       class="group relative px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-envelope group-hover:animate-bounce"></i>
                        <span>Contact Support</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </a>

                    <!-- Go Home Button -->
                    <a href="/"
                       class="group relative px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-home group-hover:animate-bounce"></i>
                        <span>Go Home</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 animate-fade-in" style="animation-delay: 1.2s;">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                <i class="fas fa-shield-alt text-red-500 mr-2"></i>
                Secure verification system powered by AFNEN
            </p>
        </div>
    </div>

    <script>
        // Enhanced Dark Mode Toggle
        const toggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';

        if (savedTheme === 'dark') {
            html.classList.add('dark');
        }

        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const newTheme = html.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', newTheme);

            // Add transition effect
            document.body.style.transition = 'background-color 0.3s ease';
            setTimeout(() => {
                document.body.style.transition = '';
            }, 300);
        });

        // Try Again Function
        function tryAgain() {
            const input = prompt('Please enter the correct reference number:');
            if (input && input.trim()) {
                window.location.href = `/verify/${input.trim()}`;
            }
        }

        // Show notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                'bg-blue-500'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Slide in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Slide out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Add loading animation for buttons
        document.querySelectorAll('button, a').forEach(element => {
            element.addEventListener('click', function() {
                if (!this.classList.contains('loading')) {
                    this.classList.add('loading');
                    setTimeout(() => {
                        this.classList.remove('loading');
                    }, 1000);
                }
            });
        });

        // Add CSS for loading animation
        const style = document.createElement('style');
        style.textContent = `
            .loading {
                position: relative;
                pointer-events: none;
            }

            .loading::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 20px;
                height: 20px;
                margin: -10px 0 0 -10px;
                border: 2px solid transparent;
                border-top: 2px solid currentColor;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // Show helpful tip after a delay
        setTimeout(() => {
            showNotification('💡 Tip: Double-check your reference number format', 'info');
        }, 3000);
    </script>
</body>
</html>
