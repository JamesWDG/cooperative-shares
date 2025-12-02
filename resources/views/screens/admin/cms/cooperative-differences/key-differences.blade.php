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
        min-height: 90px;
        resize: vertical;
    }

    .difference-card-wrapper {
        border: 1px solid #E3E7F0;
        border-radius: 10px;
        padding: 15px 15px 10px;
        margin-bottom: 12px;
        background: #F9FAFC;
    }

    .difference-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .difference-card-title {
        font-weight: 600;
        font-size: 14px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        @php
            $main_title   = is_array($sectionData) ? ($sectionData['main_title'] ?? '')   : ($sectionData->main_title ?? '');
            $closing_text = is_array($sectionData) ? ($sectionData['closing_text'] ?? '') : ($sectionData->closing_text ?? '');
            $items        = is_array($sectionData)
                            ? ($sectionData['items'] ?? [])
                            : ($sectionData->items ?? []);

            if (!is_array($items)) {
                $items = [];
            }
        @endphp

        <form id="coop-key-differences-form"
              action="{{ route('admin.cms.cooperative-differences.update') }}"
              method="POST"
              class="form-submit">
            @csrf

            {{-- Required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="propFormGroup">
                <label class="propLabel">Main Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="main_title"
                       class="propInput"
                       value="{{ $main_title }}"
                       placeholder="Key Differences">
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Differences List</h6>
                <button type="button"
                        class="btn btn-sm btn-outline-primary"
                        id="add-difference-btn">
                    + Add Difference
                </button>
            </div>

            <div id="differences-items-wrapper">
                @forelse($items as $index => $item)
                    @php
                        $title       = is_array($item) ? ($item['title'] ?? '')       : ($item->title ?? '');
                        $description = is_array($item) ? ($item['description'] ?? '') : ($item->description ?? '');
                    @endphp

                    <div class="difference-card-wrapper">
                        <div class="difference-card-header">
                            <div class="difference-card-title">
                                Difference #{{ $index + 1 }}
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger remove-difference-btn">
                                Remove
                            </button>
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="items[{{ $index }}][title]"
                                   class="propInput"
                                   value="{{ $title }}"
                                   placeholder="Ownership Structure">
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Description <span class="text-danger">*</span></label>
                            <textarea name="items[{{ $index }}][description]"
                                      class="propTextarea"
                                      placeholder="Describe this difference...">{{ $description }}</textarea>
                        </div>
                    </div>
                @empty
                    {{-- If no items, show one empty block by default --}}
                    <div class="difference-card-wrapper">
                        <div class="difference-card-header">
                            <div class="difference-card-title">
                                Difference #1
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger remove-difference-btn">
                                Remove
                            </button>
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="items[0][title]"
                                   class="propInput"
                                   value=""
                                   placeholder="Ownership Structure">
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Description <span class="text-danger">*</span></label>
                            <textarea name="items[0][description]"
                                      class="propTextarea"
                                      placeholder="Describe this difference..."></textarea>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="propFormGroup mt-3">
                <label class="propLabel">Closing Text</label>
                <textarea name="closing_text"
                          class="propTextarea"
                          placeholder="Understanding these differences helps buyers and boards recognize the advantages of co-ops...">{{ $closing_text }}</textarea>
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Save Changes">
                Save Changes
            </button>
        </form>
    </div>

    {{-- Hidden template for new difference card --}}
    <script type="text/template" id="difference-item-template">
        <div class="difference-card-wrapper">
            <div class="difference-card-header">
                <div class="difference-card-title">
                    Difference #__NUMBER__
                </div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-difference-btn">
                    Remove
                </button>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="items[__INDEX__][title]"
                       class="propInput"
                       placeholder="Ownership Structure">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Description <span class="text-danger">*</span></label>
                <textarea name="items[__INDEX__][description]"
                          class="propTextarea"
                          placeholder="Describe this difference..."></textarea>
            </div>
        </div>
    </script>
</section>
@endsection

@push('scripts')
    @include('includes.admin.cms.form-scripts')

    <script>
        (function () {
            const wrapper   = document.getElementById('differences-items-wrapper');
            const addBtn    = document.getElementById('add-difference-btn');
            const template  = document.getElementById('difference-item-template').innerHTML;

            // Start index from existing count
            let currentIndex = {{ max(count($items), 1) }};

            if (addBtn && wrapper) {
                addBtn.addEventListener('click', function () {
                    const number = currentIndex + 1;
                    let html = template
                        .replace(/__INDEX__/g, currentIndex)
                        .replace(/__NUMBER__/g, number);

                    const div = document.createElement('div');
                    div.innerHTML = html.trim();
                    wrapper.appendChild(div.firstElementChild);

                    currentIndex++;
                });

                wrapper.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-difference-btn')) {
                        const card = e.target.closest('.difference-card-wrapper');
                        if (card) {
                            card.remove();
                        }
                    }
                });
            }
        })();
    </script>
@endpush
