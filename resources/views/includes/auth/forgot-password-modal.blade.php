{{-- resources/views/includes/auth/forgot-password-modal.blade.php --}}

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
                                <p class="welcome-hd-para reset-para">
                                    Please enter the email address you’d like your password
                                    <br>reset information sent to
                                </p>

                                <form id="forgot-password-form">
                                    @csrf
                                    <div class="input-wrapper reset-input-wrapper">
                                        <input type="email" name="email" placeholder="E-mail">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>

                                    <button type="submit"
                                            class="reset-btn forgot-submit-btn"
                                            data-original-text="Reset password">
                                        Reset password
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
