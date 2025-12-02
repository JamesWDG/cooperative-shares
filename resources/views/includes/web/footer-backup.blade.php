{{-- Login Form --}}
<div class="modal fade" id="modalOverlayLogin" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content p-0 border-0">
            <div class="modal-bg" style="background-image: url('{{ asset('assets/web/images/login.png') }}');">
                <div class="modal-overlay">

                    <button type="button" class="close-modal" data-bs-dismiss="modal" aria-label="Close">
                        &times;
                    </button>

                    <div class="modal-box">


                        <div class="tab-content tab-content-22 tab-content-22-login d-block">
                            <div class="right">
                                <h2 class="sing-up-hd-md text-center login-up-hd-md">Login</h2>
                                <!--<div class="singup-icons login-icons">-->
                                <!--    <div class="singup-icon login-icon">-->
                                <!--        <i class="fa-brands fa-facebook-f"></i>-->
                                <!--    </div>-->
                                <!--    <div class="singup-icon">-->
                                <!--        <i class="fa-brands fa-google"></i>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<p class="account-para text-center">or use your account</p>-->
                                <form id="login-form">
                                    @csrf
                                    <div class="input-wrapper">
                                        <input type="email" name="email" placeholder="Email">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div class="input-wrapper">
                                        <input type="password" name="password" id="login-password"
                                            placeholder="Password">
                                        <i class="fa-solid fa-eye" id="login-password-toggle"></i>
                                    </div>
                                    <div class="forgot-password">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalOverlay"
                                            class="forgot-password-link">forgot-password</a>
                                    </div>
                                    <button class="submit-btn custumar-btn">Login</button>
                                </form>
                            </div>
                            <div class="left">
                                <h2 class="welcome-hd friend-hd">Hello, Friend!</h2>
                                <p class="welcome-hd-para friend-hd-para">Enter your detail & Start
                                    journey with us</p>
                                <button class="switch-btn submit-btn custumar-btn" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">SignUp</button>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOverlay" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content p-0 border-0">
            <div class="modal-bg" style="background-image: url('{{ asset('assets/web/images/login.png') }}');">
                <div class="modal-overlay">

                    <button type="button" class="close-modal" data-bs-dismiss="modal" aria-label="Close">
                        &times;
                    </button>

                    <div class="modal-box">


                        <div class="tab-content tab-content-22 d-block">
                            <div class="Reset-Password-box forget-Password-box">
                                <h2 class="sing-up-hd-md text-center login-up-hd-md">Forget Password</h2>
                                <p class="welcome-hd-para reset-para">Please enter the email address youâ€™d like your
                                    password
                                    <br>resent information sent to
                                </p>
                                <div class="input-wrapper reset-input-wrapper">
                                    <input type="email" placeholder="E-mail">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalOverlayReset"
                                    class="reset-btn">
                                    Reset password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOverlayReset" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content p-0 border-0">
            <div class="modal-bg" style="background-image: url('{{ asset('assets/web/images/login.png') }}');">
                <div class="modal-overlay">

                    <button type="button" class="close-modal" data-bs-dismiss="modal" aria-label="Close">
                        &times;
                    </button>

                    <div class="modal-box">


                        <div class="tab-content tab-content-22 d-block">
                            <div class="Reset-Password-box">
                                <h2 class="sing-up-hd-md text-center login-up-hd-md">Reset Password</h2>
                                <p class="welcome-hd-para reset-para">Enter your new password below.
                                </p>
                                <div class="input-wrapper reset-input-wrapper">
                                    <input type="password" placeholder="Password">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                                <div class="input-wrapper reset-input-wrapper">
                                    <input type="password" id="confirmPasswordInput2" placeholder="Condfirm Password">
                                    <i class="fa-solid fa-eye" id="togglePassword2"></i>
                                </div>
                                <a href="#" class="reset-btn reset-btn2" data-bs-toggle="modal"
                                    data-bs-target="#modalOverlayUpdated">
                                    Reset password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOverlayUpdated" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content p-0 border-0">
            <div class="modal-bg" style="background-image: url('{{ asset('assets/web/images/login.png') }}');">
                <div class="modal-overlay">

                    <button type="button" class="close-modal" data-bs-dismiss="modal" aria-label="Close">
                        &times;
                    </button>

                    <div class="modal-box">


                        <div class="tab-content tab-content-22 d-block">
                            <div class="Reset-Password-box updated-password-box">
                                <div class="updated-circle">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <h2 class="sing-up-hd-md text-center password-updated-hd">Password Updated</h2>
                                <p class="welcome-hd-para reset-para">Your new password has been changed
                                    successfully.
                                    <br> Use your new password to login
                                </p>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    class="reset-btn reset-btn2 updated-btn2">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Back to Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Cooperative Shares</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form role="form" action="">
                    <div class="confirmationField mb-3">
                        <label for="" class="form-label">Search What You Want</label>
                        <input class="form-control" placeholder="Type Here To Search" type="text" required>
                    </div>
                    <button class="modal-search w-100" type="submit">Search Now</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!--<footer class="footer <?php echo ($page === 'special-page') ? 'footer-padding' : 'pt-0'; ?>">-->
