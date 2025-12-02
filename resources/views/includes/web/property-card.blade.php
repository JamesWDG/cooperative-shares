{{-- resources/views/partials/property-card.blade.php --}}
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
