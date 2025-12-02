@extends('layouts.web.app')

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home > Blogs" subHeading="Blog Detail" :route="route('index')" />

    <section class="blogs-sec blog-detail-sec">
        <div class="container">

            {{-- Featured Image --}}
            @if($blog->featured_img)
                <img src="{{ asset('storage/blogs/' . $blog->featured_img) }}"
                     alt="{{ $blog->title }}"
                     class="img-fluid blog-img mb-4">
            @endif

            {{-- Title --}}
            <h2 class="sec-hd">{{ $blog->title }}</h2>

            {{-- Read time --}}
            @if(!is_null($blog->read_in_minutes))
                <p class="para" style="opacity:0.8;">
                    <i class="fa-regular fa-clock"></i>
                    {{ $blog->read_in_minutes }} min Read
                </p>
            @endif

            {{-- SHORT DESCRIPTION (HTML rendered) --}}
            @if(!empty($blog->short_des))
                <div class="para mb-3">
                    {!! $blog->short_des !!}
                </div>
            @endif

            {{-- LONG DESCRIPTION (HTML rendered) --}}
            @if(!empty($blog->long_des))
                <div class="para">
                    {!! $blog->long_des !!}
                </div>
            @endif

        </div>
    </section>
@endsection
