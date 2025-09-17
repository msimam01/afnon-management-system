<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Active Season - AFNON</title>
    @include('application.includes.app')
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 1s ease-out',
                        'bounce-soft': 'bounceSoft 2s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(50px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        bounceSoft: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.3)' },
                            '50%': { boxShadow: '0 0 40px rgba(59, 130, 246, 0.6)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .shape:nth-child(1) {
            top: 20%;
            left: 10%;
            width: 80px;
            height: 80px;
            background: #ffffff;
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 60%;
            right: 10%;
            width: 120px;
            height: 120px;
            background: #ffffff;
            border-radius: 20px;
            animation-delay: 5s;
        }

        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            width: 60px;
            height: 60px;
            background: #ffffff;
            transform: rotate(45deg);
            animation-delay: 10s;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(120deg); }
            66% { transform: translateY(20px) rotate(240deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .notification-bell {
            animation: bounce-soft 2s ease-in-out infinite;
        }

        .cta-button {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 animate-fade-in">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Main Content -->
    <div class="glass-card rounded-3xl p-8 md:p-12 max-w-2xl w-full text-center animate-slide-up">
        <!-- Icon -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full shadow-2xl notification-bell">
                <i class="fas fa-calendar-times text-white text-4xl"></i>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
            No Active Season
        </h1>

        <!-- Subtitle -->
        <p class="text-xl text-white/90 mb-8 leading-relaxed">
            Applications are currently closed. There is no active season available for new applications at this time.
        </p>

        <!-- Info Card -->
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-white/20">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-info-circle text-blue-300 text-xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-white mb-3">What does this mean?</h3>
            <div class="text-white/80 text-left space-y-2">
                <p class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-1 flex-shrink-0"></i>
                    <span>The current agricultural season has ended or hasn't started yet</span>
                </p>
                <p class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-1 flex-shrink-0"></i>
                    <span>New applications will be available when the next season opens</span>
                </p>
                <p class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-1 flex-shrink-0"></i>
                    <span>You'll be notified when applications become available</span>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="checkForUpdates()" class="cta-button px-8 py-4 text-white font-bold rounded-xl shadow-lg flex items-center gap-3">
                <i class="fas fa-sync-alt" id="refresh-icon"></i>
                <span>Check for Updates</span>
            </button>

            <a href="/" class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all duration-300 border border-white/20 hover:border-white/40">
                <i class="fas fa-home"></i>
                <span>Go to Homepage</span>
            </a>
        </div>

        <!-- Contact Info -->
        <div class="mt-12 pt-8 border-t border-white/20">
            <p class="text-white/70 mb-4">Need assistance or have questions?</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center text-sm">
                <a href="tel:+2348000000000" class="flex items-center gap-2 text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-phone"></i>
                    <span>+234 800 000 0000</span>
                </a>
                <a href="mailto:support@afnon.com" class="flex items-center gap-2 text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-envelope"></i>
                    <span>support@afnon.com</span>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-white/10">
            <div class="flex items-center justify-center gap-2 text-white/60">
                <img src="{{ asset('logo.png') }}" alt="AFNON Logo" class="w-4 h-4 object-contain">
                <span class="font-medium">AFNON Association Of Farmers In The Northeast Of Nigeria</span>
            </div>
            <p class="text-white/50 text-sm mt-2">Empowering farmers, growing communities</p>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="update-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="glass-card rounded-2xl p-8 max-w-md mx-4 text-center">
            <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Status Updated</h3>
            <p class="text-white/80 mb-6">We've checked for any new seasons. You'll be redirected if a new season is available.</p>
            <button onclick="closeModal()" class="cta-button px-6 py-3 text-white rounded-lg">
                Close
            </button>
        </div>
    </div>

    <script>
        // Dark mode toggle functionality
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.classList.add('dark');

        // Check for updates functionality
        function checkForUpdates() {
            const refreshIcon = document.getElementById('refresh-icon');
            const button = refreshIcon.closest('button');

            // Add loading state
            refreshIcon.classList.add('fa-spin');
            button.disabled = true;
            button.style.opacity = '0.7';

            // Simulate checking for updates
            setTimeout(() => {
                // Remove loading state
                refreshIcon.classList.remove('fa-spin');
                button.disabled = false;
                button.style.opacity = '1';

                // Check if there's actually an open season now
                fetch(window.location.href)
                    .then(response => {
                        if (response.url !== window.location.href) {
                            // Redirected, meaning there's now an open season
                            window.location.reload();
                        } else {
                            // Still no open season
                            showUpdateModal();
                        }
                    })
                    .catch(() => {
                        showUpdateModal();
                    });
            }, 2000);
        }

        function showUpdateModal() {
            document.getElementById('update-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('update-modal').classList.add('hidden');
        }

        // Auto-refresh every 5 minutes to check for new seasons
        setInterval(() => {
            fetch(window.location.href)
                .then(response => {
                    if (response.url !== window.location.href) {
                        window.location.reload();
                    }
                })
                .catch(() => {
                    // Ignore errors for background checks
                });
        }, 300000); // 5 minutes

        // Add some interactive enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add stagger animation to info items
            const infoItems = document.querySelectorAll('.fa-check-circle');
            infoItems.forEach((item, index) => {
                item.parentElement.style.animationDelay = `${index * 0.2}s`;
                item.parentElement.classList.add('animate-slide-up');
            });
        });
    </script>
</body>
</html>
