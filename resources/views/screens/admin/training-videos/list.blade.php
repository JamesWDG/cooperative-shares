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

    #videos-table {
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100%;
    }

    #videos-table thead th {
        background: #f7f7f7;
        padding: 14px 10px;
        border-bottom: 2px solid #ddd;
    }

    #videos-table tbody tr {
        background: #FFFFFF;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    }

    #videos-table tbody td {
        padding: 14px 10px;
        vertical-align: middle;
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
        <h1 class="dashboard-hd">Training Videos</h1>
        <a href="{{ route('admin.training-video.create') }}" class="add-btn">
            <img src="{{ asset('assets/vendor/images/listing-add-btn.png') }}" alt="">
            Add New Video
        </a>
    </div>

    <div class="table-container">
        <table id="videos-table">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Name</th>
                    <th>Video</th>
                    <th style="width: 110px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($videos as $video)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $video->name }}</td>
                        <td><a href="{{ asset('storage/training-videos/'.$video->video) }}" target="_blank">View Video</a></td>

                        <td>
                            <a href="{{ route('admin.training-video.edit', $video->id) }}"
                               class="btn btn-primary btn-mini text-white">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>

                            <button type="button"
                                    class="btn btn-danger btn-mini delete-video"
                                    data-id="{{ $video->id }}"
                                    data-url="{{ route('admin.training-video.delete', $video->id) }}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No Training Videos Found!</td>
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
        const table = $('#videos-table').DataTable({
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

        // Delete Video (AJAX + SweetAlert)
        $(document).on('click', '.delete-video', function () {
            const $btn      = $(this);
            const deleteUrl = $btn.data('url');
            const videoId   = $btn.data('id');
            const row       = $btn.closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "This video will be deleted permanently.",
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
                        video_id: videoId
                    },
                    success: function (res) {
                        $.LoadingOverlay("hide");

                        if (res.status) {
                            showToastjQuery("Deleted", res.msg || "Video deleted successfully.", "success");

                            table
                                .row(row)
                                .remove()
                                .draw(false);
                        } else {
                            showToastjQuery("Error", res.msg || "Unable to delete video.", "error");
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
