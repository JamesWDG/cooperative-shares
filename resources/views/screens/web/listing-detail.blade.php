@extends('layouts.web.app')

@php
    use Illuminate\Support\Facades\Storage;

    $page = false;

    // ---------- MAIN IMAGE RESOLUTION ----------
    $placeholder = asset('assets/web/images/property-placeholder.jpg');
    $mainImageUrl = $placeholder;

    if (!empty($listing->main_image)) {
        // New pattern: filename only under public/storage/listing/images
        $newPublicPath = public_path('storage/listing/images/' . $listing->main_image);

        if (file_exists($newPublicPath)) {
            $mainImageUrl = asset('storage/listing/images/' . $listing->main_image);
        } elseif (Storage::disk('public')->exists($listing->main_image)) {
            // Old stored path via Storage::disk('public')
            $mainImageUrl = Storage::disk('public')->url($listing->main_image);
        }
    }

    // ---------- IMAGES & VIDEOS FROM RELATION ----------
    $imageFiles = collect();
    $videoFiles = collect();

    if ($listing->relationLoaded('images')) {
        $imageFiles = $listing->images->where('type', 'image');
        $videoFiles = $listing->images->where('type', 'video');
    }

    // Optional fallback: old JSON column "files" if no related images
    if ($imageFiles->isEmpty() && !empty($listing->files)) {
        $oldFiles = json_decode($listing->files);
        if ($oldFiles) {
            foreach ($oldFiles as $file) {
                if (isset($file->type) && $file->type == 'mp4') {
                    $videoFiles->push((object)[
                        'old'  => true,
                        'path' => $file->path,
                        'type' => 'video',
                    ]);
                } else {
                    $imageFiles->push((object)[
                        'old'  => true,
                        'path' => $file->path,
                        'type' => 'image',
                    ]);
                }
            }
        }
    }

    // If still no main image and we have imageFiles, use first image as main
    if ($mainImageUrl === $placeholder && $imageFiles->isNotEmpty()) {
        $firstImage = $imageFiles->first();
        if (isset($firstImage->filename)) {
            $candidate = public_path('storage/listing/images/' . $firstImage->filename);
            if (file_exists($candidate)) {
                $mainImageUrl = asset('storage/listing/images/' . $firstImage->filename);
            }
        } elseif (isset($firstImage->path) && Storage::disk('public')->exists($firstImage->path)) {
            $mainImageUrl = Storage::disk('public')->url($firstImage->path);
        }
    }
@endphp

@push('styles')
    <style>
        .property-btn-wrapper {
            position: relative;
        }

        .vdo-play-btn {
            position: absolute;
            top: 45%;
            left: 48%;
            color: white;
            padding: 22px 24px;
            background: gray;
            border: 1px solid white;
            border-radius: 100%;
            cursor: pointer;
            transition: 0.5s;
        }

        .vdo-play-btn:hover {
            transition: 0.5s;
            background: #fff;
            color: #000;
        }

        .listing-det-form input, .listing-det-form textarea {
            margin-bottom: 0px !important;
        }

        label.error {
            color: crimson;
            font-family: var(--outfit-font);
            margin: 10px 0;
        }

        .listing-det-form input:not(:has(~ label.error)),
        .listing-det-form textarea:not(:has(~ label.error)) {
            margin-bottom: 20px !important;
        }
    </style>
@endpush