<!--    <div class="container">-->
<!--        <div class="row row-gap-5">-->
<!--            <div class="col-lg-3">-->
<!--                <div class="footer-logo">-->
<!--                    <a href="{{ route('index') }}">-->
<!--                        <img src="{{ asset('assets/web/images/logo.png') }}" alt="">-->
<!--                    </a>-->
<!--                </div>-->
<!--                <p class="footer-para">Lorem IpsumÂ is simply dummy text of the printing and typesetting industry. Lorem-->
<!--                    Ipsum has been.</p>-->
<!--                <div class="footer-social-area">-->
<!--                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>-->
<!--                    <a href="#"><i class="fa-brands fa-twitter"></i></a>-->
<!--                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-3 text-center">-->
<!--                <div class="f-ul-wrapper text-start">-->
<!--                    <h4 class="footer-hd">Quick Links</h4>-->
<!--                    <ul>-->
<!--                        <li><a href="{{ route('index') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>-->
<!--                        </li>-->
<!--                        <li><a href="{{ route('about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About-->
<!--                                Us</a></li>-->
<!--                        <li><a href="{{ route('listings') }}"-->
<!--                                class="{{ request()->is('listings') ? 'active' : '' }}">Listings</a></li>-->
<!--                        <li><a href="{{ route('services') }}">Services</a></li>-->
<!--                        <li><a href="{{ route('reviews') }}"-->
<!--                                class="{{ request()->is('reviews') ? 'active' : '' }}">Reviews</a></li>-->
<!--                        <li><a href="{{ route('blogs') }}"-->
<!--                                class="{{ request()->is('blogs') ? 'active' : '' }}">Blogs</a></li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-3 text-center">-->
<!--                <div class="f-ul-wrapper text-start">-->
<!--                    <h4 class="footer-hd">Helpful links</h4>-->
<!--                    <ul>-->
<!--                        <li><a href="{{ route('cooperrative.differences') }}"-->
<!--                                class="{{ request()->is('cooperrative-differences') ? 'active' : '' }}">Cooperative-->
<!--                                Differences</a></li>-->
<!--                        <li><a href="{{ route('contact') }}"-->
<!--                                class="{{ request()->is('contact') ? 'active' : '' }}">Contact Us</a></li>-->
<!--                        <li><a href="{{ route('privacy.policy') }}"-->
<!--                                class="{{ request()->is('privacy-policy') ? 'active' : '' }}">Privacy Policy</a></li>-->
<!--                        <li><a href="{{ route('faq') }}" class="{{ request()->is('faq') ? 'active' : '' }}">FAQs</a>-->
<!--                        </li>-->
<!--                        <li><a href="{{ route('terms') }}" class="{{ request()->is('terms') ? 'active' : '' }}">Terms &-->
<!--                                Conditions</a></li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-3 text-center">-->
<!--                <div class="f-ul-wrapper text-start">-->
<!--                    <h4 class="footer-hd">Contact Info</h4>-->
<!--                    <ul>-->
<!--                        <li><a href="tel:8165297022"><img src="{{ asset('assets/web/images/footer-contact-img1.png') }}"-->
<!--                                    alt="">-->
<!--                                816-529-7022</a></li>-->
<!--                        <li><a href="mailto:anderson@theaapg.com"><img-->
<!--                                    src="{{ asset('assets/web/images/footer-contact-img2.png') }}" alt="">-->
<!--                                anderson@theaapg.com</a></li>-->
<!--                        <li><a class="text-white"><img src="{{ asset('assets/web/images/footer-contact-img3.png') }}"-->
<!--                                    alt=""> 520 E 4th st-->
<!--                                Tonganoxie, KS <br> 66086</a></li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="bottom-bar">-->
<!--            <p>Â© Copyright 2025 <strong>Cooperative Homes</strong>. All rights Reserved</p>-->
<!--            <p>Design & Developed by: <strong><a href="https://www.webdesignglory.com/" class="text-white">Web Design-->
<!--                        Glory</a></strong></p>-->
<!--        </div>-->
<!--    </div>-->
<!--</footer>-->

