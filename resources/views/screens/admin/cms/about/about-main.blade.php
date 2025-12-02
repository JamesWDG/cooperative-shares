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

    .current-image-text {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    .preview-wrapper {
        margin-top: 8px;
    }

    .preview-wrapper img {
        max-height: 80px;
        border-radius: 6px;
        border: 1px solid #E5E7EB;
        padding: 2px;
        background: #F9FAFB;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        @php
            // Text fields
            $heading_prefix    = is_array($sectionData) ? ($sectionData['heading_prefix'] ?? '')    : ($sectionData->heading_prefix ?? '');
            $heading_highlight = is_array($sectionData) ? ($sectionData['heading_highlight'] ?? '') : ($sectionData->heading_highlight ?? '');
            $tagline           = is_array($sectionData) ? ($sectionData['tagline'] ?? '')           : ($sectionData->tagline ?? '');
            $paragraph_1       = is_array($sectionData) ? ($sectionData['paragraph_1'] ?? '')       : ($sectionData->paragraph_1 ?? '');
            $paragraph_2       = is_array($sectionData) ? ($sectionData['paragraph_2'] ?? '')       : ($sectionData->paragraph_2 ?? '');
            $paragraph_3       = is_array($sectionData) ? ($sectionData['paragraph_3'] ?? '')       : ($sectionData->paragraph_3 ?? '');

            // Main images (filenames only)
            $image_1 = is_array($sectionData) ? ($sectionData['image_1'] ?? null) : ($sectionData->image_1 ?? null);
            $image_2 = is_array($sectionData) ? ($sectionData['image_2'] ?? null) : ($sectionData->image_2 ?? null);
            $image_3 = is_array($sectionData) ? ($sectionData['image_3'] ?? null) : ($sectionData->image_3 ?? null);

            // Box 1
            $box1 = is_array($sectionData) ? ($sectionData['box_1'] ?? []) : ($sectionData->box_1 ?? []);
            $box1_title       = is_array($box1) ? ($box1['title'] ?? '')       : ($box1->title ?? '');
            $box1_description = is_array($box1) ? ($box1['description'] ?? '') : ($box1->description ?? '');
            $box1_logo        = is_array($box1) ? ($box1['logo'] ?? null)      : ($box1->logo ?? null);

            // Box 2
            $box2 = is_array($sectionData) ? ($sectionData['box_2'] ?? []) : ($sectionData->box_2 ?? []);
            $box2_title       = is_array($box2) ? ($box2['title'] ?? '')       : ($box2->title ?? '');
            $box2_description = is_array($box2) ? ($box2['description'] ?? '') : ($box2->description ?? '');
            $box2_logo        = is_array($box2) ? ($box2['logo'] ?? null)      : ($box2->logo ?? null);

            // 🔹 Base path for previews (matches your $uploadPath in controller)
            $imageBasePath = 'storage/cms/' . $page->page_key . '/' . $sectionType . '/';
        @endphp

        <form id="about-main-form"
              action="{{ route('admin.cms.about.update') }}"
              method="POST"
              class="form-submit"
              enctype="multipart/form-data">
            @csrf

            {{-- Required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            {{-- ======================
                 TEXT FIELDS
            ======================= --}}
            <div class="propFormGroup">
                <label class="propLabel">Heading Prefix</label>
                <input type="text"
                       name="heading_prefix"
                       class="propInput"
                       value="{{ $heading_prefix }}"
                       placeholder="About">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Heading Highlight</label>
                <input type="text"
                       name="heading_highlight"
                       class="propInput"
                       value="{{ $heading_highlight }}"
                       placeholder="CooperativeShares.com">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Tagline / Mini Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="tagline"
                       class="propInput"
                       value="{{ $tagline }}"
                       placeholder="We Build Communities Through a Reliable Co-op Marketplace">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Paragraph 1</label>
                <textarea name="paragraph_1"
                          class="propTextarea editor"
                          placeholder="Main introductory paragraph...">{{ $paragraph_1 }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Paragraph 2</label>
                <textarea name="paragraph_2"
                          class="propTextarea editor"
                          placeholder="Second paragraph...">{{ $paragraph_2 }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Paragraph 3</label>
                <textarea name="paragraph_3"
                          class="propTextarea editor"
                          placeholder="Third paragraph...">{{ $paragraph_3 }}</textarea>
            </div>

            <hr>

            {{-- ======================
                 MAIN IMAGES
            ======================= --}}
            <h6 class="mb-3">Main Images</h6>

            {{-- Main Image 1 --}}
            <div class="propFormGroup">
                <label class="propLabel">
                    Main Image 1 (left of the two center images)
                </label>
                <input type="file"
                       name="main_image_1"
                       class="propInput image-input"
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       data-preview-target="#preview_main_image_1">
                <div class="current-image-text">
                    @if($image_1)
                        Current file: {{ $image_1 }}
                    @else
                        Default: assets/web/images/about-1.png
                    @endif
                </div>
                <div class="preview-wrapper">
                    <img
                        id="preview_main_image_1"
                        src="{{ $image_1 ? asset($imageBasePath . $image_1) : asset('assets/web/images/about-1.png') }}"
                        alt="Main Image 1 Preview">
                </div>
            </div>

            {{-- Main Image 2 --}}
            <div class="propFormGroup">
                <label class="propLabel">
                    Main Image 2 (right of the two center images)
                </label>
                <input type="file"
                       name="main_image_2"
                       class="propInput image-input"
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       data-preview-target="#preview_main_image_2">
                <div class="current-image-text">
                    @if($image_2)
                        Current file: {{ $image_2 }}
                    @else
                        Default: assets/web/images/about-2.png
                    @endif
                </div>
                <div class="preview-wrapper">
                    <img
                        id="preview_main_image_2"
                        src="{{ $image_2 ? asset($imageBasePath . $image_2) : asset('assets/web/images/about-2.png') }}"
                        alt="Main Image 2 Preview">
                </div>
            </div>

            {{-- Main Image 3 --}}
            <div class="propFormGroup">
                <label class="propLabel">
                    Side Image (right column image)
                </label>
                <input type="file"
                       name="main_image_3"
                       class="propInput image-input"
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       data-preview-target="#preview_main_image_3">
                <div class="current-image-text">
                    @if($image_3)
                        Current file: {{ $image_3 }}
                    @else
                        Default: assets/web/images/about-3.png
                    @endif
                </div>
                <div class="preview-wrapper">
                    <img
                        id="preview_main_image_3"
                        src="{{ $image_3 ? asset($imageBasePath . $image_3) : asset('assets/web/images/about-3.png') }}"
                        alt="Main Image 3 Preview">
                </div>
            </div>

            <hr>

            {{-- ======================
                 BOX 1
            ======================= --}}
            <h6 class="mb-3">Box 1 (logo + title + description)</h6>

            <div class="propFormGroup">
                <label class="propLabel">Box 1 Logo</label>
                <input type="file"
                       name="box_1_logo"
                       class="propInput image-input"
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       data-preview-target="#preview_box_1_logo">
                <div class="current-image-text">
                    @if($box1_logo)
                        Current file: {{ $box1_logo }}
                    @else
                        Default: assets/web/images/about-box-img1.png
                    @endif
                </div>
                <div class="preview-wrapper">
                    <img
                        id="preview_box_1_logo"
                        src="{{ $box1_logo ? asset($imageBasePath . $box1_logo) : asset('assets/web/images/about-box-img1.png') }}"
                        alt="Box 1 Logo Preview">
                </div>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Box 1 Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="box_1_title"
                       class="propInput"
                       value="{{ $box1_title }}"
                       placeholder="Easy Listings">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Box 1 Description</label>
                <textarea name="box_1_description"
                          class="propTextarea"
                          placeholder="Quickly post your co-op homes for sale...">{{ $box1_description }}</textarea>
            </div>

            <hr>

            {{-- ======================
                 BOX 2
            ======================= --}}
            <h6 class="mb-3">Box 2 (logo + title + description)</h6>

            <div class="propFormGroup">
                <label class="propLabel">Box 2 Logo</label>
                <input type="file"
                       name="box_2_logo"
                       class="propInput image-input"
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       data-preview-target="#preview_box_2_logo">
                <div class="current-image-text">
                    @if($box2_logo)
                        Current file: {{ $box2_logo }}
                    @else
                        Default: assets/web/images/about-box-img3.png
                    @endif
                </div>
                <div class="preview-wrapper">
                    <img
                        id="preview_box_2_logo"
                        src="{{ $box2_logo ? asset($imageBasePath . $box2_logo) : asset('assets/web/images/about-box-img3.png') }}"
                        alt="Box 2 Logo Preview">
                </div>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Box 2 Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="box_2_title"
                       class="propInput"
                       value="{{ $box2_title }}"
                       placeholder="Buyer Support">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Box 2 Description</label>
                <textarea name="box_2_description"
                          class="propTextarea"
                          placeholder="Assistance for senior cooperative housing seekers...">{{ $box2_description }}</textarea>
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

    <script>
        (function () {
            // Live image preview for file inputs
            document.querySelectorAll('.image-input').forEach(function (input) {
                input.addEventListener('change', function (e) {
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
        })();
    </script>
@endpush
