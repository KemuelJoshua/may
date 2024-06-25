<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/sass/user.scss'])
</head>
<body>
    <div id="app">
        @include('components.user-sidebar')        
        @include('components.user-navbar')
        <main>
            @yield('content')
            @include('components.footer')
        </main>
    </div>
    @yield('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
         document.addEventListener('DOMContentLoaded', function() {
            AOS.init();

            window.addEventListener('scroll', function() {
            var navbar = document.querySelector('.user-navbar');
            var hamburger = document.querySelector('#hamburger');
            
                if (window.scrollY > 0) {
                    navbar.classList.add('navbar-scroll');
                    hamburger.classList.add('change');
                } else {
                    navbar.classList.remove('navbar-scroll');
                    hamburger.classList.remove('change');
                }
            });
        });

        $(document).ready(function() {
            // User layout sidebar toggle
            $('#toggleUserSidebar').on('change', function() {
                var sidebar = $('.user-sidebar');
                sidebar.toggleClass('show-sidebar', this.checked);
            });
        });
    </script>
</body>
</html>
