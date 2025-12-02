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

        /* Backdrop + z-index */
        .modal-backdrop {
            z-index: 1040 !important;
        }

        #stripePaymentModal {
            z-index: 1060 !important;
        }

        /* Dialog width + center */
        #stripePaymentModal .modal-dialog {
            max-width: 900px;
            margin: 1.75rem auto;
        }

        @media (max-width: 767.98px) {
            #stripePaymentModal .modal-dialog {
                max-width: 100%;
                margin: .5rem auto;
            }
        }

        /* ---------- PREMIUM STRIPE-STYLE LOOK ---------- */

        .payment-modal .modal-content {
            border-radius: 14px;
            border: none;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 40%, #f3f4ff 100%);
        }

        .payment-modal .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            background: linear-gradient(120deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
            color: #e5e7eb;
        }

        .payment-modal .modal-title {
            font-weight: 600;
            font-size: 1.05rem;
            letter-spacing: 0.02em;
        }

        .payment-modal .modal-header small {
            color: #9ca3af !important;
            display: block;
            margin-top: 2px;
            font-size: 0.8rem;
        }

        .payment-modal .btn-close {
            filter: invert(1) grayscale(100%);
            opacity: 0.7;
        }

        .payment-modal .btn-close:hover {
            opacity: 1;
        }

        .payment-modal .modal-body {
            padding: 1.5rem 1.5rem 1.75rem;
            max-height: calc(100vh - 180px);
            overflow-y: auto;
        }

        .payment-modal .plan-detail {
            background: #ffffff;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(79, 70, 229, 0.15);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.05);
        }

        .payment-modal .plan-detail label.control-label {
            margin-bottom: 6px;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #295568;
            font-weight: 700;
        }

        .payment-modal .plan-detail p {
            margin: 0;
            font-weight: 800 !important;
            color: #0f172a;
            font-size: 1.05rem;
        }

        .payment-modal .fld-wrp label.control-label,
        .payment-modal .expiration label.control-label,
        .payment-modal .cvc label.control-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 4px;
        }

        .payment-modal .form-control {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
            box-shadow: none;
            transition: all 0.15s ease;
            background-color: #ffffff;
        }

        .payment-modal .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.2);
            outline: none;
        }

        .payment-modal .mini-fld .form-control {
            font-size: 0.85rem;
        }

        .payment-modal .alert-danger {
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        .payment-modal #stripe-submit-btn {
            border-radius: 999px;
            padding: 0.65rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 40%, #22c55e 100%);
            box-shadow: 0 14px 30px rgba(79, 70, 229, 0.35);
        }

        .payment-modal #stripe-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 40px rgba(79, 70, 229, 0.45);
        }

        .payment-modal #stripe-submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
        }

        .payment-modal .secure-note {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 6px;
            text-align: center;
        }

        .unique-pricing-box.active-plan-box {
            border: 2px solid #295568;
            box-shadow: 0 18px 40px rgba(79, 70, 229, 0.25);
            position: relative;
            transform: translateY(-4px);
        }

        .unique-pricing-box.active-plan-box .unique-price {
            color: #295568;
        }

        .current-plan-badge {
            display: inline-block;
            margin-left: 8px;
            padding: 2px 8px;
            font-size: 0.7rem;
            border-radius: 999px;
            background: rgba(34, 197, 94, 0.12);
            color: #16a34a;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        @media (max-width: 575.98px) {
            .payment-modal .modal-body {
                padding: 1.1rem 1rem 1.25rem;
            }
        }
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Add Paid Featured Property</h1>

        <form id="listing-form">
            @csrf

            {{-- Force Featured Listing --}}
            <input type="hidden" name="listing" value="featured">

            <div class="standard-listing-wrapper" id="listing-type-wrapper">
                <div class="standard-mini-wrapper featured-option-wrapper" data-type="featured">
                    <label class="label-para">
                        <div class="radio-wrapper">
                            <label class="property-radio-label">
                                <span class="custom-radio active"></span>
                                <p class="property-para">Featured Listing (Paid)</p>
                            </label>
                        </div>
                        <p class="price-para">$349.00</p>
                    </label>
                </div>
            </div>

            {{-- Overview --}}
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
                        <input type="number" id="price" name="price" class="propInput" placeholder="Your Price">
                    </div>
                </div>
            </div>

            {{-- Listing Details --}}
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

            {{-- Uploads --}}
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

                <div id="custom-file-list" class="file-preview-list"></div>
            </div>

            {{-- Amenities --}}
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

            {{-- Address & Location --}}
            <div class="propFormContainer">
                <h2 class="view-hd">Address & Location</h2>

                <div class="propFormGroup">
                    <label for="map-location" class="propLabel requiredMark">Map Location</label>
                    <input type="text" id="map-location" name="map_location" class="propInput"
                           placeholder="Type a location or address">
                </div>

                <div class="propFormGroup">
                    <div id="map" style="width: 100%; height: 300px; border-radius: 8px; overflow: hidden;"></div>
                </div>

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

                <input type="hidden" id="lat" name="lat">
                <input type="hidden" id="lng" name="lng">

                <button class="add-btn">Submit Now</button>
            </div>

        </form>
    </section>

    {{-- Stripe Payment Modal --}}
    <div class="modal fade payment-modal"
         id="stripePaymentModal"
         tabindex="-1"
         aria-hidden="true"
         data-bs-backdrop="static"
         data-bs-keyboard="false">

        <div class="modal-dialog modal-dialog-centered modal-lg justify-content-center">
            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Pay for Featured Listing</h5>
                        <small class="text-muted">
                            Secure payment powered by Stripe
                        </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form
                        role="form"
                        method="post"
                        class="require-validation"
                        data-cc-on-file="false"
                        data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                        id="payment-form">

                        @csrf

                        <div class="required plan-detail">
                            <label class="control-label">Listing:</label>
                            <p class="mb-1">
                                <span id="stripe_listing_title_text">Featured Listing</span>
                            </p>
                        </div>

                        <div class="mb-3 required plan-detail">
                            <label class="control-label">Amount:</label>
                            <p class="mb-0">
                                <span id="stripe_listing_price_text">$349.00</span>
                            </p>
                        </div>

                        <div class="mb-3 required fld-wrp">
                            <label class="control-label">Name on Card</label>
                            <input class="form-control" type="text" name="card_name" placeholder="John Doe">
                        </div>

                        <div class="mb-3 required fld-wrp">
                            <label class="control-label">Card Number</label>
                            <input
                                autocomplete="off"
                                class="form-control card-number"
                                type="text"
                                placeholder="4242 4242 4242 4242">
                        </div>

                        <div class="row g-3 mini-fld">
                            <div class="col-6 col-md-4 required expiration">
                                <label class="control-label">Expiration Month</label>
                                <input class="form-control card-expiry-month" placeholder="MM" type="text">
                            </div>
                            <div class="col-6 col-md-4 required expiration">
                                <label class="control-label">Expiration Year</label>
                                <input class="form-control card-expiry-year" placeholder="YYYY" type="text">
                            </div>
                            <div class="col-12 col-md-4 required cvc">
                                <label class="control-label">CVC</label>
                                <input
                                    autocomplete="off"
                                    class="form-control card-cvc"
                                    placeholder="CVC"
                                    type="text">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 error form-group hide">
                                <div class="alert alert-danger">
                                    Please correct the errors and try again.
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary w-100 justify-content-center"
                                    type="submit"
                                    id="stripe-submit-btn">
                                Pay &amp; Publish Listing
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Amenities click-to-toggle --}}
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
                property_title: { required: 'Please enter a property title.' },
                description: { required: 'Please enter the overview description.' },
                price: {
                    required: 'Please enter a price.',
                    number: 'Price must be a numeric value.'
                },
                size_in_ft: { required: 'Please enter the property size.' },
                year_built: { required: 'Please provide the year built.' },
                listing_description: { required: 'Please enter the listing description.' },
                address: { required: 'Please enter the property address.' },
                country: { required: 'Please select country.' },
                state: { required: 'Please select state.' },
                city: { required: 'Please select city.' },
                zip_code: { required: 'Please enter the zip code.' },
                map_location: { required: 'Please enter the map location.' },
            },
        });
    </script>

    {{-- Main Image Preview (toast for errors) --}}
    <script>
        $('#custom-image-input').on('change', function () {
            const input = this;
            const file = input.files[0];

            if (!file) {
                $('#main-image-preview-wrapper').hide();
                return;
            }

            if (!file.type.startsWith('image/')) {
                showToastjQuery("Error", "Main image must be an image file.", "error");
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

    {{-- Validating File Types + Preview (toast only) --}}
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
                        showToastjQuery(
                            "Error",
                            `File type of (${file.name}) is not allowed. Only image or video files are allowed.`,
                            "error"
                        );
                        return;
                    }

                    if (file.size >= (20 * 1024 * 1024)) {
                        showToastjQuery(
                            "Error",
                            `File size of (${file.name}) exceeds the 20MB limit.`,
                            "error"
                        );
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
                showToastjQuery("Error", "Please upload at least one file.", "error");
            }
        });
    </script>

    {{-- Toast JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script type="text/javascript">
        function showToastjQuery(title_text, message, iconClass) {
            $.toast({
                heading: title_text,
                text: message,
                position: 'top-right',
                showHideTransition: 'slide',
                icon: iconClass,
                loaderBg: '#30809B',

                // 👇 Add duration control
                hideAfter: 10000, // 10 seconds (10000 milliseconds)
                allowToastClose: true
            });
        }
    </script>


    {{-- Step 1: Listing form submit -> backend validation (no Stripe yet) --}}
    <script>
        let listingFormData = null;

        $(document).ready(function () {
            $('#listing-form').on('submit', function (e) {
                // basic front validation
                if (!$(this).valid()) {
                    showToastjQuery(
                        "Validation Error",
                        "Please complete all required fields.",
                        "error"
                    );
                    return false;
                }

                

                // Main image validation
                let mainImage = $('#custom-image-input')[0];
                if (!mainImage.files.length || !mainImage.files[0].type.startsWith('image/')) {
                    showToastjQuery(
                        "Validation Error",
                        "Please upload a main image file.",
                        "error"
                    );
                    return false;
                }

                // Attachments required
                const filesInput = $('#custom-file-input')[0];
                if (!filesInput.files.length || !flag) {
                    showToastjQuery(
                        "Validation Error",
                        "Please upload at least one photo or video.",
                        "error"
                    );
                    return false;
                }

                e.preventDefault();

                listingFormData = new FormData(this);

                $.ajax({
                    url: "{{ route('vendor.listing.validate.paid.featured') }}",
                    type: 'POST',
                    data: listingFormData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $.LoadingOverlay('show');
                    },
                    success: function (response) {
                        $.LoadingOverlay('hide');

                        if (response.status) {
                            showToastjQuery(
                                "Validated",
                                response.msg || "Your property details have been validated. Please proceed with payment.",
                                "info"
                            );

                            if (response.data && response.data.property_title) {
                                $('#stripe_listing_title_text').text(response.data.property_title);
                            }
                            if (response.data && response.data.amount) {
                                $('#stripe_listing_price_text').text(
                                    '$' + parseFloat(response.data.amount).toFixed(2)
                                );
                            }

                            const modalEl = document.getElementById('stripePaymentModal');
                            const modal   = new bootstrap.Modal(modalEl);
                            modal.show();

                        } else {
                            showToastjQuery(
                                "Validation Error",
                                response.msg || "Please review your property details and try again.",
                                "error"
                            );
                        }
                    },
                    error: function (xhr) {
                        $.LoadingOverlay('hide');

                        let message = "An unexpected error occurred while validating your property.";
                        if (xhr.responseJSON && xhr.responseJSON.msg) {
                            message = xhr.responseJSON.msg;
                        }

                        showToastjQuery("Error", message, "error");
                    }
                });
            });
        });
    </script>

    {{-- Step 2: Stripe payment -> charge + save listing --}}
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript">
        $(function() {

            var $form = $(".require-validation");
            // ⬇️ Add this line
            var $closeBtn = $('#stripePaymentModal .btn-close');

            $('form.require-validation').on('submit', function(e) {
                e.preventDefault();

                if (!listingFormData) {
                    showToastjQuery("Error", "Please submit the property form first.", "error");
                    return;
                }

                var inputSelector = [
                        'input[type=email]',
                        'input[type=password]',
                        'input[type=text]',
                        'input[type=file]',
                        'textarea'
                    ].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;

                $errorMessage.addClass('hide');
                $('.has-error').removeClass('has-error');

                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        valid = false;
                    }
                });

                if (!valid) {
                    showToastjQuery("Validation Error", "Please fill in all card details.", "error");
                    return;
                }
                // 🔒 Disable pay button + close button while processing
                $('#stripe-submit-btn').prop('disabled', true).text('Processing...');
                $closeBtn.prop('disabled', true).addClass('disabled');

                Stripe.setPublishableKey($form.data('stripe-publishable-key'));

                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);

                    $('#stripe-submit-btn').prop('disabled', false).text('Pay & Publish Listing');
                    $closeBtn.prop('disabled', false).removeClass('disabled');

                    showToastjQuery("Payment Error", response.error.message, "error");
                } else {
                    var token = response['id'];

                    listingFormData.append('stripeToken', token);

                    showToastjQuery(
                        "Processing",
                        "Completing your payment and saving your property. Please wait...",
                        "info"
                    );

                    $.ajax({
                        url: "{{ route('vendor.listing.store.paid.featured') }}",
                        type: 'POST',
                        data: listingFormData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#stripe-submit-btn').prop('disabled', false).text('Pay & Publish Listing');
                            $closeBtn.prop('disabled', false).removeClass('disabled');
                            if (response.status) {
                                showToastjQuery(
                                    "Success",
                                    response.msg || "Your payment was successful and your property has been submitted.",
                                    "success"
                                );

                                setTimeout(function () {
                                    if (response.redirect_url) {
                                        window.location.href = response.redirect_url;
                                    } else {
                                        window.location.reload();
                                    }
                                }, 1200);

                            } else {
                                showToastjQuery(
                                    "Error",
                                    response.msg || "Something went wrong while saving your property.",
                                    "error"
                                );
                            }
                        },
                        error: function (xhr) {
                            $('#stripe-submit-btn').prop('disabled', false).text('Pay & Publish Listing');
                            $closeBtn.prop('disabled', false).removeClass('disabled');
                            let message = "An unexpected error occurred while processing your payment.";
                            if (xhr.responseJSON && xhr.responseJSON.msg) {
                                message = xhr.responseJSON.msg;
                            }

                            showToastjQuery("Payment Error", message, "error");
                        }
                    });
                }
            }

        });
    </script>

    {{-- Google Maps + Places --}}
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

    {{-- Plan Limit Popup (Swal only for decision, not for error) --}}
    <script>
        $(function () {
            // Replace "true" with your actual condition
            if (true) {
                Swal.fire({
                    title: "Plan Limit Reached",
                    text: "You have used all free Standard and Featured listings in your current plan. You can upgrade your plan or continue by creating this as a paid Featured Listing.",
                    icon: "warning",

                    showCancelButton: true,
                    showDenyButton: true,

                    confirmButtonText: "Proceed as Paid Featured",
                    denyButtonText: "Go to Subscription Plans",
                    cancelButtonText: "Go Back",

                    confirmButtonColor: "#295568",
                    denyButtonColor: "#17a2b8",
                    cancelButtonColor: "#6c757d"
                }).then((result) => {

                    if (result.isConfirmed) {
                        showToastjQuery(
                            "Success",
                            "You may proceed. This listing will be processed as a paid Featured Listing.",
                            "success"
                        );
                    } else if (result.isDenied) {
                        window.location.href = "{{ route('vendor.subscription.plans') }}";
                    } else {
                        window.location.href = "{{ route('vendor.listings') }}";
                    }
                });
            }
        });
    </script>
@endpush
