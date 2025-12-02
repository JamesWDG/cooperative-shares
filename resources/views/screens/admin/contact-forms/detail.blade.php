@extends('layouts.admin.app')

@push('styles')
<style>
    .propInput[disabled],
    .propTextarea[disabled],
    .propSelect[disabled] {
        background-color: #f5f5f5 !important;
        cursor: not-allowed;
    }

    .propFormContainer {
        background: #ffffff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        margin-top: 20px;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    <div class="propFormContainer">
        <h2 class="view-hd">Contact Form Details</h2>

        {{-- BASIC INFO --}}
        <div class="prop-wrapper">
            <div class="propFormGroup">
                <label class="propLabel">Full Name</label>
                <input type="text" value="{{ $contact->full_name }}" class="propInput" disabled>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Email Address</label>
                <input type="email" value="{{ $contact->email }}" class="propInput" disabled>
            </div>
        </div>

        {{-- PHONE & SERVICE --}}
        <div class="prop-wrapper">
            <div class="propFormGroup">
                <label class="propLabel">Phone Number</label>
                <input type="text" value="{{ $contact->phone_number ?? 'N/A' }}" class="propInput" disabled>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Selected Service</label>
                <input type="text" value="{{ $contact->service ?? 'N/A' }}" class="propInput" disabled>
            </div>
        </div>

        {{-- SUBMITTED AT --}}
        <div class="propFormGroup">
            <label class="propLabel">Submitted At</label>
            <input type="text"
                   value="{{ $contact->created_at?->format('d-m-Y H:i') ?? 'N/A' }}"
                   class="propInput"
                   disabled>
        </div>

        {{-- MESSAGE --}}
        <div class="propFormGroup">
            <label class="propLabel">Message</label>
            <textarea class="propTextarea" disabled>{{ $contact->message ?? 'N/A' }}</textarea>
        </div>

    </div>

</section>
@endsection
