@extends('layouts.web.app')

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home > Blogs" subHeading="Blog Detail" :route="route('index')" />
    
    <section class="blogs-sec blog-detail-sec">
        <div class="container">

            {{-- Featured Image --}}
            @if(!empty($blog['image']))
                <img src="{{ asset('storage/cms/'.$pageKey.'/blogs/'.$blog['image']) }}"
                     alt="{{ $blog['title'] ?? '' }}"
                     class="img-fluid blog-img mb-4">
            @endif

            {{-- Title --}}
            <h2 class="sec-hd">{{ $blog['title'] ?? '' }}</h2>

            {{-- Meta info: date + read time --}}
            <div class="d-flex gap-3 mb-3" style="opacity:0.8; font-size:14px;">
                @if(!empty($blog['date']))
                    <p class="para mb-0">
                        <i class="fa-regular fa-calendar"></i>
                        {{ $blog['date'] }}
                    </p>
                @endif

                @if(!empty($blog['read_in_minutes']))
                    <p class="para mb-0">
                        <i class="fa-regular fa-clock"></i>
                        {{ $blog['read_in_minutes'] }} min Read
                    </p>
                @endif
            </div>

            {{-- Short intro (plain text) --}}
            @if(!empty($blog['short_des']))
                <p class="para">
                    {{ strip_tags($blog['short_des']) }}
                </p>
            @endif

            {{-- Full content – render HTML --}}
            @if(!empty($blog['long_des']))
                {!! $blog['long_des'] !!}
            @endif

        </div>
    </section>
@endsection
