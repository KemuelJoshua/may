<aside class="sidebar" id="sidebar">
    <div class="header">
        {{-- <img src="{{ !empty($logo->logo_path) ? asset($logo->logo_path) : asset('img/logo.png') }}" alt="Logo image"> --}}
        <div class="header-text">
           <a>Novulutions</a>
            <small>CMS</small> 
        </div>
    </div>
    <ul class="list-group">
        <li class="sidelink {{ request()->routeIs('about-us.index') ? 'active' : '' }}">
            <a href="{{ route('about-us.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    About us
                </span>
            </a>
        </li>
        <li class="sidelink {{ request()->routeIs('organizational-chart.index') ? 'active' : '' }}">
            <a href="{{ route('organizational-chart.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    Organizational Chart
                </span>
            </a>
        </li>
        <li class="sidelink {{ request()->routeIs('carousels.index') ? 'active' : '' }}">
            <a href="{{ route('carousels.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    Carousels
                </span>
            </a>
        </li>
        <!-- Solutions -->
        <li class="sidelink {{ request()->routeIs('solutions.index') ? 'active' : '' }}">
            <a href="{{ route('solutions.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    Solutions
                </span>
            </a>
        </li>
        <li class="sidelink {{ request()->routeIs('services.index') ? 'active' : '' }}">
            <a href="{{ route('services.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    Services
                </span>
            </a>
        </li>
        <li class="sidelink {{ request()->routeIs('partners.index') ? 'active' : '' }}">
            <a href="{{ route('partners.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    Partners
                </span>
            </a>
        </li>
        <li class="sidelink {{ request()->routeIs('clients.index') ? 'active' : '' }}">
            <a href="{{ route('clients.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    Client
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
    
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    
    </ul>
</aside>
