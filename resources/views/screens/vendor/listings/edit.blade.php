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

        /* PREVIEW STYLES (same as create) */
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

        .remove-file-btn,
        .remove-main-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: none;
            background: #f44336;
            color: #fff;
            font-size: 14px;
            line-height: 18px;
            cursor: pointer;
            padding: 0;
        }

        .main-image-wrapper {
            position: relative;
            display: inline-block;
        }
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Update Property</h1>

        <form id="listing-form">
            @csrf
            {{-- PRICE OPTIONS --}}
<div class="standard-listing-wrapper">

    {{-- Show Simple Only --}}
    @if($listing->listing === 'simple')
        <div class="standard-mini-wrapper">
            <label class="label-para">
                <div class="radio-wrapper">
                    <label class="property-radio-label">
                        <input type="radio" name="listing" value="simple" checked disabled />
                        <span class="custom-radio"></span>
                        <p class="property-para">Standard Listing</p>
                    </label>
                </div>
                <p class="price-para">$0</p>
            </label>

            {{-- Keep value submitted --}}
            <input type="hidden" name="listing" value="simple">
        </div>
    @endif

    {{-- Show Featured Only --}}
    @if($listing->listing === 'featured')
        <div class="standard-mini-wrapper">
            <label class="label-para">
                <div class="radio-wrapper">
                    <label class="property-radio-label">
                        <input type="radio" name="listing" value="featured" checked disabled />
                        <span class="custom-radio"></span>
                        <p class="property-para">Featured Listing</p>
                    </label>
                </div>
                <p class="price-para">$349.00</p>
            </label>

            {{-- Keep value submitted --}}
            <input type="hidden" name="listing" value="featured">
        </div>
    @endif

