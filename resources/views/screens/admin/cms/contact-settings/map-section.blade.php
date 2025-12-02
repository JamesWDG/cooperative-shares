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

    .propTextarea {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 8px 10px;
        font-size: 14px;
        min-height: 150px;
        resize: vertical;
        font-family: monospace;
        white-space: pre-wrap;
    }

    .map-preview {
        margin-top: 20px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #E3E7F0;
    }

    .map-preview iframe {
        width: 100%;
        min-height: 280px;
        border: 0;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        @php
            $iframe_src = is_array($sectionData) ? ($sectionData['iframe_src'] ?? '') : ($sectionData->iframe_src ?? '');
        @endphp

        <form id="map-section-form"
              action="{{ route('admin.cms.contact-settings.update') }}"
              method="POST"
              class="form-submit">
            @csrf

            {{-- required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="propFormGroup">
                <label class="propLabel">
                    Google Maps Iframe Embed <span class="text-danger">*</span>
                </label>
                <textarea name="iframe_src"
                          class="propTextarea"
                          placeholder="Paste your full Google Maps iframe embed code or src URL here...">{{ $iframe_src }}</textarea>
                <small class="text-muted">
                    You can paste either the full <code>&lt;iframe ...&gt;</code> snippet
                    or just the <code>src</code> URL. Frontend can handle formatting.
                </small>
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Save Changes">
                Save Changes
            </button>
        </form>

        @if($iframe_src)
            <div class="map-preview">
                @if(Str::contains($iframe_src, '<iframe'))
                    {!! $iframe_src !!}
                @else
                    <iframe src="{{ $iframe_src }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
    @include('includes.admin.cms.form-scripts')
@endpush
