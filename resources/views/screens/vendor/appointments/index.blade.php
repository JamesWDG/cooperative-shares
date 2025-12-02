@extends('layouts.vendor.app')

@push('styles')
    <style>
        .status-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;

            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 8px 36px 8px 12px;
            font-family: "Roboto", sans-serif;
            color: #333;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);

            /* custom arrow */
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23666' height='18' viewBox='0 0 24 24' width='18' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        .status-select:hover {
            border-color: #bcbcbc;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .status-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        .status-select option {
            background-color: #fff;
            color: #333;
            font-family: "Roboto", sans-serif;
        }

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
            display: flex;
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
        <h1 class="dashboard-hd">Appointments</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Email Address</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->lead->name }}</td>
                            <td>{{ $appointment->lead->phone_number }}</td>
                            <td>{{ $appointment->lead->email }}</td>
                            <td>{{ $appointment->appointment_date->format('d M, l g:i A') }}</td>
                            <td class="status-completed">
                                <select name="status" id="status" class="status-select" data-id="{{ $appointment->id }}">
                                    <option value="Pending" {{ ($appointment->status === 'Pending') ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Completed" {{ ($appointment->status === 'Completed') ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="Cancelled" {{ ($appointment->status === 'Cancelled') ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </td>
                            <td class="position-relative custom-action-cell">
                                <i class="fa-solid fa-ellipsis-vertical custom-toggle" style="cursor: pointer;"></i>

                                <div class="custom-dropdown-menu" style="display: none;">
                                    {{-- <div class="custom-dropdown-item p-0">
                                        <a href="#" style="padding:7px 15px;"
                                            class="text-decoration-none text-light d-block w-100">View</a>
                                    </div> --}}
                                    <div class="custom-dropdown-item text-danger" id="delete-appointment"
                                        data-id="{{ $appointment->id }}">Delete</div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Appointments Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($appointments->hasPages())
                @php
                    $current = $appointments->currentPage();
                    $last = $appointments->lastPage();

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
                        <p>Showing <span>{{ $appointments->currentPage() }}</span> of
                            <span>{{ $appointments->lastPage() }}</span> Results
                        </p>
                    </div>
                    <div class="pagination-btns">
                        @if($appointments->onFirstPage())
                            <a onclick="return false" class="pagination-btns">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @else
                            <a href="{{ $appointments->previousPageUrl() }}" class="pagination-btns">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $current)
                                <a onclick="return false" class="pagination-btns active">
                                    {{ $page }}
                                </a>
                            @else
                                <a href="{{ $appointments->url($page) }}" class="pagination-btns">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if($appointments->hasMorePages())
                            <a href="{{ $appointments->nextPageUrl() }}" class="pagination-btns">
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

    {{-- Appointment Delete --}}
    <script>
        $(document).ready(function () {
            $('#delete-appointment').on('click', function (e) {
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
                    if (result.isConfirmed) {
                        let button = $(this);
                        let id = button.data('id');
                        let formData = new FormData();
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('_method', "delete");
                        $.ajax({
                            url: "{{ route('vendor.appointment.delete', ':id') }}".replace(':id', id),
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
    {{-- Appointment Delete --}}

    {{-- Appointment Delete --}}
    <script>
        $(document).ready(function () {
            $('#status').on('change', function (e) {
                let status = $(this).val();
                let id = $(this).data('id');
                Swal.fire({
                    title: "Warning!",
                    text: `Are You Sure You Want To Change Appointment Status To (${status})?`,
                    icon: 'warning',
                    confirmButtonColor: '#295568',
                    confirmButtonText: 'OK',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('_method', "PUT");
                        formData.append('status', status);
                        $.ajax({
                            url: "{{ route('vendor.appointment.update.status', ':id') }}".replace(':id', id),
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
    {{-- Appointment Delete --}}

@endpush
