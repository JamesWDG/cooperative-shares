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
    .blog-item-box {
        border: 1px dashed #D7DCE7;
        border-radius: 10px;
        padding: 12px 12px 6px;
        margin-bottom: 12px;
        background: #FBFCFF;
    }
    .blog-item-title {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    @include('includes.admin.cms.section-heading')

    @php
        $isArr       = is_array($sectionData);
        $miniHeading = $isArr ? ($sectionData['mini_heading'] ?? '')          : ($sectionData->mini_heading ?? '');
        $heading     = $isArr ? ($sectionData['heading'] ?? '')               : ($sectionData->heading ?? '');
        $viewBtnText = $isArr ? ($sectionData['view_all_button_text'] ?? '')  : ($sectionData->view_all_button_text ?? '');
        $viewBtnLink = $isArr ? ($sectionData['view_all_button_link'] ?? '')  : ($sectionData->view_all_button_link ?? '');
        $items       = $isArr ? ($sectionData['items'] ?? [])                 : ($sectionData->items ?? []);

        if (!is_array($items)) {
            $items = [];
        }

        $mediaBasePath = 'storage/cms/'.$page->page_key.'/'.$sectionType.'/';

        // ✅ We only want exactly 3 blogs
        $maxItems = 3;

        // If more than 3 exist in JSON, keep only first 3
        if (count($items) > $maxItems) {
            $items = array_slice($items, 0, $maxItems);
        }

        // Fill up to 3 slots if fewer exist
        for ($i = count($items); $i < $maxItems; $i++) {
            $items[] = [];
        }
    @endphp

    <div class="propFormContainer">
        <form id="home-blogs-form"
              action="{{ route('admin.cms.home.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-submit">
            @csrf

            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div class="propFormGroup">
                <label class="propLabel">Mini Heading</label>
                <input type="text"
                       name="mini_heading"
                       class="propInput"
                       value="{{ $miniHeading }}"
                       placeholder="Our Blogs">
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Main Heading <span class="text-danger">*</span></label>
                <input type="text"
                       name="heading"
                       class="propInput"
                       value="{{ $heading }}"
                       placeholder="Latest News & Updates">
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="propFormGroup">
                        <label class="propLabel">View All Button Text</label>
                        <input type="text"
                               name="view_all_button_text"
                               class="propInput"
                               value="{{ $viewBtnText }}"
                               placeholder="View All">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="propFormGroup">
                        <label class="propLabel">View All Button Link</label>
                        <input type="text"
                               name="view_all_button_link"
                               class="propInput"
                               value="{{ $viewBtnLink }}"
                               placeholder="{{ route('blogs') }}">
                    </div>
                </div>
            </div>

            <hr>

            <h5 class="mb-2" style="font-size:14px;">Blog Cards (max 3, fixed)</h5>

            @foreach($items as $i => $item)
                @php
                    $image          = $item['image'] ?? null;          // featured_img
                    $date           = $item['date'] ?? '';
                    $title          = $item['title'] ?? '';
                    $slug           = $item['slug'] ?? '';
                    $shortDes       = $item['short_des'] ?? '';
                    $longDes        = $item['long_des'] ?? '';
                    $readInMinutes  = $item['read_in_minutes'] ?? '';
                    $linkText       = $item['link_text'] ?? 'Read More';
                    $linkUrl        = $item['link_url'] ?? '#';
                @endphp

                <div class="blog-item-box">
                    <div class="blog-item-title">Blog Card #{{ $i + 1 }}</div>
                    <div class="row">

                        {{-- Image + Date --}}
                        <div class="col-md-3">
                            <div class="propFormGroup">
                                <label class="propLabel">Blog Image (Featured)</label>
                                <input type="file"
                                       name="items[{{ $i }}][image]"
                                       class="propInput"
                                       accept="image/*">
                                @if($image)
                                    <div class="media-preview">
                                        <img src="{{ asset($mediaBasePath.$image) }}" alt="Blog Image">
                                    </div>
                                @endif
                            </div>

                            <div class="propFormGroup">
                                <label class="propLabel">Date Label</label>
                                <input type="text"
                                       name="items[{{ $i }}][date]"
                                       class="propInput"
                                       value="{{ $date }}"
                                       placeholder="Jan 28, 2025">
                            </div>

                            <div class="propFormGroup">
                                <label class="propLabel">Read Time (minutes)</label>
                                <input type="number"
                                       min="1"
                                       name="items[{{ $i }}][read_in_minutes]"
                                       class="propInput"
                                       value="{{ $readInMinutes }}"
                                       placeholder="3">
                            </div>
                        </div>

                        {{-- Title, Slug, Short Description --}}
                        <div class="col-md-5">
                            <div class="propFormGroup">
                                <label class="propLabel">Title</label>
                                <input type="text"
                                       name="items[{{ $i }}][title]"
                                       class="propInput"
                                       value="{{ $title }}"
                                       placeholder="What is Housing Cooperative?">
                            </div>

                            <div class="propFormGroup">
                                <label class="propLabel">Slug (optional – will auto-generate if empty)</label>
                                <input type="text"
                                       name="items[{{ $i }}][slug]"
                                       class="propInput"
                                       value="{{ $slug }}"
                                       placeholder="what-is-housing-cooperative">
                            </div>

                            <div class="propFormGroup">
                                <label class="propLabel">Short Description / Summary</label>
                                <textarea name="items[{{ $i }}][short_des]"
                                          class="propTextarea editor"
                                          placeholder="Short teaser text for listing...">{{ $shortDes }}</textarea>
                            </div>
                        </div>

                        {{-- Long Description (HTML) + Link Text/URL (optional) --}}
                        <div class="col-md-4">
                            <div class="propFormGroup">
                                <label class="propLabel">Full Content (HTML allowed)</label>
                                <textarea name="items[{{ $i }}][long_des]"
                                          class="propTextarea editor"
                                          placeholder="Full blog content for home detail page...">{{ $longDes }}</textarea>
                            </div>

                            <div class="propFormGroup">
                                <label class="propLabel">Link Text</label>
                                <input type="text"
                                       name="items[{{ $i }}][link_text]"
                                       class="propInput"
                                       value="{{ $linkText }}"
                                       placeholder="Read More">
                            </div>

                            <div class="propFormGroup">
                                <label class="propLabel">Link URL (optional)</label>
                                <input type="text"
                                       name="items[{{ $i }}][link_url]"
                                       class="propInput"
                                       value="{{ $linkUrl }}"
                                       placeholder="Will use slug route by default">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

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
@endpush
