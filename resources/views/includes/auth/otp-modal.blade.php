<div class="modal fade" id="modalOverlayOtp" tabindex="-1">
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
                                <h2 class="sing-up-hd-md text-center login-up-hd-md">Verify OTP</h2>
                                <p class="welcome-hd-para reset-para">
                                    Enter the OTP sent to your email.
                                </p>

                                <form id="verify-otp-form">
                                    @csrf

                                    {{-- hidden email filled from JS --}}
                                    <input type="hidden" name="email" id="otpEmailField">

                                    <div class="input-wrapper reset-input-wrapper">
                                        <input type="number"
                                               name="otp"
                                               placeholder="Enter OTP"
                                               maxlength="6">
                                        <i class="fa-solid fa-key"></i>
                                    </div>

                                    <button type="submit"
                                            class="reset-btn reset-btn2 otp-submit-btn"
                                            data-original-text="Verify & Continue">
                                        Verify & Continue
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
