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

    #ads-calendar-table {
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100%;
    }

    #ads-calendar-table thead th {
        background: #f7f7f7;
        padding: 14px 10px;
        border-bottom: 2px solid #ddd;
        white-space: nowrap;
    }

    #ads-calendar-table tbody tr {
        background: #FFFFFF;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    }

    #ads-calendar-table tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        font-size: 14px;
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

    .badge-month {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eef2ff;
        font-size: 12px;
    }

    .amount-text {
        font-weight: 600;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <div class="heading-wrapper">
        <h1 class="dashboard-hd">Ads Purchased</h1>
        <!--<a href="{{ route('admin.ads-purchased.create') }}" class="add-btn">-->
        <!--    <img src="{{ asset('assets/vendor/images/listing-add-btn.png') }}" alt="">-->
        <!--    Add New Ad Booking-->
        <!--</a>-->
    </div>

    <div class="table-container">
        <table id="ads-calendar-table">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Adv Package</th>
                    <th>Vendor</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Month / Year</th>
                    <th>Amount</th>
                    <th>Transaction ID</th>
                    <th style="width: 110px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($adsPurchased as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ optional($item->advertisement)->package_name ?? '—' }}</td>

                        <td>{{ optional($item->vendor)->first_name.' '.optional($item->vendor)->last_name ?? '—' }}</td>

                        <td>{{ \Carbon\Carbon::parse($item->from_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->to_date)->format('d M Y') }}</td>

                        <td>
                            <span class="badge-month">
                                {{ \Carbon\Carbon::create($item->year, $item->month, 1)->format('M Y') }}
                            </span>
                        </td>

                        <td class="amount-text">
                            ${{ number_format($item->amount, 2) }}
                        </td>

                        <td>{{ $item->tran_id ?? '—' }}</td>

                        <td>
                            <a href="{{ route('admin.ads-purchased.edit', $item->id) }}"
                               class="btn btn-primary btn-mini text-white">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>

                            <button type="button"
                                    class="btn btn-danger btn-mini delete-ads-purchase"
                                    data-id="{{ $item->id }}"
                                    data-url="{{ route('admin.ads-purchased.delete', $item->id) }}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No Ad Purchases Found!</td>
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
        const table = $('#ads-calendar-table').DataTable({
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

        // Delete Ads Purchase (AJAX + SweetAlert)
        $(document).on('click', '.delete-ads-purchase', function () {
            const $btn      = $(this);
            const deleteUrl = $btn.data('url');
            const row       = $btn.closest('tr');
            const id        = $btn.data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This booking will be deleted permanently.",
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
                        id: id
                    },
                    success: function (res) {
                        $.LoadingOverlay("hide");

                        if (res.status) {
                            showToastjQuery("Deleted", res.msg || "Booking deleted successfully.", "success");

                            table
                                .row(row)
                                .remove()
                                .draw(false);
                        } else {
                            showToastjQuery("Error", res.msg || "Unable to delete booking.", "error");
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