@php
    use App\Models\CmsPage;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Route;

    // Load footer page from CMS
    $footerPage    = CmsPage::where('page_key', 'footer')->first();
    $footerContent = $footerPage->content['footer'] ?? null;

    $description   = $footerContent['description'] ?? null;
    $social        = $footerContent['social_links'] ?? [];
    $quickLinks    = $footerContent['quick_links'] ?? [];
    $helpfulLinks  = $footerContent['helpful_links'] ?? [];
    $contact       = $footerContent['contact'] ?? [];
    $bottomBar     = $footerContent['bottom_bar'] ?? [];
    $logo          = $footerContent['logo'] ?? null;

    // Helper to resolve URL + active class from one footer link item
    $resolveLink = function ($item) {
        $isRoute   = !empty($item['is_route']);
        $routeName = $item['route_name'] ?? '';
        $urlField  = $item['url'] ?? '';

        $href   = '#';
        $active = '';

        if ($isRoute && $routeName) {
            // If they stored a path like "/about"
            if (Str::startsWith($routeName, '/')) {
                $href    = url($routeName);
                $pattern = ltrim($routeName, '/');
                $active  = request()->is($pattern) ? 'active' : '';
            } else {
                // Treat as named route if it exists, otherwise as path
                if (Route::has($routeName)) {
                    $href   = route($routeName);
                    $active = request()->routeIs($routeName) ? 'active' : '';
                } else {
                    $href    = url($routeName);
                    $pattern = ltrim($routeName, '/');
                    $active  = request()->is($pattern) ? 'active' : '';
                }
            }
        } elseif (!empty($urlField)) {
            $href   = $urlField;
            $active = request()->fullUrlIs($urlField) ? 'active' : '';
        }

        return [$href, $active];
    };
@endphp

