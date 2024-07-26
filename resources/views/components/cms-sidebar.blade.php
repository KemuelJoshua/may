<aside class="sidebar" id="sidebar">
    <div class="header">
        <img src="{{ asset('img/pasig-science.png') }}" alt="Logo image">
        <div class="header-text">
           <a>Guidance Managements System</a>
        </div>
    </div>
    <ul class="list-group">
        <li class="sidelink">
            <a href="{{ route('students.index') }}">
                <i class='bx bxs-dashboard' ></i>
                <span>
                    Dashboard
                </span>
            </a>
        </li>

        <li class="sidelink {{ request()->routeIs('students.index') ? 'active' : '' }}">
            <a href="{{ route('students.index') }}">
                <i class='bx bxs-user' ></i>
                <span>
                    Student Record
                </span>
            </a>
        </li>

        <li class="sidelink">
            <a href="{{ route('students.index') }}">
                <i class='bx bxs-file-doc' ></i>
                <span>
                    Add Record
                </span>
            </a>
        </li>

        <li class="sidelink">
            <a href="{{ route('students.index') }}">
                <i class='bx bx-list-check' ></i>
                <span>
                    User Logs
                </span>
            </a>
        </li>

        <li class="sidelink">
            <a href="{{ route('students.index') }}">
                <i class='bx bx-cog' ></i>
                <span>
                    Settings
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
