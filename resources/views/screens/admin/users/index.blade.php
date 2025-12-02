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

        #users-table {
            border-collapse: separate;
            border-spacing: 0 8px;
            width: 100%;
        }

        #users-table thead th {
            background: #f7f7f7;
            padding: 14px 10px;
            border-bottom: 2px solid #ddd;
        }

        #users-table tbody tr {
            background: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.07);
        }

        #users-table tbody td {
            padding: 14px 10px;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush


@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Users List</h1>

        <div class="table-container">
            <table id="users-table">
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>User Type</th>
                        <th>Member Since</th>
                        <th style="width: 90px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $user->full_name ?? 'N/A' }}</td>

                            <td>{{ $user->email }}</td>

                            <td>{{ $user->phone_number ?? 'N/A' }}</td>

                            <td>
                                @if($user->isVendor())
                                    <span class="badge bg-warning">Vendor</span>
                                @elseif($user->isUser())
                                    <span class="badge bg-info">User</span>
                                @else
                                    <span class="badge bg-secondary">Other</span>
                                @endif
                            </td>

                            <td>{{ $user->created_at->format('d-m-Y') }}</td>

                            <td>
                                <a href="{{ route('admin.user.detail', $user->id) }}" 
                                    class="btn btn-primary btn-sm text-white"
                                    style="padding:5px 12px; border-radius:6px;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No Users Found!</td>
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
        $('#users-table').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[5, 'desc']], // Member Since
            columnDefs: [
                {
                    targets: -1, // Action
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush
