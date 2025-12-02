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
        background: #F9FAFC;
    }
    .media-preview img {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }
    .block-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    @php
        $isArr = is_array($sectionData);

        $block1Heading   = $isArr ? ($sectionData['block_1_heading'] ?? '')     : ($sectionData->block_1_heading ?? '');
        $block1Paragraph = $isArr ? ($sectionData['block_1_paragraph'] ?? '')   : ($sectionData->block_1_paragraph ?? '');
        $block1BtnText   = $isArr ? ($sectionData['block_1_button_text'] ?? '') : ($sectionData->block_1_button_text ?? '');
        $block1BtnLink   = $isArr ? ($sectionData['block_1_button_link'] ?? '') : ($sectionData->block_1_button_link ?? '');
        $image1          = $isArr ? ($sectionData['image_1'] ?? null)           : ($sectionData->image_1 ?? null);

        $block2Heading   = $isArr ? ($sectionData['block_2_heading'] ?? '')     : ($sectionData->block_2_heading ?? '');
        $block2Paragraph = $isArr ? ($sectionData['block_2_paragraph'] ?? '')   : ($sectionData->block_2_paragraph ?? '');
        $block2BtnText   = $isArr ? ($sectionData['block_2_button_text'] ?? '') : ($sectionData->block_2_button_text ?? '');
        $block2BtnLink   = $isArr ? ($sectionData['block_2_button_link'] ?? '') : ($sectionData->block_2_button_link ?? '');
        $image2          = $isArr ? ($sectionData['image_2'] ?? null)           : ($sectionData->image_2 ?? null);

        $mediaBasePath   = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="home-advertise-form"
              action="{{ route('admin.cms.home.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="block-title">Block 1 – List Your Properties</div>
                    <div class="propFormGroup">
                        <label class="propLabel">Heading <span class="text-danger">*</span></label>
                        <input type="text"
                               name="block_1_heading"
                               class="propInput"
                               value="{{ $block1Heading }}"
                               placeholder="List Your Properties">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Paragraph <span class="text-danger">*</span></label>
                        <textarea name="block_1_paragraph"
                                  class="propTextarea editor"
                                  placeholder="Reach potential buyers nationwide...">{{ $block1Paragraph }}</textarea>
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Button Text</label>
                        <input type="text"
                               name="block_1_button_text"
                               class="propInput"
                               value="{{ $block1BtnText }}"
                               placeholder="Show Your Co-op Homes for Sale">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Button Link</label>
                        <input type="text"
                               name="block_1_button_link"
                               class="propInput"
                               value="{{ $block1BtnLink }}"
                               placeholder="#">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Image 1</label>
                        <input type="file" name="image_1" class="propInput" accept="image/*">
                        @if($image1)
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.$image1) }}" alt="Block 1 Image Preview">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="block-title">Block 2 – Manage Your Properties</div>
                    <div class="propFormGroup">
                        <label class="propLabel">Heading <span class="text-danger">*</span></label>
                        <input type="text"
                               name="block_2_heading"
                               class="propInput"
                               value="{{ $block2Heading }}"
                               placeholder="Manage Your Properties">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Paragraph <span class="text-danger">*</span></label>
                        <textarea name="block_2_paragraph"
                                  class="propTextarea editor"
                                  placeholder="Track inquiries, showcase units, and analyze performance...">{{ $block2Paragraph }}</textarea>
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Button Text</label>
                        <input type="text"
                               name="block_2_button_text"
                               class="propInput"
                               value="{{ $block2BtnText }}"
                               placeholder="Manage Affordable Housing Listings">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Button Link</label>
                        <input type="text"
                               name="block_2_button_link"
                               class="propInput"
                               value="{{ $block2BtnLink }}"
                               placeholder="#">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Image 2</label>
                        <input type="file" name="image_2" class="propInput" accept="image/*">
                        @if($image2)
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.$image2) }}" alt="Block 2 Image Preview">
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
