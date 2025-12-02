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

        #contacts-table {
            border-collapse: separate;
            border-spacing: 0 8px;
            width: 100%;
        }

        #contacts-table thead th {
            background: #f7f7f7;
            padding: 14px 10px;
            border-bottom: 2px solid #ddd;
        }

        #contacts-table tbody tr {
            background: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.07);
        }

        #contacts-table tbody td {
            padding: 14px 10px;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush


@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Contact Form Submissions</h1>

        <div class="table-container">
            <table id="contacts-table">
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Service</th>
                        <th>Submitted At</th>
                        <th style="width: 90px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($contacts as $contact)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $contact->full_name }}</td>

                            <td>{{ $contact->email }}</td>

                            <td>{{ $contact->phone_number ?? 'N/A' }}</td>

                            <td>{{ $contact->service ?? 'N/A' }}</td>

                            <td>{{ $contact->created_at?->format('d-m-Y') ?? 'N/A' }}</td>

                            <td>
                                <a href="{{ route('admin.contact.detail', $contact->id) }}"
                                    class="btn btn-primary btn-sm text-white"
                                    style="padding:5px 12px; border-radius:6px;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No Contact Forms Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection



@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#contacts-table').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[5, 'desc']], // Submitted At column
            columnDefs: [
                {
                    targets: -1, // Action column
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush
