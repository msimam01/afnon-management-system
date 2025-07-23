<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>North East Commodity Distribution Associations (NECAS)</title>
    {{-- <link rel="stylesheet" href="{{asset('css/style.css')}}"> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans transition-colors duration-200">
    @include('layouts.navbar')
    <div class="flex pt-16">
        @include('layouts.sidebar')
        <main class="ml-0 md:ml-80 w-full min-h-screen px-4 sm:px-6 lg:px-8 py-12 transition-all">
            @yield('content')
        </main>
    </div>
    @include('layouts.footer')
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
