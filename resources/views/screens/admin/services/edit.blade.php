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
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <h2 class="dashboard-hd">Edit Service</h2>

    <div class="propFormContainer">
        <form action="{{ route('admin.service.update', $service->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf
            @method('PUT')

            <div class="propFormGroup">
                <label class="propLabel">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="propInput"
                       value="{{ $service->title }}" required>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Short Description</label>
                <textarea name="short_des" class="propTextarea editor" rows="3">{{ $service->short_des }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Long Description</label>
                <textarea name="long_des" class="propTextarea editor" rows="6">{{ $service->long_des }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Featured Image</label>
                <input type="file" name="featured_img" class="propInput" id="featured_img">

                @if($service->featured_img)
                    <img id="featuredPreview"
                         src="{{ asset('storage/services/'.$service->featured_img) }}"
                         class="preview-img"
                         alt="Featured Image">
                @else
                    <img id="featuredPreview" class="preview-img" style="display:none;" alt="Featured Image">
                @endif
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Background Image</label>
                <input type="file" name="background_img" class="propInput" id="background_img">

                @if($service->background_img)
                    <img id="backgroundPreview"
                         src="{{ asset('storage/services/'.$service->background_img) }}"
                         class="preview-img"
                         alt="Background Image">
                @else
                    <img id="backgroundPreview" class="preview-img" style="display:none;" alt="Background Image">
                @endif
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Update Service">
                Update Service
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function previewImage(inputSelector, imgSelector) {
        const input = document.querySelector(inputSelector);
        const img   = document.querySelector(imgSelector);

        if (!input || !img) return;

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) {
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
@include('includes.admin.form-scripts')
@endpush