@section('section')
    <section class="listing-detail-sec">
        <div class="container">
            {{-- MAIN SLIDER IMAGE --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-listing-slider">
                        <img id="mainImage" class="main-image" src="{{ $mainImageUrl }}" alt="{{ $listing->property_title }}">
                    </div>
                </div>
            </div>

            {{-- THUMBNAILS --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="thumbnail-slider-wrapper">

                        {{-- Main image as first thumbnail --}}
                        <div class="thumbnail-slide">
                            <div class="thumbnail active">
                                <img src="{{ $mainImageUrl }}" data-large="{{ $mainImageUrl }}">
                            </div>
                        </div>

                        {{-- Other images from relation / old files --}}
                        @foreach ($imageFiles as $image)
                            @php
                                if (isset($image->filename)) {
                                    // New image from listing_images
                                    $thumbUrl = asset('storage/listing/images/' . $image->filename);
                                } elseif (isset($image->path) && Storage::disk('public')->exists($image->path)) {
                                    // Old stored file path
                                    $thumbUrl = Storage::disk('public')->url($image->path);
                                } else {
                                    $thumbUrl = $placeholder;
                                }
                            @endphp
                            <div class="thumbnail-slide">
                                <div class="thumbnail">
                                    <img src="{{ $thumbUrl }}" data-large="{{ $thumbUrl }}">
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- LEFT COLUMN: PROPERTY DETAILS --}}
                <div class="col-lg-8">
                    <h2 class="listin-det-hd">{{ $listing->property_title }}</h2>
                    <p class="listin-det-para">{{ $listing->description }}</p>

                    <div class="bed-det-wrapp">
                        <p><img src="{{ asset('assets/web/images/Vector1.png') }}" alt=""> <span>Bed
                                {{ $listing->bedrooms }}</span></p>
                        <span>|</span>
                        <p><img src="{{ asset('assets/web/images/Vector2.png') }}" alt=""> <span>Bath
                                {{ $listing->bathrooms }}</span></p>
                        <span>|</span>
                        <p><img src="{{ asset('assets/web/images/Vector3.png') }}" alt=""> <span>{{ $listing->size_in_ft }}
                                sqft</span></p>
                    </div>

                    <h2 class="listin-det-hd-mini">About This Property</h2>
                    <p class="listing-para-mini">{{ $listing->listing_description }}</p>

                    <h2 class="listin-det-hd-mini">Property Overview</h2>
                    <div class="property-details">
                        <div class="property-content">
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item1.png') }}" alt="">
                                </div>
                                <p class="item-para">ID NO.<br><strong>#{{ $listing->id }}</strong></p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item2.png') }}" alt="">
                                </div>
                                <p class="item-para">Type<br><strong>{{ Str::ucfirst($listing->category) }}</strong></p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item3.png') }}" alt="">
                                </div>
                                <p class="item-para">Room<br><strong>{{ $listing->bedrooms }}</strong></p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item4.png') }}" alt="">
                                </div>
                                <p class="item-para">Bedroom<br><strong>{{ $listing->bedrooms }}</strong></p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item5.png') }}" alt="">
                                </div>
                                <p class="item-para">Bath<br><strong>{{ $listing->bathrooms }}</strong></p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item6.png') }}" alt="">
                                </div>
                                <p class="item-para">Purpose<br><strong>{{ $listing->listed_in }}</strong></p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item7.png') }}" alt="">
                                </div>
                                <p class="item-para">Sqft<br><strong>{{ $listing->size_in_ft }}</strong></p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item9.png') }}" alt="">
                                </div>
                                <p class="item-para">Parking<br><strong>{{ $listing->has_parking ? 'Yes' : 'No' }}</strong>
                                </p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item8.png') }}" alt="">
                                </div>
                                <p class="item-para">
                                    Elevator<br><strong>{{ $listing->has_elevator ? 'Yes' : 'No' }}</strong></p>
                            </div>
                            <div class="item">
                                <div class="item-img">
                                    <img src="{{ asset('assets/web/images/item10.png') }}" alt="">
                                </div>
                                <p class="item-para">Wifi<br><strong>{{ $listing->has_wifi ? 'Yes' : 'No' }}</strong></p>
                            </div>
                        </div>
                    </div>

                    {{-- GALLERY --}}
                    <h2 class="listin-det-hd-mini">From Our Gallery</h2>
                    <div class="row justify-content-evenly">
                        @foreach ($imageFiles as $image)
                            @php
                                if (isset($image->filename)) {
                                    $imgUrl = asset('storage/listing/images/' . $image->filename);
                                } elseif (isset($image->path) && Storage::disk('public')->exists($image->path)) {
                                    $imgUrl = Storage::disk('public')->url($image->path);
                                } else {
                                    $imgUrl = $placeholder;
                                }
                            @endphp
                            <div class="col-lg-4 col-md-6 col-12">
                                <a href="{{ $imgUrl }}" data-fancybox="gallery">
                                    <img src="{{ $imgUrl }}" alt="" class="Gallery-img mt-3 me-2">
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- FEATURES & AMENITIES (unchanged except small fixes) --}}
                    <h2 class="listin-det-hd-mini listin-Features">Features & Amenities</h2>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <ul class="Features-2">
                                @if($listing->has_ac)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Airconditioning
                                    </li>
                                @endif
                                @if($listing->has_garages)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Garage
                                    </li>
                                @endif
                                @if($listing->has_pool)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Pool
                                    </li>
                                @endif
                                @if($listing->has_parking)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Parking
                                    </li>
                                @endif
                                @if($listing->has_laundry)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Laundry
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <ul class="Features-2">
                                @if($listing->has_lakeview)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Lakeview
                                    </li>
                                @endif
                                @if($listing->has_garden)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Garden
                                    </li>
                                @endif
                                @if($listing->has_fireplace)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Fireplace
                                    </li>
                                @endif
                                @if($listing->has_pet)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Pet
                                    </li>
                                @endif
                                @if($listing->has_accessible)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Accessible
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <ul class="Features-2">
                                @if($listing->has_ceiling)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Ceiling
                                    </li>
                                @endif
                                @if($listing->has_shower)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Shower
                                    </li>
                                @endif
                                @if($listing->has_refrigerator)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Refrigerator
                                    </li>
                                @endif
                                @if($listing->has_lawn)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Lawn
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <ul class="Features-2">
                                @if($listing->has_dryer)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Dryer
                                    </li>
                                @endif
                                @if($listing->has_wifi)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Wifi
                                    </li>
                                @endif
                                @if($listing->has_tv)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        TV
                                    </li>
                                @endif
                                @if($listing->has_bbq)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        BBQ
                                    </li>
                                @endif
                                @if($listing->has_elevator)
                                    <li class="Feature">
                                        <img src="{{ asset('assets/web/images/tic-img.png') }}" alt="">
                                        Elevator
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @php
                        $fullAddress = $listing?->address . ', ' . $listing?->city . ', ' . $listing?->state . ' ' . $listing?->zip_code . ', ' . $listing?->country;
                        $encodedAddress = urlencode($fullAddress); 
                    @endphp
                    {{-- LOCATION --}}
                    <h2 class="listin-det-hd-mini">Location</h2>
                    <section class="map-sec-2">
                        {{-- <div id="map_canvas" style="width: 100%; height: 400px;"></div> --}}
                            <iframe 
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyD28UEoebX1hKscL3odt2TiTRVfe5SSpwE&q={{ $encodedAddress }}" 
                                style="border:0;" 
                                allowfullscreen 
                                loading="lazy" 
                                class="map-map2">
                            </iframe>
                        {{-- <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d52784203.16337093!2d-161.37967564687162!3d36.14970978124419!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e0!3m2!1sen!2s!4v1750293825685!5m2!1sen!2s"
                            style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            class="map-map2"></iframe> --}}
                    </section>

                    {{-- PROPERTY VIDEO --}}
                    @if($videoFiles->count() > 0)
                        <h2 class="listin-det-hd-mini">Property Video</h2>
                        @foreach ($videoFiles as $video)
                            @php
                                if (isset($video->filename)) {
                                    $videoUrl = asset('storage/listing/videos/' . $video->filename);
                                } elseif (isset($video->path) && Storage::disk('public')->exists($video->path)) {
                                    $videoUrl = Storage::disk('public')->url($video->path);
                                } else {
                                    $videoUrl = null;
                                }
                            @endphp
                            @if($videoUrl)
                                <div class="property-btn-wrapper">
                                    <img id="property-img" src="{{ $mainImageUrl }}" alt="" class="img-fluid">
                                    <div id="play-btn" class="vdo-play-btn" onclick="playVideo()">
                                        <i class="fa-solid fa-play"></i>
                                    </div>
                                    <video id="property-video" class="img-fluid" style="display:none;" controls>
                                        <source src="{{ $videoUrl }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                @break {{-- ek hi video dikhana hai UI ke hisaab se --}}
                            @endif
                        @endforeach
                    @endif

                    {{-- PROPERTY REVIEW (same as before) --}}
                    {{-- <h2 class="listin-det-hd-mini">Property Review</h2>
                    <div class="review-box">
                        <div class="review-div-1">
                            <div class="review-hd">
                                <h2 class="listin-det-hd-mini listin-det-hd-mini-3">Review</h2>
                            </div>
                            <div class="review-btn">
                                <button class="floor-nav-link">
                                    <i class="fa-solid fa-star"></i>
                                    Login to Write Your Review
                                </button>
                            </div>
                        </div>
                        <div class="reviews-1">
                            <div class="reviews-item">
                                <div class="revies-item-number">
                                    <h2 class="number-hd">3</h2>
                                </div>
                                <div class="reviews-stars">
                                    <img src="{{ asset('assets/web/images/star-img1.png') }}" alt="">
                                    <img src="{{ asset('assets/web/images/star-img1.png') }}" alt="">
                                    <img src="{{ asset('assets/web/images/star-img1.png') }}" alt="">
                                    <img src="{{ asset('assets/web/images/star-img2.png') }}" alt="">
                                    <img src="{{ asset('assets/web/images/star-img2.png') }}" alt="">
                                    <h2 class="review1-hd">
                                        1 review
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="reviews-1 reviews-2">
                            <div class="reviews-item">
                                <div class="realar-img">
                                    <img src="{{ asset('assets/web/images/realar-img.png') }}" alt="">
                                </div>
                                <div class="reviews-stars">
                                    <h2 class="review1-hd review2-hd">
                                        Realar
                                    </h2>
                                    <img src="{{ asset('assets/web/images/star-img1.png') }}" alt="">
                                    <img src="{{ asset('assets/web/images/star-img1.png') }}" alt="">
                                    <img src="{{ asset('assets/web/images/star-img1.png') }}" alt="">
                                    <img src="{{ asset('assets/web/images/star-img2.png') }}" alt="">
                                    <img src="{{ asset('assets/web/images/star-img2.png') }}" alt="">
                                    <p class="review-para">7 May, 2024</p>
                                </div>
                            </div>
                            <p class="listing-para-mini rev-main-para">Rapidiously myocardinate cross-platform intellectual
                                capital model. Appropriately create
                                interactive
                                infrastructures</p>
                        </div>
                    </div>
                    --}}
                </div>

                {{-- RIGHT COLUMN: CONTACT + USER INFO --}}
                <div class="col-lg-4">
                    <div class="sticky-sidebar-2">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(!$existingLead)
                            <form action="{{ route('lead.store') }}" method="POST" class="listing-det-form" id="contact-form">
                                @csrf
                                <h3>Contact the listing owner</h3>
                                <div class="form-line"></div>
                                @guest
                                    <input type="text" name="name" placeholder="Name" />
                                    <input type="number" name="phone_number" placeholder="Phone Number" />
                                    <input type="email" name="email" placeholder="Email" />
                                @endguest
                                <input type="hidden" name="listing_id" value="{{ $listing->id }}" />
                                <textarea name="message" placeholder="Message..."></textarea>
                                @error('message')
                                    <label id="message-error" class="error" for="message">{{ $message }}</label>
                                @enderror
                                @auth
                                    <button type="submit">Submit Now</button>
                                @endauth
                                @guest
                                    <button type="button" id="login-first">Submit Now</button>
                                @endguest
                            </form>
                        @endif

                        <div class="listing-user-info">
                            <h3>Vendor Info</h3>
                            <div class="form-line"></div>
                            <div class="realer-wrap">
                                <img src="{{ asset($listing->user->profile_image) }}" alt="Profile Image"
                                     style="width:51px;">
                                <div>
                                    <h5>{{ $listing->user->full_name }}</h5>
                                    <h6>Member since {{ $listing->user->member_since }}</h6>
                                </div>
                            </div>
                            <div class="realer-contact-info-wrapper">
                                <a>
                                    <p><i class="fa-solid fa-location-dot"></i><span>{{ $listing->user->address }}</span>
                                    </p>
                                </a> 
                                <a href="tel:{{ $listing->user->phone_number }}">
                                    <p>
                                        <i class="fa-solid fa-phone"></i>
                                        <span>{{ $listing->user->phone_number }}</span>
                                    </p>
                                </a>
                                <a href="mailto:{{ $listing->user->email }}">
                                    <p>
                                        <i class="fa-solid fa-envelope"></i>
                                        <span>{{ $listing->user->email }}</span>
                                    </p>
                                </a>
                                {{-- <a href="#">
                                    <p><i class="fa-solid fa-earth-americas"></i><span>www.example.com</span></p>
                                </a> --}}
                            </div>
                            {{-- <div class="realer-icon-wrapper">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                            </div> --}}
                            <a  href="{{ route('realtor.profile',$listing?->user?->id) }}" class="relaler-profile-btn" target="_blank">View Profile</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <x-partner-section />
