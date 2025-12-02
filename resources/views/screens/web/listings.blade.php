@extends('layouts.web.app')

@push('styles')
    <style>
        .pagination-btns a {
            padding: 10px 24px;
            background-color: #f5f5f5;
            border: 1px solid #c8c8c8;
            border-radius: 5px;
            font-size: 25px;
            font-weight: 600;
            font-family: var(--inter-font);
            color: inherit;
        }

        .pagination-btns a.active,
        .pagination-btns a:hover {
            background-color: #dba65a;
            color: var(--white);
        }
    </style>
@endpush

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Listings" :route="route('index')" />
    @php
        $wishlistItemIds = auth()->check() 
            ? auth()->user()->wishlist()->first()?->items()->pluck('listing_id')->toArray() ?? [] 
            : [];
    @endphp

    <section class="our-property-sec listing-area-new">
        <div class="container">
                <div class="banner-form-wrapper banner-form-wrapper-2">
                    <form action="{{ route('listings') }}" method="GET" >
                        <div class="field-wrapper">
                            <label for="">{{ $search_form['property_type_label'] ?? 'Property Type' }}</label>
                            <select name="listing_type" class="form-control">
                                <option value="">All</option> 
                                @foreach($search_form['property_type_options'] ?? [] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-wrapper">
                            <label for="">{{ $search_form['rooms_label'] ?? 'Rooms' }}</label>
                            <input type="number" name="rooms" placeholder="{{ $search_form['rooms_placeholder'] ?? '' }}" class="form-control">
                        </div>
                        <div class="field-wrapper">
                            <label for="">{{ $search_form['baths_label'] ?? 'Baths' }}</label>
                            <input type="number" name="bathrooms" placeholder="{{ $search_form['baths_placeholder'] ?? '' }}" class="form-control">
                        </div>
                        <div class="field-wrapper">
                            <label for="">{{ $search_form['sqfeet_label'] ?? 'Sq Feet' }}</label>
                            <input type="number" name="sqfeet" placeholder="{{ $search_form['sqfeet_placeholder'] ?? '' }}" class="form-control">
                        </div>
                        <button type="submit" class="primary-btn">
                            <i class="fa-solid fa-search"></i>
                            {{ $search_form['search_button_text'] ?? 'Search' }}
                        </button>
                    </form>
                </div>
            <div class="our-property-sec-header our-property-sec-header-2">
                <div>
                    <h6 class="sec-hd-mini">Property Types</h6>
                    <h2 class="sec-hd">Property Options</h2>
                </div>
                <div class="pro-icon-dev">
                    <div class="our-pro-icons">
                        <button class="our-pro-button" id="listViewBtn"><i class="fa-solid fa-list-ul"></i></button>
                        <button class="our-pro-button" id="gridViewBtn"><i class="fas fa-th-large"></i></button>
                    </div>
                </div>
            </div>

            <div class="property-card-wrapper2">
                @forelse ($listings as $listing)
                
                    @php
                        // ---------- IMAGE RESOLUTION LOGIC ----------
                        $placeholder = asset('assets/web/images/property-placeholder.jpg');
                        $mainImageUrl = $placeholder;

                        // 1) If we have main_image value
                        if (!empty($listing->main_image)) {
                            // New pattern – filename inside public/storage/listing/images
                            $newPublicPath = public_path('storage/listing/images/' . $listing->main_image);

                            if (file_exists($newPublicPath)) {
                                $mainImageUrl = asset('storage/listing/images/' . $listing->main_image);
                            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($listing->main_image)) {
                                // Old stored path with Storage::disk('public')
                                $mainImageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($listing->main_image);
                            }
                        } else {
                            // 2) No main_image => fallback to first related ListingImage (prefer image type)
                            $firstImage = $listing->images->where('type', 'image')->first() ?? $listing->images->first();
                            if ($firstImage) {
                                $candidate = public_path('storage/listing/images/' . $firstImage->filename);
                                if (file_exists($candidate)) {
                                    $mainImageUrl = asset('storage/listing/images/' . $firstImage->filename);
                                }
                            }
                        }
                    @endphp

                    <div class="property-card-prnt">
                        <div class="property-card">
                            <div class="img-area position-relative">
                                <img src="{{ $mainImageUrl }}" alt="{{ $listing->property_title }}" class="img-fluid">
                                <!--<div class="property-card-badge3">XYZ cooperative</div>-->
                                
                                <button class="btn heart-save-btn p-0 wishlistbtn" data-listingid="{{ $listing?->id }}">
                                    {{-- <i class="fa-regular fa-heart"></i> --}}
                                    <i class="{{ in_array($listing->id, $wishlistItemIds) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                                </button>
                                @if($listing->listing == 'featured')
                                <img src="{{ asset('assets/user/images/advertisment-badge.png') }}" alt="" class="advertisment-badge" style="height: unset !important;">
                                @endif
                            </div>
                            <div class="property-card-body">
                                <div class="property-name position-relative">
                                    <h3>{{ $listing->property_title }}</h3>
                                    <p><i class="fa-solid fa-location-dot"></i> {{ $listing->address }}</p>
                                    <div class="property-card-badge">{{ $listing->listed_in }}</div>
                                </div>
                                <div class="other-desc">
                                    <div class="d-flex justify-content-between">
                                        <p><img src="{{ asset('assets/web/images/Vector1.png') }}" alt=""> Bed
                                            {{ $listing->bedrooms }}
                                        </p>
                                        <span>|</span>
                                        <p><img src="{{ asset('assets/web/images/Vector2.png') }}" alt=""> Bath
                                            {{ $listing->bathrooms }}
                                        </p>
                                        <span>|</span>
                                        <p><img src="{{ asset('assets/web/images/Vector3.png') }}" alt="">
                                            {{ $listing->size_in_ft }} sqft</p>
                                    </div>
                                </div>
                                <div class="property-name d-flex justify-content-between align-items-center">
                                    <h3 class="m-0">@moneyFormat($listing->price)</h3>
                                    <a href="{{ route('listing.detail', $listing->id) }}">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <h3 class="text-center text-danger fw-bold">No Listings Found!</h3>
                @endforelse
            </div>

            <div class="row">
                <div class="col-lg-12">
                    @if($listings->hasPages())
                        <div class="pagination-btns">
                            {{-- Previous Button --}}
                            @if($listings->onFirstPage())
                                <a onclick="return false" class="pagination-btns">
                                    <i class="fa-solid fa-arrow-left" style="margin-right: 5px"></i>
                                    Previous
                                </a>
                            @else
                                <a href="{{ $listings->previousPageUrl() }}" class="pagination-btns">
                                    <i class="fa-solid fa-arrow-left" style="margin-right: 5px"></i>
                                    Previous
                                </a>
                            @endif

                            @php
                                $current = $listings->currentPage();
                                $last = $listings->lastPage();

                                // Show limited pages around current page
                                $start = max(1, $current - 1);
                                $end = min($last, $current + 1);

                                // Always show first page if not in range
                                if ($start > 1) {
                                    $start = 1;
                                }

                                // Always show last page if not in range
                                if ($end < $last) {
                                    $end = $last;
                                }
                            @endphp

                            @for($page = $start; $page <= $end; $page++)
                                @if($page == $current)
                                    <a onclick="return false" class="pagination-btns active">
                                        {{ $page }}
                                    </a>
                                @else
                                    <a href="{{ $listings->url($page) }}" class="pagination-btns">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Next Button --}}
                            @if($listings->hasMorePages())
                                <a href="{{ $listings->nextPageUrl() }}" class="pagination-btns">
                                    Next
                                    <i class="fa-solid fa-arrow-right" style="margin-left: 5px"></i>
                                </a>
                            @else
                                <a onclick="return false" class="pagination-btns">
                                    Next
                                    <i class="fa-solid fa-arrow-right" style="margin-left: 5px"></i>
                                </a>
                            @endif

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <x-partner-section />
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const listViewBtn = document.getElementById("listViewBtn");
            const gridViewBtn = document.getElementById("gridViewBtn");

            const cardWrappers = document.querySelectorAll(".property-card-wrapper2 .property-card-prnt");
            const cardImages = document.querySelectorAll(".property-card .img-area img");
            const cardBodies = document.querySelectorAll(".property-card-body");
            const cardNames = document.querySelectorAll(".property-name");
            const propertyCards = document.querySelectorAll(".property-card");

            // List View
            listViewBtn.addEventListener("click", function () {
                propertyCards.forEach(card => {
                    card.style.display = "flex";
                });

                cardWrappers.forEach(card => {
                    if (window.innerWidth < 1024) {
                        card.style.width = "calc(100% / 1 - 0px)";
                    } else {
                        card.style.width = "calc(100% / 2 - 10px)";
                    }
                });

                cardImages.forEach(img => {
                    img.style.height = "100%";
                });

                cardBodies.forEach(body => {
                    body.style.width = "70%";
                });

                cardNames.forEach(name => {
                    name.style.padding = "15px";
                });
            });

            // Grid View (Reset)
            gridViewBtn.addEventListener("click", function () {
                propertyCards.forEach(card => {
                    card.style.display = "block";
                });

                cardWrappers.forEach(card => {
                    card.style.width = "";
                });

                cardImages.forEach(img => {
                    img.style.height = "";
                });

                cardBodies.forEach(body => {
                    body.style.width = "";
                });

                cardNames.forEach(name => {
                    name.style.padding = "";
                });
            });
        });
    </script>
@endpush
