@extends('layouts.web.app')

@php
    // used by footer/header etc
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Reviews" :route="route('index')" />

    <section class="clients-sec">
        <div class="container">
            <h2 class="sec-hd text-center">Client Success Stories</h2>

            <div class="client-card-wrapper">
                @forelse($reviews as $review)
                    <div class="client-card-prnt">
                        <div class="client-card">
                            {{-- Rating stars --}}
                            <div class="star-img-wrapper">
                                @for($i = 0; $i < (int) $review->rating; $i++)
                                    <i class="fa-solid fa-star" style="color:#FFD700; font-size:18px; margin-right:2px;"></i>
                                @endfor
                            </div>


                            <p class="para">
                                {{ $review->review_text }}
                            </p>

                            <div class="client-detail">
                                {{-- Client image from storage/reviews --}}
                                @if($review->client_image)
                                    <img src="{{ asset('storage/reviews/' . $review->client_image) }}"
                                         alt="{{ $review->client_name }}"
                                         class="img-fluid client-img">
                                @else
                                    {{-- fallback (optional) --}}
                                    <img src="{{ asset('assets/web/images/client-img.png') }}"
                                         alt="{{ $review->client_name }}"
                                         class="img-fluid client-img">
                                @endif

                                <div class="client-name">
                                    <h4 class="sec-hd client-hd">{{ $review->client_name }}</h4>
                                    <p class="para">{{ $review->client_role }}</p>
                                </div>

                                <img src="{{ asset('assets/web/images/ab-rev-card.png') }}"
                                     alt=""
                                     class="img-fluid ab-rev-card">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center mt-4">No reviews available right now.</p>
                @endforelse
            </div>

            {{-- If later you add pagination on query, you can render links here --}}
            {{-- <div class="row">
                <div class="col-lg-12">
                    {{ $reviews->links() }}
                </div>
            </div> --}}
        </div>
    </section>

    <x-partner-section />
@endsection
