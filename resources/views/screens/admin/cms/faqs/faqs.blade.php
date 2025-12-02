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

    .faq-card-wrapper {
        border: 1px solid #E3E7F0;
        border-radius: 10px;
        padding: 15px 15px 10px;
        margin-bottom: 12px;
        background: #F9FAFC;
    }

    .faq-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .faq-card-title {
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
            $main_title = is_array($sectionData)
                ? ($sectionData['main_title'] ?? '')
                : ($sectionData->main_title ?? '');

            $items = is_array($sectionData)
                ? ($sectionData['items'] ?? [])
                : ($sectionData->items ?? []);

            if (!is_array($items)) {
                $items = [];
            }
        @endphp

        <form id="faqs-form"
              action="{{ route('admin.cms.faqs.update') }}"
              method="POST"
              class="form-submit">
            @csrf

            {{-- Required for controller (if you are using page/section keys) --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key ?? '' }}">
            <input type="hidden" name="section_key" value="{{ $sectionType ?? 'faqs' }}">

            <div class="propFormGroup">
                <label class="propLabel">Main Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="main_title"
                       class="propInput"
                       value="{{ $main_title }}"
                       placeholder="Frequently Asked Questions">
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">FAQ List</h6>
                <button type="button"
                        class="btn btn-sm btn-outline-primary"
                        id="add-faq-btn">
                    + Add FAQ
                </button>
            </div>

            <div id="faq-items-wrapper">
                @forelse($items as $index => $item)
                    @php
                        $title       = is_array($item) ? ($item['title'] ?? '')       : ($item->title ?? '');
                        $description = is_array($item) ? ($item['description'] ?? '') : ($item->description ?? '');
                    @endphp

                    <div class="faq-card-wrapper">
                        <div class="faq-card-header">
                            <div class="faq-card-title">
                                FAQ #{{ $index + 1 }}
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger remove-faq-btn">
                                Remove
                            </button>
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Question (Title) <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="items[{{ $index }}][title]"
                                   class="propInput"
                                   value="{{ $title }}"
                                   placeholder="What types of listings can I post on CooperativeShares.com?">
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Answer (Description) <span class="text-danger">*</span></label>
                            <textarea name="items[{{ $index }}][description]"
                                      class="propTextarea"
                                      placeholder="Answer for this question...">{{ $description }}</textarea>
                        </div>
                    </div>
                @empty
                    {{-- If no items, show one empty block by default --}}
                    <div class="faq-card-wrapper">
                        <div class="faq-card-header">
                            <div class="faq-card-title">
                                FAQ #1
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger remove-faq-btn">
                                Remove
                            </button>
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Question (Title) <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="items[0][title]"
                                   class="propInput"
                                   value=""
                                   placeholder="What types of listings can I post on CooperativeShares.com?">
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Answer (Description) <span class="text-danger">*</span></label>
                            <textarea name="items[0][description]"
                                      class="propTextarea"
                                      placeholder="Answer for this question..."></textarea>
                        </div>
                    </div>
                @endforelse
            </div>

            <button type="button"
                    class="btn btn-primary update-btn mt-3"
                    data-original-text="Save Changes">
                Save Changes
            </button>
        </form>
    </div>

    {{-- Hidden template for new FAQ card --}}
    <script type="text/template" id="faq-item-template">
        <div class="faq-card-wrapper">
            <div class="faq-card-header">
                <div class="faq-card-title">
                    FAQ #__NUMBER__
                </div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-faq-btn">
                    Remove
                </button>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Question (Title) <span class="text-danger">*</span></label>
                <input type="text"
                       name="items[__INDEX__][title]"
                       class="propInput"
                       placeholder="What types of listings can I post on CooperativeShares.com?">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Answer (Description) <span class="text-danger">*</span></label>
                <textarea name="items[__INDEX__][description]"
                          class="propTextarea"
                          placeholder="Answer for this question..."></textarea>
            </div>
        </div>
    </script>
</section>
@endsection

@push('scripts')
    @include('includes.admin.cms.form-scripts')

    <script>
        (function () {
            const wrapper  = document.getElementById('faq-items-wrapper');
            const addBtn   = document.getElementById('add-faq-btn');
            const template = document.getElementById('faq-item-template').innerHTML;

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
                    if (e.target.classList.contains('remove-faq-btn')) {
                        const card = e.target.closest('.faq-card-wrapper');
                        if (card) {
                            card.remove();
                        }
                    }
                });
            }
        })();
    </script>
@endpush
