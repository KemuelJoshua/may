<nav class="user-navbar bg-transparent">
    <div class="container-fluid navbar-content">
        <div class="logo-container" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="">
        </div>

        <ul class="d-none d-md-flex">
           @include('components.navbar-links')
        </ul>

        <div class="hamburger d-md-none" id="hamburger">
            <input class="checkbox" type="checkbox" id="toggleUserSidebar" />
            <div class="hamburger-lines">
                <span class="line line1 bg-light"></span>
                <span class="line line2 bg-light"></span>
                <span class="line line3 bg-light"></span>
            </div>
        </div>
    </div>
</nav>