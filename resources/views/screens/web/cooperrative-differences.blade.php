@extends('layouts.web.app')

@php
    // If controller didn't send something, keep it null.
    $page           = $page ?? false;
    $intro          = $intro ?? null;
    $keyDifferences = $keyDifferences ?? null;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Cooperative Differences" :route="route('index')" />

    <section class="co-op-diff-sec">
        <div class="container">

            {{-- INTRO SECTION --}}
            @if($intro)
                @if(!empty($intro['mini_heading']))
                    <h6 class="sec-hd-mini text-center">
                        {{ $intro['mini_heading'] }}
                    </h6>
                @endif

                @if(!empty($intro['main_heading']))
                    <h2 class="sec-hd text-center">
                        {{ $intro['main_heading'] }}
                    </h2>
                @endif

                @if(!empty($intro['paragraph']))
                    {{-- paragraph is stored as HTML in CMS --}}
                    <div class="sec-para text-center">
                        {!! $intro['paragraph'] !!}
                    </div>
                @endif
            @endif

            @if($keyDifferences)
                {{-- MAIN "KEY DIFFERENCES" HEADING --}}
                @if(!empty($keyDifferences['main_title']))
                    <h3 class="diff-hd diff-hd-big">
                        {{ $keyDifferences['main_title'] }}
                    </h3>
                @endif

                @php
                    $items = $keyDifferences['items'] ?? [];
                @endphp

                {{-- SUMMARY BLOCK (FIRST 3 ITEMS) --}}
                @if(!empty($items))
                    @foreach($items as $idx => $item)
                        @break($idx === 3) {{-- only first 3 --}}
                        @if(!empty($item['title']) || !empty($item['description']))
                            <h5 class="diff-para">
                                {{ $item['title'] ?? '' }}
                            </h5>

                            @if(!empty($item['description']))
                                <h5 class="diff-para1">
                                    {!! nl2br(e($item['description'])) !!}
                                </h5>
                            @endif
                        @endif
                    @endforeach
                @endif

                {{-- DETAILED SECTIONS (FROM 4th ITEM ONWARDS) --}}
                @if(!empty($items) && count($items) > 3)
                    @foreach($items as $idx => $item)
                        @continue($idx < 3)

                        @php
                            $title = $item['title'] ?? '';
                            $desc  = $item['description'] ?? '';
                            $lines = preg_split('/\r\n|\r|\n/', $desc);
                            $lines = array_filter($lines, fn($l) => trim($l) !== '');
                        @endphp

                        @if($title || !empty($lines))
                            <h3 class="diff-hd text-start">
                                {{ $title }}
                            </h3>

                            @if(!empty($lines))
                                <ul class="diff-listing">
                                    @foreach($lines as $line)
                                        <li>
                                            <p class="diff-para1 text-start">
                                                {{ $line }}
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    @endforeach
                @endif

                {{-- CLOSING TEXT --}}
                @if(!empty($keyDifferences['closing_text']))
                    <p class="diff-para1">
                        {{ $keyDifferences['closing_text'] }}
                    </p>
                @endif
            @endif

        </div>
    </section>

    <x-partner-section/>
@endsection
