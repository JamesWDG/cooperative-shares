<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"/>

<script>
    const REDIRECT_URL = "{{ $redirect_url ?? '' }}";
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

    // ---- NO IMAGE PREVIEW CODE HERE ---- //
    // AJAX SUBMIT (works for create + edit both)
    $(document).on('click', '.update-btn', function (e) {
        e.preventDefault();

        const $btn  = $(this);
        const form  = $btn.closest('form')[0];

        // 🔹 CKEditor 5: push editor content back into their textareas before FormData
        if (window._ck5) {
            for (const id in window._ck5) {
                if (!Object.prototype.hasOwnProperty.call(window._ck5, id)) continue;

                const editor   = window._ck5[id];
                const textarea = document.getElementById(id);

                if (editor && textarea) {
                    textarea.value = editor.getData();
                }
            }
        }

        const formData = new FormData(form);

        const originalText = $btn.data('original-text') || 'Save';
        $btn.prop('disabled', true).text('Please wait...');

        $.LoadingOverlay("show");

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $.LoadingOverlay("hide");

                if (response.status) {
                    showToastjQuery("Success", response.msg, "success");

                    setTimeout(() => {
                        location.reload();
                        //window.location.href = REDIRECT_URL;
                    }, 1000);
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

</script>
