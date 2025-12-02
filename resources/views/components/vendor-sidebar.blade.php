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
            <a href="{{ route('vendor.dashboard') }}" 
            class="{{ request()->is('vendor') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>


        {{-- Subscription Plans --}}
        <li>
            <a href="{{ route('vendor.subscription.plans') }}" 
               class="{{ request()->is('vendor/subscription-plans') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-layer-group"></i> {{-- Subscription Plans Icon --}}
                    <span>Subscription Plans</span>
                </div>
            </a>
        </li>

        {{-- Notifications --}}
        <li>
            <a href="{{ route('vendor.notifications') }}" 
               class="{{ request()->is('vendor/notifications*') ? 'active' : '' }}">
                
                <div class="icon-text position-relative">
                    {{-- Bell Icon --}}
                    <i class="fa-solid fa-bell"></i>

                    {{-- Unread Count Badge --}}
                    @if($vendornotifications->count())
                        <span class="sidebar-notify-circle">
                            {{ $vendornotifications->count() }}
                        </span>
                    @endif

                    {{-- Text --}}
                    <span>Notifications</span>
                </div>

            </a>
        </li>

        {{-- Profile --}}
        <li>
            <a href="{{ route('vendor.profile') }}" 
               class="{{ request()->is('vendor/profile') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-user"></i> {{-- Profile Icon --}}
                    <span>Profile</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('vendor.gallery') }}" 
               class="{{ request()->is('vendor/gallery*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-user"></i> {{-- gallery Icon --}}
                    <span>Gallery</span>
                </div>
            </a>
        </li>

        {{-- Listings --}}
        <li>
            <a href="{{ route('vendor.listings') }}" 
               class="{{ request()->routeIs('vendor.listings') || request()->routeIs('vendor.listings.*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-list"></i> {{-- Listings Icon --}}
                    <span>Listing</span>
                </div>
            </a>
        </li>

        {{-- Leads --}}
        <li>
            <a href="{{ route('vendor.leads') }}" 
               class="{{ request()->is('vendor/leads') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-handshake"></i> {{-- Leads Icon --}}
                    <span>Leads</span>
                </div>
            </a>
        </li>

        {{-- Appointments --}}
        <li>
            <a href="{{ route('vendor.appointments') }}" 
               class="{{ request()->is('vendor/appointments') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-calendar-check"></i> {{-- Appointments Icon --}}
                    <span>Appointments</span>
                </div>
            </a>
        </li>

        {{-- Marketing Plans --}}
        <li>
            <a href="{{ route('vendor.marketing.plans') }}" 
               class="{{ request()->is('vendor/marketing-plans') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-bullhorn"></i> {{-- Marketing Icon --}}
                    <span>Marketing Plans</span>
                </div>
            </a>
        </li>

        {{-- Analytics --}}
        <li>
            <a href="{{ route('vendor.analytics') }}" 
               class="{{ request()->is('vendor/analytics') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-chart-line"></i> {{-- Analytics Icon --}}
                    <span>Analytics</span>
                </div>
            </a>
        </li>

        {{-- Invoices --}}
        <li>
            <a href="{{ route('vendor.invoices') }}" 
               class="{{ request()->routeIs('vendor.invoices') || request()->routeIs('vendor.invoice.*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-file-invoice-dollar"></i> {{-- Invoices Icon --}}
                    <span>Invoices</span>
                </div>
            </a>
        </li>
        @if(!empty($vendorHasCoopAccess) && $vendorHasCoopAccess)
            <li>
                <a href="{{ route('vendor.blogs') }}" 
                class="{{ request()->is('vendor/co-op*')  ? 'active' : '' }}">
                    <div class="icon-text">
                        <i class="fa-solid fa-people-group"></i>  {{-- Co-Op Icon --}}
                        <span>Co-Op</span>
                    </div>
                </a>
            </li>
        @endif


        
       

        {{-- Logout --}}
        <li>
            <a href="{{ route('vendor.logout') }}">
                <div class="icon-text">
                    <i class="fa-solid fa-sign-out-alt"></i> {{-- Logout Icon --}}
                    <span>Logout</span>
                </div>
            </a>
        </li>

    </ul>
</div>
