<div class="content-wrapper">
    <header class="header">
        <div class="search-area">
            <div class="hamburger sidebarOpner">
                <i class="fa-solid fa-bars"></i>
            </div>
            <div class="search-form">
                <!--<form action="">-->
                <!--    <input type="search" placeholder="Search">-->
                <!--    <button>-->
                <!--        <i class="fa-solid fa-magnifying-glass"></i>-->
                <!--    </button>-->
                <!--</form>-->
            </div>
        </div>

        <div class="profile-det-area">
            <!--<div class="dropdown">-->
            <!--    <button class="btn dropdown-toggle position-relative" type="button" data-bs-toggle="dropdown"-->
            <!--            aria-expanded="false">-->
            <!--        <i class="fa-regular fa-bell"></i>-->
            <!--        <span class="badge">12</span>-->
            <!--    </button>-->
            <!--    <ul class="dropdown-menu">-->
            <!--        <li class="d-flex justify-content-between">-->
            <!--            <p><span>12</span> Unread messages</p>-->
            <!--            <a class="clear-btn" href="#">Clear All</a>-->
            <!--        </li>-->
            <!--        <li class="notify-li">-->
            <!--            <div class="d-flex align-items-start gap-2">-->
            <!--                <div>-->
            <!--                    <img src="{{ asset('assets/vendor/images/person-img.png') }}" alt="">-->
            <!--                </div>-->
            <!--                <a href="#">-->
            <!--                    <h6>2min ago</h6>-->
            <!--                    <p>Donec dapibus mauris id odio ornare tempus amet.</p>-->
            <!--                </a>-->
            <!--            </div>-->
            <!--        </li>-->
            <!--        <li class="notify-li">-->
            <!--            <div class="d-flex align-items-start gap-2">-->
            <!--                <div>-->
            <!--                    <img src="{{ asset('assets/vendor/images/person-img.png') }}" alt="">-->
            <!--                </div>-->
            <!--                <a href="#">-->
            <!--                    <h6>2min ago</h6>-->
            <!--                    <p>Donec dapibus mauris id odio ornare tempus amet.</p>-->
            <!--                </a>-->
            <!--            </div>-->
            <!--        </li>-->
            <!--        <li class="notify-li">-->
            <!--            <div class="d-flex align-items-start gap-2">-->
            <!--                <div>-->
            <!--                    <img src="{{ asset('assets/vendor/images/person-img.png') }}" alt="">-->
            <!--                </div>-->
            <!--                <a href="#">-->
            <!--                    <h6>2min ago</h6>-->
            <!--                    <p>Donec dapibus mauris id odio ornare tempus amet.</p>-->
            <!--                </a>-->
            <!--            </div>-->
            <!--        </li>-->
            <!--    </ul>-->
            <!--</div>-->
            <div class="dropdown admin-notify-dropdown">
    <button class="btn dropdown-toggle position-relative" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">

        {{-- Bell Icon --}}
        <i class="fa-solid fa-bell"></i>

        {{-- Notification Count --}}
        @if($adminnotifications->count())
            <span class="badge">{{ $adminnotifications->count() }}</span>
        @endif
    </button>

    <ul class="dropdown-menu notify-dropdown">

        {{-- Top Header: Unread count + Clear All --}}
        <li class="d-flex justify-content-between px-3 py-2 border-bottom">
            <p class="mb-0">
                <span>{{ $adminnotifications->count() }}</span> Unread Messages
            </p>

            
        </li>

        {{-- Notification List Items --}}
        @forelse($adminnotifications as $note)
            <li class="notify-li">
                <a href="{{ route('admin.notifications.view', $note->slug) }}" class="d-flex align-items-start gap-2">

                    {{-- Sender Avatar --}}
                    <div>
                        <img src="{{ asset('assets/dummy_avatar/avatar.jpg') }}" alt="" width="40">
                    </div>

                    <div>
                        <h6 class="mb-1">{{ $note->created_at->diffForHumans() }}</h6>
                        <p class="mb-0">{{ Str::limit($note->content, 45) }}</p>
                    </div>

                </a>
            </li>
        @empty
            <li class="notify-li text-center py-3">
                <p class="mb-0">No new notifications</p>
            </li>
        @endforelse

    </ul>
</div>


            {{-- ADMIN PROFILE DROPDOWN --}}
            <div class="dropdown">
                <button class="btn dropdown-toggle position-relative profile-btn" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">

                    {{-- yahan admin guard ya user guard jo bhi use kar rahe ho --}}
                    <img src="{{ (Auth::user()->profile_image ?? false) ? asset(Auth::user()->profile_image) : asset('assets/dummy_avatar/avatar.jpg') }}" alt="">
                    <div>
                        <h5>{{ (Auth::user()->full_name ?? '') !== '' ? Auth::user()->full_name : 'Admin User' }}</h5>
                        <h6>{{ Auth::user()->email ?? '' }}</h6>
                    </div>
                </button>
                <ul class="dropdown-menu profile-menu">
                    <li class="prof-li">
                        <a class="" href="{{ route('admin.profile') }}">
                            <i class="fa-solid fa-circle-user"></i> Profile
                        </a>
                    </li>
                    <li class="prof-li">
                        <a class="" href="{{ route('admin.logout') }}">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <script>
            const hamburger = document.querySelector('.hamburger');         // for mobile
            const sidebar = document.querySelector('.side-bar');
            const closeBtn = document.querySelector('.close-sidebar');

            const hamburger2 = document.querySelector('.sidebarOpner');     // for desktop
            const sidebar2 = document.querySelector('.smallBar');

            let currentMode = null;

            function activateMobileSidebar() {
                if (currentMode === 'mobile') return;
                currentMode = 'mobile';

                if (hamburger2) hamburger2.onclick = null;

                if (hamburger) {
                    hamburger.onclick = () => sidebar && sidebar.classList.add('active');
                }

                if (closeBtn) {
                    closeBtn.onclick = () => sidebar && sidebar.classList.remove('active');
                }
            }

            function activateDesktopSidebar() {
                if (currentMode === 'desktop') return;
                currentMode = 'desktop';

                if (hamburger) hamburger.onclick = null;
                if (closeBtn) closeBtn.onclick = null;

                if (hamburger2) {
                    hamburger2.onclick = () => sidebar2 && sidebar2.classList.toggle('collapsed');
                }

                if (sidebar) {
                    sidebar.classList.remove('active');
                }
            }

            function handleSidebarToggle() {
                if (window.innerWidth <= 991) {
                    activateMobileSidebar();
                } else {
                    activateDesktopSidebar();
                }
            }

            handleSidebarToggle();
            window.addEventListener('resize', handleSidebarToggle);
        </script>

    </header>
