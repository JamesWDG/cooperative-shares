@extends('layouts.vendor.app')
@section('title', 'Invoices')

@push('styles')
<style>
    .invoice-table-wrapper {
        margin-top: 15px;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-table thead th {
        background: #f7f7f7;
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .invoice-table tbody td {
        padding: 9px 12px;
        font-size: 14px;
        border-bottom: 1px solid #f1f1f1;
        vertical-align: middle;
    }

    .invoice-table tbody tr:hover {
        background: #fafafa;
    }

    .badge-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-paid {
        background: #e6f6ec;
        color: #217a3c;
    }

    .badge-pending {
        background: #fff4e5;
        color: #b4690e;
    }

    .badge-failed,
    .badge-refunded {
        background: #fdecea;
        color: #b42318;
    }

    .vendor-invoice-toolbar {
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 10px;
    }

    .vendor-invoice-toolbar .form-control,
    .vendor-invoice-toolbar .form-select {
        height: 38px;
        font-size: 14px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    <div class="profile-info-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="profile-card shadow-sm p-3">

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="Pro-info-hd mb-0">
                            <strong>Invoices</strong>
                        </h5>
                        <div class="text-muted small">
                            All payments for Subscription, Featured Listings, and Marketing Plans.
                        </div>
                    </div>

                    {{-- Toolbar --}}
                    <div class="vendor-invoice-toolbar mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4 col-12">
                                <label class="form-label mb-1">Search</label>
                                <input type="text"
                                       id="invoiceSearch"
                                       class="form-control"
                                       placeholder="Search by invoice #, type, or status">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label mb-1">Filter Type</label>
                                <select id="invoiceTypeFilter" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="subscription">Subscription</option>
                                    <option value="featured_listing">Featured Listing</option>
                                    <option value="marketing_plan">Marketing Plan</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label mb-1">Filter Status</label>
                                <select id="invoiceStatusFilter" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-12 text-md-end mt-2 mt-md-0">
                                <button id="bulkDeleteBtn"
                                        type="button"
                                        class="btn btn-danger btn-sm d-none">
                                    Delete Selected
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="invoice-table-wrapper table-responsive">
                        <table class="invoice-table" id="invoiceTable">
                            <thead>
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="selectAllInvoices">
                                    </th>
                                    <th>Invoice #</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Issued</th>
                                    <th>Paid</th>
                                    <th style="width:130px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                    @php
                                        $typeLabel = match($invoice->type) {
                                            'subscription'      => 'Subscription',
                                            'featured_listing'  => 'Featured Listing',
                                            'marketing_plan'    => 'Marketing Plan',
                                            default             => ucfirst(str_replace('_', ' ', $invoice->type)),
                                        };

                                        $badgeClass = 'badge-status';
                                        if ($invoice->status === 'paid') {
                                            $badgeClass .= ' badge-paid';
                                        } elseif ($invoice->status === 'pending') {
                                            $badgeClass .= ' badge-pending';
                                        } else {
                                            $badgeClass .= ' badge-failed';
                                        }
                                    @endphp
                                    <tr data-id="{{ $invoice->id }}"
                                        data-type="{{ $invoice->type }}"
                                        data-status="{{ $invoice->status }}"
                                        data-search="{{ strtolower($invoice->invoice_number . ' ' . $typeLabel . ' ' . $invoice->status) }}">
                                        <td>
                                            <input type="checkbox" class="row-checkbox">
                                        </td>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $typeLabel }}</td>
                                        <td>
                                            {{ number_format($invoice->amount, 2) }}
                                            {{ $invoice->currency ?? 'USD' }}
                                        </td>
                                        <td>
                                            <span class="{{ $badgeClass }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td>{{ optional($invoice->issued_at)->format('M d, Y') }}</td>
                                        <td>{{ optional($invoice->paid_at)->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('vendor.invoice.view', $invoice->id) }}"
                                                   class="btn btn-primary">
                                                    View
                                                </a>
                                                <button type="button"
                                                        class="btn btn-danger js-delete-single"
                                                        data-url="{{ route('vendor.invoice.delete', $invoice->id) }}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">
                                            No invoices found yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                      @if($invoices instanceof \Illuminate\Pagination\LengthAwarePaginator && $invoices->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">

        {{-- Summary --}}
        <div class="text-muted small">
            Showing 
            <strong>{{ $invoices->firstItem() }}</strong>
            to
            <strong>{{ $invoices->lastItem() }}</strong>
            of
            <strong>{{ $invoices->total() }}</strong>
            invoices
        </div>

        {{-- Proper Bootstrap 5 pagination --}}
        <nav aria-label="Invoice pagination">
            <ul class="pagination pagination-sm mb-0">

                {{-- Previous button --}}
                <li class="page-item {{ $invoices->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $invoices->previousPageUrl() }}" tabindex="-1">
                        &laquo; Prev
                    </a>
                </li>

                {{-- Page numbers --}}
                @foreach ($invoices->links()->elements[0] ?? [] as $page => $url)
                    <li class="page-item {{ $page == $invoices->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Next button --}}
                <li class="page-item {{ !$invoices->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $invoices->nextPageUrl() }}">
                        Next &raquo;
                    </a>
                </li>

            </ul>
        </nav>

    </div>
@endif
                    
                </div> {{-- /profile-card --}}
            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function($){

    const csrfToken = "{{ csrf_token() }}";
    const bulkDeleteUrl = "{{ route('vendor.invoice.delete-multiple') }}";

    function refreshBulkState() {
        const $rows = $('#invoiceTable tbody tr');
        const $checked = $rows.find('.row-checkbox:checked');

        $('#bulkDeleteBtn').toggleClass('d-none', $checked.length === 0);

        const allVisibleChecked = $rows.length > 0 && $checked.length === $rows.length;
        $('#selectAllInvoices').prop('checked', allVisibleChecked);
    }

    function applyFilters() {
        const searchVal = $('#invoiceSearch').val().toLowerCase();
        const typeFilter = $('#invoiceTypeFilter').val();
        const statusFilter = $('#invoiceStatusFilter').val();

        $('#invoiceTable tbody tr').each(function(){
            const $tr = $(this);
            const rowType = $tr.data('type');
            const rowStatus = $tr.data('status');
            const rowSearch = ($tr.data('search') || '').toString();

            let visible = true;

            if (typeFilter && rowType !== typeFilter) {
                visible = false;
            }

            if (statusFilter && rowStatus !== statusFilter) {
                visible = false;
            }

            if (searchVal && !rowSearch.includes(searchVal)) {
                visible = false;
            }

            $tr.toggle(visible);
        });

        refreshBulkState();
    }

    $('#invoiceSearch').on('input', applyFilters);
    $('#invoiceTypeFilter').on('change', applyFilters);
    $('#invoiceStatusFilter').on('change', applyFilters);

    $('#selectAllInvoices').on('change', function(){
        const checked = $(this).is(':checked');
        $('#invoiceTable tbody tr:visible .row-checkbox').prop('checked', checked);
        refreshBulkState();
    });

    $(document).on('change', '.row-checkbox', function(){
        refreshBulkState();
    });

    // Single delete
    $(document).on('click', '.js-delete-single', function(e){
        e.preventDefault();
        const url = $(this).data('url');
        const $row = $(this).closest('tr');

        Swal.fire({
            title: 'Delete this invoice?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b42318',
            confirmButtonText: 'Yes, delete',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.LoadingOverlay('show');
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: csrfToken
                },
                success: function(resp){
                    $.LoadingOverlay('hide');
                    if (resp.status) {
                        $row.remove();
                        refreshBulkState();
                        Swal.fire('Deleted', resp.msg || 'Invoice deleted.', 'success');
                    } else {
                        Swal.fire('Error', resp.msg || 'Failed to delete invoice.', 'error');
                    }
                },
                error: function(xhr){
                    $.LoadingOverlay('hide');
                    Swal.fire('Error', xhr.responseJSON?.msg || 'Failed to delete invoice.', 'error');
                }
            });
        });
    });

    // Bulk delete
    $('#bulkDeleteBtn').on('click', function(){
        const ids = [];
        $('#invoiceTable tbody tr').each(function(){
            const $tr = $(this);
            if ($tr.find('.row-checkbox').is(':checked')) {
                ids.push($tr.data('id'));
            }
        });

        if (!ids.length) return;

        Swal.fire({
            title: `Delete ${ids.length} selected invoice(s)?`,
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b42318',
            confirmButtonText: 'Yes, delete',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.LoadingOverlay('show');
            $.ajax({
                url: bulkDeleteUrl,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    ids: ids
                },
                success: function(resp){
                    $.LoadingOverlay('hide');
                    if (resp.status) {
                        $('#invoiceTable tbody tr').each(function(){
                            const $tr = $(this);
                            if (ids.includes($tr.data('id'))) {
                                $tr.remove();
                            }
                        });
                        refreshBulkState();
                        Swal.fire('Deleted', resp.msg || 'Selected invoices deleted.', 'success');
                    } else {
                        Swal.fire('Error', resp.msg || 'Failed to delete selected invoices.', 'error');
                    }
                },
                error: function(xhr){
                    $.LoadingOverlay('hide');
                    Swal.fire('Error', xhr.responseJSON?.msg || 'Failed to delete selected invoices.', 'error');
                }
            });
        });
    });

})(jQuery);
</script>
@endpush
