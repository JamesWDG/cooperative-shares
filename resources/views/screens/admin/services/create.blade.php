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
    <h2 class="dashboard-hd">Add New Service</h2>

    <div class="propFormContainer">
        <form action="{{ route('admin.service.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <div class="propFormGroup">
                <label class="propLabel">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="propInput" required>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Short Description</label>
                <textarea name="short_des" class="propTextarea editor" rows="3"></textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Long Description</label>
                <textarea name="long_des" class="propTextarea editor" rows="6"></textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Featured Image <span class="text-danger">*</span></label>
                <input type="file" name="featured_img" class="propInput" id="featured_img">
                <img id="featuredPreview" class="preview-img" alt="Featured Preview">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Background Image</label>
                <input type="file" name="background_img" class="propInput" id="background_img">
                <img id="backgroundPreview" class="preview-img" alt="Background Preview">
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Save Service">
                Save Service
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
@include('includes.admin.form-scripts',[
    'redirect_url' => route('admin.services')
])
<script>
    function previewImage(inputSelector, imgSelector) {
        const input = document.querySelector(inputSelector);
        const img   = document.querySelector(imgSelector);

        if (!input || !img) return;

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) {
                img.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    previewImage('#featured_img', '#featuredPreview');
    previewImage('#background_img', '#backgroundPreview');
</script>
@endpush
