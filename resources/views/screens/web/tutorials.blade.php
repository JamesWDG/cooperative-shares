@extends('layouts.web.app')

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Tutorials" :route="route('index')" />
    <section class="our-property-sec">
        <div class="container">
            <h2 class="sec-hd text-center mb-5">Our Tutorials</h2>
            <div class="row mt-3 row-gap-5">
                @forelse($videos as $video)
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="video-container">
                        <video controls="true" preload="none" poster="{{ asset('assets/web/images/listing-detail-img1.png') }}" class="w-100">
                            <source src="{{ asset('storage/training-videos/'.$video?->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p class="video-title">{{ $video?->name }}</p>
                    </div>
                </div>
                @empty
                @endforelse
                {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="video-container">
                        <video controls="true" preload="none" poster="{{ asset('assets/web/images/listing-detail-img1.png') }}" class="w-100">
                            <source src="{{ asset('assets/web/images/banner-video.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p class="video-title">Video Title Here</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="video-container">
                        <video controls="true" preload="none" poster="{{ asset('assets/web/images/listing-detail-img1.png') }}" class="w-100">
                            <source src="{{ asset('assets/web/images/banner-video.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p class="video-title">Video Title Here</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="video-container">
                        <video controls="true" preload="none" poster="{{ asset('assets/web/images/listing-detail-img1.png') }}" class="w-100">
                            <source src="{{ asset('assets/web/images/banner-video.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p class="video-title">Video Title Here</p>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    <x-partner-section />
@endsection
