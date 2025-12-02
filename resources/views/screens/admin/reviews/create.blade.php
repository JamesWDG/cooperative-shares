@extends('layouts.admin.app')

@push('styles')
<style>
    .propFormContainer {
        margin-top: 25px;
        background: #fff;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }

    .propFormGroup {
        margin-bottom: 18px;
    }

    .propLabel {
        display: block;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .propInput,
    .propSelect,
    .propTextarea {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 8px 10px;
        font-size: 14px;
    }

    .preview-img {
        margin-top: 10px;
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e4e4e4;
        display: none;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <h2 class="dashboard-hd">Add New Review</h2>

    <div class="propFormContainer">
        <form action="{{ route('admin.review.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <div class="propFormGroup">
                <label class="propLabel">Client Name <span class="text-danger">*</span></label>
                <input type="text" name="client_name" class="propInput" required>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Client Role <span class="text-danger">*</span></label>
                <input type="text" name="client_role" class="propInput" required>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Rating <span class="text-danger">*</span></label>
                <select name="rating" class="propSelect" required>
                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4">⭐⭐⭐⭐ (4)</option>
                    <option value="3">⭐⭐⭐ (3)</option>
                    <option value="2">⭐⭐ (2)</option>
                    <option value="1">⭐ (1)</option>
                </select>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Client Image</label>
                <input type="file" name="client_image" class="propInput" id="client_image">
                <img id="imagePreview" class="preview-img" alt="Preview">
                <small class="text-muted d-block mt-1">Allowed types: jpg, jpeg, png | Max size: 2MB</small>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Review Text <span class="text-danger">*</span></label>
                <textarea name="review_text" class="propTextarea" rows="5" required></textarea>
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Save Review">
                Save Review
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
@include('includes.admin.form-scripts',[
    'redirect_url' => route('admin.reviews')
])
<script>
    // Image preview
    $('#client_image').on('change', function () {
        const file = this.files[0];
        if (!file) {
            $('#imagePreview').hide();
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            $('#imagePreview').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
