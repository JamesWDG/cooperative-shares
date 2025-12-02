@extends('layouts.admin.app')

@push('styles')
    <style>
        .table-container {
            margin-top: 25px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }

        div.dataTables_wrapper {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #CCD2E0;
            padding: 6px 10px;
            font-size: 14px;
        }

        #listings-table {
            border-collapse: separate;
            border-spacing: 0 8px;
            width: 100%;
        }

        #listings-table thead th {
            background: #f7f7f7;
            padding: 14px 10px;
            border-bottom: 2px solid #ddd;
        }

        #listings-table tbody tr {
            background: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.07);
        }

        #listings-table tbody td {
            padding: 14px 10px;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Listings</h1>

        <div class="table-container">
            <table id="listings-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Property Name</th>
                        <th>Vendor Name</th>
                        <th>Property Type</th>
                        <th>Square Ft</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($listings as $listing)
                        <tr>
                            <td>{{ $listing->id }}</td>
                            <td><img src="{{ asset('storage/listing/images/' . $listing->main_image) }}" alt="{{ $listing->property_title }}" width="100" /></td>
                            <td>{{ $listing->property_title }}</td>
                            <td>{{ $listing->user->full_name }}</td> <!-- Assuming Vendor Name is the 'full_name' of the user -->
                            <td>{{ $listing->category }}</td>
                            <td>{{ $listing->size_in_ft }} sqft</td>
                            <td>@moneyFormat($listing->price)</td>
                            <td>
                                <a href="{{ route('admin.listing.detail', $listing->id) }}" class="btn btn-primary btn-sm text-white" style="padding:5px 12px; border-radius:6px;">View</a>
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm text-white delete-listing" data-id="{{ $listing->id }}" data-url="{{ route('admin.listing.delete', $listing->id) }}" style="padding:5px 12px; border-radius:6px;">Delete</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No Listings Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#listings-table').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'asc']], // ID column
            columnDefs: [
                {
                    targets: -1, // Action column
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Delete listing using SweetAlert and AJAX
        $('.delete-listing').on('click', function () {
            const $btn = $(this);
            const listingId = $btn.data('id');
            const deleteUrl = $btn.data('url');

            // Show confirmation modal using SweetAlert
            Swal.fire({
                title: 'Are you sure?',
                text: "This listing will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.LoadingOverlay("show"); // Show loading overlay

                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            $.LoadingOverlay("hide"); // Hide loading overlay

                            if (response.status) {
                                Swal.fire('Deleted!', response.message, 'success');
                                // Remove the listing row from the table
                                $btn.closest('tr').remove();
                            } else {
                                Swal.fire('Error!', response.message || 'Something went wrong!', 'error');
                            }
                        },
                        error: function () {
                            $.LoadingOverlay("hide"); // Hide loading overlay
                            Swal.fire('Error!', 'Something went wrong!', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
