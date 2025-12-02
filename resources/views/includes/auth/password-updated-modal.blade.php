<div class="modal fade" id="modalOverlayUpdated" tabindex="-1">
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
                            <div class="Reset-Password-box updated-password-box">
                                <div class="updated-circle">
                                    <i class="fa-solid fa-check"></i>
                                </div>

                                <h2 class="sing-up-hd-md text-center password-updated-hd">
                                    Password Updated
                                </h2>

                                <p class="welcome-hd-para reset-para">
                                    Your new password has been changed successfully.
                                    <br> Use your new password to login
                                </p>

                                <a href="#"
                                   data-bs-toggle="modal"
                                   data-bs-target="#exampleModal"
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
