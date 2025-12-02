@extends('layouts.user.app')

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

    #notifications-table {
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100%;
    }

    #notifications-table thead th {
        background: #f7f7f7;
        padding: 14px 10px;
        border-bottom: 2px solid #ddd;
    }

    #notifications-table tbody tr {
        background: #FFFFFF;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    }

    #notifications-table tbody td {
        padding: 14px 10px;
        vertical-align: middle;
    }

    .heading-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .status-badge {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-read {
        background: #e4f6e9;
        color: #2e7d32;
    }

    .status-unread {
        background: #ffe6e6;
        color: #c62828;
    }

    .btn-mini {
        padding: 3px 8px;
        font-size: 12px;
        border-radius: 4px;
        line-height: 1;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <div class="heading-wrapper">
        <h1 class="dashboard-hd">Notifications</h1>

        <button type="button"
                class="btn btn-danger btn-mini"
                id="delete-selected">
            <i class="fa-solid fa-trash"></i> Delete Selected
        </button>
    </div>

    <div class="table-container">
        <table id="notifications-table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th>Sr.No</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th style="width: 120px;">Action</th>
                </tr>
            </thead>

            <tbody>
                 @foreach ($usernotificationslist as $notification)
                    <tr>
                        <td>
                            <input type="checkbox"
                                   class="select-notification"
                                   value="{{ $notification->id }}">
                        </td>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <a href="{{ route('user.notifications.view', $notification->slug) }}">
                                {{ $notification->title }}
                            </a>
                        </td>

                        <td>{{ $notification->type ?? '—' }}</td>

                        <td>
                            @php
                                $statusClass = $notification->status === 'Read'
                                    ? 'status-read'
                                    : 'status-unread';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ ucfirst($notification->status) }}
                            </span>
                        </td>

                        <td>{{ $notification->created_at?->format('d M Y h:i A') }}</td>

                        <td>
                            <a href="{{ route('user.notifications.view', $notification->slug) }}"
                               class="btn btn-primary btn-mini text-white">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                
                    
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('scripts')
@include('includes.admin.form-scripts')

<script>
    $(document).ready(function () {
        const table = $('#notifications-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[1, 'asc']],
        columnDefs: [
            { targets: [0, -1], orderable: false, searchable: false }
        ],
        language: {
            emptyTable: "No Notifications Found!",
            infoEmpty: "",
            info: ""
        },
        drawCallback: function (settings) {
            const api  = this.api();
            const info = api.page.info();

            if (info.recordsTotal === 0) {
                // jab table completely empty ho
                $('#notifications-table_paginate').hide(); // Previous / Next
                $('#notifications-table_length').hide();   // "Show 10 entries"
                $('#notifications-table_filter').hide();   // Search (agar chhupana chaho)
            } else {
                $('#notifications-table_paginate').show();
                $('#notifications-table_length').show();
                $('#notifications-table_filter').show();
            }
        }
    });

        // Select all
        $('#select-all').on('change', function () {
            $('.select-notification').prop('checked', this.checked);
        });

        // Delete selected
        $('#delete-selected').on('click', function () {
            const selectedIds = $('.select-notification:checked')
                .map(function () { return $(this).val(); })
                .get();

            if (!selectedIds.length) {
                showToastjQuery("Error", "Please select at least one notification.", "error");
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Selected notifications will be deleted permanently.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.LoadingOverlay("show");

                $.ajax({
                    url: "{{ route('user.notifications.delete-multiple') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedIds
                    },
                    success: function (res) {
                        $.LoadingOverlay("hide");

                        if (res.status) {
                            showToastjQuery("Success", res.msg || "Notifications deleted.", "success");

                            // Remove rows from DataTable
                            $('.select-notification:checked').each(function () {
                                table
                                    .row($(this).closest('tr'))
                                    .remove()
                                    .draw(false);
                            });
                        } else {
                            showToastjQuery("Error", res.msg || "Unable to delete notifications.", "error");
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
