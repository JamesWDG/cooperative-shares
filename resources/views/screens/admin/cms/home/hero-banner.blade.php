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
        border: 1px solid rgba(3, 10, 27, 1);
        border-radius: 8px;
        padding: 6px;
        max-width: 260px;
        background: rgba(3, 10, 27, 1);
    }
    .media-preview img,
    .media-preview video {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }
    .small-hint {
        font-size: 12px;
        color: #6c757d;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    @php
        $isArr       = is_array($sectionData);
        $heading     = $isArr ? ($sectionData['heading'] ?? '')     : ($sectionData->heading ?? '');
        $description = $isArr ? ($sectionData['description'] ?? '') : ($sectionData->description ?? '');

        $socialLinks = $isArr ? ($sectionData['social_links'] ?? []) : ($sectionData->social_links ?? []);
        if (!is_array($socialLinks)) $socialLinks = [];

        $facebook = $socialLinks['facebook'] ?? '';
        $twitter  = $socialLinks['twitter']  ?? '';
        $linkedin = $socialLinks['linkedin'] ?? '';
        $whatsapp = $socialLinks['whatsapp'] ?? '';

        $scrollLink = $isArr ? ($sectionData['scroll_link'] ?? '#property') : ($sectionData->scroll_link ?? '#property');

        $searchForm = $isArr ? ($sectionData['search_form'] ?? []) : ($sectionData->search_form ?? []);
        if (!is_array($searchForm)) $searchForm = [];

        $propertyTypeLabel  = $searchForm['property_type_label'] ?? '';
        $roomsLabel         = $searchForm['rooms_label'] ?? '';
        $bathsLabel         = $searchForm['baths_label'] ?? '';
        $sqfeetLabel        = $searchForm['sqfeet_label'] ?? '';

        $propertyTypeOptions = $searchForm['property_type_options'] ?? ['','',''];
        if (!is_array($propertyTypeOptions)) {
            $propertyTypeOptions = ['Cooperative','Senior','Family'];
        }

        $roomsPlaceholder  = $searchForm['rooms_placeholder']  ?? '';
        $bathsPlaceholder  = $searchForm['baths_placeholder']  ?? '';
        $sqfeetPlaceholder = $searchForm['sqfeet_placeholder'] ?? '';
        $searchButtonText  = $searchForm['search_button_text'] ?? '';

        $videoFile   = $isArr ? ($sectionData['video'] ?? null)        : ($sectionData->video ?? null);
        $playImage   = $isArr ? ($sectionData['play_image'] ?? null)   : ($sectionData->play_image ?? null);
        $scrollImage = $isArr ? ($sectionData['scroll_image'] ?? null) : ($sectionData->scroll_image ?? null);

        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="home-hero-banner-form"
              action="{{ route('admin.cms.home.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            {{-- Heading / Description --}}
            <div class="propFormGroup">
                <label class="propLabel">Hero Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="heading"
                       class="propInput"
                       value="{{ $heading }}"
                       placeholder="Your Trusted Marketplace for Cooperative Housing Shares">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Hero Description <span class="text-danger">*</span></label>
                <textarea name="description"
                          class="propTextarea editor"
                          placeholder="Discover cooperative living communities on a trusted nationwide platform...">{{ $description }}</textarea>
            </div>

            <hr>

            {{-- Media --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Background Video</label>
                        <input type="file" name="video_file" class="propInput" accept="video/*">
                        <p class="small-hint mb-1">MP4 / WebM, optional.</p>

                        @if($videoFile)
                            <div class="media-preview">
                                <video src="{{ asset($mediaBasePath.$videoFile) }}" controls></video>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Play Image (Center)</label>
                        <input type="file" name="play_image" class="propInput" accept="image/*">
                        @if($playImage)
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.$playImage) }}" alt="Play Image Preview">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Scroll Image (Bottom)</label>
                        <input type="file" name="scroll_image" class="propInput" accept="image/*">
                        @if($scrollImage)
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.$scrollImage) }}" alt="Scroll Image Preview">
                            </div>
                        @endif
                    </div>

                    <div class="propFormGroup">
                        <label class="propLabel">Scroll Link (Anchor)</label>
                        <input type="text"
                               name="scroll_link"
                               class="propInput"
                               value="{{ $scrollLink }}"
                               placeholder="#property">
                    </div>
                </div>
            </div>

            <hr>

            {{-- Social Links --}}
            <h5 class="mb-2">Social Links</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Facebook URL</label>
                        <input type="url"
                               name="facebook_url"
                               class="propInput"
                               value="{{ $facebook }}"
                               placeholder="https://facebook.com/...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Twitter / X URL</label>
                        <input type="url"
                               name="twitter_url"
                               class="propInput"
                               value="{{ $twitter }}"
                               placeholder="https://twitter.com/...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">LinkedIn URL</label>
                        <input type="url"
                               name="linkedin_url"
                               class="propInput"
                               value="{{ $linkedin }}"
                               placeholder="https://linkedin.com/...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">WhatsApp Link</label>
                        <input type="text"
                               name="whatsapp_url"
                               class="propInput"
                               value="{{ $whatsapp }}"
                               placeholder="wa.me/... or tel:...">
                    </div>
                </div>
            </div>

            <hr>

            {{-- Search Form Settings --}}
            <h5 class="mb-2">Search Form Settings</h5>

            <div class="row">
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Property Type Label</label>
                        <input type="text"
                               name="property_type_label"
                               class="propInput"
                               value="{{ $propertyTypeLabel }}"
                               placeholder="Property Type">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Rooms Label</label>
                        <input type="text"
                               name="rooms_label"
                               class="propInput"
                               value="{{ $roomsLabel }}"
                               placeholder="Rooms">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Baths Label</label>
                        <input type="text"
                               name="baths_label"
                               class="propInput"
                               value="{{ $bathsLabel }}"
                               placeholder="Baths">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Sq Feet Label</label>
                        <input type="text"
                               name="sqfeet_label"
                               class="propInput"
                               value="{{ $sqfeetLabel }}"
                               placeholder="Sq Feet">
                    </div>
                </div>
            </div>

            @php
                $opt1 = $propertyTypeOptions[0] ?? '';
                $opt2 = $propertyTypeOptions[1] ?? '';
                $opt3 = $propertyTypeOptions[2] ?? '';
            @endphp

            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Property Type Option 1</label>
                        <input type="text"
                               name="property_type_option_1"
                               class="propInput"
                               value="{{ $opt1 }}"
                               placeholder="Cooperative">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Option 2</label>
                        <input type="text"
                               name="property_type_option_2"
                               class="propInput"
                               value="{{ $opt2 }}"
                               placeholder="Senior">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Option 3</label>
                        <input type="text"
                               name="property_type_option_3"
                               class="propInput"
                               value="{{ $opt3 }}"
                               placeholder="Family">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Rooms Placeholder</label>
                        <input type="text"
                               name="rooms_placeholder"
                               class="propInput"
                               value="{{ $roomsPlaceholder }}"
                               placeholder="Total Rooms">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Baths Placeholder</label>
                        <input type="text"
                               name="baths_placeholder"
                               class="propInput"
                               value="{{ $bathsPlaceholder }}"
                               placeholder="Total Baths">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Sq Feet Placeholder</label>
                        <input type="text"
                               name="sqfeet_placeholder"
                               class="propInput"
                               value="{{ $sqfeetPlaceholder }}"
                               placeholder="Square Feet">
                    </div>
                </div>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Search Button Text</label>
                <input type="text"
                       name="search_button_text"
                       class="propInput"
                       value="{{ $searchButtonText }}"
                       placeholder="Search">
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
