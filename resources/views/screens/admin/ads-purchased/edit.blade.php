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

    .propInput,
    .propSelect {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #CCD2E0;
        padding: 8px 10px;
        font-size: 14px;
    }

    .propSmall {
        font-size: 12px;
        color: #777;
    }

    .two-cols {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <h2 class="dashboard-hd">Edit Ad Booking</h2>

    <div class="propFormContainer">
        <form action="{{ route('admin.ads-purchased.update', $adsPurchase->id) }}"
              method="POST"
              class="form-submit">
            @csrf
            @method('PUT')

            <div class="propFormGroup">
                <label class="propLabel">Adv Package <span class="text-danger">*</span></label>
                <select name="add_id" class="propSelect" required>
                    <option value="">Select Adv Package</option>
                    @foreach($advertisements as $ad)
                        <option value="{{ $ad->id }}"
                            {{ $adsPurchase->add_id == $ad->id ? 'selected' : '' }}>
                            {{ $ad->package_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Vendor <span class="text-danger">*</span></label>
                <select name="user_id" class="propSelect" required>
                    <option value="">Select Vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}"
                            {{ $adsPurchase->user_id == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->first_name.' '.$vendor->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="two-cols">
                <div class="propFormGroup">
                    <label class="propLabel">From Date <span class="text-danger">*</span></label>
                    <input type="date" name="from_date" class="propInput"
                           value="{{ $adsPurchase->from_date }}" required>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">To Date <span class="text-danger">*</span></label>
                    <input type="date" name="to_date" class="propInput"
                           value="{{ $adsPurchase->to_date }}" required>
                </div>
            </div>

            <div class="two-cols">
                <div class="propFormGroup">
                    <label class="propLabel">Month <span class="text-danger">*</span></label>
                    <select name="month" class="propSelect" required>
                        <option value="">Select Month</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}"
                                {{ $adsPurchase->month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2000, $m, 1)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Year <span class="text-danger">*</span></label>
                    <select name="year" class="propSelect" required>
                        <option value="">Select Year</option>
                        @for($y = 2025; $y <= 2050; $y++)
                            <option value="{{ $y }}" {{ $adsPurchase->year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

            </div>

            <div class="two-cols">
                <div class="propFormGroup">
                    <label class="propLabel">Amount ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="amount"
                           class="propInput" value="{{ $adsPurchase->amount }}" required>
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Transaction ID</label>
                    <input type="text" name="tran_id" class="propInput"
                           value="{{ $adsPurchase->tran_id }}" placeholder="Optional transaction reference">
                </div>
            </div>

            <button type="button"
                    class="btn btn-primary update-btn"
                    data-original-text="Update Booking">
                Update Booking
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
@include('includes.admin.form-scripts')
@endpush
