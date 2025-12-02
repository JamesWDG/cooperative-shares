<div class="modal fade" id="modalOverlayReset" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content p-0 border-0">
            <div class="modal-bg" style="background-image: url('{{ asset('assets/web/images/login.png') }}');">
                <div class="modal-overlay">

                    <button type="button"
                            class="close-modal"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        &times;
                    </button>

                    <div class="modal-box">
                        <div class="tab-content tab-content-22 d-block">
                            <div class="Reset-Password-box">
                                <h2 class="sing-up-hd-md text-center login-up-hd-md">Reset Password</h2>
                                <p class="welcome-hd-para reset-para">
                                    Enter your new password below.
                                </p>

                                <form id="reset-password-form">
                                    @csrf

                                    {{-- hidden fields populated via JS --}}
                                    <input type="hidden" name="email" id="resetEmailField">
                                    <input type="hidden" name="otp" id="resetOtpField">

                                    <div class="input-wrapper reset-input-wrapper">
                                        <input type="password"
                                               name="password"
                                               id="resetPasswordInput"
                                               placeholder="Password">
                                        <i class="fa-solid fa-eye toggle-password"
                                           data-target="#resetPasswordInput"></i>
                                    </div>
                                    
                                    <div class="input-wrapper reset-input-wrapper">
                                        <input type="password"
                                               name="password_confirmation"
                                               id="confirmPasswordInput2"
                                               placeholder="Confirm Password">
                                        <i class="fa-solid fa-eye toggle-password"
                                           id="togglePassword2"
                                           data-target="#confirmPasswordInput2"></i>
                                    </div>

                                    <button type="submit"
                                            class="reset-btn reset-btn2 reset-submit-btn"
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
