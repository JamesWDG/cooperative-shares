@extends('layouts.user.app')
@section('title', 'Appointments')

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

    .badge-pending {
        background: #fff4e5;
        color: #b4690e;
    }

    .badge-confirmed {
        background: #e6f6ec;
        color: #217a3c;
    }

    .badge-cancelled {
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
                            <strong>My Appointments</strong>
                        </h5>
                        <div class="text-muted small">
                            All property visit appointments you have scheduled.
                        </div>
                    </div>

                    {{-- Toolbar --}}
                    <div class="vendor-invoice-toolbar mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6 col-12">
                                <label class="form-label mb-1">Search</label>
                                <input type="text"
                                       id="appointmentSearch"
                                       class="form-control"
                                       placeholder="Search by property, name, email, status">
                            </div>

                            <div class="col-md-3 col-6">
                                <label class="form-label mb-1">Filter Status</label>
                                <select id="appointmentStatusFilter" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="invoice-table-wrapper table-responsive">
                        <table class="invoice-table" id="appointmentTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Property</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Appointment Date</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th style="width:130px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    @php
                                        $lead    = $appointment->lead;
                                        $listing = $lead?->listing;

                                        $propertyTitle = $listing?->property_title ?? 'Listing #' . ($listing->id ?? 'N/A');
                                        $searchText    = strtolower(
                                            ($propertyTitle ?? '') . ' ' .
                                            ($lead->name ?? '') . ' ' .
                                            ($lead->email ?? '') . ' ' .
                                            ($appointment->status ?? '')
                                        );

                                        $status = strtolower($appointment->status ?? 'pending');

                                        $badgeClass = 'badge-status ';
                                        if ($status === 'confirmed') {
                                            $badgeClass .= 'badge-confirmed';
                                        } elseif ($status === 'cancelled') {
                                            $badgeClass .= 'badge-cancelled';
                                        } else {
                                            $badgeClass .= 'badge-pending';
                                        }
                                    @endphp
                                    <tr data-status="{{ $status }}"
                                        data-search="{{ $searchText }}">
                                        <td>{{ $appointment->id }}</td>
                                        <td>{{ $propertyTitle }}</td>
                                        <td>{{ $lead->name ?? '-' }}</td>
                                        <td>{{ $lead->email ?? '-' }}</td>
                                        <td>{{ optional($appointment->appointment_date)->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <span class="{{ $badgeClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td>{{ optional($appointment->created_at)->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if($listing)
                                                    <a href="{{ route('listing.detail', $listing->id) }}"
                                                       class="btn btn-primary">
                                                        View Listing
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-secondary" disabled>
                                                        No Listing
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">
                                            You have no appointments yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($appointments instanceof \Illuminate\Pagination\LengthAwarePaginator && $appointments->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">

                            <div class="text-muted small">
                                Showing
                                <strong>{{ $appointments->firstItem() }}</strong>
                                to
                                <strong>{{ $appointments->lastItem() }}</strong>
                                of
                                <strong>{{ $appointments->total() }}</strong>
                                appointments
                            </div>

                            <div>
                                {{ $appointments->links() }}
                            </div>
                        </div>
                    @endif

                </div> {{-- /profile-card --}}
            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')
<script>
(function($){

    function applyAppointmentFilters() {
        const searchVal   = $('#appointmentSearch').val().toLowerCase();
        const statusFilter = $('#appointmentStatusFilter').val();

        $('#appointmentTable tbody tr').each(function () {
            const $tr       = $(this);
            const rowStatus = ($tr.data('status') || '').toString();
            const rowSearch = ($tr.data('search') || '').toString();

            let visible = true;

            if (statusFilter && rowStatus !== statusFilter) {
                visible = false;
            }

            if (searchVal && !rowSearch.includes(searchVal)) {
                visible = false;
            }

            $tr.toggle(visible);
        });
    }

    $('#appointmentSearch').on('input', applyAppointmentFilters);
    $('#appointmentStatusFilter').on('change', applyAppointmentFilters);

})(jQuery);
</script>
@endpush
