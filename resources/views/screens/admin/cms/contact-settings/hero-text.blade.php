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

    .propTextarea {
        min-height: 90px;
        resize: vertical;
    }

    .hero-image-preview {
        max-width: 260px;
        max-height: 260px;
        border-radius: 10px;
        border: 1px solid #e2e2e2;
        object-fit: cover;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        @php
            $heading     = is_array($sectionData) ? ($sectionData['heading'] ?? '')     : ($sectionData->heading ?? '');
            $paragraph_1 = is_array($sectionData) ? ($sectionData['paragraph_1'] ?? '') : ($sectionData->paragraph_1 ?? '');
            $paragraph_2 = is_array($sectionData) ? ($sectionData['paragraph_2'] ?? '') : ($sectionData->paragraph_2 ?? '');
            $imageName   = is_array($sectionData) ? ($sectionData['image'] ?? null)     : ($sectionData->image ?? null);

            // Storage path: storage/cms/{page_key}/{section_key}/{imageName}
            $imageUrl = $imageName
                ? asset('storage/cms/' . $page->page_key . '/' . $sectionType . '/' . $imageName)
                : null; // fallback image
        @endphp

        <form id="hero-text-form"
              action="{{ route('admin.cms.contact-settings.update') }}"
              method="POST"
              class="form-submit"
              enctype="multipart/form-data">
            @csrf

            {{-- required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            {{-- Heading --}}
            <div class="propFormGroup">
                <label class="propLabel">Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="heading"
                       class="propInput"
                       value="{{ $heading }}"
                       placeholder="Reach Out Anytime">
            </div>

            {{-- Paragraph 1 --}}
            <div class="propFormGroup">
                <label class="propLabel">Paragraph 1 <span class="text-danger">*</span></label>
                <textarea name="paragraph_1"
                          class="propTextarea"
                          placeholder="Have questions or need guidance? ...">{{ $paragraph_1 }}</textarea>
            </div>

            {{-- Paragraph 2 --}}
            <div class="propFormGroup">
                <label class="propLabel">Paragraph 2</label>
                <textarea name="paragraph_2"
                          class="propTextarea"
                          placeholder="Reach out via the form below...">{{ $paragraph_2 }}</textarea>
            </div>

            {{-- Hero Image Upload --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">
                            Hero Image
                            
                        </label>
                        <input type="file"
                               name="hero_image"
                               id="hero_image_input"
                               class="propInput"
                               accept="image/*">
                        
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Current / Preview Image</label>
                        <img src="{{ $imageUrl }}"
                             alt="Hero Preview"
                             id="hero-image-preview"
                             class="hero-image-preview">
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

    {{-- Single image live preview --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input  = document.getElementById('hero_image_input');
            const preview = document.getElementById('hero-image-preview');

            if (input && preview) {
                input.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function (event) {
                        preview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endpush
