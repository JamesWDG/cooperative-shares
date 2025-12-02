@extends('layouts.vendor.app')
@section('title', 'Co-Op')

@push('styles')
<style>
    .coop-table-wrapper {
        margin-top: 15px;
    }

    .coop-table {
        width: 100%;
        border-collapse: collapse;
    }

    .coop-table thead th {
        background: #f7f7f7;
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .coop-table tbody td {
        padding: 9px 12px;
        font-size: 14px;
        border-bottom: 1px solid #f1f1f1;
        vertical-align: middle;
    }

    .coop-table tbody tr:hover {
        background: #fafafa;
    }

    .coop-img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e4e4e4;
    }

    .vendor-coop-toolbar {
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 10px;
    }

    .vendor-coop-toolbar .form-control {
        height: 38px;
        font-size: 14px;
    }

    .vendor-coop-toolbar .btn-add-coop {
        white-space: nowrap;
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
                            <strong>Co-Op</strong>
                        </h5>
                        <div class="text-muted small">
                            Share your Co-Op posts with buyers & other members.
                        </div>
                    </div>

                    {{-- Toolbar --}}
                    <div class="vendor-coop-toolbar mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6 col-12">
                                <label class="form-label mb-1">Search</label>
                                <input type="text"
                                       id="coopSearch"
                                       class="form-control"
                                       placeholder="Search by title or short description">
                            </div>
                            <div class="col-md-6 col-12 text-md-end mt-2 mt-md-0">
                                <a href="{{ route('vendor.blog.create') }}"
                                   class="btn btn-primary btn-sm btn-add-coop">
                                    <i class="fa-solid fa-plus"></i> Add Co-Op
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="coop-table-wrapper table-responsive">
                        <table class="coop-table" id="coopTable">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">Sr.No</th>
                                    <th>Title</th>
                                    <th>Read (min)</th>
                                    <th>Featured Image</th>
                                    <th>Short Description</th>
                                    <th>Created</th>
                                    <th style="width: 130px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blogs as $blog)
                                    @php
                                        $searchString = strtolower(
                                            ($blog->title ?? '') . ' ' .
                                            strip_tags($blog->short_des ?? '')
                                        );
                                    @endphp
                                    <tr data-search="{{ $searchString }}">
                                        <td>{{ $loop->iteration + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>

                                        <td>{{ $blog->title }}</td>

                                        <td>{{ $blog->read_in_minutes ?? '—' }}</td>

                                        <td>
                                            @if($blog->featured_img)
                                                <img src="{{ asset('storage/vendor-blogs/'.$blog->featured_img) }}"
                                                     class="coop-img"
                                                     alt="Featured Image">
                                            @else
                                                —
                                            @endif
                                        </td>

                                        <td>{!! \Illuminate\Support\Str::limit(strip_tags($blog->short_des), 60) !!}</td>

                                        <td>
                                            {{ optional($blog->created_at)->format('M d, Y') }}
                                        </td>

                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('vendor.blog.edit', $blog->id) }}"
                                                   class="btn btn-primary btn-mini text-white">
                                                    <i class="fa-solid fa-pen"></i> Edit
                                                </a>

                                                <button type="button"
                                                        class="btn btn-danger btn-mini js-delete-coop"
                                                        data-url="{{ route('vendor.blog.delete', $blog->id) }}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">
                                            No Co-Op posts found yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (same style idea as invoices) --}}
                    @if($blogs instanceof \Illuminate\Pagination\LengthAwarePaginator && $blogs->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">

                            {{-- Summary --}}
                            <div class="text-muted small">
                                Showing
                                <strong>{{ $blogs->firstItem() }}</strong>
                                to
                                <strong>{{ $blogs->lastItem() }}</strong>
                                of
                                <strong>{{ $blogs->total() }}</strong>
                                Co-Op posts
                            </div>

                            {{-- Basic pagination using default links, but wrapped in Bootstrap container --}}
                            <nav aria-label="Co-Op pagination">
                                {{ $blogs->links() }}
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

    // Simple search filter (no DataTables)
    $('#coopSearch').on('input', function () {
        const searchVal = $(this).val().toLowerCase();

        $('#coopTable tbody tr').each(function () {
            const $tr = $(this);
            const rowSearch = ($tr.data('search') || '').toString();

            const visible = !searchVal || rowSearch.includes(searchVal);
            $tr.toggle(visible);
        });
    });

    // Single delete Co-Op
    $(document).on('click', '.js-delete-coop', function(e){
        e.preventDefault();
        const url = $(this).data('url');
        const $row = $(this).closest('tr');

        Swal.fire({
            title: 'Delete this Co-Op?',
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

                        if (typeof showToastjQuery === 'function') {
                            showToastjQuery("Deleted", resp.msg || "Co-Op deleted.", "success");
                        } else {
                            Swal.fire('Deleted', resp.msg || 'Co-Op deleted.', 'success');
                        }
                    } else {
                        if (typeof showToastjQuery === 'function') {
                            showToastjQuery("Error", resp.msg || "Failed to delete Co-Op.", "error");
                        } else {
                            Swal.fire('Error', resp.msg || 'Failed to delete Co-Op.', 'error');
                        }
                    }
                },
                error: function(xhr){
                    $.LoadingOverlay('hide');
                    const msg = xhr.responseJSON?.msg || 'Failed to delete Co-Op.';
                    if (typeof showToastjQuery === 'function') {
                        showToastjQuery("Error", msg, "error");
                    } else {
                        Swal.fire('Error', msg, 'error');
                    }
                }
            });
        });
    });

})(jQuery);
</script>
@endpush
