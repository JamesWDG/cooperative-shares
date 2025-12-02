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

    #services-table {
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100%;
    }

    #services-table thead th {
        background: #f7f7f7;
        padding: 14px 10px;
        border-bottom: 2px solid #ddd;
    }

    #services-table tbody tr {
        background: #FFFFFF;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    }

    #services-table tbody td {
        padding: 14px 10px;
        vertical-align: middle;
    }

    .service-img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e4e4e4;
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
        <h1 class="dashboard-hd">Services List</h1>
        <a href="{{ route('admin.service.create') }}" class="add-btn">
            <img src="{{ asset('assets/vendor/images/listing-add-btn.png') }}" alt="">
            Add New Service
        </a>
    </div>

    <div class="table-container">
        <table id="services-table">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Title</th>
                    <th>Featured Image</th>
                    <th>Background Image</th>
                    <th>Short Description</th>
                    <th style="width: 110px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $service->title }}</td>

                        <td>
                            @if($service->featured_img)
                                <img src="{{ asset('storage/services/'.$service->featured_img) }}"
                                     class="service-img"
                                     alt="Featured Image">
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if($service->background_img)
                                <img src="{{ asset('storage/services/'.$service->background_img) }}"
                                     class="service-img"
                                     alt="Background Image">
                            @else
                                —
                            @endif
                        </td>

                        <td>{!! Str::limit($service->short_des, 60) !!}</td>

                        <td>
                            <a href="{{ route('admin.service.edit', $service->id) }}"
                               class="btn btn-primary btn-mini text-white">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>

                            <button type="button"
                                    class="btn btn-danger btn-mini delete-service"
                                    data-id="{{ $service->id }}"
                                    data-url="{{ route('admin.service.delete', $service->id) }}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Services Found!</td>
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
        const table = $('#services-table').DataTable({
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

        // Delete Service (AJAX + SweetAlert)
        $(document).on('click', '.delete-service', function () {
            const $btn      = $(this);
            const deleteUrl = $btn.data('url');
            const serviceId = $btn.data('id');
            const row       = $btn.closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "This service will be deleted permanently.",
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
                        service_id: serviceId
                    },
                    success: function (res) {
                        $.LoadingOverlay("hide");

                        if (res.status) {
                            showToastjQuery("Deleted", res.msg || "Service deleted successfully.", "success");

                            table
                                .row(row)
                                .remove()
                                .draw(false);
                        } else {
                            showToastjQuery("Error", res.msg || "Unable to delete service.", "error");
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
