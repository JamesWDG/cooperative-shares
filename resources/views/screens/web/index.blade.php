@extends('layouts.web.app')

@push('styles')
    <style>
        label.error {
            color: #dc3545 !important;
        }
    </style>
@endpush

@php
    $page = 'special-page';
    $pageKey = 'home'; // for CMS media paths
@endphp

@section('section')
    @php
        $wishlistItemIds = auth()->check() 
            ? auth()->user()->wishlist()->first()?->items()->pluck('listing_id')->toArray() ?? [] 
            : [];
    @endphp
    {{-- ================= HERO BANNER (CMS) ================= --}}
    @if($heroBanner)
    <section class="hero-banner">
        <div class="icon-box">
            <div class="line"></div>
            @php $social = $heroBanner['social_links'] ?? []; @endphp

            @if(!empty($social['facebook']))
                <a href="{{ $social['facebook'] }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
            @endif
            @if(!empty($social['twitter']))
                <a href="{{ $social['twitter'] }}" target="_blank"><i class="fa-brands fa-twitter"></i></a>
            @endif
            @if(!empty($social['linkedin']))
                <a href="{{ $social['linkedin'] }}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
            @endif
            {{-- @if(!empty($social['whatsapp']))
                <a href="{{ $social['whatsapp'] }}" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
            @endif --}}
        </div>
        <div class="video-area position-relative">
            <div>
                @php $videoFile = $heroBanner['video'] ?? null; @endphp
                @if($videoFile)
                    <video src="{{ asset('storage/cms/'.$pageKey.'/hero_banner/'.$videoFile) }}" autoplay="true" muted="true" loop="true" class="video1">
                        Your browser does not support the video tag.
                    </video>
                @endif
            </div>
            <div class="banner-content-wrapper">
                <div class="banner-content-area text-center">
                    @php $playImage = $heroBanner['play_image'] ?? null; @endphp
                    @if($playImage)
                        <img src="{{ asset('storage/cms/'.$pageKey.'/hero_banner/'.$playImage) }}" alt="" class="text-center">
                    @endif
                    
                   <h1 class="banner-hd">{!! nl2br($heroBanner['heading'] ?? '') !!}</h1>
                    
                    @if (!empty($heroBanner['description']))
                        {!! str_replace('<p>', '<p class="banner-para">', $heroBanner['description']) !!}
                    @endif

                    @php $form = $heroBanner['search_form'] ?? null; @endphp
                    @if($form)
                        <div class="banner-form-wrapper">
                            <form action="{{ route('listings') }}" method="GET" >
                                <div class="field-wrapper">
                                    <label for="">{{ $form['property_type_label'] ?? 'Property Type' }}</label>
                                    <select name="listing_type">
                                        @foreach($form['property_type_options'] ?? [] as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field-wrapper">
                                    <label for="">{{ $form['rooms_label'] ?? 'Rooms' }}</label>
                                    <input type="number" name="rooms" placeholder="{{ $form['rooms_placeholder'] ?? '' }}">
                                </div>
                                <div class="field-wrapper">
                                    <label for="">{{ $form['baths_label'] ?? 'Baths' }}</label>
                                    <input type="number" name="bathrooms" placeholder="{{ $form['baths_placeholder'] ?? '' }}">
                                </div>
                                <div class="field-wrapper">
                                    <label for="">{{ $form['sqfeet_label'] ?? 'Sq Feet' }}</label>
                                    <input type="number" name="sqfeet" placeholder="{{ $form['sqfeet_placeholder'] ?? '' }}">
                                </div>
                                <button type="submit" class="primary-btn">
                                    <i class="fa-solid fa-search"></i>
                                    {{ $form['search_button_text'] ?? 'Search' }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            @php $scrollImg = $heroBanner['scroll_image'] ?? null; @endphp
            @if($scrollImg)
                <a href="{{ $heroBanner['scroll_link'] ?? '#property' }}" class="scroll-box">
                    <img src="{{ asset('storage/cms/'.$pageKey.'/hero_banner/'.$scrollImg) }}" alt="">
                </a>
            @endif
        </div>
    </section>
    @endif
    {{-- =============== / HERO BANNER =============== --}}

    {{-- ================= PROPERTIES SECTION (CMS TEXT + dynamic listings) ================= --}}
    @if($propertiesSection)
    <section class="properties-sec" id="property">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-sm-12">
                    <div class="property-sec-header">
                        <div>
                            <h6 class="sec-hd-mini">{{ $propertiesSection['mini_heading'] ?? '' }}</h6>
                            <h2 class="sec-hd">{{ $propertiesSection['heading'] ?? '' }}</h2>
                        </div>
                        <div>
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-all-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-all" type="button" role="tab" aria-controls="nav-all"
                                        aria-selected="true">{{ $propertiesSection['tab_all_label'] ?? 'View All' }}</button>

                                    <button class="nav-link" id="nav-apartment-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-apartment" type="button" role="tab" aria-controls="nav-apartment"
                                        aria-selected="false">{{ $propertiesSection['tab_senior_55_label'] ?? '' }}</button>

                                    <button class="nav-link" id="nav-commercial-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-commercial" type="button" role="tab" aria-controls="nav-commercial"
                                        aria-selected="false">{{ $propertiesSection['tab_senior_62_label'] ?? '' }}</button>

                                    <button class="nav-link" id="nav-land-or-plot-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-land-or-plot" type="button" role="tab" aria-controls="nav-land-or-plot"
                                        aria-selected="false">{{ $propertiesSection['tab_family_label'] ?? '' }}</button>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="tab-content w-100" id="nav-tabContent">

                        {{-- ALL --}}
                        <div class="tab-pane fade show active w-100" id="nav-all" role="tabpanel"
                            aria-labelledby="nav-all-tab" tabindex="0">
                            <div class="property-slider-wrapper w-100 position-relative">
                                @foreach ($allListings as $listing)
                                    @include('includes.web.property-card', ['listing' => $listing])
                                @endforeach
                            </div>
                            @if($allListings->isNotEmpty())
                                <div class="property-card-slider-button-wrapper position-relative">
                                    <button class="property-button-prev slider-btn"><i class="fa-solid fa-arrow-left"></i></button>
                                    <button class="property-button-next slider-btn"><i class="fa-solid fa-arrow-right"></i></button>
                                </div>
                            @endif
                        </div>

                        {{-- Senior 55+ (apartment) --}}
                        <div class="tab-pane fade w-100" id="nav-apartment" role="tabpanel" aria-labelledby="nav-apartment-tab"
                            tabindex="0">
                            <div class="property-slider-wrapper w-100 position-relative">
                                @foreach ($apartmentListings as $apartmentListing)
                                    {{-- same card markup --}}
                                    @include('includes.web.property-card', ['listing' => $apartmentListing])
                                @endforeach
                            </div>
                            @if($apartmentListings->isNotEmpty())
                                <div class="property-card-slider-button-wrapper position-relative">
                                    <button class="property-button-prev slider-btn"><i class="fa-solid fa-arrow-left"></i></button>
                                    <button class="property-button-next slider-btn"><i class="fa-solid fa-arrow-right"></i></button>
                                </div>
                            @endif
                        </div>

                        {{-- Senior 62+ (commercial) --}}
                        <div class="tab-pane fade w-100" id="nav-commercial" role="tabpanel" aria-labelledby="nav-commercial-tab"
                            tabindex="0">
                            <div class="property-slider-wrapper w-100 position-relative">
                                @foreach ($commercialListings as $commercialListing)
                                    @include('includes.web.property-card', ['listing' => $commercialListing])
                                @endforeach
                            </div>
                            @if($commercialListings->isNotEmpty())
                                <div class="property-card-slider-button-wrapper position-relative">
                                    <button class="property-button-prev slider-btn"><i class="fa-solid fa-arrow-left"></i></button>
                                    <button class="property-button-next slider-btn"><i class="fa-solid fa-arrow-right"></i></button>
                                </div>
                            @endif
                        </div>

                        {{-- Family (land / plot) --}}
                        <div class="tab-pane fade w-100" id="nav-land-or-plot" role="tabpanel"
                            aria-labelledby="nav-land-or-plot-tab" tabindex="0">
                            <div class="property-slider-wrapper w-100 position-relative">
                                @foreach ($landOrPlotListings as $landOrPlotListing)
                                    @include('includes.web.property-card', ['listing' => $landOrPlotListing])
                                @endforeach
                            </div>
                            @if($landOrPlotListings->isNotEmpty())
                                <div class="property-card-slider-button-wrapper position-relative">
                                    <button class="property-button-prev slider-btn"><i class="fa-solid fa-arrow-left"></i></button>
                                    <button class="property-button-next slider-btn"><i class="fa-solid fa-arrow-right"></i></button>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-sm-12">
                    {{-- You can also make this from CMS if needed later --}}
                    @if(isset($adsByPackage["Sidebar Ad"]))
                    
                    @php
                        $SideBarAd = $adsByPackage["Sidebar Ad"];
                        $randomSidebar = $SideBarAd->random();
                    @endphp
                        <a href="{{ route('listing.detail', $randomSidebar?->listing_id) }}"><img src="{{ asset('storage/add-images/'.$randomSidebar?->image) }}" alt="" class="index-slide-img"></a>
                    @else
                        <img src="{{ asset('assets/web/images/about-3.png') }}" alt="">
                    @endif
                    {{-- <img src="{{ asset('assets/web/images/index-slide-img.png') }}" alt="" class="index-slide-img"> --}}
                </div>
            </div>

            <div class="d-flex justify-content-center mt-5">
                <a href="{{ route('listings') }}" class="primary-btn">
                    {{ $propertiesSection['view_all_button_text'] ?? 'View All' }}
                </a>
            </div>
        </div>
    </section>
    @endif
    {{-- =============== / PROPERTIES =============== --}}
    {{-- ================= HOME ABOUT SECTION ================= --}}
    @if($homeAbout)
    <section class="about-sec">
        <div class="container">
            <div class="row row-gap-5">
                <div class="col-lg-6">
                    @php $aboutImage = $homeAbout['about_image'] ?? null; @endphp
                    @if($aboutImage)
                        <img src="{{ asset('storage/cms/'.$pageKey.'/home_about/'.$aboutImage) }}" alt="" class="img-fluid">
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="about-content-area">
                        <h6 class="sec-hd-mini">{{ $homeAbout['mini_heading'] ?? '' }}</h6>
                        <h2 class="sec-hd">{!! $homeAbout['main_heading'] ?? '' !!}</h2>
                        <p class="sec-para">{!! $homeAbout['paragraph'] ?? '' !!}</p>

                        <div class="about-quality-box-wrapper">
                            <div class="about-quality-box">
                                @php $box1 = $homeAbout['box_1'] ?? []; @endphp
                                <div>
                                    @if(!empty($box1['logo']))
                                        <img src="{{ asset('storage/cms/'.$pageKey.'/home_about/'.$box1['logo']) }}" alt="">
                                    @endif
                                    <h5>{{ $box1['title'] ?? '' }}</h5>
                                    <p>{{ $box1['description'] ?? '' }}</p>
                                </div>

                                @php $box2 = $homeAbout['box_2'] ?? []; @endphp
                                <div>
                                    @if(!empty($box2['logo']))
                                        <img src="{{ asset('storage/cms/'.$pageKey.'/home_about/'.$box2['logo']) }}" alt="">
                                    @endif
                                    <h5>{{ $box2['title'] ?? '' }}</h5>
                                    <p>{{ $box2['description'] ?? '' }}</p>
                                </div>
                            </div>

                            <div class="about-quality-box">
                                @php $box3 = $homeAbout['box_3'] ?? []; @endphp
                                <div>
                                    @if(!empty($box3['logo']))
                                        <img src="{{ asset('storage/cms/'.$pageKey.'/home_about/'.$box3['logo']) }}" alt="">
                                    @endif
                                    <h5>{{ $box3['title'] ?? '' }}</h5>
                                    <p>{{ $box3['description'] ?? '' }}</p>
                                </div>

                                @php $box4 = $homeAbout['box_4'] ?? []; @endphp
                                <div>
                                    @if(!empty($box4['logo']))
                                        <img src="{{ asset('storage/cms/'.$pageKey.'/home_about/'.$box4['logo']) }}" alt="">
                                    @endif
                                    <h5>{{ $box4['title'] ?? '' }}</h5>
                                    <p>{{ $box4['description'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="about-contact-wrapper">
                            <a href="{{ $homeAbout['more_about_btn_link'] ?? '#' }}" class="primary-btn">
                                {{ $homeAbout['more_about_btn_text'] ?? 'More About Us' }}
                            </a>
                            <a href="tel:{{ $homeAbout['phone_number'] ?? '' }}" class="about-tel-btn">
                                <img src="{{ asset('assets/web/images/about-phone.png') }}" alt="">
                                <div>
                                    <p>{{ $homeAbout['phone_text'] ?? '' }}</p>
                                    <h6>{{ $homeAbout['phone_number'] ?? '' }}</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- =============== / HOME ABOUT =============== --}}
    {{-- Existing component for loop slider --}}
    <x-loop-slider-section />
    
    {{-- ================= ADVERTISE SECTION ================= --}}
    @if($advertiseSection)
    <section class="advertise-sec position-relative">
        <div class="container">
            <div class="col-lg-12">
                <div class="hdr-adz-img">
                    {{-- You can also move this to CMS later --}}
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
            <div class="row first-row">
                <div class="col-lg-7">
                    <div class="advertise-box">
                        <h2 class="sec-hd">{{ $advertiseSection['block_1_heading'] ?? '' }}</h2>
                        <p>{!! $advertiseSection['block_1_paragraph'] ?? '' !!}</p>
                        <a href="{{ $advertiseSection['block_1_button_link'] ?? '#' }}" class="primary-btn">
                            {{ $advertiseSection['block_1_button_text'] ?? '' }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    @php $img1 = $advertiseSection['image_1'] ?? null; @endphp
                    @if($img1)
                        <img src="{{ asset('storage/cms/'.$pageKey.'/advertise/'.$img1) }}" alt="" class="advertise-img-1">
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    @php $img2 = $advertiseSection['image_2'] ?? null; @endphp
                    @if($img2)
                        <img src="{{ asset('storage/cms/'.$pageKey.'/advertise/'.$img2) }}" alt="" class="advertise-img-2">
                    @endif
                </div>
                <div class="col-lg-7">
                    <div class="advertise-box advertise-box2">
                        <h2 class="sec-hd">{{ $advertiseSection['block_2_heading'] ?? '' }}</h2>
                        <p>{!! $advertiseSection['block_2_paragraph'] ?? '' !!}</p>
                        <a href="{{ $advertiseSection['block_2_button_link'] ?? '#' }}" class="primary-btn">
                            {{ $advertiseSection['block_2_button_text'] ?? '' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- =============== / ADVERTISE =============== --}}
    
    {{-- ================= DISCOVER SECTION ================= --}}
    @if($discoverSection)
    <section class="discover-sec">
        <div class="video-area2">
            <div>
                @php $discoverVideo = $discoverSection['video_file'] ?? null; @endphp
                @if($discoverVideo)
                    <video src="{{ asset('storage/cms/'.$pageKey.'/discover/'.$discoverVideo) }}" autoplay muted loop
                        class="video2">
                        Your browser does not support the video tag.
                    </video>
                @endif
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="discover-content">
                        <h6 class="sec-hd-mini">{{ $discoverSection['mini_heading'] ?? '' }}</h6>
                        <h4 style="color: white; font-size: 24px;">
                            {{ $discoverSection['sub_heading'] ?? '' }}
                        </h4>
                        <h2 class="sec-hd">{{ $discoverSection['heading'] ?? '' }}</h2>
                        <p>{!! $discoverSection['paragraph'] ?? '' !!}</p>
                        <a href="#" class="primary-btn">
                            {{ $discoverSection['button_text'] ?? 'Start Your Search Now' }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center discover-content">
                    @php $overlay = $discoverSection['overlay_image'] ?? null; @endphp
                    @if($overlay)
                        <img src="{{ asset('storage/cms/'.$pageKey.'/discover/'.$overlay) }}" alt="">
                    @else
                        <img src="{{ asset('assets/web/images/play-img.png') }}" alt="">
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- =============== / DISCOVER =============== --}}

    {{-- Partners via component (already present) --}}
    <x-partner-section />

    {{-- ================= REVIEWS / TESTIMONIALS ================= --}}
    @if($reviewsSection)
    <section class="testimonial-sec">
        <div class="container">
            <h6 class="sec-hd-mini text-center">{{ $reviewsSection['mini_heading'] ?? '' }}</h6>
            <h2 class="sec-hd text-center">{{ $reviewsSection['heading'] ?? '' }}</h2>

            <div class="testimonial-slider-wrapper">
                <div class="testimonial-slider">
                    @php
                        $ratingImg = $reviewsSection['rating_image'] ?? null;
                        $feedbackImg1 = $reviewsSection['feedback_image_1'] ?? null;
                        $feedbackImg2 = $reviewsSection['feedback_image_2'] ?? null;
                    @endphp

                    @foreach($reviewsSection['items'] ?? [] as $item)
                        <div class="testimonial-slide text-center">
                            <p>{!! $item['quote'] ?? '' !!}</p>
                            @if($ratingImg)
                                <img src="{{ asset('storage/cms/'.$pageKey.'/reviews/'.$ratingImg) }}" alt="" class="rating-img">
                            @endif
                            @if(!empty($item['avatar']))
                                <img src="{{ asset('storage/cms/'.$pageKey.'/reviews/'.$item['avatar']) }}" alt="">
                            @endif
                            <h5>{{ $item['name'] ?? '' }}</h5>
                            <h6>{{ $item['role'] ?? '' }}</h6>
                            <div class="line"></div>
                        </div>
                    @endforeach
                </div>
                <button class="testimonial-prev-arrow"><i class="fa-solid fa-arrow-left"></i></button>
                <button class="testimonial-next-arrow"><i class="fa-solid fa-arrow-right"></i></button>
            </div>

            <div class="feedback-area">
                @if($feedbackImg1)
                    <img src="{{ asset('storage/cms/'.$pageKey.'/reviews/'.$feedbackImg1) }}" alt="">
                @endif
                <div class="line2"></div>
                @if($feedbackImg2)
                    <img src="{{ asset('storage/cms/'.$pageKey.'/reviews/'.$feedbackImg2) }}" alt="">
                @endif
            </div>
        </div>
    </section>
    @endif
    {{-- =============== / REVIEWS =============== --}}

    {{-- ================= BLOGS SECTION ================= --}}
    @php
        use Illuminate\Support\Str;
    @endphp
@if(!empty($blogsSection) && !empty($blogsSection['items']))
    

    <section class="blog-sec">
        <div class="container">
            <h6 class="sec-hd-mini text-center">{{ $blogsSection['mini_heading'] ?? '' }}</h6>
            <h2 class="sec-hd text-center">{{ $blogsSection['heading'] ?? '' }}</h2>

            <div class="blog-slider">
                @foreach($blogsSection['items'] as $blog)
                    @php
                        // Decide detail URL: prefer custom link_url, otherwise use slug route
                        $detailUrl = (!empty($blog['link_url']) && $blog['link_url'] !== '#')
                            ? $blog['link_url']
                            : (!empty($blog['slug']) ? route('home.blog.detail', $blog['slug']) : '#');
                    @endphp

                    <div class="blog-card">
                        <div class="all-area">
                            <div class="blog-img-area">
                                @if(!empty($blog['image']))
                                    <img src="{{ asset('storage/cms/'.$pageKey.'/blogs/'.$blog['image']) }}"
                                         alt="{{ $blog['title'] ?? '' }}"
                                         class="w-100">
                                @endif

                                @if(!empty($blog['date']))
                                    <div class="date">{{ $blog['date'] }}</div>
                                @endif
                            </div>

                            <div class="blog-content-area">
                                @if(!empty($blog['title']))
                                    <h4>{{ Str::limit(strip_tags($blog['title']), 30) }}</h4>
                                @endif
                                
                                {{-- Short description teaser (no HTML tags) --}}
                                @if(!empty($blog['short_des']))
                                    <p>{{ Str::limit(strip_tags($blog['short_des']), 60) }}</p>
                                @endif

                                {{-- Read time --}}
                                @if(!empty($blog['read_in_minutes']))
                                    <p class="cmnt-para" style="opacity:0.8;">
                                        <i class="fa-regular fa-clock"></i>
                                        {{ $blog['read_in_minutes'] }} min Read
                                    </p>
                                @endif

                                <a href="{{ $detailUrl }}">
                                    {{ $blog['link_text'] ?? 'Read More' }}
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                <a href="{{ $blogsSection['view_all_button_link'] ?? route('blogs') }}" class="primary-btn">
                    {{ $blogsSection['view_all_button_text'] ?? 'View All' }}
                </a>
            </div>
        </div>
    </section>
@endif
{{-- =============== / BLOGS =============== --}}


    {{-- ================= CONTACT SECTION ================= --}}
    @if($contactSection)
    <section class="contact-sec">
        <div class="container">
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
            <div class="row position-relative">
                <div class="col-lg-7">
                    <div class="contact-form-wrapper">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                            @csrf
                            <h6 class="sec-hd-mini">{{ $contactSection['mini_heading'] ?? '' }}</h6>
                            <h2 class="sec-hd">{{ $contactSection['heading'] ?? '' }}</h2>

                            @php $ph = $contactSection['placeholders'] ?? []; @endphp

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="contact-field-wrapper">
                                        <input type="text" name="full_name" placeholder="{{ $ph['name'] ?? 'Your name' }}" required>
                                        <img src="{{ asset('assets/web/images/form-img1.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-field-wrapper">
                                        <input type="email" name="email" placeholder="{{ $ph['email'] ?? 'Your email' }}" required>
                                        <img src="{{ asset('assets/web/images/form-img2.png') }}" alt="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="contact-field-wrapper">
                                        <input type="text" name="phone_number" placeholder="{{ $ph['phone'] ?? 'Phone number' }}" required>
                                        <img src="{{ asset('assets/web/images/form-img3.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-field-wrapper">
                                        <select name="service" aria-placeholder="{{ $ph['service'] ?? 'Select Service' }}" required>
                                            <option>Service 1</option>
                                            <option>Service 2</option>
                                            <option>Service 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="contact-field-wrapper">
                                        <textarea name="message" placeholder="{{ $ph['message'] ?? 'Write Message...' }}" required></textarea>
                                        <img src="{{ asset('assets/web/images/form-img4.png') }}" alt="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="contact-field-wrapper">
                                        <button class="primary-btn" type="submit">
                                            {{ $ph['submit'] ?? 'Submit Now' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @php $rightImg = $contactSection['right_image'] ?? null; @endphp
                @if($rightImg)
                    <img src="{{ asset('storage/cms/'.$pageKey.'/contact/'.$rightImg) }}" alt="" class="sale-img">
                @endif
            </div>
        </div>
    </section>
    @endif
    {{-- =============== / CONTACT =============== --}}
@endsection