<footer class="footer {{ ($page ?? null) === 'special-page' ? 'footer-padding' : 'pt-0' }}">
    <div class="container">
        <div class="row row-gap-5">
            {{-- Logo + description + socials --}}
            <div class="col-lg-3">
                <div class="footer-logo">
                    <a href="{{ route('index') }}">
                        @if($logo)
                            {{-- Upload path: storage/cms/footer/footer/{filename} --}}
                            <img src="{{ asset('storage/cms/footer/footer/' . $logo) }}" alt="Footer Logo">
                        @else
                            <img src="{{ asset('assets/web/images/logo.png') }}" alt="">
                        @endif
                    </a>
                </div>

                @if(!empty($description))
                    <p class="footer-para">{!! $description !!}</p>
                @endif

                @if(!empty($social))
                    <div class="footer-social-area">
                        @if(!empty($social['facebook']))
                            <a href="{{ $social['facebook'] }}"><i class="fa-brands fa-facebook-f"></i></a>
                        @endif
                        <!--@if(!empty($social['twitter']))-->
                        <!--    <a href="{{ $social['twitter'] }}"><i class="fa-brands fa-twitter"></i></a>-->
                        <!--@endif-->
                        @if(!empty($social['linkedin']))
                            <a href="{{ $social['linkedin'] }}"><i class="fa-brands fa-linkedin-in"></i></a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-3 text-center">
                <div class="f-ul-wrapper text-start">
                    <h4 class="footer-hd">Quick Links</h4>
                    <ul class="animate">
                        @foreach($quickLinks as $link)
                            @php
                                [$href, $active] = $resolveLink($link);
                            @endphp

                            @if(!empty($link['label']) && $href !== '#')
                                <li>
                                    <a href="{{ $href }}" class="{{ $active }}">
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Helpful Links --}}
            <div class="col-lg-3 text-center">
                <div class="f-ul-wrapper text-start">
                    <h4 class="footer-hd">Helpful links</h4>
                    <ul class="animate">
                        @foreach($helpfulLinks as $link)
                            @php
                                [$href, $active] = $resolveLink($link);
                            @endphp

                            @if(!empty($link['label']) && $href !== '#')
                                <li>
                                    <a href="{{ $href }}" class="{{ $active }}">
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="col-lg-3 text-center">
                <div class="f-ul-wrapper text-start">
                    <h4 class="footer-hd">Contact Info</h4>
                    <ul class="animate">
                        @if(!empty($contact['phone']))
                            <li>
                                <a href="tel:{{ $contact['phone'] }}">
                                    <img src="{{ asset('assets/web/images/footer-contact-img1.png') }}" alt="">
                                    {{ $contact['phone'] }}
                                </a>
                            </li>
                        @endif

                        <!--@if(!empty($contact['email']))--> 
                        <!--    <li>-->
                        <!--        <a href="mailto:{{ $contact['email'] }}">-->
                        <!--            <img src="{{ asset('assets/web/images/footer-contact-img2.png') }}" alt="">-->
                        <!--            {{ $contact['email'] }}-->
                        <!--        </a>-->
                        <!--    </li>-->
                        <!--@endif-->
                            <li>
                                <a href="mailto:info@cooperativeshares.com">
                                    <img src="{{ asset('assets/web/images/footer-contact-img2.png') }}" alt="">
                                    info@cooperativeshares.com
                                </a>
                            </li>
                            <li>
                                <a href="mailto:aanderson@cooperativeshares.com">
                                    <img src="{{ asset('assets/web/images/footer-contact-img2.png') }}" alt="">
                                    aanderson@cooperativeshares.com
                                </a>
                            </li>
                            <li>
                                <a href="mailto:bfriederich@cooperativeshares.com">
                                    <img src="{{ asset('assets/web/images/footer-contact-img2.png') }}" alt="">
                                    bfriederich@cooperativeshares.com
                                </a>
                            </li>

                        @if(!empty($contact['address']))
                            <li>
                                <a class="text-white">
                                    <img src="{{ asset('assets/web/images/footer-contact-img3.png') }}" alt="">
                                    {!! $contact['address'] !!}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="bottom-bar">
            <p>{!! $bottomBar['left_text']  ?? '' !!}</p>
            <p>{!! $bottomBar['right_text'] ?? '' !!}</p>
        </div>
    </div>
</footer>


