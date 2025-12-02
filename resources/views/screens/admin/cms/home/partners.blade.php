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
        min-height: 70px;
        resize: vertical;
    }
    .media-preview {
        margin-top: 6px;
        border: 1px solid #E3E7F0;
        border-radius: 8px;
        padding: 6px;
        max-width: 220px;
        background: #F9FAFC;
    }
    .media-preview img {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }
    .loop-item {
        border: 1px dashed #D7DCE7;
        border-radius: 10px;
        padding: 12px 12px 6px;
        margin-bottom: 12px;
        background: #FBFCFF;
    }
    .loop-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .loop-item-title {
        font-size: 13px;
        font-weight: 600;
    }
    .small-hint {
        font-size: 11px;
        color: #6c757d;
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
        $items       = $isArr ? ($sectionData['items'] ?? [])        : ($sectionData->items ?? []);

        if (!is_array($items)) {
            $items = [];
        }

        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="home-partners-form"
              action="{{ route('admin.cms.home.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            {{-- Headings --}}
            <div class="propFormGroup">
                <label class="propLabel">Mini Heading</label>
                <input type="text"
                       name="mini_heading"
                       class="propInput"
                       value="{{ $miniHeading }}"
                       placeholder="Our Partners">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Main Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="heading"
                       class="propInput"
                       value="{{ $heading }}"
                       placeholder="Supporting Cooperative Communities Together">
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0" style="font-size:14px;">Partner Logos & Cards</h5>
                
            </div>
            <p class="small-hint mb-2">
                Each partner card supports: logo, stats (Communities/Units/Cities), footer text, and link (e.g. Realtor profile).
            </p>

            <div id="partnersRepeater">
                @php $idx = 0; @endphp
                @foreach($items as $i => $item)
                    @php
                        $title       = $item['title'] ?? '';
                        $link        = $item['link'] ?? '';
                        $communities = $item['communities'] ?? '';
                        $units       = $item['units'] ?? '';
                        $cities      = $item['cities'] ?? '';
                        $headerText  = $item['header_text'] ?? '';
                        $image       = $item['image'] ?? null;
                    @endphp
                    <div class="loop-item" data-index="{{ $idx }}">
                        <div class="loop-item-header">
                            <div class="loop-item-title">Partner #<span class="partner-index">{{ $idx + 1 }}</span></div>
                            <button type="button" class="btn btn-xs btn-outline-danger remove-partner-item">
                                Remove
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Title (Tooltip / Internal)</label>
                                    <input type="text"
                                           name="items[{{ $idx }}][title]"
                                           class="propInput"
                                           value="{{ $title }}"
                                           placeholder="Mac Properties">
                                </div>
                                <div class="propFormGroup">
                                    <label class="propLabel">Profile Link (URL)</label>
                                    <input type="text"
                                           name="items[{{ $idx }}][link]"
                                           class="propInput"
                                           value="{{ $link }}"
                                           placeholder="{{ route('realtor.profile') }}">
                                </div>
                                <div class="propFormGroup">
                                    <label class="propLabel">Logo Image</label>
                                    <input type="file"
                                           name="items[{{ $idx }}][image]"
                                           class="propInput"
                                           accept="image/*">
                                    @if($image)
                                        <div class="media-preview">
                                            <img src="{{ asset($mediaBasePath.$image) }}" alt="Partner Logo">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Communities Count</label>
                                    <input type="text"
                                           name="items[{{ $idx }}][communities]"
                                           class="propInput"
                                           value="{{ $communities }}"
                                           placeholder="28">
                                </div>
                                <div class="propFormGroup">
                                    <label class="propLabel">Units Count</label>
                                    <input type="text"
                                           name="items[{{ $idx }}][units]"
                                           class="propInput"
                                           value="{{ $units }}"
                                           placeholder="28">
                                </div>
                                <div class="propFormGroup">
                                    <label class="propLabel">Cities Count</label>
                                    <input type="text"
                                           name="items[{{ $idx }}][cities]"
                                           class="propInput"
                                           value="{{ $cities }}"
                                           placeholder="28">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Footer Text</label>
                                    <textarea name="items[{{ $idx }}][header_text]"
                                              class="propTextarea"
                                              placeholder="37 Communities in Chicago">{{ $headerText }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $idx++; @endphp
                @endforeach

                @if($idx === 0)
                    {{-- Default empty item --}}
                    <div class="loop-item" data-index="0">
                        <div class="loop-item-header">
                            <div class="loop-item-title">Partner #<span class="partner-index">1</span></div>
                            <button type="button" class="btn btn-xs btn-outline-danger remove-partner-item">
                                Remove
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Title (Tooltip / Internal)</label>
                                    <input type="text"
                                           name="items[0][title]"
                                           class="propInput"
                                           placeholder="Mac Properties">
                                </div>
                                <div class="propFormGroup">
                                    <label class="propLabel">Profile Link (URL)</label>
                                    <input type="text"
                                           name="items[0][link]"
                                           class="propInput"
                                           placeholder="{{ route('realtor.profile') }}">
                                </div>
                                <div class="propFormGroup">
                                    <label class="propLabel">Logo Image</label>
                                    <input type="file"
                                           name="items[0][image]"
                                           class="propInput"
                                           accept="image/*">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Communities Count</label>
                                    <input type="text"
                                           name="items[0][communities]"
                                           class="propInput"
                                           placeholder="28">
                                </div>
                                <div class="propFormGroup">
                                    <label class="propLabel">Units Count</label>
                                    <input type="text"
                                           name="items[0][units]"
                                           class="propInput"
                                           placeholder="28">
                                </div>
                                <div class="propFormGroup">
                                    <label class="propLabel">Cities Count</label>
                                    <input type="text"
                                           name="items[0][cities]"
                                           class="propInput"
                                           placeholder="28">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Footer Text</label>
                                    <textarea name="items[0][header_text]"
                                              class="propTextarea"
                                              placeholder="37 Communities in Chicago"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-end align-items-center mb-2">
                <button id="addPartnerItem" type="button" class="btn btn-sm btn-dark">
                    + Add Partner
                </button>
            </div>

            <button type="button"
                    class="btn btn-primary update-btn mt-2"
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
        (function () {
            const repeater = document.getElementById('partnersRepeater');
            const addBtn   = document.getElementById('addPartnerItem');

            if (!repeater || !addBtn) return;

            function refreshIndexes() {
                const items = repeater.querySelectorAll('.loop-item');
                items.forEach(function (item, idx) {
                    item.dataset.index = idx;
                    const indexLabel = item.querySelector('.partner-index');
                    if (indexLabel) indexLabel.textContent = idx + 1;

                    // rename inputs
                    item.querySelectorAll('input[name], textarea[name]').forEach(function (field) {
                        field.name = field.name.replace(/items\[\d+]/, 'items[' + idx + ']');
                    });
                });
            }

            repeater.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-partner-item')) {
                    const item = e.target.closest('.loop-item');
                    if (item && repeater.children.length > 1) {
                        item.remove();
                        refreshIndexes();
                    }
                }
            });

            addBtn.addEventListener('click', function () {
                const items = repeater.querySelectorAll('.loop-item');
                const last  = items[items.length - 1];
                const clone = last.cloneNode(true);

                // clear values
                clone.querySelectorAll('input[type="text"], input[type="hidden"], textarea').forEach(function (field) {
                    field.value = '';
                });
                // file inputs – reset
                clone.querySelectorAll('input[type="file"]').forEach(function (field) {
                    field.value = '';
                });
                // remove previews
                clone.querySelectorAll('.media-preview').forEach(function (preview) {
                    preview.remove();
                });

                repeater.appendChild(clone);
                refreshIndexes();
            });
        })();
    </script>
@endpush
