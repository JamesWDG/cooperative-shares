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

    .propInput[readonly] {
        background-color: #f5f6fa;
        cursor: not-allowed;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        @php
            $phone   = is_array($sectionData) ? ($sectionData['phone'] ?? '')   : ($sectionData->phone ?? '');
            $email   = is_array($sectionData) ? ($sectionData['email'] ?? '')   : ($sectionData->email ?? '');
            $address = is_array($sectionData) ? ($sectionData['address'] ?? '') : ($sectionData->address ?? '');
        @endphp

        <form id="contact-cards-form"
              action="{{ route('admin.cms.contact-settings.update') }}"
              method="POST"
              class="form-submit">
            @csrf

            {{-- required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Phone Number <span class="text-danger">*</span></label>
                        <input type="text"
                               name="phone"
                               class="propInput"
                               value="{{ $phone }}"
                               placeholder="(816) 529-7022">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Email Address <span class="text-danger">*</span></label>
                        <input type="email"
                               name="email"
                               class="propInput"
                               value="{{ $email }}"
                               placeholder="info@cooperativeshares.com">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Address <span class="text-danger">*</span></label>
                        <input type="text"
                               name="address"
                               class="propInput"
                               value="{{ $address }}"
                               placeholder="520 E 4th st Tonganoxie, KS 66086">
                    </div>
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
@endpush
