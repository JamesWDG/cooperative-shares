<div class="side-bar smallBar">
    <button class="close-sidebar">
        <i class="fa-regular fa-circle-xmark"></i>
    </button>

    <div class="logo-area">
        <a href="{{ route('index') }}">
            <img src="{{ asset('assets/vendor/images/logo.png') }}" alt="">
        </a>
    </div>

    <ul class="p-0 m-0 sidebar-ul">

        {{-- Dashboard --}}
        <li>
            <a href="{{ route('user.dashboard') }}" 
            class="{{ request()->is('user') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>
        

        {{-- Profile --}}
        <li>
            <a href="{{ route('user.profile') }}" 
               class="{{ request()->is('user/profile') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-user"></i> {{-- Profile Icon --}}
                    <span>Profile</span>
                </div>
            </a>
        </li>
        {{-- Listings --}}
        <li>
            <a href="{{ route('user.saved-listing') }}" 
               class="{{ request()->routeIs('user.saved-listing') || request()->routeIs('user.saved-listing.*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-list"></i> {{-- Listings Icon --}}
                    <span>Saved Listing</span>
                </div>
            </a>
        </li>

        {{-- Appointments --}}
        <li>
            <a href="{{ route('user.appointments') }}" 
               class="{{ request()->is('user/appointments') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-calendar-check"></i> {{-- Appointments Icon --}}
                    <span>Appointments</span>
                </div>
            </a>
        </li>
        {{-- Notifications --}}
        <li>
            <a href="{{ route('user.notifications') }}" 
               class="{{ request()->is('user/notifications*') ? 'active' : '' }}">
                
                <div class="icon-text position-relative">
                    {{-- Bell Icon --}}
                    <i class="fa-solid fa-bell"></i>

                    {{-- Unread Count Badge --}}
                    @if($usernotifications->count())
                        <span class="sidebar-notify-circle">
                            {{ $usernotifications->count() }}
                        </span>
                    @endif

                    {{-- Text --}}
                    <span>Notifications</span>
                </div>

            </a>
        </li>
        {{-- Logout --}}
        <li>
            <a href="{{ route('user.logout') }}">
                <div class="icon-text">
                    <i class="fa-solid fa-sign-out-alt"></i> {{-- Logout Icon --}}
                    <span>Logout</span>
                </div>
            </a>
        </li>

    </ul>
</div>
