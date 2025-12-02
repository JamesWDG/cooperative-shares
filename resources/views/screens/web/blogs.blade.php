@extends('layouts.web.app')

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Blogs" :route="route('index')" />

    <section class="blogs-sec">
        <div class="container">
            <h2 class="sec-hd text-center">Latest News & Updates</h2>

            @if($blogs->count())
                <div class="blogs-card-wrapper">
                    @foreach($blogs as $blog)
                        <div class="blogs-card-prnt">
                            <div class="blogs-card">
                                <div class="blog-img-dev">
                                    @if($blog->featured_img)
                                        <img src="{{ asset('storage/blogs/' . $blog->featured_img) }}"
                                             alt="{{ $blog->title }}"
                                             class="img-fluid blog-img">
                                    @endif
                                </div>

                                <div class="blog-content">
                                    <div class="cmnt-prnt">
                                        {{-- Show read time only if value exists (no default) --}}
                                        @if(!is_null($blog->read_in_minutes))
                                            <p class="cmnt-para">
                                                <i class="fa-regular fa-clock"></i>
                                                {{ $blog->read_in_minutes }} min Read
                                            </p>
                                        @endif
                                    </div>

                                    <h2 class="sec-hd blog-hd">{!! $blog->title !!}</h2>

                                    <p class="para">
                                        {!!  \Illuminate\Support\Str::limit($blog->short_des, 180) !!}
                                    </p>

                                    <a href="{{ route('blog-detail', $blog->id) }}" class="blog-btn">
                                        Read More
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination (optional) --}}
                @if(method_exists($blogs, 'links'))
                    <div class="row mt-4">
                        <div class="col-lg-12 d-flex justify-content-center">
                            {{ $blogs->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center mt-4">
                    <p class="para">No blogs available yet.</p>
                </div>
            @endif
        </div>
    </section>

    <x-partner-section />
@endsection
