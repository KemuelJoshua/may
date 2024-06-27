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
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-dTzT+hVn3JFp+ts6mccvhJBpUknS5Ffi6ZfaC6V9OeZRbWeyAaiZH9w7jRGwxLoMsBk6QmyIdJNZ6t0a+UPaU0A==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/sass/cms.scss'])
</head>
<body>
    <div id="cms">
        @include('components.cms-sidebar')
        <div class="hamburger d-lg-none">
            <input class="checkbox" type="checkbox" id="toggleSidebar" />
            <div class="hamburger-lines">
                <span class="line line1 bg-dark"></span>
                <span class="line line2 bg-dark"></span>
                <span class="line line3 bg-dark"></span>
            </div>
        </div>
        <div class="content">
            <main>
                <div class="p-3 py-4 main">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @yield('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
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
            $('#toggleSidebar').on('change', function() {
                var sidebar = $('.sidebar');
                sidebar.toggleClass('show-sidebar', this.checked);
            });
        });
    </script>
</body>
</html>
