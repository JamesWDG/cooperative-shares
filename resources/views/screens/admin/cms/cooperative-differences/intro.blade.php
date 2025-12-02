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
        min-height: 120px;
        resize: vertical;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        @php
            $mini_heading = is_array($sectionData) ? ($sectionData['mini_heading'] ?? '') : ($sectionData->mini_heading ?? '');
            $main_heading = is_array($sectionData) ? ($sectionData['main_heading'] ?? '') : ($sectionData->main_heading ?? '');
            $paragraph    = is_array($sectionData) ? ($sectionData['paragraph'] ?? '')    : ($sectionData->paragraph ?? '');
        @endphp

        <form id="coop-intro-form"
              action="{{ route('admin.cms.cooperative-differences.update') }}"
              method="POST"
              class="form-submit">
            @csrf

            {{-- Required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="propFormGroup">
                <label class="propLabel">Mini Heading</label>
                <input type="text"
                       name="mini_heading"
                       class="propInput"
                       value="{{ $mini_heading }}"
                       placeholder="Cooperative Differences">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Main Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="main_heading"
                       class="propInput"
                       value="{{ $main_heading }}"
                       placeholder="What Makes Co-op Housing Unique">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Main Paragraph <span class="text-danger">*</span></label>
                <textarea name="paragraph"
                          class="propTextarea editor"
                          placeholder="Write the main description for cooperative differences...">{{ $paragraph }}</textarea>
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
