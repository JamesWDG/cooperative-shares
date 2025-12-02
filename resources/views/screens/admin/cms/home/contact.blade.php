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
        $isArr       = is_array($sectionData);
        $miniHeading = $isArr ? ($sectionData['mini_heading'] ?? '') : ($sectionData->mini_heading ?? '');
        $heading     = $isArr ? ($sectionData['heading'] ?? '')      : ($sectionData->heading ?? '');

        $placeholders = $isArr ? ($sectionData['placeholders'] ?? []) : ($sectionData->placeholders ?? []);
        if (!is_array($placeholders)) $placeholders = [];

        $namePlaceholder    = $placeholders['name']    ?? 'Your name';
        $emailPlaceholder   = $placeholders['email']   ?? 'Your email';
        $phonePlaceholder   = $placeholders['phone']   ?? 'Phone number';
        $servicePlaceholder = $placeholders['service'] ?? 'Select Service';
        $messagePlaceholder = $placeholders['message'] ?? 'Write Message...';
        $submitText         = $placeholders['submit']  ?? 'Submit Now';

        $rightImage = $isArr ? ($sectionData['right_image'] ?? null) : ($sectionData->right_image ?? null);

        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="home-contact-form"
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
                       placeholder="Contact Us">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Main Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="heading"
                       class="propInput"
                       value="{{ $heading }}"
                       placeholder="Get a Free Consultation">
            </div>

            <hr>

            <h5 class="mb-2">Form Placeholders</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Name Placeholder</label>
                        <input type="text"
                               name="name_placeholder"
                               class="propInput"
                               value="{{ $namePlaceholder }}"
                               placeholder="Your name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Email Placeholder</label>
                        <input type="text"
                               name="email_placeholder"
                               class="propInput"
                               value="{{ $emailPlaceholder }}"
                               placeholder="Your email">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Phone Placeholder</label>
                        <input type="text"
                               name="phone_placeholder"
                               class="propInput"
                               value="{{ $phonePlaceholder }}"
                               placeholder="Phone number">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Service Placeholder</label>
                        <input type="text"
                               name="service_placeholder"
                               class="propInput"
                               value="{{ $servicePlaceholder }}"
                               placeholder="Select Service">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Message Placeholder</label>
                        <input type="text"
                               name="message_placeholder"
                               class="propInput"
                               value="{{ $messagePlaceholder }}"
                               placeholder="Write Message...">
                    </div>
                </div>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Submit Button Text</label>
                <input type="text"
                       name="submit_button_text"
                       class="propInput"
                       value="{{ $submitText }}"
                       placeholder="Submit Now">
            </div>

            <hr>

            <div class="propFormGroup">
                <label class="propLabel">Right Side Illustration Image</label>
                <input type="file" name="right_image" class="propInput" accept="image/*">
                @if($rightImage)
                    <div class="media-preview">
                        <img src="{{ asset($mediaBasePath.$rightImage) }}" alt="Right Image Preview">
                    </div>
                @endif
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
