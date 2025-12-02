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
</style>
@endpush

@section('section')
<section class="main-content-area">
    <h2 class="dashboard-hd">Add New Advertisement</h2>

    <div class="propFormContainer">
        <form action="{{ route('admin.advertisement.store') }}"
              method="POST"
              class="form-submit">
            @csrf

            <div class="propFormGroup">
                <label class="propLabel">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="propInput">
                <small class="text-muted">Example: Header, Home Main, Home Mid, Home Footer, About Main, About Mid, About Footer etc.</small>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Price per Month ($) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="price_per_month" class="propInput">
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Save Advertisement">
                Save Advertisement
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
@include('includes.admin.form-scripts',[
    'redirect_url' => route('admin.advertisements')
])
@endpush
