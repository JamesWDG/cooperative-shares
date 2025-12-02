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
    .propTextarea {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 8px 10px;
        font-size: 14px;
    }

    .preview-video {
        margin-top: 10px;
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e4e4e4;
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
    <h2 class="dashboard-hd">Add New Training Video</h2>

    <div class="propFormContainer">
        <form action="{{ route('admin.training-video.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <div class="propFormGroup">
                <label class="propLabel">Video Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="propInput" required>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Upload Video <span class="text-danger">*</span></label>
                <input type="file" name="video" class="propInput" id="video-file" required>
                <video id="videoPreview" class="preview-video" controls style="display: none;"></video>
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
    'redirect_url' => route('admin.training-videos')
])
<script>
    // Preview Video before submitting
    document.getElementById("video-file").addEventListener("change", function (e) {
        const file = e.target.files[0];
        const videoPreview = document.getElementById("videoPreview");

        if (file && file.type.startsWith('video/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                videoPreview.src = e.target.result;
                videoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
