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
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-tachometer-alt"></i>  <!-- Dashboard Icon -->
                    <span>Dashboard</span>
                </div>
            </a>
        </li>
        <li>
    <a href="{{ route('admin.notifications') }}" 
       class="{{ request()->is('admin/notifications*') ? 'active' : '' }}">
        
        <div class="icon-text position-relative">

            {{-- Bell Icon --}}
            <i class="fa-solid fa-bell"></i>

            {{-- Unread Count Badge --}}
            @if($adminnotifications->count())
                <span class="sidebar-notify-circle">
                    {{ $adminnotifications->count() }}
                </span>
            @endif

            {{-- Text --}}
            <span>Notifications</span>
        </div>

    </a>
</li>


        <li>
            <a href="{{ route('admin.profile') }}" class="{{ request()->is('admin/profile') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-user"></i>  <!-- Profile Icon -->
                    <span>Profile</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-users"></i>  <!-- Users Icon -->
                    <span>Users</span>
                </div>
            </a>
        </li>
        <li class="has-menu sidebar-dropdown {{ request()->routeIs('admin.cms.*') ? 'mm-active active' : '' }}">
            <a class="has-arrow ai-icon side-bar-tab" href="javascript:void(0);" aria-expanded="false">
                <div class="icon-text">
                    <i class="fa-solid fa-cogs"></i>  <!-- CMS Icon -->
                    <span>CMS</span>
                </div>
            </a>
            <ul class="sidebar-submenu mm-collapse" style="{{ request()->routeIs('admin.cms.*') ? 'display:block;' : 'display:none;' }}">
                <li>
                    <a class="{{ request()->is('admin/cms/header*') ? 'active' : '' }}" href="{{ route('admin.cms.header.list') }}">
                        <i class="fa-solid fa-heading"></i>  <!-- Header Icon -->
                        Header
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('admin/cms/footer*') ? 'active' : '' }}" href="{{ route('admin.cms.footer.list') }}">
                        <i class="fa-solid fa-football-ball"></i>  <!-- Footer Icon -->
                        Footer
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('admin/cms/home*') ? 'active' : '' }}" href="{{ route('admin.cms.home.list') }}">
                        <i class="fa-solid fa-home"></i>  <!-- Home Icon -->
                        Home
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('admin/cms/about*') ? 'active' : '' }}" href="{{ route('admin.cms.about.list') }}">
                        <i class="fa-solid fa-info-circle"></i>  <!-- About Icon -->
                        About
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('admin/cms/faqs*') ? 'active' : '' }}" href="{{ route('admin.cms.faqs.list') }}">
                        <i class="fa-solid fa-question-circle"></i>  <!-- FAQs Icon -->
                        FAQs
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('admin/cms/privacy-policy*') ? 'active' : '' }}" href="{{ route('admin.cms.privacy.list') }}">
                        <i class="fa-solid fa-shield-alt"></i>  <!-- Privacy Policy Icon -->
                        Privacy Policy
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('admin/cms/terms-conditions*') ? 'active' : '' }}" href="{{ route('admin.cms.terms.list') }}">
                        <i class="fa-solid fa-file-contract"></i>  <!-- Terms & Conditions Icon -->
                        Terms & Condi
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('admin/cms/cooperative-differences*') ? 'active' : '' }}" href="{{ route('admin.cms.cooperative-differences.list') }}">
                        <i class="fa-solid fa-balance-scale"></i>  <!-- Cooperative Differences Icon -->
                        Cooperative Diff
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('admin/cms/contact-settings*') ? 'active' : '' }}" href="{{ route('admin.cms.contact-settings.list') }}">
                        <i class="fa-solid fa-cogs"></i>  <!-- Contact Settings Icon -->
                        Contact Settings
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('admin.listings') }}" class="{{ request()->is('admin/listings') || request()->is('admin/listings/*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-list"></i>  <!-- Listings Icon -->
                    <span>Listing</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.leads') }}" class="{{ request()->is('admin/leads*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-handshake"></i>  <!-- Use fa-users for Leads -->
                    <span>Leads</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.contact-forms') }}" class="{{ request()->is('admin/contact-forms*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-address-book"></i>  <!-- Contact List Icon -->
                    <span>Contact List</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.services') }}" class="{{ request()->is('admin/services*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-briefcase"></i>  <!-- Services Icon -->
                    <span>Services</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.blogs') }}" class="{{ request()->is('admin/blogs*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-newspaper"></i>  <!-- Blogs Icon -->
                    <span>Blogs</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.training-videos') }}" class="{{ request()->is('admin/training-videos*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-play-circle"></i> <!-- Play Button Icon -->
                    <span>Training Videos</span>
                </div>
            </a>
        </li>


        <li>
            <a href="{{ route('admin.advertisements') }}" class="{{ request()->is('admin/advertisements*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-ad"></i>  <!-- Advertisements Icon -->
                    <span>Advertisements</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.ads-purchased') }}" class="{{ request()->is('admin/ads-purchased*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-cart-arrow-down"></i>  <!-- Ads Purchased Icon -->
                    <span>Ads Purchased</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reviews') }}" class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                <div class="icon-text">
                    <i class="fa-solid fa-star"></i>  <!-- Reviews Icon -->
                    <span>Reviews</span>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.logout') }}">
                <div class="icon-text">
                    <i class="fa-solid fa-sign-out-alt"></i>  <!-- Logout Icon -->
                    <span>Logout</span>
                </div>
            </a>
        </li>
    </ul>
</div>
