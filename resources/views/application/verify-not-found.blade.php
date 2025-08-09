<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    keyframes: {
                        fadeSlideUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        bounceOnce: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-8px)' } }
                    },
                    animation: {
                        fadeSlideUp: 'fadeSlideUp 0.8s ease-out forwards',
                        bounceOnce: 'bounceOnce 1s ease-in-out infinite'
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full bg-gradient-to-br from-red-50 to-pink-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4">

    <!-- Dark Mode Toggle -->
    <div class="fixed bottom-6 right-6">
        <button id="darkModeToggle"
            class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            <svg id="sunIcon" class="h-6 w-6 hidden dark:block text-yellow-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 
                       6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 
                       0l-.707.707M6.343 17.657l-.707.707M16 
                       12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="moonIcon" class="h-6 w-6 block dark:hidden text-gray-800" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 
                       3.646 9.003 9.003 0 0012 21a9.003 
                       9.003 0 008.354-5.646z"/>
            </svg>
        </button>
    </div>

    <!-- Card -->
    <div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 text-center animate-fadeSlideUp">
        <div class="mx-auto w-20 h-20 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center animate-bounceOnce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-bold text-red-600 dark:text-red-400">Application Not Found</h1>
        <p class="mt-3 text-gray-600 dark:text-gray-300">
            The reference number <strong class="text-gray-900 dark:text-white">{{ $reference }}</strong> could not be found in our records.
        </p>
        <a href="/" class="mt-6 inline-block px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-lg shadow hover:bg-emerald-700 transition-colors">
            Go Back Home
        </a>
    </div>

    <script>
        const toggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const saved = localStorage.getItem('theme') || 'light';
        if (saved === 'dark') html.classList.add('dark');
        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
    </script>
</body>
</html>
