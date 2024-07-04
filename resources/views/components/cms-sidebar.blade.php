<aside class="sidebar" id="sidebar">
    <div class="header">
        <img src="{{ !empty($logo->logo_path) ? asset($logo->logo_path) : asset('img/wallpaper.png') }}" alt="Logo image">
        <div class="header-text">
           <a>Gallos Photocopy</a>
            <small>Smart Attendance System</small> 
        </div>
    </div>
    <ul class="list-group">
        <li class="sidelink">
            <a href="{{ route('employees.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    Dashboard
                </span>
            </a>
        </li>

        <li class="sidelink {{ request()->routeIs('employees.index') ? 'active' : '' }}">
            <a href="{{ route('employees.index') }}">
                <i class='bx bxs-hard-hat'></i>
                <span>
                    Employees
                </span>
            </a>
        </li>

        <li class="sidelink">
            <a href="{{ route('employees.index') }}">
                <i class='bx bxs-time' ></i>
                <span>
                    Schedule
                </span>
            </a>
        </li>


        
        <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class='bx bxs-dashboard' ></i>
                {{ __('Logout') }}
            </a>
    
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    
    </ul>
</aside>
