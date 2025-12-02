{{-- resources/views/includes/auth/form-scripts.blade.php --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"/>

<script>
    function showToastjQuery(title_text, message, iconClass) {
        $.toast({
            heading: title_text,
            text: message,
            position: 'top-right',
            showHideTransition: 'slide',
            icon: iconClass,
            loaderBg: '#30809B',
        });
    }
</script>

<script>
    let forgotEmailGlobal = null;
    let forgotOtpGlobal    = null;

    // -------------------------------
    // Step 1: Forgot Password (send OTP)
    // -------------------------------
    $(document).on('submit', '#forgot-password-form', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $form.find('.forgot-submit-btn');
        const originalText = $btn.data('original-text') || 'Reset password';

        $btn.prop('disabled', true).text('Please wait...');

        $.LoadingOverlay("show");

        $.ajax({
            url: "{{ route('password.forgot.sendOtp') }}",
            type: 'POST',
            data: $form.serialize(),
            success: function (response) {
                $.LoadingOverlay("hide");

                if (response.status) {
                    showToastjQuery("Success", response.msg, "success");

                    // Save email for later steps
                    forgotEmailGlobal = $form.find('input[name="email"]').val();
                    $('#otpEmailField').val(forgotEmailGlobal);

                    setTimeout(function () {
                        $('#modalOverlay').modal('hide');      // forgot email modal
                        $('#modalOverlayOtp').modal('show');   // OTP modal
                    }, 800);
                } else {
                    $btn.prop('disabled', false).text(originalText);
                    showToastjQuery("Error", response.msg || "Something went wrong.", "error");
                }
            },
            error: function (xhr) {
                $.LoadingOverlay("hide");
                $btn.prop('disabled', false).text(originalText);

                let message = "Unexpected error.";
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.msg) {
                    message = xhr.responseJSON.msg;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                showToastjQuery("Validation Error", message, "error");
            }
        });
    });

    // -------------------------------
    // Step 2: Verify OTP
    // -------------------------------
    $(document).on('submit', '#verify-otp-form', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $form.find('.otp-submit-btn');
        const originalText = $btn.data('original-text') || 'Verify & Continue';

        $btn.prop('disabled', true).text('Verifying...');

        $.LoadingOverlay("show");

        // Ensure email is set
        if (!forgotEmailGlobal) {
            forgotEmailGlobal = $('#otpEmailField').val();
        }

        $.ajax({
            url: "{{ route('password.forgot.verifyOtp') }}",
            type: 'POST',
            data: $form.serialize(),
            success: function (response) {
                $.LoadingOverlay("hide");

                if (response.status) {
                    showToastjQuery("Success", response.msg, "success");

                    // Save OTP globally & pass it to Reset form hidden field
                    forgotOtpGlobal = $form.find('input[name="otp"]').val();

                    $('#resetEmailField').val(forgotEmailGlobal);
                    $('#resetOtpField').val(forgotOtpGlobal);

                    setTimeout(function () {
                        $('#modalOverlayOtp').modal('hide');
                        $('#modalOverlayReset').modal('show');
                    }, 800);
                } else {
                    $btn.prop('disabled', false).text(originalText);
                    showToastjQuery("Error", response.msg || "Invalid OTP.", "error");
                }
            },
            error: function (xhr) {
                $.LoadingOverlay("hide");
                $btn.prop('disabled', false).text(originalText);

                let message = "Unexpected error.";
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.msg) {
                    message = xhr.responseJSON.msg;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                showToastjQuery("Validation Error", message, "error");
            }
        });
    });

    // -------------------------------
    // Step 3: Reset Password
    // -------------------------------
    $(document).on('submit', '#reset-password-form', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $form.find('.reset-submit-btn');
        const originalText = $btn.data('original-text') || 'Reset password';

        $btn.prop('disabled', true).text('Updating...');

        $.LoadingOverlay("show");

        // Ensure email & otp hidden fields have values
        if (!$('#resetEmailField').val() && forgotEmailGlobal) {
            $('#resetEmailField').val(forgotEmailGlobal);
        }
        if (!$('#resetOtpField').val() && forgotOtpGlobal) {
            $('#resetOtpField').val(forgotOtpGlobal);
        }

        $.ajax({
            url: "{{ route('password.forgot.reset') }}",
            type: 'POST',
            data: $form.serialize(),
            success: function (response) {
                $.LoadingOverlay("hide");

                if (response.status) {
                    showToastjQuery("Success", response.msg, "success");

                    // Clear globals for safety
                    forgotEmailGlobal = null;
                    forgotOtpGlobal   = null;

                    // Close reset modal & show success modal
                    setTimeout(function () {
                        $('#modalOverlayReset').modal('hide');
                        $('#modalOverlayUpdated').modal('show');
                    }, 800);
                } else {
                    $btn.prop('disabled', false).text(originalText);
                    showToastjQuery("Error", response.msg || "Unable to reset password.", "error");
                }
            },
            error: function (xhr) {
                $.LoadingOverlay("hide");
                $btn.prop('disabled', false).text(originalText);

                let message = "Unexpected error.";
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.msg) {
                    message = xhr.responseJSON.msg;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                showToastjQuery("Validation Error", message, "error");
            }
        });
    });
</script>
<script>
    // Generic eye toggle for any .toggle-password iconss
    $(document).on('click', '.toggle-password', function () {
        const $icon  = $(this);
        const target = $icon.data('target');
        const $input = $(target);

        if (!$input.length) return;

        const isPassword = $input.attr('type') === 'password';

        $input.attr('type', isPassword ? 'text' : 'password');

        // Swap eye / eye-slash icons
        $icon.toggleClass('fa-eye fa-eye-slash');
    });
</script>

