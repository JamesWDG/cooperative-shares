@extends('layouts.admin.app')

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

        /* Optional: cursor change for disabled fields so user knows they can't edit */
        .propInput[disabled],
        .propTextarea[disabled],
        .propSelect[disabled],
        .amenity-item input[disabled],
        .standard-mini-wrapper input[disabled] {
            cursor: not-allowed;
            background-color: #f5f5f5;
        }

        .amenity-item {
            pointer-events: none; /* click bhi disable ho jaye */
        }
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">View Property</h1>

        {{-- View only: no real form submit needed, but keeping form for layout --}}
        <form>
            @csrf
            <div class="standard-listing-wrapper">
                <div class="standard-mini-wrapper">
                    <label for="simple-listing" class="label-para">
                        <div class="radio-wrapper">
                            <label class="property-radio-label">
                                <input type="radio" name="listing" value="simple" id="simple-listing"
                                       {{ ($listing->listing == 'simple') ? 'checked' : '' }} disabled />
                                <span class="custom-radio"></span>
                                <p class="property-para">Standard Listing</p>
                            </label>
                        </div>
                        <p class="price-para">$0</p>
                    </label>
                </div>
                <!--<div class="standard-mini-wrapper">-->
                <!--    <label for="standard-listing" class="label-para">-->
                <!--        <div class="radio-wrapper">-->
                <!--            <label class="property-radio-label">-->
                <!--                <input type="radio" name="listing" value="standard" id="standard-listing"-->
                <!--                       {{ ($listing->listing == 'standard') ? 'checked' : '' }} disabled />-->
                <!--                <span class="custom-radio"></span>-->
                <!--                <p class="property-para">Standard Listing</p>-->
                <!--            </label>-->
                <!--        </div>-->
                <!--        <p class="price-para">$149.00</p>-->
                <!--    </label>-->
                <!--</div>-->
                <div class="standard-mini-wrapper">
                    <label for="featured-listing" class="label-para">
                        <div class="radio-wrapper">
                            <label class="property-radio-label">
                                <input type="radio" name="listing" value="featured" id="featured-listing"
                                       {{ ($listing->listing == 'featured') ? 'checked' : '' }} disabled />
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
                    <input type="text" id="property-title" name="property_title"
                           value="{{ $listing->property_title }}" class="propInput" disabled>
                </div>
                <div class="propFormGroup">
                    <label for="description" class="propLabel requiredMark">Description</label>
                    <textarea id="description" name="description" class="propTextarea" disabled
                              placeholder="Write about property...">{{ $listing->description }}</textarea>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="category" class="propLabel requiredMark">Category</label>
                        <select id="category" name="category" class="propSelect" disabled>
                            <option value="apartment" {{ ($listing->category == 'apartment') ? 'selected' : '' }}>Apartment</option>
                            <option value="commercial" {{ ($listing->category == 'commercial') ? 'selected' : '' }}>Commercial</option>
                            <option value="land-or-plot" {{ ($listing->category == 'land-or-plot') ? 'selected' : '' }}>Land or Plot</option>
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="listed-in" class="propLabel requiredMark">Listed Type</label>
                        <select id="listed-in" name="listed_in" class="propSelect" disabled>
                            <option value="Full Ownership" {{ ($listing->listed_in == 'Full Ownership') ? 'selected' : '' }}>
                                Full Ownership
                            </option>
                            <option value="Co-Op Share" {{ ($listing->listed_in == 'Co-Op Share') ? 'selected' : '' }}>
                                Co-Op Share
                            </option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="price" class="propLabel requiredMark">Price</label>
                        <input type="number" id="price" name="price" value="{{ $listing->price }}"
                               class="propInput" placeholder="Your Price" disabled>
                    </div>
                    <div class="propFormGroup">
                        <label for="tax-rate" class="propLabel requiredMark">Yearly Tax Rate</label>
                        <input type="number" id="tax-rate" name="tax_rate" value="{{ $listing->tax_rate }}"
                               class="propInput" placeholder="Tax Rate" disabled>
                    </div>
                </div>
            </div>

            <div class="propFormContainer">
                <h2 class="view-hd">Listing Details</h2>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="size-in-ft" class="propLabel requiredMark">Size in ft</label>
                        <input type="text" id="size-in-ft" name="size_in_ft" value="{{ $listing->size_in_ft }}"
                               class="propInput" placeholder="Ex.3,210 sqft" disabled>
                    </div>
                    <div class="propFormGroup">
                        <label for="bedrooms" class="propLabel requiredMark">Bedrooms</label>
                        <select id="bedrooms" name="bedrooms" class="propSelect" disabled>
                            <option value="1" {{ ($listing->bedrooms == '1') ? 'selected' : '' }}>01</option>
                            <option value="2" {{ ($listing->bedrooms == '2') ? 'selected' : '' }}>02</option>
                            <option value="3" {{ ($listing->bedrooms == '3') ? 'selected' : '' }}>03</option>
                            <option value="4" {{ ($listing->bedrooms == '4') ? 'selected' : '' }}>04</option>
                            <option value="5" {{ ($listing->bedrooms == '5') ? 'selected' : '' }}>05</option>
                            <option value="6" {{ ($listing->bedrooms == '6') ? 'selected' : '' }}>06</option>
                            <option value="7" {{ ($listing->bedrooms == '7') ? 'selected' : '' }}>07</option>
                            <option value="8" {{ ($listing->bedrooms == '8') ? 'selected' : '' }}>08</option>
                            <option value="9" {{ ($listing->bedrooms == '9') ? 'selected' : '' }}>09</option>
                            <option value="10" {{ ($listing->bedrooms == '10') ? 'selected' : '' }}>10</option>
                            <option value="10+" {{ ($listing->bedrooms == '10+') ? 'selected' : '' }}>10+</option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="bathrooms" class="propLabel requiredMark">Bathrooms</label>
                        <select id="bathrooms" name="bathrooms" class="propSelect" disabled>
                            <option value="1" {{ ($listing->bathrooms == '1') ? 'selected' : '' }}>01</option>
                            <option value="2" {{ ($listing->bathrooms == '2') ? 'selected' : '' }}>02</option>
                            <option value="3" {{ ($listing->bathrooms == '3') ? 'selected' : '' }}>03</option>
                            <option value="4" {{ ($listing->bathrooms == '4') ? 'selected' : '' }}>04</option>
                            <option value="5" {{ ($listing->bathrooms == '5') ? 'selected' : '' }}>05</option>
                            <option value="6" {{ ($listing->bathrooms == '6') ? 'selected' : '' }}>06</option>
                            <option value="7" {{ ($listing->bathrooms == '7') ? 'selected' : '' }}>07</option>
                            <option value="8" {{ ($listing->bathrooms == '8') ? 'selected' : '' }}>08</option>
                            <option value="9" {{ ($listing->bathrooms == '9') ? 'selected' : '' }}>09</option>
                            <option value="10" {{ ($listing->bathrooms == '10') ? 'selected' : '' }}>10</option>
                            <option value="10+" {{ ($listing->bathrooms == '10+') ? 'selected' : '' }}>10+</option>
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="kitchens" class="propLabel requiredMark">Kitchens</label>
                        <select id="kitchens" name="kitchens" class="propSelect" disabled>
                            <option value="1" {{ ($listing->kitchens == '1') ? 'selected' : '' }}>01</option>
                            <option value="2" {{ ($listing->kitchens == '2') ? 'selected' : '' }}>02</option>
                            <option value="3" {{ ($listing->kitchens == '3') ? 'selected' : '' }}>03</option>
                            <option value="4" {{ ($listing->kitchens == '4') ? 'selected' : '' }}>04</option>
                            <option value="5" {{ ($listing->kitchens == '5') ? 'selected' : '' }}>05</option>
                            <option value="5+" {{ ($listing->kitchens == '5+') ? 'selected' : '' }}>5+</option>
                        </select>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="garages" class="propLabel requiredMark">Garages</label>
                        <select id="garages" name="garages" class="propSelect" disabled>
                            <option value="1" {{ ($listing->garages == '1') ? 'selected' : '' }}>01</option>
                            <option value="2" {{ ($listing->garages == '2') ? 'selected' : '' }}>02</option>
                            <option value="3" {{ ($listing->garages == '3') ? 'selected' : '' }}>03</option>
                            <option value="3+" {{ ($listing->garages == '3+') ? 'selected' : '' }}>3+</option>
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="garage-size" class="propLabel requiredMark">Garage Size</label>
                        <input type="text" id="garage-size" name="garage_size" value="{{ $listing->garage_size }}"
                               class="propInput" placeholder="Ex.3,210 sqft" disabled>
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="year-built" class="propLabel requiredMark">Year Built</label>
                        <input type="date" id="year-built" name="year_built" value="{{ $listing->year_built }}"
                               class="propInput" disabled />
                    </div>
                    <div class="propFormGroup">
                        <label for="floors" class="propLabel requiredMark">Floors No</label>
                        <select id="floors" name="floors" class="propSelect" disabled>
                            <option value="1" {{ ($listing->floors == '1') ? 'selected' : '' }}>01</option>
                            <option value="2" {{ ($listing->floors == '2') ? 'selected' : '' }}>02</option>
                            <option value="3" {{ ($listing->floors == '3') ? 'selected' : '' }}>03</option>
                            <option value="4" {{ ($listing->floors == '4') ? 'selected' : '' }}>04</option>
                            <option value="5" {{ ($listing->floors == '5') ? 'selected' : '' }}>05</option>
                            <option value="6" {{ ($listing->floors == '6') ? 'selected' : '' }}>06</option>
                            <option value="7" {{ ($listing->floors == '7') ? 'selected' : '' }}>07</option>
                            <option value="8" {{ ($listing->floors == '8') ? 'selected' : '' }}>08</option>
                            <option value="9" {{ ($listing->floors == '9') ? 'selected' : '' }}>09</option>
                            <option value="10" {{ ($listing->floors == '10') ? 'selected' : '' }}>10</option>
                            <option value="10+" {{ ($listing->floors == '10+') ? 'selected' : '' }}>10+</option>
                        </select>
                    </div>
                </div>
                <div class="propFormGroup">
                    <label for="listing-description" class="propLabel requiredMark">Description</label>
                    <textarea id="listing-description" name="listing_description" class="propTextarea"
                              placeholder="Write about property..." disabled>{{ $listing->listing_description }}</textarea>
                </div>
            </div>

            <div class="custom-upload-wrapper">
                <h2 class="view-hd">Main Image</h2>
                <div class="custom-file-list">
                    @if($listing->main_image)
                        <div class="custom-file-item" id="main-image">
                            <img src="{{ asset('storage/' . $listing->main_image) }}" alt="Main Image"
                                 style="width:200px;" />
                            {{-- Cross / delete button removed for view-only --}}
                        </div>
                    @endif
                </div>

                <hr />
                <h2 class="view-hd mt-5">Photo & Video Attachment</h2>
                <div id="custom-file-list" class="custom-file-list">
                    @if(json_decode($listing->files))
                        @foreach(json_decode($listing->files) as $file)
                            <div class="custom-file-item">
                                <span>{{ $file->name }}</span>
                                {{-- Delete cross removed --}}
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="custom-upload-wrapper">
                <h2 class="view-hd">Select Amenities</h2>
                <div class="amenities">
                    <div class="amenity-item">
                        <input type="checkbox" name="has_ac" value="ac" id="ac"
                               {{ ($listing->has_ac != null) ? 'checked' : '' }} disabled />A/C & Heating
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_garages" value="garages" id="garages"
                               {{ ($listing->has_garages != null) ? 'checked' : '' }} disabled />Garages
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_pool" value="pool" id="pool"
                               {{ ($listing->has_pool != null) ? 'checked' : '' }} disabled />Swimming Pool
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_parking" value="parking" id="parking"
                               {{ ($listing->has_parking != null) ? 'checked' : '' }} disabled />Parking
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_lakeview" value="lakeview" id="lakeview"
                               {{ ($listing->has_lakeview != null) ? 'checked' : '' }} disabled />Lake View
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_garden" value="garden" id="garden"
                               {{ ($listing->has_garden != null) ? 'checked' : '' }} disabled />Garden
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_fireplace" value="fireplace" id="fireplace"
                               {{ ($listing->has_fireplace != null) ? 'checked' : '' }} disabled />Fireplace
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_pet" value="pet" id="pet"
                               {{ ($listing->has_pet != null) ? 'checked' : '' }} disabled />Pet Friendly
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_ceiling" value="ceiling" id="ceiling"
                               {{ ($listing->has_ceiling != null) ? 'checked' : '' }} disabled />Ceiling Height
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_shower" value="shower" id="shower"
                               {{ ($listing->has_shower != null) ? 'checked' : '' }} disabled />Outdoor Shower
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_refrigerator" value="refrigerator" id="refrigerator"
                               {{ ($listing->has_refrigerator != null) ? 'checked' : '' }} disabled />Refrigerator
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_dryer" value="dryer" id="dryer"
                               {{ ($listing->has_dryer != null) ? 'checked' : '' }} disabled />Dryer
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_wifi" value="wifi" id="wifi"
                               {{ ($listing->has_wifi != null) ? 'checked' : '' }} disabled />Wifi
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_tv" value="tv" id="tv"
                               {{ ($listing->has_tv != null) ? 'checked' : '' }} disabled />TV Cable
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_bbq" value="bbq" id="bbq"
                               {{ ($listing->has_bbq != null) ? 'checked' : '' }} disabled />Barbeque
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_laundry" value="laundry" id="laundry"
                               {{ ($listing->has_laundry != null) ? 'checked' : '' }} disabled />Laundry
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_accessible" value="accessible" id="accessible"
                               {{ ($listing->has_accessible != null) ? 'checked' : '' }} disabled />Disable Access
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_lawn" value="lawn" id="lawn"
                               {{ ($listing->has_lawn != null) ? 'checked' : '' }} disabled />Lawn
                    </div>
                    <div class="amenity-item">
                        <input type="checkbox" name="has_elevator" value="elevator" id="elevator"
                               {{ ($listing->has_elevator != null) ? 'checked' : '' }} disabled />Elevator
                    </div>
                </div>
            </div>

            <div class="propFormContainer">
                <h2 class="view-hd">Address & Location</h2>
                <div class="propFormGroup">
                    <label for="address" class="propLabel requiredMark">Address</label>
                    <input type="text" id="address" name="address" value="{{ $listing->address }}"
                           class="propInput" placeholder="19 yawkey Way" disabled>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="country" class="propLabel requiredMark">Country</label>
                        <select id="country" name="country" class="propSelect" autocomplete="true" disabled>
                            <option value="United States">United States</option>
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="state" class="propLabel requiredMark">State</label>
                        <select id="state" name="state" class="propSelect" autocomplete="true" disabled>
                            <option value="">Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->name }}" data-id="{{ $state->id }}"
                                    {{ ($state->name === $listing->state) ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="city" class="propLabel requiredMark">City</label>
                        <select id="city" name="city" class="propSelect" disabled>
                            <option value="">Select City</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->name }}" data-id="{{ $city->id }}"
                                    {{ ($city->name === $listing->city) ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="propFormGroup">
                        <label for="zip-code" class="propLabel requiredMark">Zip Code</label>
                        <input type="number" id="zip-code" name="zip_code" value="{{ $listing->zip_code }}"
                               class="propInput" placeholder="7078" disabled>
                    </div>
                </div>

                <div class="propFormGroup">
                    <label for="map-location" class="propLabel requiredMark">Map Location</label>
                    <input type="text" id="map-location" name="map_location"
                           value="{{ $listing->map_location }}" class="propInput"
                           placeholder="XC23+6XC, Moiran, N105" disabled>
                </div>
                <div class="propFormGroup">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14474.552842670242!2d67.0583741!3d24.91031945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1754333400866!5m2!1sen!2s"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </form>
    </section>
@endsection

{{-- View-only: no scripts needed, so @push('scripts') removed --}}
