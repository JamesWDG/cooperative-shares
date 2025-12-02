@extends('layouts.admin.app')

@push('styles')
    <style>
        .propInput,
        .propTextarea,
        .propSelect {
            margin-bottom: 0px !important;
        }

        .propFormGroup {
            margin-bottom: 20px;
        }

        .propLabel {
            font-weight: 600;
            font-family: var(--outfit-font);
            margin-bottom: 4px;
            display: block;
        }

        .propValue {
            font-family: var(--outfit-font);
            font-size: 14px;
            color: #333;
        }

        .propFormContainer {
            margin-top: 25px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }

        .prop-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .propFormGroup {
            flex: 1 1 220px;
        }

        .view-hd {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .standard-listing-wrapper {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .badge-listing {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            font-family: var(--outfit-font);
            background: #e0f2f1;
            color: #004d40;
        }

        /* PREVIEW STYLES */
        .image-preview-wrapper {
            margin-top: 15px;
        }

        #main-image-preview {
            max-width: 220px;
            max-height: 220px;
            border-radius: 8px;
            border: 1px solid #e2e2e2;
            object-fit: cover;
        }

        .file-preview-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 15px;
        }

        .file-preview-item {
            width: 140px;
            text-align: center;
            font-size: 12px;
            font-family: var(--outfit-font);
            position: relative;
        }

        .file-preview-item img,
        .file-preview-item video {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e2e2e2;
        }

        .file-preview-item .file-name {
            display: block;
            margin-top: 4px;
            word-break: break-all;
        }

        .amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 20px;
            margin-top: 10px;
        }

        .amenity-item {
            font-family: var(--outfit-font);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .amenity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4caf50;
        }

        .amenity-item.disabled .amenity-dot {
            background: #ccc;
        }

        .amenity-item.disabled span {
            color: #999;
        }
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Property Details</h1>

        {{-- LISTING TYPE --}}
        <div class="propFormContainer">
            <h2 class="view-hd">Listing Type</h2>
            <div class="standard-listing-wrapper">
                <div>
                    <span class="propLabel">Type</span>
                    <span class="badge-listing">
                        {{ $listing->listing == 'featured' ? 'Featured Listing ($349.00)' : 'Standard Listing ($0)' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- OVERVIEW --}}
        <div class="propFormContainer">
            <h2 class="view-hd">Overview</h2>

            <div class="propFormGroup">
                <label class="propLabel">Property Title</label>
                <p class="propValue">{{ $listing->property_title }}</p>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Description</label>
                <p class="propValue">{{ $listing->description }}</p>
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Category</label>
                    <p class="propValue">
                        @if($listing->category == 'apartment')
                            Senior Coop 55+
                        @elseif($listing->category == 'commercial')
                            Senior Coop 62+
                        @elseif($listing->category == 'land-or-plot')
                            Family Coop
                        @else
                            {{ ucfirst($listing->category) }}
                        @endif
                    </p>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Property Type</label>
                    <p class="propValue">{{ $listing->listed_in }}</p>
                </div>
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Price</label>
                    <p class="propValue">${{ number_format($listing->price, 0) }}</p>
                </div>
            </div>
        </div>

        {{-- LISTING DETAILS --}}
        <div class="propFormContainer">
            <h2 class="view-hd">Listing Details</h2>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Size in ft</label>
                    <p class="propValue">{{ $listing->size_in_ft }}</p>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Bedrooms</label>
                    <p class="propValue">{{ $listing->bedrooms }}</p>
                </div>
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Bathrooms</label>
                    <p class="propValue">{{ $listing->bathrooms }}</p>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Kitchens</label>
                    <p class="propValue">{{ $listing->kitchens }}</p>
                </div>
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Garages</label>
                    <p class="propValue">{{ $listing->garages }}</p>
                </div>
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Year Built</label>
                    <p class="propValue">{{ $listing->year_built }}</p>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Number of floors in the building</label>
                    <p class="propValue">{{ $listing->floors }}</p>
                </div>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Listing Description</label>
                <p class="propValue">{{ $listing->listing_description }}</p>
            </div>
        </div>

        {{-- MAIN IMAGE + FILES --}}
        <div class="propFormContainer">
            <h2 class="view-hd">Main Image</h2>

            <div class="image-preview-wrapper" id="existing-main-image-wrapper"
                 style="{{ $listing->main_image ? '' : 'display:none;' }}">
                @if($listing->main_image)
                    <img id="main-image-preview"
                         src="{{ asset('storage/listing/images/' . $listing->main_image) }}"
                         alt="Main Image Preview">
                @else
                    <p class="propValue">No main image uploaded.</p>
                @endif
            </div>

            <hr class="mt-4 mb-3">

            <h2 class="view-hd">Photo &amp; Video Attachment</h2>

            <div id="existing-file-list" class="file-preview-list">
                @forelse($listing->images as $file)
                    @php
                        $isImage = $file->type === 'image';
                        $url = $isImage
                            ? asset('storage/listing/images/' . $file->filename)
                            : asset('storage/listing/videos/' . $file->filename);
                    @endphp
                    <div class="file-preview-item">
                        @if($isImage)
                            <img src="{{ $url }}" alt="{{ $file->filename }}">
                        @else
                            <video src="{{ $url }}" controls></video>
                        @endif
                        <span class="file-name">{{ $file->filename }}</span>
                    </div>
                @empty
                    <p class="propValue">No additional photos or videos.</p>
                @endforelse
            </div>
        </div>

        {{-- AMENITIES --}}
        <div class="propFormContainer">
            <h2 class="view-hd">Amenities</h2>

            <div class="amenities">
                @php
                    $amenities = [
                        'has_garages' => 'Garages',
                        'has_pool' => 'Swimming Pool',
                        'has_parking' => 'Parking',
                        'has_lakeview' => 'Lake View',
                        'has_garden' => 'Garden',
                        'has_fireplace' => 'Fireplace',
                        'has_pet' => 'Pet Friendly',
                        'has_refrigerator' => 'Refrigerator',
                        'has_dryer' => 'Dryer',
                        'has_wifi' => 'Wifi',
                        'has_tv' => 'TV Cable',
                        'has_bbq' => 'Barbeque',
                        'has_laundry' => 'Laundry',
                        'has_accessible' => 'Disable Access',
                        'has_lawn' => 'Lawn',
                        'has_elevator' => 'Elevator',
                        'has_fitness_center' => 'Fitness Center',
                        'has_common_room' => 'Common Room Reservation',
                        'has_guest_suite' => 'Guest Suite',
                        'has_all_appliances_included' => 'All Appliances Included',
                        'has_all_appliances_not_included' => 'All Appliances Not Included',
                        'has_washer_dryer_included' => 'Washer & Dryer Included',
                        'has_washer_dryer_not_included' => 'Washer & Dryer Not Included',
                    ];
                @endphp

                @foreach($amenities as $field => $label)
                    @php $enabled = $listing->{$field} ?? false; @endphp
                    <div class="amenity-item {{ $enabled ? '' : 'disabled' }}">
                        <span class="amenity-dot"></span>
                        <span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ADDRESS & LOCATION --}}
        <div class="propFormContainer">
            <h2 class="view-hd">Address &amp; Location</h2>

            <div class="propFormGroup">
                <label class="propLabel">Map Location (Text)</label>
                <p class="propValue">{{ $listing->map_location }}</p>
            </div>

            <div class="propFormGroup">
                <div id="map" style="width: 100%; height: 300px; border-radius: 8px; overflow: hidden;"></div>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Address</label>
                <p class="propValue">{{ $listing->address }}</p>
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Country</label>
                    <p class="propValue">{{ $listing->country }}</p>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">State</label>
                    <p class="propValue">{{ $listing->state }}</p>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">City</label>
                    <p class="propValue">{{ $listing->city }}</p>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Zip Code</label>
                    <p class="propValue">{{ $listing->zip_code }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Google Maps (view-only) --}}
    <script>
        let map, marker, geocoder;

        function initMap() {
            const defaultLatLng = { lat: 24.91031945, lng: 67.0583741 };

            geocoder = new google.maps.Geocoder();

            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLatLng,
                zoom: 13,
            });

            marker = new google.maps.Marker({
                map: map,
                position: defaultLatLng,
            });

            const locationAddress = @json($listing->map_location);

            if (locationAddress) {
                geocoder.geocode({ address: locationAddress }, function (results, status) {
                    if (status === 'OK' && results[0]) {
                        const loc = results[0].geometry.location;
                        map.setCenter(loc);
                        map.setZoom(15);
                        marker.setPosition(loc);
                    } else {
                        console.log('Geocode failed: ' + status);
                    }
                });
            }
        }
    </script>

    <script async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD28UEoebX1hKscL3odt2TiTRVfe5SSpwE&callback=initMap">
    </script>
@endpush
