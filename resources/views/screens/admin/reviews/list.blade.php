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

    #reviews-table {
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100%;
    }

    #reviews-table thead th {
        background: #f7f7f7;
        padding: 14px 10px;
        border-bottom: 2px solid #ddd;
    }

    #reviews-table tbody tr {
        background: #FFFFFF;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    }

    #reviews-table tbody td {
        padding: 14px 10px;
        vertical-align: middle;
    }

    .client-img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e4e4e4;
    }

    .star {
        color: #FFD43B;
        font-size: 16px;
    }

    .btn-mini {
        padding: 3px 8px;
        font-size: 12px;
        border-radius: 4px;
        line-height: 1;
    }

    .btn-mini i {
        font-size: 11px;
        margin-right: 2px;
    }

    .heading-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <div class="heading-wrapper">
        <h1 class="dashboard-hd">Reviews List</h1>
        <a href="{{ route('admin.review.create') }}" class="add-btn">
            <img src="{{ asset('assets/vendor/images/listing-add-btn.png') }}" alt="">
            Add New Review
        </a>
    </div>

    <div class="table-container">
        <table id="reviews-table">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Client</th>
                    <th>Image</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th style="width: 110px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $review->client_name }}</td>

                        <td>
                            <img src="{{ asset('storage/reviews/'.$review->client_image) }}"
                                 class="client-img"
                                 alt="Review Image">
                        </td>

                        <td>{{ Str::limit($review->review_text, 60) }}</td>

                        <td>
                            @for($i = 1; $i <= $review->rating; $i++)
                                <i class="fa-solid fa-star star"></i>
                            @endfor
                        </td>

                        <td>
                            <a href="{{ route('admin.review.edit', $review->id) }}"
                               class="btn btn-primary btn-mini text-white">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>

                            <button type="button"
                                    class="btn btn-danger btn-mini delete-review"
                                    data-id="{{ $review->id }}"
                                    data-url="{{ route('admin.review.delete', $review->id) }}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Reviews Found!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
@push('scripts')
@include('includes.admin.form-scripts')
<script>
    $(document).ready(function () {
        // Init DataTable
        const table = $('#reviews-table').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'asc']],
            columnDefs: [
                {
                    targets: -1,
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Delete Review (AJAX + SweetAlert)
        $(document).on('click', '.delete-review', function () {
            const $btn      = $(this);
            const deleteUrl = $btn.data('url');
            const reviewId  = $btn.data('id');
            const row       = $btn.closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "This review will be deleted permanently.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.LoadingOverlay("show");

                $.ajax({
                    url: deleteUrl,
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE',
                        review_id: reviewId
                    },
                    success: function (res) {
                        $.LoadingOverlay("hide");

                        if (res.status) {
                            showToastjQuery("Deleted", res.msg || "Review deleted successfully.", "success");

                            // Remove row from DataTable
                            table
                                .row(row)
                                .remove()
                                .draw(false);
                        } else {
                            showToastjQuery("Error", res.msg || "Unable to delete review.", "error");
                        }
                    },
                    error: function () {
                        $.LoadingOverlay("hide");
                        showToastjQuery("Error", "Something went wrong!", "error");
                    }
                });
            });
        });
    });
</script>
@endpush
