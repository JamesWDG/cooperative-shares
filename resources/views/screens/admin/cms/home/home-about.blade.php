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
    .box-heading {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 6px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    @php
        $isArr       = is_array($sectionData);
        $miniHeading = $isArr ? ($sectionData['mini_heading'] ?? '')   : ($sectionData->mini_heading ?? '');
        $mainHeading = $isArr ? ($sectionData['main_heading'] ?? '')   : ($sectionData->main_heading ?? '');
        $paragraph   = $isArr ? ($sectionData['paragraph'] ?? '')      : ($sectionData->paragraph ?? '');

        $box1 = $isArr ? ($sectionData['box_1'] ?? []) : ($sectionData->box_1 ?? []);
        $box2 = $isArr ? ($sectionData['box_2'] ?? []) : ($sectionData->box_2 ?? []);
        $box3 = $isArr ? ($sectionData['box_3'] ?? []) : ($sectionData->box_3 ?? []);
        $box4 = $isArr ? ($sectionData['box_4'] ?? []) : ($sectionData->box_4 ?? []);

        if (!is_array($box1)) $box1 = (array) $box1;
        if (!is_array($box2)) $box2 = (array) $box2;
        if (!is_array($box3)) $box3 = (array) $box3;
        if (!is_array($box4)) $box4 = (array) $box4;

        $aboutImage = $isArr ? ($sectionData['about_image'] ?? null) : ($sectionData->about_image ?? null);

        $moreAboutText = $isArr ? ($sectionData['more_about_btn_text'] ?? '') : ($sectionData->more_about_btn_text ?? '');
        $moreAboutLink = $isArr ? ($sectionData['more_about_btn_link'] ?? '') : ($sectionData->more_about_btn_link ?? '');
        $phoneText     = $isArr ? ($sectionData['phone_text'] ?? '')          : ($sectionData->phone_text ?? '');
        $phoneNumber   = $isArr ? ($sectionData['phone_number'] ?? '')        : ($sectionData->phone_number ?? '');

        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="home-about-form"
              action="{{ route('admin.cms.home.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Mini Heading</label>
                        <input type="text"
                               name="mini_heading"
                               class="propInput"
                               value="{{ $miniHeading }}"
                               placeholder="About Us">
                    </div>

                    <div class="propFormGroup">
                        <label class="propLabel">Main Heading <span class="text-danger">*</span></label>
                        <input type="text"
                               name="main_heading"
                               class="propInput"
                               value="{{ $mainHeading }}"
                               placeholder="Empowering Communities Through Reliable Co-op Marketplace">
                    </div>

                    <div class="propFormGroup">
                        <label class="propLabel">Main Paragraph <span class="text-danger">*</span></label>
                        <textarea name="paragraph"
                                  class="propTextarea editor"
                                  placeholder="CooperativeShares.com provides a housing cooperative marketplace...">{{ $paragraph }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Left About Image</label>
                        <input type="file" name="about_image" class="propInput" accept="image/*">
                        @if($aboutImage)
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.$aboutImage) }}" alt="About Image Preview">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr>

            {{-- Boxes 1–4 --}}
            <h5 class="mb-2">About Feature Boxes</h5>
            <div class="row">
                {{-- Box 1 --}}
                <div class="col-md-6">
                    <div class="box-heading">Box 1</div>
                    <div class="propFormGroup">
                        <label class="propLabel">Title <span class="text-danger">*</span></label>
                        <input type="text"
                               name="box_1_title"
                               class="propInput"
                               value="{{ $box1['title'] ?? '' }}"
                               placeholder="Easy Listing">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Description</label>
                        <textarea name="box_1_description"
                                  class="propTextarea"
                                  placeholder="Post your cooperative housing listings quickly">{{ $box1['description'] ?? '' }}</textarea>
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Logo</label>
                        <input type="file" name="box_1_logo" class="propInput" accept="image/*">
                        @if(!empty($box1['logo'] ?? null))
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.($box1['logo'] ?? '')) }}" alt="Box 1 Logo">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Box 2 --}}
                <div class="col-md-6">
                    <div class="box-heading">Box 2</div>
                    <div class="propFormGroup">
                        <label class="propLabel">Title <span class="text-danger">*</span></label>
                        <input type="text"
                               name="box_2_title"
                               class="propInput"
                               value="{{ $box2['title'] ?? '' }}"
                               placeholder="Premium Exposure">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Description</label>
                        <textarea name="box_2_description"
                                  class="propTextarea"
                                  placeholder="Featured listings get nationwide visibility">{{ $box2['description'] ?? '' }}</textarea>
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Logo</label>
                        <input type="file" name="box_2_logo" class="propInput" accept="image/*">
                        @if(!empty($box2['logo'] ?? null))
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.($box2['logo'] ?? '')) }}" alt="Box 2 Logo">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Box 3 --}}
                <div class="col-md-6">
                    <div class="box-heading">Box 3</div>
                    <div class="propFormGroup">
                        <label class="propLabel">Title <span class="text-danger">*</span></label>
                        <input type="text"
                               name="box_3_title"
                               class="propInput"
                               value="{{ $box3['title'] ?? '' }}"
                               placeholder="Educational Tools">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Description</label>
                        <textarea name="box_3_description"
                                  class="propTextarea"
                                  placeholder="Resources on co-op housing explained">{{ $box3['description'] ?? '' }}</textarea>
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Logo</label>
                        <input type="file" name="box_3_logo" class="propInput" accept="image/*">
                        @if(!empty($box3['logo'] ?? null))
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.($box3['logo'] ?? '')) }}" alt="Box 3 Logo">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Box 4 --}}
                <div class="col-md-6">
                    <div class="box-heading">Box 4</div>
                    <div class="propFormGroup">
                        <label class="propLabel">Title <span class="text-danger">*</span></label>
                        <input type="text"
                               name="box_4_title"
                               class="propInput"
                               value="{{ $box4['title'] ?? '' }}"
                               placeholder="Buyer Support">
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Description</label>
                        <textarea name="box_4_description"
                                  class="propTextarea"
                                  placeholder="Guidance for seniors and families">{{ $box4['description'] ?? '' }}</textarea>
                    </div>
                    <div class="propFormGroup">
                        <label class="propLabel">Logo</label>
                        <input type="file" name="box_4_logo" class="propInput" accept="image/*">
                        @if(!empty($box4['logo'] ?? null))
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.($box4['logo'] ?? '')) }}" alt="Box 4 Logo">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr>

            {{-- Buttons + Phone --}}
            <h5 class="mb-2">Buttons & Contact</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">More About Button Text</label>
                        <input type="text"
                               name="more_about_btn_text"
                               class="propInput"
                               value="{{ $moreAboutText }}"
                               placeholder="More About Us">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">More About Button Link</label>
                        <input type="text"
                               name="more_about_btn_link"
                               class="propInput"
                               value="{{ $moreAboutLink }}"
                               placeholder="/about">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Phone Text</label>
                        <input type="text"
                               name="phone_text"
                               class="propInput"
                               value="{{ $phoneText }}"
                               placeholder="Free Consulting">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Phone Number</label>
                        <input type="text"
                               name="phone_number"
                               class="propInput"
                               value="{{ $phoneNumber }}"
                               placeholder="816-529-7022">
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
