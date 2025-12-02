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
    .propInput {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 8px 10px;
        font-size: 14px;
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
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    @php
        $isArr        = is_array($sectionData);
        $miniHeading  = $isArr ? ($sectionData['mini_heading'] ?? '')         : ($sectionData->mini_heading ?? '');
        $heading      = $isArr ? ($sectionData['heading'] ?? '')              : ($sectionData->heading ?? '');
        $tabAll       = $isArr ? ($sectionData['tab_all_label'] ?? '')        : ($sectionData->tab_all_label ?? '');
        $tab55        = $isArr ? ($sectionData['tab_senior_55_label'] ?? '')  : ($sectionData->tab_senior_55_label ?? '');
        $tab62        = $isArr ? ($sectionData['tab_senior_62_label'] ?? '')  : ($sectionData->tab_senior_62_label ?? '');
        $tabFamily    = $isArr ? ($sectionData['tab_family_label'] ?? '')     : ($sectionData->tab_family_label ?? '');
        $viewAllText  = $isArr ? ($sectionData['view_all_button_text'] ?? '') : ($sectionData->view_all_button_text ?? '');
        $sideImage    = $isArr ? ($sectionData['side_image'] ?? null)         : ($sectionData->side_image ?? null);

        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="home-properties-form"
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
                       placeholder="Property Types">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Main Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="heading"
                       class="propInput"
                       value="{{ $heading }}"
                       placeholder="Property Options">
            </div>

            <hr>

            <h5 class="mb-2">Tab Labels</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">View All Tab <span class="text-danger">*</span></label>
                        <input type="text"
                               name="tab_all_label"
                               class="propInput"
                               value="{{ $tabAll }}"
                               placeholder="View All">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Senior Coop 55+ Tab <span class="text-danger">*</span></label>
                        <input type="text"
                               name="tab_senior_55_label"
                               class="propInput"
                               value="{{ $tab55 }}"
                               placeholder="Senior Coop 55+">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Senior Coop 62+ Tab <span class="text-danger">*</span></label>
                        <input type="text"
                               name="tab_senior_62_label"
                               class="propInput"
                               value="{{ $tab62 }}"
                               placeholder="Senior Coop 62+">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Family Coop Tab <span class="text-danger">*</span></label>
                        <input type="text"
                               name="tab_family_label"
                               class="propInput"
                               value="{{ $tabFamily }}"
                               placeholder="Family Coop">
                    </div>
                </div>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">View All Button Text</label>
                <input type="text"
                       name="view_all_button_text"
                       class="propInput"
                       value="{{ $viewAllText }}"
                       placeholder="View All">
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
