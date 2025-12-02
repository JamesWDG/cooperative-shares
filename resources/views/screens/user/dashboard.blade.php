@extends('layouts.user.app')

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Dashboard</h1>

        {{-- Stats Row --}}
        <div class="row mb-5 mt-4">
            {{-- Listings Visited --}}
            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                <div class="listing-wrapp">
                    <div class="listing-box justify-content-between">
                        <h4>Listings </br> Visited</h4>
                        <h5>{{ $listingCount }}</h5>
                    </div>
                </div>
            </div>

            {{-- Leads Generated --}}
            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                <div class="listing-wrapp">
                    <div class="listing-box justify-content-between listing-box2 m-0">
                        <h4>Leads </br> Generated</h4>
                        <h5>{{ $leadCount }}</h5>
                    </div>
                </div>
            </div>

            {{-- Appointments --}}
            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                <div class="listing-wrapp">
                    <div class="listing-box justify-content-between"> 
                        <h4>My </br> Appointments</h4>
                        <h5>{{ $appointmentCount }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Saved Listings --}}
        <h1 class="dashboard-hd">Saved Listings</h1>

        <div class="listing-card-wrapper">
            @forelse($wishlistItems as $item)
                @php
                    $listing = $item->listing;
                    if (!$listing) {
                        continue;
                    }

                    // Image path same as vendor listing
                    $imageUrl = $listing->main_image
                        ? asset('storage/listing/images/' . $listing->main_image)
                        : asset('assets/user/images/Container2.png');

                    // Title & address from listing
                    $title   = $listing->property_title ?? 'Listing #' . $listing->id;
                    $address = $listing->address ?? 'Address not available';

                    // Badge text (listed_in)
                    $listedIn = $listing->listed_in ?? 'Co-Op Share';

                    // Listing type for advertisement badge logic
                    $listingType = $listing->listing ?? null; // 'simple' or 'featured'
                @endphp

                <div class="property-card">
                    <div class="img-area position-relative">
                        <img src="{{ $imageUrl }}" alt="" class="img-fluid">

                        {{-- Example co-op badge, if you have such field --}}
                        @if (!empty($listing->cooperative_name))
                            <div class="property-card-badge3">{{ $listing->cooperative_name }}</div>
                        @endif

                        {{-- Save/heart icon (remove from wishlist) --}}
                        <button class="btn heart-save-btn p-0 js-remove-wishlist" data-id="{{ $item->id }}">
                            <i class="fa-regular fa-heart"></i>
                        </button>

                        {{-- Show advertisement badge ONLY if listing type is "featured" --}}
                        @if ($listingType === 'featured')
                            <img src="{{ asset('assets/user/images/advertisment-badge.png') }}" alt=""
                                class="advertisment-badge">
                        @endif
                    </div>

                    <div class="property-card-body">
                        <div class="property-name position-relative">
                            <h3>{{ $title }}</h3>
                            <p>
                                <i class="fa-solid fa-location-dot"></i>
                                {{ $address }}
                            </p>
                            <div class="property-card-badge">{{ $listedIn }}</div>
                        </div>

                        <div class="other-desc">
                            <div class="d-flex justify-content-between">
                                <p>
                                    <img src="{{ asset('assets/user/images/Vector1.png') }}" alt="">
                                    Bed {{ $listing->bedrooms ?? '-' }}
                                </p>
                                <span>|</span>
                                <p>
                                    <img src="{{ asset('assets/user/images/Vector2.png') }}" alt="">
                                    Bath {{ $listing->bathrooms ?? '-' }}
                                </p>
                                <span>|</span>
                                <p>
                                    <img src="{{ asset('assets/user/images/Vector3.png') }}" alt="">
                                    {{ $listing->size_in_ft ?? '-' }} sqft
                                </p>
                            </div>
                        </div>

                        <div class="property-name d-flex justify-content-between align-items-center">
                            <h3 class="m-0">
                                @if (!is_null($listing->price))
                                    @moneyFormat($listing->price)
                                @else
                                    Price on request
                                @endif
                            </h3>

                            <a href="{{ route('listing.detail', $listing->id) }}">View More</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>No saved listings yet.</p>
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
    {{-- SweetAlert2 if not already globally loaded --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function ($) {

            $(document).on('click', '.js-remove-wishlist', function (e) {
                e.preventDefault();

                const $btn   = $(this);
                const itemId = $btn.data('id');
                const $card  = $btn.closest('.property-card');

                Swal.fire({
                    title: 'Remove from saved listings?',
                    text: 'This listing will be removed from your wishlist.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#295568',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: "{{ route('user.saved-listing.remove') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            wishlist_item_id: itemId
                        },
                        success: function (response) {
                            if (response.status) {
                                Swal.fire(
                                    'Removed!',
                                    response.message || 'Listing removed from your saved list.',
                                    'success'
                                );
                                $card.fadeOut(200, function () {
                                    $(this).remove();
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    response.message || 'Unable to remove listing.',
                                    'error'
                                );
                            }
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Something went wrong.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                });
            });

        })(jQuery);
    </script>
@endpush
