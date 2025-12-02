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
    .propLabel { font-weight: 600; margin-bottom: 6px; }
    .propTextarea {
        width: 100%;
        min-height: 350px;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 14px;
        font-size: 14px;
        resize: vertical;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        <form action="{{ route('admin.cms.terms.update') }}"
              method="POST"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="propFormGroup">
                <label class="propLabel">Terms & Conditions Content</label>
                <textarea name="content" class="propTextarea editor">{{ $sectionData }}</textarea>
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
