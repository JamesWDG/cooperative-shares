@extends('layouts.web.app')

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home > Our Partners" subHeading="Vendor Profile" :route="route('index')" />
    @php
        $logo = $vendor?->profile_logo != null ? $vendor?->profile_logo : 'assets/web/images/profile-circle-img.png';
        $uniqueCities = $vendor?->listings?->pluck('city')->map(fn($city) => strtolower($city))->unique();
        
        
        $wishlistItemIds = auth()->check() 
            ? auth()->user()->wishlist()->first()?->items()->pluck('listing_id')->toArray() ?? [] 
            : [];
    @endphp
    <section class="profile-sec">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-12">
                    <div class="profile-sec-div">

                        <img src="{{ asset($logo) }}" alt="" class="img-fluid profile-circle-img">
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-12">
                    <div class="circle-profile-hd3 text-center circle-profile2-hd3">
                        <h3 class="profile-heading">{{ count($vendor?->listings) }} <span>Properties</span></h3>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="text-center circle-profile2-hd3">
                        <h3 class="profile-heading">{{ $uniqueCities?->count() }} <span>Cities</span></h3>
                    </div>
                </div>
                <div class="col-lg-12">
                    <p class="profile-crcle-para text-center">All community data is sourced from <span>Cooperative
                            Homes</span> listings and may not represent this company's complete portfolio.</p>
                </div>
                @if($uniqueCities?->count())
                <div class="col-lg-12">
                    <div class="select-div">
                        <select id="cityFilter" placeholder="Select city">
                            <option value="">All</option>
                            @foreach($uniqueCities as $uniqueCity)
                                <option value="{{ $uniqueCity }}">{{ strtoupper($uniqueCity) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="map" style="width: 100%; height: 450px;"></div>
                    {{-- <div class="realtor-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24768.68367075121!2d-95.09900107819405!3d39.10452448995828!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87bf7db9fcc83609%3A0x1330a70179a4b4c4!2sTonganoxie%20Christian%20Church!5e0!3m2!1sen!2s!4v1752702664922!5m2!1sen!2s"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div> --}} 
                </div>
                @endif
            </div>
        </div>
    </section>
    <section class="profile-card-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="sec-hd text-center mb-5">{{ $vendor?->first_name ?? '' }} Communities {{-- in USA --}}</h2>
                </div>
            </div>
            <div class="property-card-wrapper2">
                @forelse($vendor?->listings as $listing)
                <div class="property-card-prnt">
                    <div class="property-card">
                        <div class="img-area position-relative">
                            <img src="{{ asset('storage/listing/images/'.$listing->main_image) }}" alt="" class="img-fluid">
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
                                    <p>
                                        <img src="{{ asset('assets/web/images/Vector1.png') }}" alt="">
                                        Bed {{ $listing->bedrooms }}
                                    </p>
                                    <span>|</span>
                                    <p>
                                        <img src="{{ asset('assets/web/images/Vector2.png') }}" alt="">
                                        Bath {{ $listing->bathrooms }}
                                    </p>
                                    <span>|</span>
                                    <p>
                                        <img src="{{ asset('assets/web/images/Vector3.png') }}" alt="">
                                        {{ $listing->size_in_ft }} sqft
                                    </p>
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
                {{-- <div class="property-card-prnt">
                    <div class="property-card">
                        <div class="img-area position-relative">
                            <img src="{{ asset('assets/web/images/Container.png') }}" alt="" class="img-fluid">
                            <div class="property-card-badge3">XYZ cooperative</div>
                            <button class="btn heart-save-btn p-0">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>
                        <div class="property-card-body">
                            <div class="property-name position-relative">
                                <h3>Homes For Sale</h3>
                                <p><i class="fa-solid fa-location-dot"></i> Lorem Ipsum&nbsp;is simply dummy</p>
                                <div class="property-card-badge">Co-Op Share</div>
                            </div>
                            <div class="other-desc">
                                <div class="d-flex justify-content-between">
                                    <p><img src="{{ asset('assets/web/images/Vector1.png') }}" alt=""> Bed 4</p>
                                    <span>|</span>
                                    <p><img src="{{ asset('assets/web/images/Vector2.png') }}" alt=""> Bath 2</p>
                                    <span>|</span>
                                    <p><img src="{{ asset('assets/web/images/Vector3.png') }}" alt=""> 1500 sqft</p>
                                </div>
                            </div>
                            <div class="property-name d-flex justify-content-between align-items-center">
                                <h3 class="m-0">$179,800.00</h3>
                                <a href="#">View More</a>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @endforelse
            </div>
            {{-- <div class="row">
                <div class="col-lg-12">
                    <div class="profile-crd-btn text-center">
                        <a href="" class="profile-crd-btn-a">Load More</a>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
    {{-- <section class="advertise-sec position-relative">
        <div class="container">
            <div class="row first-row">
                <div class="col-lg-7">
                    <div class="advertise-box">
                        <h2 class="sec-hd">USA</h2>
                        <p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem
                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer
                            took a galley of type and scrambled it to make a type specimen book. It has survived not only
                            five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                            passages, and more recently with desktop publishing software like Aldus PageMaker including
                            versions of Lorem Ipsum..</p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img src="{{ asset('assets/web/images/main-img1.png') }}" alt="" class="advertise-img-1">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <img src="{{ asset('assets/web/images/main-img.png') }}" alt="" class="advertise-img-2">
                </div>
                <div class="col-lg-7">
                    <div class="advertise-box advertise-box2">
                        <h2 class="sec-hd">Las Vigas</h2>
                        <p>Lorem Ipsum&nbsp; is simply dummy text of the printing and typesetting industry. Lorem
                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer
                            took a galley of type and scrambled it to make a type specimen book. It has survived not only
                            five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                            passages, and more recently with desktop publishing software like Aldus PageMaker including
                            versions of Lorem Ipsum..</p>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    @if(count($vendor?->galleries))
        <section class="Photo-Gallery-sec">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="sec-hd text-center">Photo Gallery</h2>
                    </div>
                </div>
                <div class="photo-gallery-div">
                    @foreach($vendor?->galleries as $gallery)
                    <div class="gallery-img">
                        <a href="{{ asset('gallery/' .$gallery?->image) }}" data-fancybox="gallery">
                            <img src="{{ asset('gallery/' .$gallery?->image) }}" alt="" class="img-fluid">
                        </a>
                    </div>
                    @endforeach
                </div>
                {{-- <div class="row">
                    <div class="col-lg-12">
                        <div class="pagination-btns circle-pagination-btns">
                            <button>1</button>
                            <button>2</button>
                            <button>3</button>
                            <button class="active">Next
                                <i><i class="fa-solid fa-arrow-right"></i></i>
                            </button>
                        </div>
                    </div>
                </div> --}}
            </div>
        </section>
    @endif
    
    @if(!empty($vendorProfileHasCoopAccess) && $vendorProfileHasCoopAccess)
        @if(count($vendor?->vendorBlogs))
            <section class="blog-sec" style="padding-top: 0px !important; padding-bottom: 90px !important;">
                <div class="container">
                    {{--<h6 class="sec-hd-mini text-center"></h6> --}}
                    <h2 class="sec-hd text-center">Latest News & Updates</h2>
        
                    <div class="blog-slider">
                        @foreach($vendor?->vendorBlogs as $blog)
    
                            <div class="blog-card">
                                <div class="all-area">
                                    <div class="blog-img-area">
                                        <img src="{{ asset('storage/vendor-blogs/'.$blog?->featured_img) }}" alt="{{ $blog?->title ?? '' }}" class="w-100">
                        
                                        <div class="date">{{ $blog?->created_at->format('M d, Y') }}</div>
                                        
                                    </div>
        
                                    <div class="blog-content-area">
                                        
                                        <h4>{{ \Str::limit(strip_tags($blog?->title), 30) }}</h4>
                                        
                                        <p>{{ \Str::limit(strip_tags($blog?->short_des ?? ''), 60) }}</p>
                                        
                                        <p class="cmnt-para" style="opacity:0.8;">
                                            <i class="fa-regular fa-clock"></i>
                                            {{ $blog?->read_in_minutes ?? 0 }} min Read
                                        </p>
    
                                        <a href="{{ route('vendor.blog.detail', $blog['slug']) }}">
                                            Read More
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
        
                    {{--<div class="d-flex justify-content-center">
                        <a href="{{ $blogsSection['view_all_button_link'] ?? route('blogs') }}" class="primary-btn">
                            {{ $blogsSection['view_all_button_text'] ?? 'View All' }}
                        </a>
                    </div> --}}
                </div>
            </section>
        @endif
    @endif
    
    
@endsection


@push('scripts')
    @php
    $listingsAddresses = $vendor?->listings?->map(fn($listing) => [
        'id' => $listing->id,
        'city' => strtolower($listing->city),
        'state' => $listing->state,
        'country' => $listing->country,
        'title' => $listing->title,
    ]) ?? [];
    @endphp

    <script>
    const listings = @json($listingsAddresses);
    
    let map;
    let markers = [];
    
    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 39.104524, lng: -95.099001 }, // default center
            zoom: 5,
        });
    
        placeMarkers(listings);
    
        // Filter on select
        document.getElementById("cityFilter").addEventListener("change", function() {
            const selectedCity = this.value.toLowerCase();
            const filteredListings = selectedCity ? listings.filter(l => l.city === selectedCity) : listings;
            clearMarkers();
            placeMarkers(filteredListings);
        });
    }
    
    function placeMarkers(listingsToShow) {
        const geocoder = new google.maps.Geocoder();
        
        listingsToShow.forEach(listing => {
            const address = `${listing.city}, ${listing.state}, ${listing.country}`;
            
            geocoder.geocode({ address: address }, (results, status) => {
                if (status === "OK" && results[0]) {
                    const marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                        title: listing.title
                    });
                    markers.push(marker);
    
                    // Adjust bounds
                    const bounds = new google.maps.LatLngBounds();
                    markers.forEach(m => bounds.extend(m.getPosition()));
                    map.fitBounds(bounds);
                } else {
                    console.warn("Geocode failed for address:", address, status);
                }
            });
        });
    }

    
    function clearMarkers() {
        markers.forEach(m => m.setMap(null));
        markers = [];
    }
    </script>
    
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD28UEoebX1hKscL3odt2TiTRVfe5SSpwE&callback=initMap">
    </script>
    
    <script>
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
@endpush
