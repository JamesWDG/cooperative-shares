@extends('layouts.web.app')

@php
    $page              = $page ?? false;
    $aboutMain         = $aboutMain ?? null;
    $ourStory          = $ourStory ?? null;
    $exploreProperties = $exploreProperties ?? null;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="About Us" :route="route('index')" />

    {{-- ABOUT MAIN SECTION --}}
    <section class="realEstate-sec 6 position-relative">
        <div class="container">
            <div class="row">

                {{-- Left side decorative / side image (use image_3 if present) --}}
                <div class="col-lg-3">
                    {{-- @if($aboutMain && !empty($aboutMain['image_3']))
                        <div class="about-state-adz">
                            <img
                                src="{{ asset('storage/cms/about/about_main/' . $aboutMain['image_3']) }}"
                                alt=""
                            >
                        </div>
                    @endif --}}
                    <div class="about-state-adz">
                        @if(isset($adsByPackage["Sidebar Ad"][0]))
                        @php
                            $SideBarAdFirst = $adsByPackage["Sidebar Ad"][0];
                            
                        @endphp
                            <a href="{{ route('listing.detail', $SideBarAdFirst['listing_id']) }}"><img src="{{ asset('storage/add-images/'.$SideBarAdFirst['image']) }}" alt="" class="index-slide-img"></a>
                        @else
                            <img src="{{ asset('assets/web/images/about-3.png') }}" alt="">
                        @endif
                    </div>
                </div>

                {{-- Center main content --}}
                <div class="col-lg-6">
                    <div class="realEstate-content">

                        {{-- Heading: prefix + highlight in <span> --}}
                        @if($aboutMain && (!empty($aboutMain['heading_prefix']) || !empty($aboutMain['heading_highlight'])))
                            <h2 class="sec-hd mb-4">
                                {{ $aboutMain['heading_prefix'] ?? '' }}
                                @if(!empty($aboutMain['heading_highlight']))
                                    <span> {{ $aboutMain['heading_highlight'] }}</span>
                                @endif
                            </h2>
                        @endif

                        {{-- Tagline --}}
                        @if($aboutMain && !empty($aboutMain['tagline']))
                            <p class="para">
                                <strong>{{ $aboutMain['tagline'] }}</strong>
                            </p>
                        @endif

                        {{-- Paragraph 1 (HTML from CMS) --}}
                        @if($aboutMain && !empty($aboutMain['paragraph_1']))
                            <div class="para">
                                {!! $aboutMain['paragraph_1'] !!}
                            </div>
                        @endif

                        {{-- Two inner images (image_1, image_2) --}}
                        <div class="row">
                            @if($aboutMain && !empty($aboutMain['image_1']))
                                <div class="col-lg-6">
                                    <img
                                        src="{{ asset('storage/cms/about/about_main/' . $aboutMain['image_1']) }}"
                                        alt=""
                                        class="img-fluid realstate-img"
                                    >
                                </div>
                            @endif

                            @if($aboutMain && !empty($aboutMain['image_2']))
                                <div class="col-lg-6">
                                    <img
                                        src="{{ asset('storage/cms/about/about_main/' . $aboutMain['image_2']) }}"
                                        alt=""
                                        class="img-fluid realstate-img"
                                    >
                                </div>
                            @endif
                        </div>

                        {{-- Paragraph 2 & 3 (HTML) --}}
                        @if($aboutMain && !empty($aboutMain['paragraph_2']))
                            <div class="para para-2">
                                {!! $aboutMain['paragraph_2'] !!}
                            </div>
                        @endif

                        @if($aboutMain && !empty($aboutMain['paragraph_3']))
                            <div class="para para-2">
                                {!! $aboutMain['paragraph_3'] !!}
                            </div>
                        @endif

                        {{-- Quality boxes (box_1 & box_2) --}}
                        @if($aboutMain && (!empty($aboutMain['box_1']) || !empty($aboutMain['box_2'])))
                            <div class="about-quality-box-wrapper">
                                <div class="about-quality-box">

                                    {{-- Box 1 --}}
                                    @if(!empty($aboutMain['box_1']))
                                        <div>
                                            @if(!empty($aboutMain['box_1']['logo']))
                                                <img
                                                    src="{{ asset('storage/cms/about/about_main/' . $aboutMain['box_1']['logo']) }}"
                                                    alt=""
                                                >
                                            @endif

                                            @if(!empty($aboutMain['box_1']['title']))
                                                <h5>{{ $aboutMain['box_1']['title'] }}</h5>
                                            @endif

                                            @if(!empty($aboutMain['box_1']['description']))
                                                <p>{!! nl2br(e($aboutMain['box_1']['description'])) !!}</p>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Box 2 --}}
                                    @if(!empty($aboutMain['box_2']))
                                        <div>
                                            @if(!empty($aboutMain['box_2']['logo']))
                                                <img
                                                    src="{{ asset('storage/cms/about/about_main/' . $aboutMain['box_2']['logo']) }}"
                                                    alt=""
                                                >
                                            @endif

                                            @if(!empty($aboutMain['box_2']['title']))
                                                <h5>{{ $aboutMain['box_2']['title'] }}</h5>
                                            @endif

                                            @if(!empty($aboutMain['box_2']['description']))
                                                <p>{!! nl2br(e($aboutMain['box_2']['description'])) !!}</p>
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Right side image (you can reuse image_3 or leave empty) --}}
                <div class="col-lg-3">
                    {{-- @if($aboutMain && !empty($aboutMain['image_3']))
                        <div class="about-state-adz">
                            <img
                                src="{{ asset('storage/cms/about/about_main/' . $aboutMain['image_3']) }}"
                                alt=""
                            >
                        </div>
                    @endif --}}
                    <div class="about-state-adz">
                        @if(isset($adsByPackage["Sidebar Ad"][1]))
                        @php
                            $SideBarAdSecond = $adsByPackage["Sidebar Ad"][1];
                            
                        @endphp
                            <a href="{{ route('listing.detail', $SideBarAdSecond['listing_id']) }}"><img src="{{ asset('storage/add-images/'.$SideBarAdSecond['image']) }}" alt="" class="index-slide-img"></a>
                        @else
                            <img src="{{ asset('assets/web/images/about-3.png') }}" alt="">
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- LOOP SLIDER (already dynamic via its own component) --}}
    <x-loop-slider-section/>

    {{-- OUR STORY + WHY CHOOSE US --}}
    <section class="mission-sec">
        <div class="container">
            <div class="row">
                {{-- Top header decorative image (keep static if you like) --}}
                <div class="col-lg-12">
                    <div class="hdr-adz-img">
                    @if(isset($adsByPackage["Front Page & Footer Sponsor Slot"]))
                        @php
                            $frontPageAd = $adsByPackage["Front Page & Footer Sponsor Slot"];
                            $randomfrontPageAd = $frontPageAd->random();
                            $randomfrontPageAdImage = $randomfrontPageAd?->image;
                        @endphp
                        <!--baad men random ki image lagygi-->
                        <img src="{{ asset('assets/web/images/hd-adz-old.png') }}" alt="">
                    @else
                        <img src="{{ asset('assets/web/images/hd-adz.png') }}" alt="">
                    @endif
                    </div>
                </div>

                {{-- Left story image --}}
                <div class="col-lg-5 col-12">
                    @if($ourStory && !empty($ourStory['image_left']))
                        <img
                            src="{{ asset('storage/cms/about/our_story/' . $ourStory['image_left']) }}"
                            alt=""
                            class="img-fluid mission-img"
                        >
                    @endif
                </div>

                {{-- Center cards: Our Story + Why Choose Us --}}
                <div class="col-lg-3 px-0">
                    {{-- Our Story card --}}
                    @if($ourStory && (!empty($ourStory['our_story_heading']) || !empty($ourStory['our_story_paragraph_1']) || !empty($ourStory['our_story_paragraph_2'])))
                        <div class="mission-card mission-card2">
                            @if(!empty($ourStory['our_story_heading']))
                                <h2 class="sec-hd text-center">
                                    {{ $ourStory['our_story_heading'] }}
                                </h2>
                            @endif

                            @if(!empty($ourStory['our_story_paragraph_1']))
                                <div class="para text-center">
                                    {!! $ourStory['our_story_paragraph_1'] !!}
                                </div>
                            @endif

                            @if(!empty($ourStory['our_story_paragraph_2']))
                                <div class="para text-center">
                                    {!! $ourStory['our_story_paragraph_2'] !!}
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Why Choose Us card --}}
                    @if($ourStory && (!empty($ourStory['why_choose_heading']) || !empty($ourStory['why_choose_items'])))
                        <div class="mission-card mission-card3">
                            @if(!empty($ourStory['why_choose_heading']))
                                <h2 class="sec-hd text-center">
                                    {{ $ourStory['why_choose_heading'] }}
                                </h2>
                            @endif

                            @if(!empty($ourStory['why_choose_items']))
                                @foreach($ourStory['why_choose_items'] as $item)
                                    <p class="para text-center">
                                        @if(!empty($item['title']))
                                            <span>{{ $item['title'] }}</span><br>
                                        @endif
                                        @if(!empty($item['description']))
                                            {{ $item['description'] }}
                                        @endif
                                    </p>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Right story image --}}
                <div class="col-lg-4">
                    @if($ourStory && !empty($ourStory['image_right']))
                        <img
                            src="{{ asset('storage/cms/about/our_story/' . $ourStory['image_right']) }}"
                            alt=""
                            class="img-fluid mission-img"
                        >
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- EXPLORE PROPERTIES (TEXT) --}}
    <section class="luxury-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if($exploreProperties)
                        <div class="luxury-dev">
                            @if(!empty($exploreProperties['heading']))
                                <h2 class="sec-hd text-center">
                                    {{ $exploreProperties['heading'] }}
                                </h2>
                            @endif

                            @if(!empty($exploreProperties['paragraph_1']))
                                <div class="para text-center">
                                    {!! $exploreProperties['paragraph_1'] !!}
                                </div>
                            @endif

                            @if(!empty($exploreProperties['paragraph_2']))
                                <div class="para text-center">
                                    {!! $exploreProperties['paragraph_2'] !!}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- VIDEO + IMAGE STRIP (from explore_properties video_file & image_file) --}}
    <section class="cop-sec">
        <div class="cop-vedio position-relative">
            @if($exploreProperties && !empty($exploreProperties['video_file']))
                <video
                    src="{{ asset('storage/cms/about/explore_properties/' . $exploreProperties['video_file']) }}"
                    autoplay="true"
                    muted="true"
                    loop="true"
                    class="video2"
                >
                </video>
            @endif
        </div>

        @if($exploreProperties && !empty($exploreProperties['image_file']))
            <img
                src="{{ asset('storage/cms/about/explore_properties/' . $exploreProperties['image_file']) }}"
                alt=""
                class="img-fluid cop-img"
            >
        @endif
    </section>

    {{-- Decorative header strip at bottom (static is fine) --}}
    <section class="adz-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hdr-adz-img">
                        @if(isset($adsByPackage["Front Page & Footer Sponsor Slot"]))
                            @php
                                $frontPageAd = $adsByPackage["Front Page & Footer Sponsor Slot"];
                                $randomfrontPageAd = $frontPageAd->random();
                                $randomfrontPageAdImage = $randomfrontPageAd?->image;
                            @endphp
                            <!--baad men random ki image lagygi-->
                            <img src="{{ asset('assets/web/images/hd-adz-old.png') }}" alt="">
                        @else
                            <img src="{{ asset('assets/web/images/hd-adz.png') }}" alt="">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-partner-section/> 
    

@endsection
