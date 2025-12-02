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

    .propTextarea {
        min-height: 100px;
        resize: vertical;
    }

    .current-file-text {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    .preview-wrapper {
        margin-top: 8px;
    }

    .preview-wrapper img {
        width: 220px;        /* fixed width */
        height: 120px;       /* fixed height */
        object-fit: contain; /* show full image without crop */
        border-radius: 6px;
        border: 1px solid #E5E7EB;
        background: #F9FAFB;
        padding: 2px;
    }

    .preview-wrapper video {
        width: 220px;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #E5E7EB;
        background: #F9FAFB;
        padding: 2px;
    }
</style>
@endpush


@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        @php
            $heading     = is_array($sectionData) ? ($sectionData['heading'] ?? '')     : ($sectionData->heading ?? '');
            $paragraph_1 = is_array($sectionData) ? ($sectionData['paragraph_1'] ?? '') : ($sectionData->paragraph_1 ?? '');
            $paragraph_2 = is_array($sectionData) ? ($sectionData['paragraph_2'] ?? '') : ($sectionData->paragraph_2 ?? '');

            // media filenames from JSON
            $video_file = is_array($sectionData) ? ($sectionData['video_file'] ?? null) : ($sectionData->video_file ?? null);
            $image_file = is_array($sectionData) ? ($sectionData['image_file'] ?? null) : ($sectionData->image_file ?? null);

            // base path for CMS media
            $mediaBasePath = 'storage/cms/' . $page->page_key . '/' . $sectionType . '/';
        @endphp

        <form id="about-explore-properties-form"
              action="{{ route('admin.cms.about.update') }}"
              method="POST"
              class="form-submit"
              enctype="multipart/form-data">
            @csrf

            {{-- Required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            {{-- ======================
                 TEXT
            ======================= --}}
            <div class="propFormGroup">
                <label class="propLabel">Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="heading"
                       class="propInput"
                       value="{{ $heading }}"
                       placeholder="Explore Co-op Properties">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Paragraph 1 <span class="text-danger">*</span></label>
                <textarea name="paragraph_1"
                          class="propTextarea editor"
                          placeholder="Our site allows you to search for co-op homes, view detailed listings...">{{ $paragraph_1 }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Paragraph 2 <span class="text-danger">*</span></label>
                <textarea name="paragraph_2"
                          class="propTextarea editor"
                          placeholder="Experience seamless access to co-op housing options with community-focused features...">{{ $paragraph_2 }}</textarea>
            </div>

            <hr>

            {{-- ======================
                 VIDEO + IMAGE (cop section)
            ======================= --}}
            <h6 class="mb-3">Explore Properties Media (Video & Image)</h6>

            {{-- Video --}}
            <div class="propFormGroup">
                <label class="propLabel">Background Video (cop-vedio)</label>
                <input type="file"
                       name="video_file"
                       class="propInput video-input"
                       accept="video/*"
                       data-preview-target="#preview_video_file">
                <div class="current-file-text">
                    @if($video_file)
                        Current file: {{ $video_file }}
                    @else
                        Default: assets/web/images/MicrosoftTeams-video.mp4
                    @endif
                </div>
                <div class="preview-wrapper">
                    <video id="preview_video_file"
                           class="img-fluid"
                           muted
                           loop
                           controls>
                        <source src="{{ $video_file ? asset($mediaBasePath . $video_file) : null }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>

            {{-- Image --}}
            <div class="propFormGroup">
                <label class="propLabel">Overlay / Side Image (cop-img)</label>
                <input type="file"
                       name="image_file"
                       class="propInput image-input"
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       data-preview-target="#preview_image_file">
                <div class="current-file-text">
                    @if($image_file)
                        Current file: {{ $image_file }}
                    @else
                        Default: assets/web/images/cop2.png
                    @endif
                </div>
                <div class="preview-wrapper">
                    <img
                        id="preview_image_file"
                        src="{{ $image_file ? asset($mediaBasePath . $image_file) : null }}"
                        alt="Explore Properties Image Preview">
                </div>

            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Save Changes">
                Save Changes
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
    @include('includes.admin.cms.form-scripts')

    <script>
        (function () {
            // Image preview
            document.querySelectorAll('.image-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    const targetSelector = this.getAttribute('data-preview-target');
                    const previewImg = document.querySelector(targetSelector);
                    if (!previewImg) return;

                    const file = this.files && this.files[0];
                    if (file) {
                        const url = URL.createObjectURL(file);
                        previewImg.src = url;
                    }
                });
            });

            // Video preview
            document.querySelectorAll('.video-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    const targetSelector = this.getAttribute('data-preview-target');
                    const previewVideo = document.querySelector(targetSelector);
                    if (!previewVideo) return;

                    const file = this.files && this.files[0];
                    if (file) {
                        const url = URL.createObjectURL(file);
                        previewVideo.src = url;
                        previewVideo.load();
                        previewVideo.play().catch(() => {});
                    }
                });
            });
        })();
    </script>
@endpush