</div>


            {{-- OVERVIEW --}}
            <div class="propFormContainer">
                <h2 class="view-hd">Overview</h2>
                <div class="propFormGroup">
                    <label for="property-title" class="propLabel requiredMark">Property Title</label>
                    <input type="text" id="property-title" name="property_title"
                           value="{{ $listing->property_title }}" class="propInput">
                </div>
                <div class="propFormGroup">
                    <label for="description" class="propLabel requiredMark">Description</label>
                    <textarea id="description" name="description" class="propTextarea"
                              placeholder="Write about property...">{{ $listing->description }}</textarea>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="category" class="propLabel requiredMark">Category</label>
                        <select id="category" name="category" class="propSelect">
                            <option value="apartment" {{ $listing->category == 'apartment' ? 'selected' : '' }}>
                                Senior Coop 55+</option>
                            <option value="commercial" {{ $listing->category == 'commercial' ? 'selected' : '' }}>
                                Senior Coop 62+</option>
                            <option value="land-or-plot" {{ $listing->category == 'land-or-plot' ? 'selected' : '' }}>
                                Family Coop</option>
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="listed-in" class="propLabel requiredMark">Property Type</label>
                        <select id="listed-in" name="listed_in" class="propSelect">
                            <option value="Cooperative" {{ $listing->listed_in == 'Cooperative' ? 'selected' : '' }}>Cooperative</option>
                            <option value="Senior" {{ $listing->listed_in == 'Senior' ? 'selected' : '' }}>Senior</option>
                            <option value="Family" {{ $listing->listed_in == 'Family' ? 'selected' : '' }}>Family</option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="price" class="propLabel requiredMark">Price</label>
                        <input type="number" id="price" name="price" value="{{ $listing->price }}" class="propInput"
                               placeholder="Your Price">
                    </div>
                </div>
            </div>

            {{-- LISTING DETAILS --}}
            <div class="propFormContainer">
                <h2 class="view-hd">Listing Details</h2>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="size-in-ft" class="propLabel requiredMark">Size in ft</label>
                        <input type="text" id="size-in-ft" name="size_in_ft"
                               value="{{ $listing->size_in_ft }}" class="propInput"
                               placeholder="Ex.3,210 sqft">
                    </div>
                    <div class="propFormGroup">
                        <label for="bedrooms" class="propLabel requiredMark">Bedrooms</label>
                        <select id="bedrooms" name="bedrooms" class="propSelect">
                            @foreach(['1','2','3','4','5','6','7','8','9','10','10+'] as $val)
                                <option value="{{ $val }}" {{ $listing->bedrooms == $val ? 'selected' : '' }}>
                                    {{ str_pad($val, 2, '0', STR_PAD_LEFT) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="bathrooms" class="propLabel requiredMark">Bathrooms</label>
                        <select id="bathrooms" name="bathrooms" class="propSelect">
                            @foreach(['1','2','3','4','5','6','7','8','9','10','10+'] as $val)
                                <option value="{{ $val }}" {{ $listing->bathrooms == $val ? 'selected' : '' }}>
                                    {{ str_pad($val, 2, '0', STR_PAD_LEFT) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="kitchens" class="propLabel requiredMark">Kitchens</label>
                        <select id="kitchens" name="kitchens" class="propSelect">
                            @foreach(['1','2','3','4','5','5+'] as $val)
                                <option value="{{ $val }}" {{ $listing->kitchens == $val ? 'selected' : '' }}>
                                    {{ $val == '5+' ? '5+' : str_pad($val, 2, '0', STR_PAD_LEFT) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="garages" class="propLabel requiredMark">Garages</label>
                        <select id="garages" name="garages" class="propSelect">
                            <option value="Underground" {{ $listing->garages == 'Underground' ? 'selected' : '' }}>Underground</option>
                            <option value="Exterior" {{ $listing->garages == 'Exterior' ? 'selected' : '' }}>Exterior</option>
                            <option value="Outside Parking" {{ $listing->garages == 'Outside Parking' ? 'selected' : '' }}>Outside Parking</option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="year-built" class="propLabel requiredMark">Year Built</label>
                        <input type="date" id="year-built" name="year_built"
                               value="{{ $listing->year_built }}" class="propInput" />
                    </div>
                    <div class="propFormGroup">
                        <label for="floors" class="propLabel requiredMark">Number of floors in the building *</label>
                        <select id="floors" name="floors" class="propSelect">
                            @foreach(['1','2','3','4','5','6','7','8','9','10','10+'] as $val)
                                <option value="{{ $val }}" {{ $listing->floors == $val ? 'selected' : '' }}>
                                    {{ str_pad($val, 2, '0', STR_PAD_LEFT) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="propFormGroup">
                    <label for="listing-description" class="propLabel requiredMark">Description</label>
                    <textarea id="listing-description" name="listing_description" class="propTextarea"
                              placeholder="Write about property...">{{ $listing->listing_description }}</textarea>
                </div>
            </div>

            {{-- MAIN IMAGE + FILES --}}
            <div class="custom-upload-wrapper">
                <h2 class="view-hd">Main Image</h2>

                {{-- Existing main image preview --}}
                <div class="image-preview-wrapper" id="existing-main-image-wrapper"
                     style="{{ $listing->main_image ? '' : 'display:none;' }}">
                    @if($listing->main_image)
                        <div class="main-image-wrapper">
                            <img id="main-image-preview"
                                 src="{{ asset('storage/listing/images/' . $listing->main_image) }}"
                                 alt="Main Image Preview">
                            <button type="button" class="remove-main-btn" id="remove-existing-main">&times;</button>
                        </div>
                    @else
                        <img id="main-image-preview" src="" alt="Main Image Preview" style="display:none;">
                    @endif
                </div>

                {{-- New main image input --}}
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

                <hr />
                <h2 class="view-hd mt-5">Photo & Video Attachment</h2>

                {{-- Existing files (from listing_images) --}}
                <div id="existing-file-list" class="file-preview-list">
                    @foreach($listing->images as $file)
                        @php
                            $isImage = $file->type === 'image';
                            $url = $isImage
                                ? asset('storage/listing/images/' . $file->filename)
                                : asset('storage/listing/videos/' . $file->filename);
                        @endphp
                        <div class="file-preview-item" data-id="{{ $file->id }}">
                            @if($isImage)
                                <img src="{{ $url }}" alt="{{ $file->filename }}">
                            @else
                                <video src="{{ $url }}" controls></video>
                            @endif
                            <span class="file-name">{{ $file->filename }}</span>
                            <button type="button" class="remove-file-btn remove-existing-file"
                                    data-id="{{ $file->id }}">&times;</button>
                        </div>
                    @endforeach
                </div>

                {{-- New files preview --}}
                <div id="custom-file-list" class="file-preview-list"></div>

                {{-- New files input --}}
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
            </div>

            {{-- AMENITIES (same as create, but pre-checked from listing) --}}
            <div class="custom-upload-wrapper">
                <h2 class="view-hd">Select Amenities</h2>
                <div class="amenities">
                    <div class="amenity-item"><input type="checkbox" name="has_garages" value="garages" id="has_garages"
                        {{ $listing->has_garages ? 'checked' : '' }}> Garages</div>
                    <div class="amenity-item"><input type="checkbox" name="has_pool" value="pool" id="has_pool"
                        {{ $listing->has_pool ? 'checked' : '' }}> Swimming Pool</div>
                    <div class="amenity-item"><input type="checkbox" name="has_parking" value="parking" id="has_parking"
                        {{ $listing->has_parking ? 'checked' : '' }}> Parking</div>
                    <div class="amenity-item"><input type="checkbox" name="has_lakeview" value="lakeview" id="has_lakeview"
                        {{ $listing->has_lakeview ? 'checked' : '' }}> Lake View</div>
                    <div class="amenity-item"><input type="checkbox" name="has_garden" value="garden" id="has_garden"
                        {{ $listing->has_garden ? 'checked' : '' }}> Garden</div>
                    <div class="amenity-item"><input type="checkbox" name="has_fireplace" value="fireplace" id="has_fireplace"
                        {{ $listing->has_fireplace ? 'checked' : '' }}> Fireplace</div>
                    <div class="amenity-item"><input type="checkbox" name="has_pet" value="pet" id="has_pet"
                        {{ $listing->has_pet ? 'checked' : '' }}> Pet Friendly</div>
                    <div class="amenity-item"><input type="checkbox" name="has_refrigerator" value="refrigerator" id="has_refrigerator"
                        {{ $listing->has_refrigerator ? 'checked' : '' }}> Refrigerator</div>
                    <div class="amenity-item"><input type="checkbox" name="has_dryer" value="dryer" id="has_dryer"
                        {{ $listing->has_dryer ? 'checked' : '' }}> Dryer</div>
                    <div class="amenity-item"><input type="checkbox" name="has_wifi" value="wifi" id="has_wifi"
                        {{ $listing->has_wifi ? 'checked' : '' }}> Wifi</div>
                    <div class="amenity-item"><input type="checkbox" name="has_tv" value="tv" id="has_tv"
                        {{ $listing->has_tv ? 'checked' : '' }}> TV Cable</div>
                    <div class="amenity-item"><input type="checkbox" name="has_bbq" value="bbq" id="has_bbq"
                        {{ $listing->has_bbq ? 'checked' : '' }}> Barbeque</div>
                    <div class="amenity-item"><input type="checkbox" name="has_laundry" value="laundry" id="has_laundry"
                        {{ $listing->has_laundry ? 'checked' : '' }}> Laundry</div>
                    <div class="amenity-item"><input type="checkbox" name="has_accessible" value="accessible" id="has_accessible"
                        {{ $listing->has_accessible ? 'checked' : '' }}> Disable Access</div>
                    <div class="amenity-item"><input type="checkbox" name="has_lawn" value="lawn" id="has_lawn"
                        {{ $listing->has_lawn ? 'checked' : '' }}> Lawn</div>
                    <div class="amenity-item"><input type="checkbox" name="has_elevator" value="elevator" id="has_elevator"
                        {{ $listing->has_elevator ? 'checked' : '' }}> Elevator</div>

                    <div class="amenity-item"><input type="checkbox" name="has_fitness_center" value="has_fitness_center"
                        {{ $listing->has_fitness_center ?? false ? 'checked' : '' }}>Fitness Center</div>
                    <div class="amenity-item"><input type="checkbox" name="has_common_room" value="has_common_room"
                        {{ $listing->has_common_room ?? false ? 'checked' : '' }}>Common Room Reservation</div>
                    <div class="amenity-item"><input type="checkbox" name="has_guest_suite" value="has_guest_suite"
                        {{ $listing->has_guest_suite ?? false ? 'checked' : '' }}>Guest Suite</div>
                    <div class="amenity-item"><input type="checkbox" name="has_all_appliances_included" value="has_all_appliances_included"
                        {{ $listing->has_all_appliances_included ?? false ? 'checked' : '' }}>All Appliances Included</div>
                    <div class="amenity-item"><input type="checkbox" name="has_all_appliances_not_included" value="has_all_appliances_not_included"
                        {{ $listing->has_all_appliances_not_included ?? false ? 'checked' : '' }}>All Appliances Not Included</div>
                    <div class="amenity-item"><input type="checkbox" name="has_washer_dryer_included" value="has_washer_dryer_included"
                        {{ $listing->has_washer_dryer_included ?? false ? 'checked' : '' }}>Washer & Dryer Included</div>
                    <div class="amenity-item"><input type="checkbox" name="has_washer_dryer_not_included" value="has_washer_dryer_not_included"
                        {{ $listing->has_washer_dryer_not_included ?? false ? 'checked' : '' }}>Washer & Dryer Not Included</div>
                </div>
            </div>

            {{-- ADDRESS & LOCATION (Google Maps same as create) --}}
            <div class="propFormContainer">
                <h2 class="view-hd">Address & Location</h2>

                <div class="propFormGroup">
                    <label for="map-location" class="propLabel requiredMark">Map Location</label>
                    <input type="text" id="map-location" name="map_location" class="propInput"
                           placeholder="Type a location or address"
                           value="{{ $listing->map_location }}">
                </div>

                <div class="propFormGroup">
                    <div id="map" style="width: 100%; height: 300px; border-radius: 8px; overflow: hidden;"></div>
                </div>

                <div class="propFormGroup">
                    <label for="address" class="propLabel">Address</label>
                    <input type="text" id="address" name="address" class="propInput"
                           placeholder="Street, number" value="{{ $listing->address }}">
                </div>

                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="country" class="propLabel requiredMark">Country</label>
                        <input type="text" id="country" name="country" class="propInput"
                               placeholder="Country" value="{{ $listing->country }}">
                    </div>

                    <div class="propFormGroup">
                        <label for="state" class="propLabel requiredMark">State</label>
                        <input type="text" id="state" name="state" class="propInput"
                               placeholder="State" value="{{ $listing->state }}">
                    </div>

                    <div class="propFormGroup">
                        <label for="city" class="propLabel requiredMark">City</label>
                        <input type="text" id="city" name="city" class="propInput"
                               placeholder="City" value="{{ $listing->city }}">
                    </div>

                    <div class="propFormGroup">
                        <label for="zip-code" class="propLabel requiredMark">Zip Code</label>
                        <input type="text" id="zip-code" name="zip_code" class="propInput"
                               placeholder="Zip Code" value="{{ $listing->zip_code }}">
                    </div>
                </div>

                <input type="hidden" id="lat" name="lat">
                <input type="hidden" id="lng" name="lng">


                <button class="add-btn">Update Now</button>
            </div>

        </form>
    </section>
@endsection

@push('scripts')
    <script>
        // Select listing type by clicking whole box
        document.querySelectorAll('.standard-mini-wrapper').forEach(wrapper => {
            wrapper.addEventListener('click', () => {
                const radio = wrapper.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });
    </script>

    <script>
        // Amenity clickable wrapper (same as create)
        const amenityItems = document.querySelectorAll('.amenity-item');
        const selectedAmenities = [];

        amenityItems.forEach(item => {
            item.addEventListener('click', (e) => {
                // prevent double-trigger when clicking checkbox directly
                if (e.target.tagName.toLowerCase() === 'input') return;

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

    {{-- Validation (same rules as create) --}}
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

    {{-- Main image preview (accept any image/*) --}}
    <script>
        $('#custom-image-input').on('change', function () {
            const input = this;
            const file = input.files[0];

            if (!file) {
                // if no new file, don't touch existing preview
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
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                $('#main-image-preview').attr('src', e.target.result).show();
                $('#existing-main-image-wrapper').show();

                // If user is uploading a new main image, we should NOT delete it on backend
                // so remove delete_main_image flag if present
                $('#delete_main_image_flag').remove();
            };
            reader.readAsDataURL(file);
        });

        // Remove existing main image (mark for delete)
        $(document).on('click', '#remove-existing-main', function () {
            // hide preview
            $('#existing-main-image-wrapper').hide();
            $('#main-image-preview').attr('src', '');

            // clear file input
            $('#custom-image-input').val('');

            // add hidden flag for backend
            if (!$('#delete_main_image_flag').length) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'delete_main_image_flag',
                    name: 'delete_main_image',
                    value: '1'
                }).appendTo('#listing-form');
            }
        });
    </script>

    {{-- Files preview (image or video, no "flag" requirement) --}}
    <script>
        $('#custom-file-input').on('change', function () {
            const filesInput = this;
            const previewList = $("#custom-file-list");
            previewList.empty();

            if (!filesInput.files.length) return;

            const files = Array.from(filesInput.files);

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
        });

        // Remove existing files (listing_images) – mark for delete
        $(document).on('click', '.remove-existing-file', function () {
            const id = $(this).data('id');
            $(this).closest('.file-preview-item').remove();

            // append hidden input deleted_files[]
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_files[]',
                value: id
            }).appendTo('#listing-form');
        });
    </script>

    {{-- Form submission (no "flag" check, just validation) --}}
    <script>
        $(document).ready(function () {
            $('#listing-form').on('submit', function (e) {
                if (!$(this).valid()) {
                    return false;
                }

                e.preventDefault();
                let formData = new FormData(this);
                formData.append('_method', 'PUT')
                $.ajax({
                    url: "{{ route('vendor.listing.update', $listing->id) }}",
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

    {{-- Google Maps (same logic as create) --}}
    
    <script>
    let map, marker, autocomplete, geocoder;

    function initMap() {
        const defaultLatLng = { lat: 24.91031945, lng: 67.0583741 };

        const latInput  = document.getElementById('lat');
        const lngInput  = document.getElementById('lng');
        const locationInput = document.getElementById('map-location');

        geocoder = new google.maps.Geocoder();

        // 1) Map init – default center pe
        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLatLng,
            zoom: 13,
        });

        // 2) Marker init – default position pe
        marker = new google.maps.Marker({
            map: map,
            position: defaultLatLng,
            draggable: true,
        });

        // 3) Agar edit page pe map_location already filled hai, usko geocode karo
        if (locationInput.value) {
            geocoder.geocode({ address: locationInput.value }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    const loc = results[0].geometry.location;

                    map.setCenter(loc);
                    map.setZoom(15);
                    marker.setPosition(loc);

                    latInput.value = loc.lat();
                    lngInput.value = loc.lng();
                } else {
                    console.log('Geocode failed: ' + status);
                }
            });
        }

        // 4) Marker drag hone pe hidden lat/lng update karo (future ke liye useful)
        google.maps.event.addListener(marker, 'dragend', function (e) {
            const lat = e.latLng.lat();
            const lng = e.latLng.lng();

            latInput.value = lat;
            lngInput.value = lng;
        });

        // 5) Autocomplete setup – same as pehle, bus lat/lng bhi update karwa do
        autocomplete = new google.maps.places.Autocomplete(locationInput, {
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

                const lat = place.geometry.location.lat();
                const lng = place.geometry.location.lng();

                latInput.value = lat;
                lngInput.value = lng;
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
