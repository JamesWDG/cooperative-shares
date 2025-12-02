@extends('layouts.admin.app')

@push('styles')
    <style>
        .propInput[disabled],
        .propTextarea[disabled] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <div class="propFormContainer">
            <h2 class="view-hd">Lead Appointment Details</h2>
            
            {{-- NO FORM NEEDED NOW --}}
            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Property</label>
                    <input type="text"
                           value="{{ $lead->listing->property_title ?? 'N/A' }}"
                           class="propInput"
                           disabled />
                </div>
            
                <div class="propFormGroup">
                    <label class="propLabel">Property Owner</label>
                    <input type="text"
                           value="{{ $lead->listing->user->first_name.' '.$lead->listing->user->last_name ?? 'N/A' }}"
                           class="propInput"
                           disabled />
                </div>
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Name</label>
                    <input type="text" value="{{ $lead->name }}" class="propInput" disabled />
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Number</label>
                    <input type="text" value="{{ $lead->phone_number }}" class="propInput" disabled />
                </div>
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label class="propLabel">Email</label>
                    <input type="email" value="{{ $lead->email }}" class="propInput" disabled />
                </div>

                <div class="propFormGroup">
                    <label class="propLabel">Appointment Date & Time</label>
                    <input type="text" value="{{ $lead->appointment_date ?? 'Not Assigned' }}"
                           class="propInput" disabled />
                </div>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Message</label>
                <textarea class="propTextarea" disabled>{{ $lead->message }}</textarea>
            </div>

            {{-- Remove Make Appointment Button --}}
            {{-- <button class="add-btn">Make Appointment</button> --}}

        </div>
    </section>
@endsection

{{-- No scripts needed --}}
