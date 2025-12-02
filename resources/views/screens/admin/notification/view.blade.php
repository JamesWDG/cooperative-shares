@extends('layouts.admin.app')

@push('styles')
<style>
    .propFormContainer {
        margin-top: 25px;
        background: #fff;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }
    .detail-row {
        margin-bottom: 12px;
    }
    .detail-label {
        font-weight: 600;
        width: 180px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <h2 class="dashboard-hd mb-3">Notification Details</h2>

    <div class="propFormContainer">
        {{-- Title --}}
        <div class="detail-row d-flex">
            <div class="detail-label">Title:</div>
            <div>{{ $notification->title ?? 'No Title Provided' }}</div>
        </div>
        <hr>

        {{-- Content --}}
        <div class="detail-row d-flex">
            <div class="detail-label">Content:</div>
            <div>{{ $notification->content ?? 'No Content Available' }}</div>
        </div>
        <hr>

        {{-- Type --}}
        <div class="detail-row d-flex">
            <div class="detail-label">Type:</div>
            <div>{{ $notification->type ?? '—' }}</div>
        </div>
        <hr>

        {{-- Status --}}
        <div class="detail-row d-flex">
            <div class="detail-label">Status:</div>
            <div>{{ ucfirst($notification->status ?? '—') }}</div>
        </div>
        <hr>

        {{-- Created At --}}
        <div class="detail-row d-flex">
            <div class="detail-label">Created At:</div>
            <div>{{ $notification->created_at?->format('d M Y h:i A') ?? '—' }}</div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@endpush