<script src="{{ asset('assets/web/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/web/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/web/js/slick.min.js') }}"></script>
<script src="{{ asset('assets/web/js/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<script>
    function togglePasswordIcon($password, $icon) {
        const password = document.getElementById($password);
        const toggleIcon = document.getElementById($icon);

        toggleIcon.addEventListener("click", () => {
            const isPassword = password.type === "password";
            password.type = isPassword ? "text" : "password";
            toggleIcon.classList.toggle("fa-eye");
            toggleIcon.classList.toggle("fa-eye-slash");
        });
    }

    togglePasswordIcon('vendor-password', 'vendorTogglePassword');
    togglePasswordIcon('user-password', 'userTogglePassword');
    togglePasswordIcon('login-password', 'login-password-toggle');

</script>

<script>
    const passwordInput2 = document.getElementById("confirmPasswordInput2");
    const toggleIcon2 = document.getElementById("togglePassword2");

    toggleIcon2.addEventListener("click", () => {
        const isPassword = passwordInput2.type === "password";
        passwordInput2.type = isPassword ? "text" : "password";
        toggleIcon2.classList.toggle("fa-eye");
        toggleIcon2.classList.toggle("fa-eye-slash");
    });
</script>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const tab = btn.getAttribute('data-tab');
            document.querySelectorAll('.step-form .tab-content').forEach(content => {
                content.classList.add('d-none');
            });
            document.getElementById(tab).classList.remove('d-none');
        });
    });

    function switchForm(type) {
        alert(`Switch to ${type} form`);
    }
</script>

<script>
    const passwordInput = document.getElementById("confirmPasswordInput");
    const toggleIcon = document.getElementById("togglePassword");

    toggleIcon.addEventListener("click", () => {
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        toggleIcon.classList.toggle("fa-eye");
        toggleIcon.classList.toggle("fa-eye-slash");
    });
</script>

<script>
    const passwordInput3 = document.getElementById("confirmPasswordInput3");
    const toggleIcon3 = document.getElementById("togglePassword3");

    toggleIcon3.addEventListener("click", () => {
        const isPassword = passwordInput3.type === "password";
        passwordInput3.type = isPassword ? "text" : "password";
        toggleIcon3.classList.toggle("fa-eye");
        toggleIcon3.classList.toggle("fa-eye-slash");
    });
</script>

{{-- Sweet Alert & Loader --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

{{-- Validation Scripts Start --}}
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
{{-- Validation Scripts End --}}

{{-- User Register Form Validation --}}
<script>
    $("#register-form").validate({
        rules: {

            email: {
                required: true,
                email: true
            },

            password: {
                required: true,
                minlength: 8
            },

            password_confirmation: {
                required: true,
                equalTo: "#user-password"
            }
        },

        messages: {

            email: {
                required: "Please Enter Your Email!",
                email: "Please Enter A Valid Email!"
            },

            password: {
                required: "Please Enter Your Password!",
                minlength: "Password Should Be Atleast 8 Characters Long!"
            },

            password_confirmation: {
                required: "Please Enter Confirm Password!",
                equalTo: "Passwords Do Not Match!"
            }
        },

        errorPlacement: function (error, element) {
            error.insertAfter($(element).closest('.input-wrapper'));
        }
    });
</script>
{{-- User Register Form Validation --}}

{{-- User Register Form Submittion --}}
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', ".wishlistbtn", function () {
        
            let id = $(this).data("listingid");
            let btn = $(this);  // ðŸ‘ˆ yeh sab ka baap fix
        
            $.ajax({
                url: "{{ route('wishlist.add', ['listing' => ':id']) }}".replace(':id', id),
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $.LoadingOverlay('show');
                },
                success: function (response) {
                    $.LoadingOverlay('hide');
        
                    if (response.status) {
                        Swal.fire({
                            title: "There's Information For You!",
                            text: response.message,
                            icon: 'info',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        });
        
                        // ðŸ‘‡ Actual icon jahan change hoga
                        let icon = btn.find("i");
        
                        icon.removeClass("fa-regular");
                        icon.addClass("fa-solid");
                    }
                },
                error: function (error) {
                    $.LoadingOverlay('hide');
                    let message = error.responseJSON?.message ?? error.statusText;
        
                    if(message == "Unauthenticated.") {
                        message = 'Please login first !';
                    }
        
                    Swal.fire({
                        title: 'Something Went Wrong!',
                        text: message,
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        $('#register-form').on('submit', function (e) {
            if (!$(this).valid()) {
                return false;
            }
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('user.register') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $.LoadingOverlay('show');
                },
                success: function (response) {
                    $.LoadingOverlay('hide');
                    if (response.status) {
                        Swal.fire({
                            title: "There's Information For You!",
                            text: response.message,
                            icon: 'info',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        })
                    }
                },
                error: function (error) {
                    $.LoadingOverlay('hide');
                    let message = (error.responseJSON?.message) ? error.responseJSON?.message : error.statusText;
                    Swal.fire({
                        title: 'Something Went Wrong!',
                        text: message,
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    });
                }
            })
        });
    });
</script>
{{-- User Register Form Submittion --}}

{{-- Vendor Register Form Validation --}}
<script>
    $("#vendor-register-form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },

            password: {
                required: true,
                minlength: 8
            },

            password_confirmation: {
                required: true,
                equalTo: "#vendor-password"
            }
        },

        messages: {

            email: {
                required: "Please Enter Your Email!",
                email: "Please Enter A Valid Email!"
            },

            password: {
                required: "Please Enter Your Password!",
                minlength: "Password Should Be Atleast 8 Characters Long!"
            },

            password_confirmation: {
                required: "Please Enter Confirm Password!",
                equalTo: "Passwords Do Not Match!"
            }
        },

        errorPlacement: function (error, element) {
            error.insertAfter($(element).closest('.input-wrapper'));
        }
    });
