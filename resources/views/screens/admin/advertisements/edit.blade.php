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

    .propFormGroup {
        margin-bottom: 18px;
    }

    .propLabel {
        display: block;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .propInput {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 8px 10px;
        font-size: 14px;
    }

    .propInput[disabled] {
        background-color: #f5f5f5;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <h2 class="dashboard-hd">Edit Advertisement</h2>

    <div class="propFormContainer">
        <form action="{{ route('admin.advertisement.update', $ad->id) }}"
              method="POST"
              class="form-submit">
            @csrf
            @method('PUT')

            <div class="propFormGroup">
                <label class="propLabel">Package Name</label>
                <input type="text" name="package_name"
                       class="propInput" value="{{ $ad->package_name }}" disabled>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Package Duration (months)</label>
                <input type="text" name="package_duration"
                       class="propInput" value="{{ $ad->package_duration }}" disabled>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Amount ($) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="amount"
                       class="propInput" value="{{ $ad->amount }}" required>
            </div>

            <button type="submit"
                    class="btn btn-primary update-btn"
                    data-original-text="Update Advertisement">
                Update Advertisement
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
@include('includes.admin.form-scripts')
@endpush
