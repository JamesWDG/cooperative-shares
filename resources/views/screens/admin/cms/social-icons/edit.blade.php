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

    .propInput[readonly] {
        background-color: #f5f6fa;
        cursor: not-allowed;
    }

    .social-card-title {
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .social-card-wrapper {
        border: 1px solid #E3E7F0;
        border-radius: 10px;
        padding: 15px 15px 5px;
        margin-bottom: 15px;
        background: #F9FAFC;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
   @include('includes.admin.cms.section-heading')
    <div class="propFormContainer">

        <form id="social-icons-form"
              action="{{ route('admin.cms.social-icons.update') }}"
              method="POST"
              class="form-submit">
            @csrf

            {{-- required for controller --}}
            <input type="hidden" name="page_key" value="{{ $page->page_key }}">
            <input type="hidden" name="section_key" value="{{ $sectionType }}">

            <div id="social-icons-wrapper">
                @foreach($socialLinks as $index => $item)
                    @php
                        $platform = is_array($item) ? ($item['platform'] ?? '') : ($item->platform ?? '');
                        $icon     = is_array($item) ? ($item['icon'] ?? '')     : ($item->icon ?? '');
                        $url      = is_array($item) ? ($item['url'] ?? '')      : ($item->url ?? '');
                        $sort     = is_array($item) ? ($item['sort'] ?? null)   : ($item->sort ?? null);
                        if ($sort === null || $sort === '') {
                            $sort = $index + 1;
                        }
                    @endphp

                    <div class="social-card-wrapper">
                        <div class="social-card-title">
                            Social Icon #{{ $index + 1 }}
                        </div>

                        <div class="row">
                            {{-- Platform (readonly) --}}
                            <div class="col-md-3">
                                <div class="propFormGroup">
                                    <label class="propLabel">Platform <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="social_links[{{ $index }}][platform]"
                                           class="propInput"
                                           value="{{ $platform }}"
                                           readonly>
                                </div>
                            </div>

                            {{-- Icon (readonly) --}}
                            <div class="col-md-4">
                                <div class="propFormGroup">
                                    <label class="propLabel">FontAwesome Icon Class <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="social_links[{{ $index }}][icon]"
                                           class="propInput"
                                           value="{{ $icon }}"
                                           readonly>
                                </div>
                            </div>

                            {{-- URL (editable) --}}
                            <div class="col-md-3">
                                <div class="propFormGroup">
                                    <label class="propLabel">URL <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="social_links[{{ $index }}][url]"
                                           class="propInput"
                                           value="{{ $url }}"
                                           placeholder="https://facebook.com/your-page">
                                </div>
                            </div>

                            {{-- Sort (dropdown 1–4) --}}
                            <div class="col-md-2">
                                <div class="propFormGroup">
                                    <label class="propLabel">Sort</label>
                                    <select name="social_links[{{ $index }}][sort]"
                                            class="propSelect">
                                        @for($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ (int)$sort === $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
