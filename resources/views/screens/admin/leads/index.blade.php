@extends('layouts.admin.app')

@push('styles')
    <style>

        /* MAIN SPACING IMPROVEMENTS */
        .table-container {
            margin-top: 25px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }

        /* Add spacing above & below the DataTable search/pagination areas */
        div.dataTables_wrapper {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        /* Search Box Styling */
        .dataTables_filter {
            margin-bottom: 15px !important;
        }
        .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #CCD2E0;
            padding: 6px 10px;
            font-size: 14px;
            outline: none;
        }

        /* Length dropdown */
        .dataTables_length select {
            border-radius: 6px;
            border: 1px solid #CCD2E0;
            padding: 4px 8px;
            font-size: 14px;
            outline: none;
        }

        /* Table Styling */
        #leads-table {
            border-collapse: separate;
            border-spacing: 0 8px; /* Row gap */
            width: 100%;
        }

        #leads-table thead th {
            background: #f7f7f7;
            padding: 14px 10px;
            border-bottom: 2px solid #ddd;
        }

        #leads-table tbody tr {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        #leads-table tbody td {
            padding: 14px 10px;
            border: none !important;
        }

        /* Pagination spacing */
        .dataTables_paginate {
            margin-top: 15px !important;
        }

        /* Pagination buttons better look */
        .dataTables_paginate .paginate_button {
            padding: 6px 12px !important;
            border-radius: 6px !important;
            margin: 0 3px !important;
            border: 1px solid #e2e2e2 !important;
            background: #f8f8f8 !important;
            color: #333 !important;
            font-size: 13px;
        }

        .dataTables_paginate .paginate_button.current {
            background: var(--blue) !important;
            color: #fff !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: var(--blue) !important;
            color: #fff !important;
            cursor: pointer;
        }

    </style>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush


@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Leads</h1>
        <div class="table-container">
            <table id="leads-table">
                <table id="leads-table">
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Email Address</th>
                        <th>Property</th>        {{-- NEW --}}
                        <th>Property Owner</th>  {{-- NEW --}}
                        <th>Date</th>
                        <th>Status</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>

                <tbody>
    @forelse ($leads as $lead)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $lead->name }}</td>
            <td>{{ $lead->phone_number }}</td>
            <td>{{ $lead->email }}</td>

            {{-- NEW: Property Title --}}
            <td>
                {{ optional($lead->listing)->property_title ?? 'N/A' }}
            </td>

            {{-- NEW: Property Owner Name --}}
            <td>
                {{ optional(optional($lead->listing)->user)->first_name.' '.optional(optional($lead->listing)->user)->last_name ?? 'N/A' }}
            </td>

            <td>{{ $lead->updated_at?->format('d-m-Y') }}</td>
            <td class="status-completed">{{ $lead->status }}</td>
            <td class="custom-action-cell">
                <a href="{{ route('admin.lead.detail', $lead->id) }}" 
                   class="btn btn-primary btn-sm text-white"
                   style="padding:6px 14px; border-radius:6px;">
                    View
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td class="text-center" colspan="9">No Leads Found!</td>
        </tr>
    @endforelse
</tbody>

            </table>

            {{-- Laravel pagination hata do jab DataTables use karein --}}
            {{-- @if($leads->hasPages()) ... @endif --}}
        </div>
    </section>
@endsection

@push('scripts')
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#leads-table').DataTable({
                // Optional customizations:
                pageLength: 10,          // default rows per page
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[4, 'desc']],    // default sort by Date (5th column, index 4)
                columnDefs: [
                    {
                        targets: -1,     // last column (Action)
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
