@extends('layouts.vendor.app')

@push('styles')
    <style>
        label.error {
            color: crimson;
            font-family: var(--outfit-font);
            font-weight: 500;
            margin-top: 8px;
        }

        .propInput,
        .propTextarea,
        .propSelect {
            margin-bottom: 0px !important;
        }

        .propFormGroup {
            margin-bottom: 30px;
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
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Add New Property</h1>
        <!-- Price Options -->
        <form id="listing-form">
            @csrf
            <div class="standard-listing-wrapper">
                <div class="standard-mini-wrapper">
                    <label for="simple-listing" class="label-para">
                        <div class="radio-wrapper">
                            <label class="property-radio-label">
                                <input type="radio" name="listing" value="simple" id="simple-listing" checked />
                                <span class="custom-radio"></span>
                                <p class="property-para">Standard Listing</p>
                            </label>
                        </div>
                        <p class="price-para">$0</p>
                    </label>
                </div>

                <div class="standard-mini-wrapper">
                    <label for="standard-listing" class="label-para">
                        <div class="radio-wrapper">
                            <label class="property-radio-label">
                                <input type="radio" name="listing" value="featured" id="featured-listing">
                                <span class="custom-radio"></span>
                                <p class="property-para">Featured Listing</p>
                            </label>
                        </div>
                        <p class="price-para">$349.00</p>
                    </label>
                </div>
            </div>

            <div class="propFormContainer">
                <h2 class="view-hd">Overview</h2>
                <div class="propFormGroup">
                    <label for="property-title" class="propLabel requiredMark">Property Title</label>
                    <input type="text" id="property-title" name="property_title" class="propInput">
                </div>
                <div class="propFormGroup">
                    <label for="description" class="propLabel requiredMark">Description</label>
                    <textarea id="description" name="description" class="propTextarea"
                              placeholder="Write about property..."></textarea>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="category" class="propLabel requiredMark">Category</label>
                        <select id="category" name="category" class="propSelect">
                            <option value="apartment">Senior Coop 55+</option>
                            <option value="commercial">Senior Coop 62+</option>
                            <option value="land-or-plot">Family Coop</option>
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="listed-in" class="propLabel requiredMark">Property Type</label>
                        <select id="listed-in" name="listed_in" class="propSelect">
                            <option value="Cooperative">Cooperative</option>
                            <option value="Senior">Senior</option>
                            <option value="Family">Family</option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="price" class="propLabel requiredMark">Price</label>
                        <input type="number" id="price" name="price" value="" class="propInput" placeholder="Your Price">
                    </div>
                </div>
            </div>

            <div class="propFormContainer">
                <h2 class="view-hd">Listing Details</h2>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="size-in-ft" class="propLabel requiredMark">Size in ft</label>
                        <input type="text" id="size-in-ft" name="size_in_ft" class="propInput" placeholder="Ex.3,210 sqft">
                    </div>
                    <div class="propFormGroup">
                        <label for="bedrooms" class="propLabel requiredMark">Bedrooms</label>
                        <select id="bedrooms" name="bedrooms" class="propSelect">
                            <option value="1">01</option>
                            <option value="2">02</option>
                            <option value="3">03</option>
                            <option value="4">04</option>
                            <option value="5">05</option>
                            <option value="6">06</option>
                            <option value="7">07</option>
                            <option value="8">08</option>
                            <option value="9">09</option>
                            <option value="10">10</option>
                            <option value="10+">10+</option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="bathrooms" class="propLabel requiredMark">Bathrooms</label>
                        <select id="bathrooms" name="bathrooms" class="propSelect">
                            <option value="1">01</option>
                            <option value="2">02</option>
                            <option value="3">03</option>
                            <option value="4">04</option>
                            <option value="5">05</option>
                            <option value="6">06</option>
                            <option value="7">07</option>
                            <option value="8">08</option>
                            <option value="9">09</option>
                            <option value="10">10</option>
                            <option value="10+">10+</option>
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="kitchens" class="propLabel requiredMark">Kitchens</label>
                        <select id="kitchens" name="kitchens" class="propSelect">
                            <option value="1">01</option>
                            <option value="2">02</option>
                            <option value="3">03</option>
                            <option value="4">04</option>
                            <option value="5">05</option>
                            <option value="5+">5+</option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="garages" class="propLabel requiredMark">Garages</label>
                        <select id="garages" name="garages" class="propSelect">
                            <option value="Underground">Underground</option>
                            <option value="Exterior">Exterior</option>
                            <option value="Outside Parking">Outside Parking</option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="year-built" class="propLabel requiredMark">Year Built</label>
                        <input type="date" id="year-built" name="year_built" class="propInput" />
                    </div>
                    <div class="propFormGroup">
                        <label for="floors" class="propLabel requiredMark">Number of floors in the building *</label>
                        <select id="floors" name="floors" class="propSelect">
                            <option value="1">01</option>
                            <option value="2">02</option>
                            <option value="3">03</option>
                            <option value="4">04</option>
                            <option value="5">05</option>
                            <option value="6">06</option>
                            <option value="7">07</option>
                            <option value="8">08</option>
                            <option value="9">09</option>
                            <option value="10">10</option>
                            <option value="10+">10+</option>
                        </select>
                    </div>
                </div>
                <div class="propFormGroup">
                    <label for="listing-description" class="propLabel requiredMark">Description</label>
                    <textarea id="listing-description" name="listing_description" class="propTextarea"
                              placeholder="Write about property..."></textarea>
                </div>
            </div>

            <div class="custom-upload-wrapper">
                <h2 class="view-hd">Main Image</h2>
                <div class="custom-upload-footer mb-5">
                    <label class="custom-upload-btn">
                        <input type="file" id="custom-image-input" name="main_image" hidden />
                        <span id="upload-files">
                            <img src="{{ asset('assets/vendor/images/listing-add-btn.png') }}" alt="">
                            Upload Image
                        </span>
                    </label>
                    <small>Upload any image file</small>
                </div>

                {{-- MAIN IMAGE PREVIEW --}}
                <div id="main-image-preview-wrapper" class="image-preview-wrapper" style="display:none;">
                    <img id="main-image-preview" src="" alt="Main Image Preview">
                </div>

                <hr />
                <h2 class="view-hd mt-5">Photo & Video Attachment</h2>
                <div class="custom-upload-footer">
                    <label class="custom-upload-btn">
                        <input type="file" id="custom-file-input" name="files[]" multiple hidden />
                        <span id="upload-files">
                            <img src="{{ asset('assets/vendor/images/listing-add-btn.png') }}" alt="">
                            Upload File
                        </span>
                    </label>
                    <small>Upload image or video files</small>
                </div>

                {{-- MULTIPLE FILES PREVIEW --}}
                <div id="custom-file-list" class="file-preview-list"></div>
            </div>

            <div class="custom-upload-wrapper">
                <h2 class="view-hd">Select Amenities</h2>
                <div class="amenities">
                    <div class="amenity-item"><input type="checkbox" name="has_garages" value="garages" id="garages"> Garages</div>
                    <div class="amenity-item"><input type="checkbox" name="has_pool" value="pool" id="pool"> Swimming Pool</div>
                    <div class="amenity-item"><input type="checkbox" name="has_parking" value="parking" id="parking"> Parking</div>
                    <div class="amenity-item"><input type="checkbox" name="has_lakeview" value="lakeview" id="lakeview"> Lake View</div>
                    <div class="amenity-item"><input type="checkbox" name="has_garden" value="garden" id="garden"> Garden</div>
                    <div class="amenity-item"><input type="checkbox" name="has_fireplace" value="fireplace" id="fireplace"> Fireplace</div>
                    <div class="amenity-item"><input type="checkbox" name="has_pet" value="pet" id="pet"> Pet Friendly</div>
                    <div class="amenity-item"><input type="checkbox" name="has_refrigerator" value="refrigerator" id="refrigerator"> Refrigerator</div>
                    <div class="amenity-item"><input type="checkbox" name="has_dryer" value="dryer" id="dryer"> Dryer</div>
                    <div class="amenity-item"><input type="checkbox" name="has_wifi" value="wifi" id="wifi"> Wifi</div>
                    <div class="amenity-item"><input type="checkbox" name="has_tv" value="tv" id="tv"> TV Cable</div>
                    <div class="amenity-item"><input type="checkbox" name="has_bbq" value="bbq" id="bbq"> Barbeque</div>
                    <div class="amenity-item"><input type="checkbox" name="has_laundry" value="laundry" id="laundry"> Laundry</div>
                    <div class="amenity-item"><input type="checkbox" name="has_accessible" value="accessible" id="accessible"> Disable Access</div>
                    <div class="amenity-item"><input type="checkbox" name="has_lawn" value="lawn" id="lawn"> Lawn</div>
                    <div class="amenity-item"><input type="checkbox" name="has_elevator" value="elevator" id="elevator"> Elevator</div>

                    <div class="amenity-item"><input type="checkbox" name="fitness_center" value="fitness_center">Fitness Center</div>
                    <div class="amenity-item"><input type="checkbox" name="common_room" value="common_room">Common Room Reservation</div>
                    <div class="amenity-item"><input type="checkbox" name="guest_suite" value="guest_suite">Guest Suite</div>
                    <div class="amenity-item"><input type="checkbox" name="all_appliances_included" value="all_appliances_included">All Appliances Included</div>
                    <div class="amenity-item"><input type="checkbox" name="all_appliances_not_included" value="all_appliances_not_included">All Appliances Not Included</div>
                    <div class="amenity-item"><input type="checkbox" name="washer_dryer_included" value="washer_dryer_included">Washer & Dryer Included</div>
                    <div class="amenity-item"><input type="checkbox" name="washer_dryer_not_included" value="washer_dryer_not_included">Washer & Dryer Not Included</div>
                </div>
            </div>

            <div class="propFormContainer">
                <h2 class="view-hd">Address & Location</h2>

                <!-- Map Location (search box) -->
                <div class="propFormGroup">
                    <label for="map-location" class="propLabel requiredMark">Map Location</label>
                    <input type="text" id="map-location" name="map_location" class="propInput"
                           placeholder="Type a location or address">
                </div>

                <!-- Map -->
                <div class="propFormGroup">
                    <div id="map" style="width: 100%; height: 300px; border-radius: 8px; overflow: hidden;"></div>
                </div>

                <!-- Full address -->
                <div class="propFormGroup">
                    <label for="address" class="propLabel">Address</label>
                    <input type="text" id="address" name="address" class="propInput" placeholder="Street, number">
                </div>

                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="country" class="propLabel requiredMark">Country</label>
                        <input type="text" id="country" name="country" class="propInput" placeholder="Country">
                    </div>

                    <div class="propFormGroup">
                        <label for="state" class="propLabel requiredMark">State</label>
                        <input type="text" id="state" name="state" class="propInput" placeholder="State">
                    </div>

                    <div class="propFormGroup">
                        <label for="city" class="propLabel requiredMark">City</label>
                        <input type="text" id="city" name="city" class="propInput" placeholder="City">
                    </div>

                    <div class="propFormGroup">
                        <label for="zip-code" class="propLabel requiredMark">Zip Code</label>
                        <input type="text" id="zip-code" name="zip_code" class="propInput" placeholder="Zip Code">
                    </div>
                </div>

                <!-- Hidden lat/lng -->
                <input type="hidden" id="lat" name="lat">
                <input type="hidden" id="lng" name="lng">

                <button class="add-btn">Submit Now</button>
            </div>

        </form>

    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.standard-mini-wrapper').forEach(wrapper => {
            wrapper.addEventListener('click', () => {
                const radio = wrapper.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });
    </script>

    <script>
        const amenityItems = document.querySelectorAll('.amenity-item');
        const selectedAmenities = [];

        amenityItems.forEach(item => {
            item.addEventListener('click', () => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;

                const value = checkbox.value;

                if (checkbox.checked) {
                    if (!selectedAmenities.includes(value)) {
                        selectedAmenities.push(value);
                    }
                } else {
                    const index = selectedAmenities.indexOf(value);
                    if (index > -1) {
                        selectedAmenities.splice(index, 1);
                    }
                }

                console.log(selectedAmenities);
            });
        });
    </script>

    {{-- Listing Form Validation --}}
    <script>
        $('#listing-form').validate({
            rules: {
                property_title: { required: true },
                description: { required: true },
                price: { required: true, number: true },
                size_in_ft: { required: true },
                year_built: { required: true },
                listing_description: { required: true },
                address: { required: true },
                country: { required: true },
                state: { required: true },
                city: { required: true },
                zip_code: { required: true },
                map_location: { required: true },
            },
            messages: {
                property_title: { required: 'Please Enter Property Title!' },
                description: { required: 'Please Enter Overview Description!' },
                price: {
                    required: 'Please Enter Price!',
                    number: 'Price Should Be In Numbers!'
                },
                size_in_ft: { required: 'Please Enter Property Size!' },
                year_built: { required: 'Please Provide Built Year!' },
                listing_description: { required: 'Please Enter Listing Description!' },
                address: { required: 'Please Enter Property Address!' },
                country: { required: 'Please Enter Select Country!' },
                state: { required: 'Please Enter Select State!' },
                city: { required: 'Please Enter Select City!' },
                zip_code: { required: 'Please Enter Zip Code!' },
                map_location: { required: 'Please Enter Map Location!' },
            },
        });
    </script>
    {{-- Listing Form Validation --}}

    {{-- Main Image Preview --}}
    <script>
        $('#custom-image-input').on('change', function () {
            const input = this;
            const file = input.files[0];

            if (!file) {
                $('#main-image-preview-wrapper').hide();
                return;
            }

            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    title: "Error!",
                    text: 'Main Image must be an image file!',
                    icon: 'error',
                    confirmButtonColor: '#295568',
                    confirmButtonText: 'OK'
                });
                input.value = '';
                $('#main-image-preview-wrapper').hide();
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                $('#main-image-preview').attr('src', e.target.result);
                $('#main-image-preview-wrapper').show();
            };
            reader.readAsDataURL(file);
        });
    </script>
    {{-- Main Image Preview --}}

    {{-- Validating File Types + Preview --}}
    <script>
        let flag = false;

        $('#custom-file-input').on('change', function () {
            let filesInput = this;
            const previewList = $("#custom-file-list");
            previewList.empty();
            flag = false;

            if (filesInput.files.length) {
                const files = Array.from(filesInput.files);
                const validFiles = [];

                files.forEach(file => {
                    const type = file.type;
                    const isImage = type.startsWith('image/');
                    const isVideo = type.startsWith('video/');

                    if (!isImage && !isVideo) {
                        Swal.fire({
                            title: "Error!",
                            text: `File Type Of (${file.name}) Is Not Allowed, Only image or video files are allowed.`,
                            icon: 'error',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    if (file.size >= (20 * 1024 * 1024)) {
                        Swal.fire({
                            title: "Error!",
                            text: `File Size Of (${file.name}) Exceeds 20MB Limit.`,
                            icon: 'error',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    validFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        let html = '';

                        if (isImage) {
                            html = `
                                <div class="file-preview-item">
                                    <img src="${e.target.result}" alt="${file.name}">
                                    <span class="file-name">${file.name}</span>
                                </div>
                            `;
                        } else if (isVideo) {
                            html = `
                                <div class="file-preview-item">
                                    <video src="${e.target.result}" controls></video>
                                    <span class="file-name">${file.name}</span>
                                </div>
                            `;
                        }

                        previewList.append(html);
                    };
                    reader.readAsDataURL(file);
                });

                if (validFiles.length && validFiles.length === files.length) {
                    flag = true;
                }
            } else {
                Swal.fire({
                    title: "Error!",
                    text: 'Please Upload A File First',
                    icon: 'error',
                    confirmButtonColor: '#295568',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
    {{-- Validating File Types + Preview --}}

    {{-- Listing Form Submission --}}
    <script>
        $(document).ready(function () {
            $('#listing-form').on('submit', function (e) {
                if (!$(this).valid() || !flag) {
                    Swal.fire({
                        title: "Error!",
                        text: 'Please Upload A File!',
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                let mainImage = $('#custom-image-input')[0];
                if (mainImage.files.length) {
                    if (!mainImage.files[0].type.startsWith('image/')) {
                        Swal.fire({
                            title: "Error!",
                            text: 'Main Image File Type Is Invalid!',
                            icon: 'error',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: 'Please Upload Main Image!',
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('vendor.listing.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $.LoadingOverlay('show');
                    },
                    success: function (response) {
                        $.LoadingOverlay('hide');
                        if (response.status) {
                            Swal.fire({
                                title: "Info!",
                                text: response.message,
                                icon: 'info',
                                confirmButtonColor: '#295568',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function (error) {
                        $.LoadingOverlay('hide');
                        let message = (error.responseJSON?.message) ? error.responseJSON?.message : error.statusText;
                        Swal.fire({
                            title: 'Something Went Wrong!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
    {{-- Listing Form Submission --}}

    <script>
        let map, marker, autocomplete;

        function initMap() {
            const defaultLatLng = { lat: 24.91031945, lng: 67.0583741 };

            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLatLng,
                zoom: 13,
            });

            marker = new google.maps.Marker({
                map: map,
                position: defaultLatLng,
                draggable: true,
            });

            const input = document.getElementById('map-location');
            autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['geocode'],
            });

            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                if (!place.geometry) {
                    console.log("Place has no geometry");
                    return;
                }

                if (place.geometry.location) {
                    map.setCenter(place.geometry.location);
                    map.setZoom(15);
                    marker.setPosition(place.geometry.location);

                    document.getElementById('lat').value = place.geometry.location.lat();
                    document.getElementById('lng').value = place.geometry.location.lng();
                }

                let street = '';
                let city   = '';
                let state  = '';
                let zip    = '';
                let country = '';

                if (place.address_components) {
                    place.address_components.forEach(function (component) {
                        const types = component.types;

                        if (types.includes('street_number')) {
                            street = component.long_name + ' ' + street;
                        }
                        if (types.includes('route')) {
                            street += component.long_name;
                        }

                        if (types.includes('locality')) {
                            city = component.long_name;
                        } else if (types.includes('postal_town')) {
                            city = component.long_name;
                        } else if (types.includes('administrative_area_level_2') && !city) {
                            city = component.long_name;
                        }

                        if (types.includes('administrative_area_level_1')) {
                            state = component.short_name;
                        }

                        if (types.includes('postal_code')) {
                            zip = component.long_name;
                        }

                        if (types.includes('country')) {
                            country = component.long_name;
                        }
                    });
                }

                document.getElementById('address').value  = street;
                document.getElementById('city').value     = city;
                document.getElementById('state').value    = state;
                document.getElementById('zip-code').value = zip;
                document.getElementById('country').value  = country;
            });
        }
    </script>

    <script async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD28UEoebX1hKscL3odt2TiTRVfe5SSpwE&libraries=places&callback=initMap">
    </script>

@endpush
