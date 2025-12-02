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
    <h2 class="dashboard-hd">Edit Review</h2>

    <div class="propFormContainer">
        <form action="{{ route('admin.review.update', $review->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf
            @method('PUT')

            <div class="propFormGroup">
                <label class="propLabel">Client Name <span class="text-danger">*</span></label>
                <input type="text" name="client_name" class="propInput"
                       value="{{ $review->client_name }}" required>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Client Role <span class="text-danger">*</span></label>
                <input type="text" name="client_role" class="propInput"
                       value="{{ $review->client_role }}" required>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Rating <span class="text-danger">*</span></label>
                <select name="rating" class="propSelect" required>
                    <option value="5" {{ $review->rating == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4" {{ $review->rating == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4)</option>
                    <option value="3" {{ $review->rating == 3 ? 'selected' : '' }}>⭐⭐⭐ (3)</option>
                    <option value="2" {{ $review->rating == 2 ? 'selected' : '' }}>⭐⭐ (2)</option>
                    <option value="1" {{ $review->rating == 1 ? 'selected' : '' }}>⭐ (1)</option>
                </select>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Client Image</label>
                <input type="file" name="client_image" class="propInput" id="client_image">

                @if($review->client_image)
                    <img id="imagePreview"
                         src="{{ asset('storage/reviews/'.$review->client_image) }}"
                         class="preview-img"
                         alt="Client Image">
                @else
                    <img id="imagePreview" class="preview-img" style="display:none;" alt="Client Image">
                @endif

                <!-- <small class="text-muted d-block mt-1">
                    Uploading a new image will replace the existing one. Allowed types: jpg, jpeg, png | Max size: 2MB
                </small> -->
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Review Text <span class="text-danger">*</span></label>
                <textarea name="review_text" class="propTextarea" rows="5" required>{{ $review->review_text }}</textarea>
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Update Review">
                Update Review
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Image preview (on change)
    $('#client_image').on('change', function () {
        const file = this.files[0];
        if (!file) {
            return; // keep old if no new selected
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            $('#imagePreview').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
    });
</script>
@include('includes.admin.form-scripts')
@endpush
