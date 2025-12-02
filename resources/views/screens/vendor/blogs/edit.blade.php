@extends('layouts.vendor.app')

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
    <h2 class="dashboard-hd">Edit Co-Op</h2>

    <div class="propFormContainer">
        <form action="{{ route('vendor.blog.update', $blog->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf
            @method('PUT')

            <div class="propFormGroup">
                <label class="propLabel">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="propInput"
                       value="{{ $blog->title }}" required>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Read in Minutes</label>
                <input type="number" name="read_in_minutes" class="propInput"
                       min="1"
                       value="{{ $blog->read_in_minutes }}">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Short Description</label>
                <textarea name="short_des" class="propTextarea editor" rows="3">{{ $blog->short_des }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Long Description</label>
                <textarea name="long_des" class="propTextarea editor" rows="6">{{ $blog->long_des }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Featured Image</label>
                <input type="file" name="featured_img" class="propInput" id="featured_img">

                @if($blog->featured_img)
                    <img id="featuredPreview"
                         src="{{ asset('storage/vendor-blogs/'.$blog->featured_img) }}"
                         class="preview-img"
                         alt="Featured Image">
                @else
                    <img id="featuredPreview" class="preview-img" style="display:none;" alt="Featured Image">
                @endif
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Update Co-Op">
                Update Co-Op
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
</script>
@include('includes.admin.form-scripts')
@endpush
