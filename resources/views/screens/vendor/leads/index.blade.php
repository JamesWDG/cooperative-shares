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
        <h1 class="dashboard-hd">Leads</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Email Address</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->phone_number }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->updated_at->format('d-m-Y') }}</td>
                            <td class="status-completed">{{ $lead->status }}</td>
                            <td class="position-relative custom-action-cell">
                                <i class="fa-solid fa-ellipsis-vertical custom-toggle" style="cursor: pointer;"></i>

                                <div class="custom-dropdown-menu" style="display: none;">
                                    <div class="custom-dropdown-item p-0">
                                        @if($lead->appointment)
                                            <a style="padding:7px 15px;" class="text-decoration-none text-light d-block w-100">View</a>
                                        @else
                                            <a href="{{ route('vendor.lead.edit', $lead->id) }}" style="padding:7px 15px;" class="text-decoration-none text-light d-block w-100">View</a>
                                        @endif
                                    </div>
                                    <div class="custom-dropdown-item text-danger" data-id="{{ $lead->id }}" id="delete-lead">Delete</div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="7">No Leads Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($leads->hasPages())
                @php
                    $current = $leads->currentPage();
                    $last = $leads->lastPage();

                    $start = max(1, $current - 1);
                    $end = min($last, $current + 1);

                    if ($start > 1) {
                        $start = 1;
                    }

                    if ($end < $last) {
                        $end = $last;
                    }
                @endphp

                <div class="pagination-wrapper pagination-wrapper-apo">
                    <div class="pag-para">
                        <p>Showing <span>{{ $leads->currentPage() }}</span> of <span>{{ $leads->lastPage() }}</span> Results</p>
                    </div>
                    <div class="pagination-btns">
                        @if($leads->onFirstPage())
                            <a onclick="return false" class="pagination-btns">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @else
                            <a href="{{ $leads->previousPageUrl() }}" class="pagination-btns">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $current)
                                <a onclick="return false" class="pagination-btns active">
                                    {{ $page }}
                                </a>
                            @else
                                <a href="{{ $leads->url($page) }}" class="pagination-btns">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if($leads->hasMorePages())
                            <a href="{{ $leads->nextPageUrl() }}" class="pagination-btns">
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

    {{-- Lead Delete --}}
    <script>
        $(document).ready(function () {
            $('#delete-lead').on('click', function (e) {

               Swal.fire({
                    title: "Warning!",
                    text: "Are You Sure You Want To Delete This Lead?",
                    icon: 'warning',
                    confirmButtonColor: '#295568',
                    confirmButtonText: 'OK',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if(result.isConfirmed){
                        let button = $(this);
                        let id = button.data('id');
                        let formData = new FormData();
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('_method', "delete");
                        $.ajax({
                            url: "{{ route('vendor.lead.delete', ':id') }}".replace(':id', id),
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
                });
            });
        });
    </script>
    {{-- Lead Delete --}}

@endpush
