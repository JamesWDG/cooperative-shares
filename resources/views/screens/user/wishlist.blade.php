@extends('layouts.user.app')

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Save Listings</h1>

        <div class="profile-info-wrapper listing-info">
            <div class="listing-card-wrapper listing-card-wrapper2">
                @forelse($wishlistItems as $item)
                    @php
                        $listing = $item->listing;
                        if (!$listing) {
                            continue;
                        }

                        // Same image path you use on vendor side
                        $imageUrl = $listing->main_image
                            ? asset('storage/listing/images/' . $listing->main_image)
                            : asset('assets/user/images/Container2.png');

                        $title    = $listing->property_title ?? 'Listing #' . $listing->id;
                        $address  = $listing->address ?? 'Address not available';
                        $listedIn = $listing->listed_in ?? 'Co-Op Share';
                    @endphp

                    <div class="property-card">
                        <div class="img-area position-relative">
                            <img src="{{ $imageUrl }}" alt="" class="img-fluid">

                            {{-- Heart button: remove from wishlist --}}
                            <button class="btn heart-save-btn p-0 js-remove-wishlist"
                                    data-id="{{ $item->id }}">
                                <i class="fa-regular fa-heart"></i>
                            </button>

                            {{-- Show advertisement badge only if listing type is "featured" --}}
                            @if($listing->listing === 'featured')
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
                                    @if(!is_null($listing->price))
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
                    <h3 class="text-center w-100">No saved listings yet!</h3>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($wishlistItems instanceof \Illuminate\Pagination\LengthAwarePaginator && $wishlistItems->hasPages())
                @php
                    $current = $wishlistItems->currentPage();
                    $last    = $wishlistItems->lastPage();

                    // Limit: show one page before and after current
                    $start = max(1, $current - 1);
                    $end   = min($last, $current + 1);
                @endphp

                <div class="pagination-wrapper">
                    <div class="pag-para">
                        <p>
                            Showing <span>{{ $current }}</span> of
                            <span>{{ $last }}</span> Results
                        </p>
                    </div>
                    <div class="pagination-btns">
                        {{-- Prev --}}
                        @if($wishlistItems->onFirstPage())
                            <button disabled>
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $wishlistItems->previousPageUrl() }}">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pages --}}
                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $current)
                                <button class="active">{{ $page }}</button>
                            @else
                                <a href="{{ $wishlistItems->url($page) }}">{{ $page }}</a>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if($wishlistItems->hasMorePages())
                            <a href="{{ $wishlistItems->nextPageUrl() }}">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled>
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Reuse pagination circle style like vendor listing */
        .pagination-btns a,
        .pagination-btns button {
            width: 34px;
            height: 34px;
            border-radius: 100%;
            background-color: #346A7112;
            border: none;
            font-size: 16px;
            font-family: var(--roboto-font);
            font-weight: 400;
            color: var(--blue);
            text-decoration: none;
            display:flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-btns a.active,
        .pagination-btns button.active,
        .pagination-btns a:hover {
            background-color: var(--blue);
            color: var(--white);
        }

        .pagination-btns a i,
        .pagination-btns button i {
            font-size: 12px;
        }
    </style>
@endpush

@push('scripts')
    {{-- SweetAlert2 – skip if already globally loaded --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function ($) {

            $(document).on('click', '.js-remove-wishlist', function (e) {
                e.preventDefault();

                const $btn  = $(this);
                const itemId = $btn.data('id');
                const $card = $btn.closest('.property-card');

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
