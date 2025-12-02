@extends('layouts.vendor.app')

@push('styles')
    <style>
        .pagination-btns a {
            width: 34px;
            height: 34px;
            border-radius: 100%;
            background-color: #346A7112;
            border: none;
            font-size: 16px;
            font-family: var(--roboto-font);
            font-family: 400;
            color: var(--blue);
            text-decoration: none;
            display:flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-btns a.active,
        .pagination-btns a:hover {
            background-color: var(--blue);
            color: var(--white);
        }

        .pagination-btns a i {
            font-size: 12px;
        }
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <div class="heading-wrapper">
            <h1 class="dashboard-hd">Listings</h1>
            <a href="{{ route('vendor.listing.create') }}" class="add-btn"><img
                    src="{{ asset('assets/vendor/images/listing-add-btn.png') }}" alt=""> Add Listing</a>
        </div>
        <div class="profile-info-wrapper listing-info">
            <div class="listing-card-wrapper listing-card-wrapper2">
                @forelse ($listings as $listing)
                    <div class="property-card">
                        <div class="img-area position-relative">
                            <img src="{{ $listing->main_image ? asset('storage/listing/images/' . $listing->main_image) : '' }}" alt="" class="img-fluid">
                            <div class="property-card-badge3">XYZ cooperative</div>
                            {{-- <img src="{{ asset('assets/vendor/images/advertisment-badge.png') }}" alt=""
                                class="advertisment-badge"> --}}
                        </div>
                        <div class="property-card-body">
                            <div class="property-name position-relative">
                                <div class="property-dotted-btn-wrapper">
                                    <div>
                                        <h3>{{ $listing->property_title }}</h3>
                                        <p><i class="fa-solid fa-location-dot"></i> {{ $listing->address }}</p>
                                    </div>
                                    <div>
                                        <img src="{{ asset('assets/vendor/images/property-dotted-btn.png') }}"
                                            class="custom-toggle" alt="">
                                        <div class="custom-dropdown-menu" style="display: none;">
                                            <div class="custom-dropdown-item">
                                               <a href="{{ route('vendor.listing.edit', $listing->id) }}" class="text-light text-decoration-none d-block w-100">Edit</a>
                                            </div>
                                            <div class="custom-dropdown-item">
                                                <a id="delete-btn" data-id="{{ $listing->id }}" class="text-danger text-decoration-none d-block w-100">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="property-card-badge">{{ $listing->listed_in }}</div>
                            </div>
                            <div class="other-desc">
                                <div class="d-flex justify-content-between">
                                    <p><img src="{{ asset('assets/vendor/images/Vector1.png') }}" alt=""> Bed
                                        {{ $listing->bedrooms }}
                                    </p>
                                    <span>|</span>
                                    <p><img src="{{ asset('assets/vendor/images/Vector2.png') }}" alt=""> Bath
                                        {{ $listing->bathrooms }}
                                    </p>
                                    <span>|</span>
                                    <p><img src="{{ asset('assets/vendor/images/Vector3.png') }}" alt="">
                                        {{ $listing->size_in_ft }} sqft</p>
                                </div>
                            </div>
                            <div class="property-name d-flex justify-content-between align-items-center">
                                <h3 class="m-0">@moneyFormat($listing->price)</h3>
                                <a href="{{ route('listing.detail', $listing->id) }}">View More</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <h3 class="text-center w-100">No Listing Added Yet!</h3>
                @endforelse
            </div>

            @if($listings->hasPages())
                @php
                    $current = $listings->currentPage();
                    $last = $listings->lastPage();

                    // Show limited pages around current page
                    $start = max(1, $current - 1);
                    $end = min($last, $current + 1);

                    // Always show first page if not in range
                    if ($start > 1) {
                        $start = 1;
                    }

                    // Always show last page if not in range
                    if ($end < $last) {
                        $end = $last;
                    }
                @endphp

                <div class="pagination-wrapper">
                    <div class="pag-para">
                        <p>Showing <span>{{ $listings->currentPage() }}</span> of <span>{{ $listings->lastPage() }}</span> Results</p>
                    </div>
                    <div class="pagination-btns">
                        @if($listings->onFirstPage())
                            <a onclick="return false" class="pagination-btns">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @else
                            <a href="{{ $listings->previousPageUrl() }}" class="pagination-btns">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $current)
                                <a onclick="return false" class="pagination-btns active">
                                    {{ $page }}
                                </a>
                            @else
                                <a href="{{ $listings->url($page) }}" class="pagination-btns">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if($listings->hasMorePages())
                            <a href="{{ $listings->nextPageUrl() }}" class="pagination-btns">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        @else
                            <a onclick="return false" class="pagination-btns">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggles = document.querySelectorAll(".custom-toggle");

            toggles.forEach((toggle) => {
                toggle.addEventListener("click", function (e) {
                    e.stopPropagation();
                    const menu = this.parentElement.querySelector(".custom-dropdown-menu");

                    // Close other open dropdowns
                    document.querySelectorAll(".custom-dropdown-menu").forEach((m) => {
                        if (m !== menu) m.style.display = "none";
                    });

                    // Toggle current dropdown
                    menu.style.display = menu.style.display === "block" ? "none" : "block";
                });
            });

            // Hide dropdown if clicked outside
            document.addEventListener("click", function () {
                document.querySelectorAll(".custom-dropdown-menu").forEach((menu) => {
                    menu.style.display = "none";
                });
            });
        });
    </script>

    {{-- Delete Listing --}}
    <script>
        $(document).ready(function () {
            $('#delete-btn').on('click', function (e) {
                Swal.fire({
                    title: "Warning!",
                    text: "Are You Sure You Want To Delete This Listing?",
                    icon: 'warning',
                    confirmButtonColor: '#295568',
                    confirmButtonText: 'OK',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if(result.isConfirmed){
                        let id = $(this).data('id');
                        let formData = new FormData();
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('_method', 'delete');
                        $.ajax({
                            url: "{{ route('vendor.listing.delete', ':id') }}".replace(':id', id),
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
                    }
                    else{
                        return false;
                    }
                });
            });
        });
    </script>
    {{-- Delete Listing --}}
@endpush
