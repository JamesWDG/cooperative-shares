<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/web/images/logo.png') }}" type="image/x-icon">

    {{-- GOOGLE FONTS (same as frontend) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    {{-- ICONS & CSS (NOTE: /web/ not /vendor/) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/style/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/style/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/style/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/style/responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">

    <title>Cooperative Shares – Admin Login</title>

    <style>
        /* sirf errors ke liye, layout ko touch nahi kar rahe */
        label.error {
            color: #dc3545 !important;
        }

        /* background me kuch nahi chahiye, sirf body ko dark kar diya */
        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #000; /* modal backdrop ke peeche black */
        }
    </style>
</head>

<body>

    {{-- ADMIN LOGIN MODAL (exact copy of frontend login modal layout) --}}
    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content p-0 border-0">
                <div class="modal-bg" style="background-image: url('{{ asset('assets/web/images/login.png') }}');">
                    <div class="modal-overlay">

                        <!-- <button type="button" class="close-modal" data-bs-dismiss="modal" aria-label="Close">
                            &times;
                        </button> -->

                        <div class="modal-box">

                            <div class="tab-content tab-content-22 tab-content-22-login d-block">
                                <div class="right">
                                    <h2 class="sing-up-hd-md text-center login-up-hd-md">Admin Login</h2>
                                    <p class="account-para text-center">Enter your admin credentials to access the dashboard</p>

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
                                    <h2 class="welcome-hd friend-hd">Welcome, Admin!</h2>
                                    <p class="welcome-hd-para friend-hd-para">
                                        Enter your details & access the admin dashboard.
                                    </p>
                                    {{-- yahan koi SignUp nahi, sirf info --}}
                                </div>
                            </div>

                        </div> {{-- /.modal-box --}}
                    </div> {{-- /.modal-overlay --}}
                </div> {{-- /.modal-bg --}}
            </div>
        </div>
    </div>
    {{-- Login Modal stays as is --}}
    @include('includes.auth.forgot-password-modal')
    @include('includes.auth.otp-modal')
    @include('includes.auth.reset-password-modal')
    @include('includes.auth.password-updated-modal')
    {{-- JS FILES (same as frontend) --}}
    <script src="{{ asset('assets/web/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    {{-- Sweet Alert & Loader --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    {{-- jQuery Validation --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>

    <script>
        // page load hote hi admin login modal open kare
        document.addEventListener('DOMContentLoaded', function () {
            const loginModalEl = document.getElementById('exampleModal');
            if (loginModalEl) {
                const loginModal = new bootstrap.Modal(loginModalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                loginModal.show();
            }
        });

        // password eye toggle
        function togglePasswordIcon(passwordId, iconId) {
            const password = document.getElementById(passwordId);
            const toggleIcon = document.getElementById(iconId);

            if (!password || !toggleIcon) return;

            toggleIcon.addEventListener("click", () => {
                const isPassword = password.type === "password";
                password.type = isPassword ? "text" : "password";
                toggleIcon.classList.toggle("fa-eye");
                toggleIcon.classList.toggle("fa-eye-slash");
            });
        }

        togglePasswordIcon('login-password', 'login-password-toggle');

        const passwordInput2 = document.getElementById("confirmPasswordInput2");
        const toggleIcon2 = document.getElementById("togglePassword2");

        if (passwordInput2 && toggleIcon2) {
            toggleIcon2.addEventListener("click", () => {
                const isPassword = passwordInput2.type === "password";
                passwordInput2.type = isPassword ? "text" : "password";
                toggleIcon2.classList.toggle("fa-eye");
                toggleIcon2.classList.toggle("fa-eye-slash");
            });
        }

        // form validation (same rules)
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

        // admin login AJAX
        $(document).ready(function () {
            $('#login-form').on('submit', function (e) {
                if (!$(this).valid()) {
                    return false;
                }
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.login-process') }}", // yahan apna admin login route
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
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
                        } else if (response.url) {
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
{{-- Your form scripts (forgot password / ajax) --}}
@include('includes.auth.form-scripts')
</body>

</html>
