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
    .propFormGroup { margin-bottom: 14px; }
    .propLabel {
        display: block;
        font-weight: 500;
        margin-bottom: 6px;
        font-size: 13px;
    }
    .propInput,
    .propTextarea {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 7px 10px;
        font-size: 13px;
    }
    .propTextarea {
        min-height: 60px;
        resize: vertical;
    }
    .small-hint {
        font-size: 11px;
        color: #6c757d;
    }
    .media-preview {
        margin-top: 6px;
        border: 1px solid #E3E7F0;
        border-radius: 8px;
        padding: 6px;
        max-width: 200px;
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
        $isArr = is_array($sectionData);

        $topBar     = $isArr ? ($sectionData['top_bar'] ?? []) : ($sectionData->top_bar ?? []);
        if (!is_array($topBar)) { $topBar = []; }
        $topEmail   = $topBar['email'] ?? 'anderson@theaapg.com';
        $topPhone   = $topBar['phone'] ?? '816-529-7022';

        $social     = $isArr ? ($sectionData['social_links'] ?? []) : ($sectionData->social_links ?? []);
        if (!is_array($social)) { $social = []; }
        $facebook   = $social['facebook'] ?? '#';
        $twitter    = $social['twitter'] ?? '#';
        $linkedin   = $social['linkedin'] ?? '#';
        $whatsapp   = $social['whatsapp'] ?? '#';

        $headerCfg  = $isArr ? ($sectionData['header'] ?? []) : ($sectionData->header ?? []);
        if (!is_array($headerCfg)) { $headerCfg = []; }
        $logo       = $headerCfg['logo'] ?? null;
        $logoRoute  = $headerCfg['logo_link_route'] ?? '';
        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="header-main-form"
              action="{{ route('admin.cms.header.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            {{-- TOP BAR --}}
            <h5 style="font-size:14px;">Top Bar</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Top Bar Email</label>
                        <input type="text"
                               name="top_bar_email"
                               class="propInput"
                               value="{{ $topEmail }}"
                               placeholder="anderson@theaapg.com">
                        <p class="small-hint mb-0">Shown as <code>mailto:</code> link in the top bar.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Top Bar Phone</label>
                        <input type="text"
                               name="top_bar_phone"
                               class="propInput"
                               value="{{ $topPhone }}"
                               placeholder="816-529-7022">
                        <p class="small-hint mb-0">Shown as <code>tel:</code> link in the top bar.</p>
                    </div>
                </div>
            </div>

            <hr>

            {{-- SOCIAL LINKS --}}
            <h5 style="font-size:14px;">Top Bar Social Links</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Facebook URL</label>
                        <input type="text"
                               name="facebook_url"
                               class="propInput"
                               value="{{ $facebook }}"
                               placeholder="https://facebook.com/...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">Twitter (X) URL</label>
                        <input type="text"
                               name="twitter_url"
                               class="propInput"
                               value="{{ $twitter }}"
                               placeholder="https://x.com/...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">LinkedIn URL</label>
                        <input type="text"
                               name="linkedin_url"
                               class="propInput"
                               value="{{ $linkedin }}"
                               placeholder="https://linkedin.com/company/...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="propFormGroup">
                        <label class="propLabel">WhatsApp URL</label>
                        <input type="text"
                               name="whatsapp_url"
                               class="propInput"
                               value="{{ $whatsapp }}"
                               placeholder="https://wa.me/...">
                    </div>
                </div>
            </div>

            <hr>

            {{-- HEADER LOGO --}}
            <h5 style="font-size:14px;">Header Logo</h5>
            <div class="row">
                <div class="col-md-8">
                    <div class="propFormGroup">
                        <label class="propLabel">Logo Image</label>
                        <input type="file"
                               name="logo"
                               class="propInput"
                               accept="image/*">
                        @if($logo)
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.$logo) }}" alt="Header Logo">
                            </div>
                        @endif
                        <p class="small-hint mb-0">Recommended: PNG with transparent background.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Logo Link Route Name</label>
                        <input type="text"
                               name="logo_link_route"
                               class="propInput"
                               value="{{ $logoRoute }}"
                               placeholder="index">
                        <p class="small-hint mb-0">Example: <code>index</code>, <code>about</code>. Used if not empty.</p>
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