</script>
{{-- Vendor Register Form Validation --}}

{{-- Vendor Register Form Submittion --}}
<script>
    $(document).ready(function () {
        $('#vendor-register-form').on('submit', function (e) {
            if (!$(this).valid()) {
                return false;
            }
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('vendor.register') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $.LoadingOverlay('show');
                },
                success: function (response) {
                    $.LoadingOverlay('hide');
                    if (response.status) {
                        Swal.fire({
                            title: "There's Information For You!",
                            text: response.message,
                            icon: 'info',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        })
                    }
                },
                error: function (error) {
                    $.LoadingOverlay('hide');
                    let message = (error.responseJSON?.message) ? error.responseJSON?.message : error.statusText;
                    Swal.fire({
                        title: 'Something Went Wrong!',
                        text: message,
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    });
                }
            })
        });
    });
</script>
{{-- Vendor Register Form Submittion --}}

{{-- Login Form Validation --}}
<script>
    $("#login-form").validate({
        rules: {

            email: {
                required: true,
                email: true
            },

            password: {
                required: true,
            },
        },

        messages: {

            email: {
                required: "Please Enter Your Email!",
                email: "Please Enter A Valid Email!"
            },

            password: {
                required: "Please Enter Your Password!",
            },
        },

        errorPlacement: function (error, element) {
            error.insertAfter($(element).closest('.input-wrapper'));
        }
    });
</script>
{{-- Login Form Validation --}}

{{-- Login Form Submittion --}}
<script>
    $(document).ready(function () {
        $('#login-form').on('submit', function (e) {
            if (!$(this).valid()) {
                return false;
            }
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('user.login') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                beforeSend: function () {
                    $.LoadingOverlay('show');
                },
                success: function (response) {
                    $.LoadingOverlay('hide');
                    if (response.message) {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: 'error',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        })
                    }
                    else {
                        window.location.href = response.url;
                    }
                },
                error: function (error) {
                    $.LoadingOverlay('hide');
                    Swal.fire({
                        title: 'Something Went Wrong!',
                        text: error.statusText,
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    });
                }
            })
        });
    });
</script>
{{-- Login Form Submittion --}}

@stack('scripts')
</body>

</html>