@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD28UEoebX1hKscL3odt2TiTRVfe5SSpwE&libraries=places"></script>
    
    <script>
    
    // Map
    
    var address = document.getElementById('autocompleteSearch').value
    var placeSearch, autocomplete;
    var componentForm = {
        // street_number: 'short_name',
        // route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        // country: 'long_name',
        postal_code: 'short_name'
    };

    if (typeof google === 'undefined') {
        jQuery.getScript(
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyAHPUufTlBkF5NfBT3uhS9K4BbW2N-mkb4&libraries=geometry,places',
            () => {
                var input = document.getElementById('autocompleteSearch');
                autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['geocode']
                });
                autocomplete.setFields(['address_component']);
                autocomplete.addListener('place_changed', fillIn);
                initialaddress();
            });
    } else {
        var input = document.getElementById('autocompleteSearch');
        autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode']
        });
        autocomplete.setFields(['address_component']);
        autocomplete.addListener('place_changed', fillIn);
        initialaddress();
    }

    function fillIn() {

        address = document.getElementById('autocompleteSearch').value;
        var place = autocomplete.getPlace();
        let city = '';
        let state = '';
        let zipcode = '';
        let country = '';
        for (const component of place.address_components) {
            if (component.types.includes('locality')) {
                city = component.long_name;
            }
            if (component.types.includes('administrative_area_level_1')) {
                state = component.short_name;
            }
            if (component.types.includes('postal_code')) {
                zipcode = component.long_name;
            }
            if (component.types.includes('country')) {
                country = component.long_name;
            }
        }
        address = document.getElementById('autocompleteSearch').value;
        document.getElementById('citySearch').value = city;
        document.getElementById('stateSearch').value = state;
        document.getElementById('zipcodeSearch').value = zipcode;
        document.getElementById('countrySearch').value = country;
        document.getElementById('address').value = address

        initialaddress(address);
    }

    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }

    function initialaddress(addressinput = address) {

        var geocoder;
        var map;
        var address = addressinput;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);
        var myOptions = {
            zoom: 15,
            center: latlng,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        if (geocoder) {
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                        map.setCenter(results[0].geometry.location);
                        var infowindow = new google.maps.InfoWindow({
                            content: '<b>' + address + '</b>',
                            size: new google.maps.Size(150, 50)
                        });
                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                            title: address
                        });
                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.open(map, marker);
                        });
                    } else {
                        alert("No results found");
                    }
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        }
    }

    //Map end
    
        // Fancybox Config
        $('[data-fancybox="gallery"]').fancybox({
            buttons: [
                "slideShow",
                "thumbs",
                "zoom",
                "fullScreen",
                "share",
                "close"
            ],
            loop: false,
            protect: true
        });
    </script>

    <script>
        // Play video: hide image + button, show video
        function playVideo() {
            document.getElementById('property-img').style.display = 'none';
            document.getElementById('play-btn').style.display = 'none';

            var video = document.getElementById('property-video');
            video.style.display = 'block';
            video.play();
        }

        // Optional: pause & revert (if needed)
        function pauseVideo() {
            var video = document.getElementById('property-video');
            video.pause();
            video.style.display = 'none';
            document.getElementById('property-img').style.display = 'block';
            document.getElementById('play-btn').style.display = 'block';
        }
    </script>

    {{-- Contact Form Validation --}}
    <script>
        $('#contact-form').validate({
            rules: {
                message: {
                    required: true,
                }
            },
            messages: {
                message: {
                    required: 'Please Enter Your Message!',
                }
            },
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#login-first').on('click', function () {
                Swal.fire({
                    title: "Warning!",
                    text: "Please Login First!",
                    icon: 'warning',
                    confirmButtonColor: '#295568',
                    confirmButtonText: 'OK'
                })
            });
        });
    </script>
@endpush
