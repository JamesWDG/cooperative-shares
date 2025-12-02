@extends('layouts.admin.app')

@push('styles')
    @include('includes.admin.cms.list-style')
@endpush

@section('section')
<section class="main-content-area">

    {{-- HEADING --}}
    @include('includes.admin.cms.list-heading', [
        'title' => $pageTitle??null,
        'subtitle' => $subtitle??null,
    ])

    {{-- TABLE --}}
    <div class="table-container">
        <table id="listing-table">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Section Name</th>
                    <th style="width: 120px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($sections as $index => $record)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $record['name'] }}</td>
                        <td>
                            <a href="{{ route($routeName, $record['type']) }}"
                               target="_blank"
                               class="btn btn-primary btn-sm text-white btn-edit-section">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No Sections Found!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>
@endsection

@push('scripts')
    @include('includes.admin.cms.list-script', [
        'tableId' => 'listing-table',
        'pageLength' => 10,
        'lengthMenu' => [10, 25, 50, 100],
        'order' => [[0, 'asc']],
        'columnDefs' => [
            ['targets' => -1, 'orderable' => false, 'searchable' => false],
        ],
    ])
@endpush
