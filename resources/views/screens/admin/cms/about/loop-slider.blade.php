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

    .loop-item-wrapper {
        border: 1px solid #E3E7F0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        background: #F9FAFC;
    }

    .loop-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .preview-img {
        width: 90px;
        height: 90px;
        object-fit: contain;
        border: 1px solid #DDD;
        background: #FFF;
        padding: 3px;
        border-radius: 6px;
        margin-top: 5px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    @php
        $items = is_array($sectionData)
            ? ($sectionData['items'] ?? [])
            : ($sectionData->items ?? []);

        if (!is_array($items)) $items = [];
        $mediaPath = 'storage/cms/' . $page->page_key . '/' . $sectionType . '/';
    @endphp

    <div class="propFormContainer">

        <form action="{{ route('admin.cms.about.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Slider Items (Text + Image)</h6>
            </div>

            <div id="loopItemsWrapper">

                @foreach($items as $i => $item)
                    @php
                        $text  = $item['text'] ?? '';
                        $image = $item['image'] ?? null;
                    @endphp

                    <div class="loop-item-wrapper">

                        <div class="loop-item-header">
                            <strong>Item #{{ $i+1 }}</strong>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-btn">Remove</button>
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Text <span class="text-danger">*</span></label>
                            <input type="text" name="items[{{ $i }}][text]" class="propInput"
                                   value="{{ $text }}" placeholder="Enter slide text">
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Image</label>
                            <input type="file"
                                   name="items[{{ $i }}][image]"
                                   class="propInput image-input"
                                   data-preview="#preview_{{ $i }}"
                                   accept="image/*">

                            <img id="preview_{{ $i }}"
                                 class="preview-img"
                                 src="{{ $image ? asset($mediaPath.$image) : asset('assets/web/images/star-img.png') }}">
                        </div>

                    </div>
                @endforeach

            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button id="addLoopItem" type="button" class="btn btn-sm btn-dark">+ Add More</button>
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

<script>
(function(){
    const wrapper = document.getElementById('loopItemsWrapper');
    const addBtn  = document.getElementById('addLoopItem');
    let index = {{ count($items) }};

    addBtn.addEventListener('click', () => {
        let html = `
            <div class="loop-item-wrapper">
                <div class="loop-item-header">
                    <strong>Item #${index+1}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-btn">Remove</button>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Text</label>
                    <input type="text" name="items[${index}][text]" class="propInput" placeholder="Enter slide text">
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Image</label>
                    <input type="file"
                           name="items[${index}][image]"
                           class="propInput image-input"
                           data-preview="#preview_new_${index}"
                           accept="image/*">

                    <img id="preview_new_${index}"
                         class="preview-img"
                         src="{{ asset('assets/web/images/star-img.png') }}">
                </div>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        index++;
    });

    wrapper.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-btn')){
            e.target.closest('.loop-item-wrapper').remove();
        }
    });

    wrapper.addEventListener('change', function(e){
        if(e.target.classList.contains('image-input')){
            let previewSelector = e.target.getAttribute('data-preview');
            let img = document.querySelector(previewSelector);
            img.src = URL.createObjectURL(e.target.files[0]);
        }
    });

})();
</script>
@endpush
