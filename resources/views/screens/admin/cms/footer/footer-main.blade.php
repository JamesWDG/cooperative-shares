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
    .propTextarea,
    .propSelect {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 7px 10px;
        font-size: 13px;
    }
    .propTextarea {
        min-height: 80px;
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
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    @php
        $isArr        = is_array($sectionData);
        $logo         = $isArr ? ($sectionData['logo'] ?? null) : ($sectionData->logo ?? null);
        $description  = $isArr ? ($sectionData['description'] ?? '') : ($sectionData->description ?? '');

        $social       = $isArr ? ($sectionData['social_links'] ?? []) : ($sectionData->social_links ?? []);
        if (!is_array($social)) { $social = []; }

        $facebook     = $social['facebook'] ?? '';
        $twitter      = $social['twitter'] ?? '';
        $linkedin     = $social['linkedin'] ?? '';

        $quickLinks   = $isArr ? ($sectionData['quick_links'] ?? []) : ($sectionData->quick_links ?? []);
        if (!is_array($quickLinks)) { $quickLinks = []; }

        $helpfulLinks = $isArr ? ($sectionData['helpful_links'] ?? []) : ($sectionData->helpful_links ?? []);
        if (!is_array($helpfulLinks)) { $helpfulLinks = []; }

        $contact      = $isArr ? ($sectionData['contact'] ?? []) : ($sectionData->contact ?? []);
        if (!is_array($contact)) { $contact = []; }

        $contactPhone   = $contact['phone'] ?? '';
        $contactEmail   = $contact['email'] ?? '';
        $contactAddress = $contact['address'] ?? '';

        $bottomBar    = $isArr ? ($sectionData['bottom_bar'] ?? []) : ($sectionData->bottom_bar ?? []);
        if (!is_array($bottomBar)) { $bottomBar = []; }

        $bottomLeftText  = $bottomBar['left_text'] ?? '';
        $bottomRightText = $bottomBar['right_text'] ?? '';

        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';
    @endphp

    <div class="propFormContainer">
        <form id="footer-form"
              action="{{ route('admin.cms.footer.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            {{-- LOGO + DESCRIPTION --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Footer Logo</label>
                        <input type="file"
                               name="logo"
                               class="propInput"
                               accept="image/*">
                        @if($logo)
                            <div class="media-preview">
                                <img src="{{ asset($mediaBasePath.$logo) }}" alt="Footer Logo">
                            </div>
                        @endif
                        <p class="small-hint mb-0">Recommended transparent PNG for best results.</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="propFormGroup">
                        <label class="propLabel">Short Description</label>
                        <textarea name="description"
                                  class="propTextarea"
                                  placeholder="Short footer description...">{{ $description }}</textarea>
                        <p class="small-hint mb-0">Shown under the logo; you can use basic HTML if needed.</p>
                    </div>
                </div>
            </div>

            <hr>

            {{-- SOCIAL LINKS --}}
            <h5 style="font-size:14px;">Social Links</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Facebook URL</label>
                        <input type="text"
                               name="facebook_url"
                               class="propInput"
                               value="{{ $facebook }}"
                               placeholder="https://facebook.com/...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Twitter (X) URL</label>
                        <input type="text"
                               name="twitter_url"
                               class="propInput"
                               value="{{ $twitter }}"
                               placeholder="https://x.com/...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">LinkedIn URL</label>
                        <input type="text"
                               name="linkedin_url"
                               class="propInput"
                               value="{{ $linkedin }}"
                               placeholder="https://linkedin.com/company/...">
                    </div>
                </div>
            </div>

            <hr>

            {{-- QUICK LINKS REPEATER --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0" style="font-size:14px;">Quick Links (Left Column)</h5>
                <button id="addQuickLink" type="button" class="btn btn-sm btn-dark">
                    + Add Quick Link
                </button>
            </div>
            <p class="small-hint">
                Use either <strong>Route Name</strong> (recommended) or direct URL. If "Is Route?" is checked, the route name will be used to generate URL.
            </p>

            <div id="quickLinksRepeater">
                @php $qIdx = 0; @endphp
                @foreach($quickLinks as $link)
                    @php
                        $label     = $link['label'] ?? '';
                        $url       = $link['url'] ?? '';
                        $isRoute   = (bool)($link['is_route'] ?? false);
                        $routeName = $link['route_name'] ?? '';
                    @endphp
                    <div class="loop-item" data-index="{{ $qIdx }}">
                        <div class="loop-item-header">
                            <div class="loop-item-title">Quick Link #<span class="quick-index">{{ $qIdx + 1 }}</span></div>
                            <button type="button" class="btn btn-xs btn-outline-danger remove-quick-link">
                                Remove
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Label <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="quick_links[{{ $qIdx }}][label]"
                                           class="propInput"
                                           value="{{ $label }}"
                                           placeholder="Home">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Route Name</label>
                                    <input type="text"
                                           name="quick_links[{{ $qIdx }}][route_name]"
                                           class="propInput"
                                           value="{{ $routeName }}"
                                           placeholder="index">
                                    <p class="small-hint mb-0">Example: <code>index</code>, <code>about</code>, <code>listings</code>.</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="propFormGroup">
                                    <label class="propLabel">Direct URL</label>
                                    <input type="text"
                                           name="quick_links[{{ $qIdx }}][url]"
                                           class="propInput"
                                           value="{{ $url }}"
                                           placeholder="/custom-url">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="propFormGroup">
                                    <label class="propLabel">Is Route?</label>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="quick_links[{{ $qIdx }}][is_route]"
                                               value="1"
                                               {{ $isRoute ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $qIdx++; @endphp
                @endforeach

                @if($qIdx === 0)
                    <div class="loop-item" data-index="0">
                        <div class="loop-item-header">
                            <div class="loop-item-title">Quick Link #<span class="quick-index">1</span></div>
                            <button type="button" class="btn btn-xs btn-outline-danger remove-quick-link">
                                Remove
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Label <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="quick_links[0][label]"
                                           class="propInput"
                                           placeholder="Home">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Route Name</label>
                                    <input type="text"
                                           name="quick_links[0][route_name]"
                                           class="propInput"
                                           placeholder="index">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="propFormGroup">
                                    <label class="propLabel">Direct URL</label>
                                    <input type="text"
                                           name="quick_links[0][url]"
                                           class="propInput"
                                           placeholder="/custom-url">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="propFormGroup">
                                    <label class="propLabel">Is Route?</label>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="quick_links[0][is_route]"
                                               value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <hr>

            {{-- HELPFUL LINKS REPEATER --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0" style="font-size:14px;">Helpful Links (Middle Column)</h5>
                <button id="addHelpfulLink" type="button" class="btn btn-sm btn-dark">
                    + Add Helpful Link
                </button>
            </div>

            <div id="helpfulLinksRepeater">
                @php $hIdx = 0; @endphp
                @foreach($helpfulLinks as $link)
                    @php
                        $label     = $link['label'] ?? '';
                        $url       = $link['url'] ?? '';
                        $isRoute   = (bool)($link['is_route'] ?? false);
                        $routeName = $link['route_name'] ?? '';
                    @endphp
                    <div class="loop-item" data-index="{{ $hIdx }}">
                        <div class="loop-item-header">
                            <div class="loop-item-title">Helpful Link #<span class="helpful-index">{{ $hIdx + 1 }}</span></div>
                            <button type="button" class="btn btn-xs btn-outline-danger remove-helpful-link">
                                Remove
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Label <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="helpful_links[{{ $hIdx }}][label]"
                                           class="propInput"
                                           value="{{ $label }}"
                                           placeholder="Privacy Policy">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Route Name</label>
                                    <input type="text"
                                           name="helpful_links[{{ $hIdx }}][route_name]"
                                           class="propInput"
                                           value="{{ $routeName }}"
                                           placeholder="privacy.policy">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="propFormGroup">
                                    <label class="propLabel">Direct URL</label>
                                    <input type="text"
                                           name="helpful_links[{{ $hIdx }}][url]"
                                           class="propInput"
                                           value="{{ $url }}"
                                           placeholder="/privacy-policy">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="propFormGroup">
                                    <label class="propLabel">Is Route?</label>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="helpful_links[{{ $hIdx }}][is_route]"
                                               value="1"
                                               {{ $isRoute ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $hIdx++; @endphp
                @endforeach

                @if($hIdx === 0)
                    <div class="loop-item" data-index="0">
                        <div class="loop-item-header">
                            <div class="loop-item-title">Helpful Link #<span class="helpful-index">1</span></div>
                            <button type="button" class="btn btn-xs btn-outline-danger remove-helpful-link">
                                Remove
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Label <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="helpful_links[0][label]"
                                           class="propInput"
                                           placeholder="Cooperative Differences">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">Route Name</label>
                                    <input type="text"
                                           name="helpful_links[0][route_name]"
                                           class="propInput"
                                           placeholder="cooperrative.differences">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="propFormGroup">
                                    <label class="propLabel">Direct URL</label>
                                    <input type="text"
                                           name="helpful_links[0][url]"
                                           class="propInput"
                                           placeholder="/cooperrative-differences">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="propFormGroup">
                                    <label class="propLabel">Is Route?</label>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="helpful_links[0][is_route]"
                                               value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <hr>

            {{-- CONTACT INFO --}}
            <h5 style="font-size:14px;">Contact Info</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Phone</label>
                        <input type="text"
                               name="contact_phone"
                               class="propInput"
                               value="{{ $contactPhone }}"
                               placeholder="816-529-7022">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Email</label>
                        <input type="text"
                               name="contact_email"
                               class="propInput"
                               value="{{ $contactEmail }}"
                               placeholder="anderson@theaapg.com">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">Address (HTML allowed)</label>
                        <textarea name="contact_address"
                                  class="propTextarea"
                                  placeholder="520 E 4th st Tonganoxie, KS 66086">{{ $contactAddress }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            {{-- BOTTOM BAR --}}
            <h5 style="font-size:14px;">Bottom Bar Text</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Left Text (HTML allowed)</label>
                        <textarea name="bottom_left_text"
                                  class="propTextarea"
                                  placeholder="© Copyright 2025 &lt;strong&gt;Cooperative Homes&lt;/strong&gt;. All rights Reserved">{{ $bottomLeftText }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="propFormGroup">
                        <label class="propLabel">Right Text (HTML allowed)</label>
                        <textarea name="bottom_right_text"
                                  class="propTextarea"
                                  placeholder="Design &amp; Developed by: &lt;strong&gt;&lt;a href='https://www.webdesignglory.com/'&gt;Web Design Glory&lt;/a&gt;&lt;/strong&gt;">{{ $bottomRightText }}</textarea>
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

    <script>
        (function () {
            // ---------- QUICK LINKS ----------
            const quickRepeater = document.getElementById('quickLinksRepeater');
            const addQuickBtn   = document.getElementById('addQuickLink');

            function refreshQuickIndexes() {
                if (!quickRepeater) return;
                const items = quickRepeater.querySelectorAll('.loop-item');
                items.forEach(function (item, idx) {
                    item.dataset.index = idx;
                    const label = item.querySelector('.quick-index');
                    if (label) label.textContent = idx + 1;

                    item.querySelectorAll('input[name], textarea[name]').forEach(function (field) {
                        field.name = field.name.replace(/quick_links\[\d+]/, 'quick_links[' + idx + ']');
                    });
                });
            }

            if (quickRepeater) {
                quickRepeater.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-quick-link')) {
                        const item = e.target.closest('.loop-item');
                        if (item && quickRepeater.children.length > 1) {
                            item.remove();
                            refreshQuickIndexes();
                        }
                    }
                });
            }

            if (addQuickBtn && quickRepeater) {
                addQuickBtn.addEventListener('click', function () {
                    const items = quickRepeater.querySelectorAll('.loop-item');
                    const last  = items[items.length - 1];
                    const clone = last.cloneNode(true);

                    // Clear values
                    clone.querySelectorAll('input[type="text"], textarea').forEach(function (field) {
                        field.value = '';
                    });
                    clone.querySelectorAll('input[type="checkbox"]').forEach(function (chk) {
                        chk.checked = false;
                    });

                    quickRepeater.appendChild(clone);
                    refreshQuickIndexes();
                });
            }

            // ---------- HELPFUL LINKS ----------
            const helpfulRepeater = document.getElementById('helpfulLinksRepeater');
            const addHelpfulBtn   = document.getElementById('addHelpfulLink');

            function refreshHelpfulIndexes() {
                if (!helpfulRepeater) return;
                const items = helpfulRepeater.querySelectorAll('.loop-item');
                items.forEach(function (item, idx) {
                    item.dataset.index = idx;
                    const label = item.querySelector('.helpful-index');
                    if (label) label.textContent = idx + 1;

                    item.querySelectorAll('input[name], textarea[name]').forEach(function (field) {
                        field.name = field.name.replace(/helpful_links\[\d+]/, 'helpful_links[' + idx + ']');
                    });
                });
            }

            if (helpfulRepeater) {
                helpfulRepeater.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-helpful-link')) {
                        const item = e.target.closest('.loop-item');
                        if (item && helpfulRepeater.children.length > 1) {
                            item.remove();
                            refreshHelpfulIndexes();
                        }
                    }
                });
            }

            if (addHelpfulBtn && helpfulRepeater) {
                addHelpfulBtn.addEventListener('click', function () {
                    const items = helpfulRepeater.querySelectorAll('.loop-item');
                    const last  = items[items.length - 1];
                    const clone = last.cloneNode(true);

                    // Clear values
                    clone.querySelectorAll('input[type="text"], textarea').forEach(function (field) {
                        field.value = '';
                    });
                    clone.querySelectorAll('input[type="checkbox"]').forEach(function (chk) {
                        chk.checked = false;
                    });

                    helpfulRepeater.appendChild(clone);
                    refreshHelpfulIndexes();
                });
            }
        })();
    </script>
@endpush
