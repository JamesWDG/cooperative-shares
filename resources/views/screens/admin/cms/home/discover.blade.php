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
    .propFormGroup { margin-bottom: 18px; }
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
    .media-preview {
        margin-top: 8px;
        border: 1px solid #E3E7F0;
        border-radius: 8px;
        padding: 6px;
        max-width: 260px;
        background: rgba(3, 10, 27, 1); /* DARK for IMAGES */
    }

    .video-media-preview {
        margin-top: 8px;
        border: 1px solid #E3E7F0;
        border-radius: 8px;
        padding: 6px;
        max-width: 260px;
        background: #F9FAFC; /* LIGHT for VIDEOS */
    }

    .media-preview img,
    .video-media-preview video {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }

</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    @php
        $isArr       = is_array($sectionData);
        $miniHeading = $isArr ? ($sectionData['mini_heading'] ?? '') : ($sectionData->mini_heading ?? '');
        $subHeading  = $isArr ? ($sectionData['sub_heading'] ?? '')  : ($sectionData->sub_heading ?? '');
        $heading     = $isArr ? ($sectionData['heading'] ?? '')      : ($sectionData->heading ?? '');
        $paragraph   = $isArr ? ($sectionData['paragraph'] ?? '')    : ($sectionData->paragraph ?? '');
        $buttonText  = $isArr ? ($sectionData['button_text'] ?? '')  : ($sectionData->button_text ?? '');

        $videoFile   = $isArr ? ($sectionData['video_file'] ?? null)     : ($sectionData->video_file ?? null);
        $overlayImg  = $isArr ? ($sectionData['overlay_image'] ?? null)  : ($sectionData->overlay_image ?? null);

        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="home-discover-form"
              action="{{ route('admin.cms.home.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="propFormGroup">
                <label class="propLabel">Mini Heading</label>
                <input type="text"
                       name="mini_heading"
                       class="propInput"
                       value="{{ $miniHeading }}"
                       placeholder="How It Works">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Sub Heading</label>
                <input type="text"
                       name="sub_heading"
                       class="propInput"
                       value="{{ $subHeading }}"
                       placeholder="Browse Listing, Post Your Listing, Get Noticed, and Connect & Transact.">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Main Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="heading"
                       class="propInput"
                       value="{{ $heading }}"
                       placeholder="Why Choose CooperativeShares.com?">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Paragraph <span class="text-danger">*</span></label>
                <textarea name="paragraph"
                          class="propTextarea editor"
                          placeholder="We connect communities nationwide with cooperative housing opportunities...">{{ $paragraph }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Button Text</label>
                <input type="text"
                       name="button_text"
                       class="propInput"
                       value="{{ $buttonText }}"
                       placeholder="Start Your Search Now">
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Background Video</label>
                        <input type="file" name="video_file" class="propInput" accept="video/*">
                        @if($videoFile)
                            <div class="video-media-preview">
                                <video src="{{ asset($mediaBasePath.$videoFile) }}" controls></video>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Overlay Image (Right Side)</label>
                        <input type="file" name="overlay_image" class="propInput" accept="image/*">
                        @if($overlayImg)
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.$overlayImg) }}" alt="Overlay Image Preview">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <button type="button"
                    class="btn btn-primary update-btn mt-3"
                    data-original-text="Save Changes">
                Save Changes
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
    @include('includes.admin.cms.form-scripts')
@endpush
