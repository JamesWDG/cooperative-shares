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
        min-height: 100px;
        resize: vertical;
    }

    .why-item-wrapper {
        border: 1px solid #E3E7F0;
        border-radius: 10px;
        padding: 15px 15px 10px;
        margin-bottom: 12px;
        background: #F9FAFC;
    }

    .why-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .why-item-title {
        font-weight: 600;
        font-size: 14px;
    }

    .current-image-text {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    .preview-wrapper {
        margin-top: 8px;
    }

    .preview-wrapper img {
        max-height: 80px;
        border-radius: 6px;
        border: 1px solid #E5E7EB;
        padding: 2px;
        background: #F9FAFB;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    <div class="propFormContainer">
        @php
            $our_story_heading      = is_array($sectionData) ? ($sectionData['our_story_heading'] ?? '')      : ($sectionData->our_story_heading ?? '');
            $our_story_paragraph_1  = is_array($sectionData) ? ($sectionData['our_story_paragraph_1'] ?? '')  : ($sectionData->our_story_paragraph_1 ?? '');
            $our_story_paragraph_2  = is_array($sectionData) ? ($sectionData['our_story_paragraph_2'] ?? '')  : ($sectionData->our_story_paragraph_2 ?? '');
            $why_choose_heading     = is_array($sectionData) ? ($sectionData['why_choose_heading'] ?? '')     : ($sectionData->why_choose_heading ?? '');
            $why_choose_items       = is_array($sectionData)
                                      ? ($sectionData['why_choose_items'] ?? [])
                                      : ($sectionData->why_choose_items ?? []);

            if (!is_array($why_choose_items)) {
                $why_choose_items = [];
            }

            // Images (filenames only)
            $image_left  = is_array($sectionData) ? ($sectionData['image_left'] ?? null)  : ($sectionData->image_left ?? null);
            $image_right = is_array($sectionData) ? ($sectionData['image_right'] ?? null) : ($sectionData->image_right ?? null);

            // Base preview path (matches controller upload path)
            $imageBasePath = 'storage/cms/' . $page->page_key . '/' . $sectionType . '/';
        @endphp

        <form id="about-our-story-form"
              action="{{ route('admin.cms.about.update') }}"
              method="POST"
              class="form-submit"
              enctype="multipart/form-data">
            @csrf

            {{-- Required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            {{-- ======================
                 OUR STORY TEXT
            ======================= --}}
            <div class="propFormGroup">
                <label class="propLabel">Our Story Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="our_story_heading"
                       class="propInput"
                       value="{{ $our_story_heading }}"
                       placeholder="Our Story">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Our Story Paragraph 1 <span class="text-danger">*</span></label>
                <textarea name="our_story_paragraph_1"
                          class="propTextarea editor"
                          placeholder="CooperativeShares.com was created to modernize the way people find and sell co-op shares...">{{ $our_story_paragraph_1 }}</textarea>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Our Story Paragraph 2</label>
                <textarea name="our_story_paragraph_2"
                          class="propTextarea editor"
                          placeholder="Our platform not only showcases co-op homes for sale but also provides educational support...">{{ $our_story_paragraph_2 }}</textarea>
            </div>

            {{-- ======================
                 LEFT / RIGHT IMAGES
            ======================= --}}
            <hr>
            <h6 class="mb-3">Section Images (left & right)</h6>

            {{-- Left image (col-lg-5) --}}
            <div class="propFormGroup">
                <label class="propLabel">Left Image (mission1)</label>
                <input type="file"
                       name="story_image_left"
                       class="propInput image-input"
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       data-preview-target="#preview_story_image_left">
                <div class="current-image-text">
                    @if($image_left)
                        Current file: {{ $image_left }}
                    @else
                        Default: assets/web/images/mission1.png
                    @endif
                </div>
                <div class="preview-wrapper">
                    <img
                        id="preview_story_image_left"
                        src="{{ $image_left ? asset($imageBasePath . $image_left) : asset('assets/web/images/mission1.png') }}"
                        alt="Left Image Preview">
                </div>
            </div>

            {{-- Right image (mission2) --}}
            <div class="propFormGroup">
                <label class="propLabel">Right Image (mission2)</label>
                <input type="file"
                       name="story_image_right"
                       class="propInput image-input"
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       data-preview-target="#preview_story_image_right">
                <div class="current-image-text">
                    @if($image_right)
                        Current file: {{ $image_right }}
                    @else
                        Default: assets/web/images/mission2.png
                    @endif
                </div>
                <div class="preview-wrapper">
                    <img
                        id="preview_story_image_right"
                        src="{{ $image_right ? asset($imageBasePath . $image_right) : asset('assets/web/images/mission2.png') }}"
                        alt="Right Image Preview">
                </div>
            </div>

            <hr>

            {{-- ======================
                 WHY CHOOSE US
            ======================= --}}
            <div class="propFormGroup">
                <label class="propLabel">Why Choose Us Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="why_choose_heading"
                       class="propInput"
                       value="{{ $why_choose_heading }}"
                       placeholder="Why Choose Us?">
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Why Choose Us Items (3 cards)</h6>
                <button type="button"
                        class="btn btn-sm btn-outline-primary"
                        id="add-why-item-btn">
                    + Add Item
                </button>
            </div>

            <div id="why-items-wrapper">
                @forelse($why_choose_items as $index => $item)
                    @php
                        $title       = is_array($item) ? ($item['title'] ?? '')       : ($item->title ?? '');
                        $description = is_array($item) ? ($item['description'] ?? '') : ($item->description ?? '');
                    @endphp

                    <div class="why-item-wrapper">
                        <div class="why-item-header">
                            <div class="why-item-title">
                                Item #{{ $index + 1 }}
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger remove-why-item-btn">
                                Remove
                            </button>
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="why_choose_items[{{ $index }}][title]"
                                   class="propInput"
                                   value="{{ $title }}"
                                   placeholder="100% Focused on Co-op Communities">
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Description <span class="text-danger">*</span></label>
                            <textarea name="why_choose_items[{{ $index }}][description]"
                                      class="propTextarea"
                                      placeholder="Buyers can explore verified co-op homes, and sellers and boards gain nationwide exposure.">{{ $description }}</textarea>
                        </div>
                    </div>
                @empty
                    <div class="why-item-wrapper">
                        <div class="why-item-header">
                            <div class="why-item-title">
                                Item #1
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger remove-why-item-btn">
                                Remove
                            </button>
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="why_choose_items[0][title]"
                                   class="propInput"
                                   placeholder="100% Focused on Co-op Communities">
                        </div>

                        <div class="propFormGroup">
                            <label class="propLabel">Description <span class="text-danger">*</span></label>
                            <textarea name="why_choose_items[0][description]"
                                      class="propTextarea"
                                      placeholder="Buyers can explore verified co-op homes, and sellers and boards gain nationwide exposure."></textarea>
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

    {{-- Hidden template for new Why Choose Us item --}}
    <script type="text/template" id="why-item-template">
        <div class="why-item-wrapper">
            <div class="why-item-header">
                <div class="why-item-title">
                    Item #__NUMBER__
                </div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-why-item-btn">
                    Remove
                </button>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="why_choose_items[__INDEX__][title]"
                       class="propInput"
                       placeholder="100% Focused on Co-op Communities">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Description <span class="text-danger">*</span></label>
                <textarea name="why_choose_items[__INDEX__][description]"
                          class="propTextarea"
                          placeholder="Buyers can explore verified co-op homes, and sellers and boards gain nationwide exposure."></textarea>
            </div>
        </div>
    </script>
</section>
@endsection

@push('scripts')
    @include('includes.admin.cms.form-scripts')

    <script>
        (function () {
            // dynamic Why Choose Us items
            const wrapper  = document.getElementById('why-items-wrapper');
            const addBtn   = document.getElementById('add-why-item-btn');
            const template = document.getElementById('why-item-template').innerHTML;

            let currentIndex = {{ max(count($why_choose_items), 1) }};

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
                    if (e.target.classList.contains('remove-why-item-btn')) {
                        const card = e.target.closest('.why-item-wrapper');
                        if (card) {
                            card.remove();
                        }
                    }
                });
            }

            // image previews
            document.querySelectorAll('.image-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    const targetSelector = this.getAttribute('data-preview-target');
                    const previewImg = document.querySelector(targetSelector);
                    if (!previewImg) return;

                    const file = this.files && this.files[0];
                    if (file) {
                        const url = URL.createObjectURL(file);
                        previewImg.src = url;
                    }
                });
            });
        })();
    </script>
@endpush
