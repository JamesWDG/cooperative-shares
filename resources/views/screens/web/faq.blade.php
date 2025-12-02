@extends('layouts.web.app')

@php
    $page = $page ?? false;
    $faqs = $faqs ?? null;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="FAQs" :route="route('index')" />

    <section class="co-op-diff-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    @if($faqs && !empty($faqs['items']))
                        {{-- Optional main title from CMS --}}
                        @if(!empty($faqs['main_title']))
                            <h2 class="sec-hd text-center mb-4">
                                {{ $faqs['main_title'] }}
                            </h2>
                        @endif

                        <div class="accordion" id="accordionExample">
                            @foreach($faqs['items'] as $index => $item)
                                @php
                                    $headingId  = 'headingFaq' . $index;
                                    $collapseId = 'collapseFaq' . $index;
                                @endphp

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="{{ $headingId }}">
                                        <button
                                            class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#{{ $collapseId }}"
                                            aria-expanded="false"
                                            aria-controls="{{ $collapseId }}"
                                        >
                                            {{ $item['title'] ?? '' }}
                                        </button>
                                    </h2>
                                    <div
                                        id="{{ $collapseId }}"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="{{ $headingId }}"
                                        data-bs-parent="#accordionExample"
                                    >
                                        <div class="accordion-body">
                                            {{ $item['description'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

    <x-partner-section />
@endsection
