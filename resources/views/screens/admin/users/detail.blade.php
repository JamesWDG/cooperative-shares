@extends('layouts.admin.app')

@push('styles')
<style>
    .propInput[disabled],
    .propTextarea[disabled],
    .propSelect[disabled] {
        background-color: #f5f5f5 !important;
        cursor: not-allowed;
    }

    .profile-img-box {
        text-align: center;
        margin-bottom: 25px;
    }

    .profile-img-box img {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border-radius: 10px;
        border: 3px solid #e4e4e4;
    }
</style>
@endpush

@section('section')
<section class="main-content-area">

    <div class="propFormContainer">
        <h2 class="view-hd">User Details</h2>

        {{-- PROFILE IMAGE --}}
        <div class="profile-img-box">
            <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/dummy_avatar/avatar.jpg') }}"
                 alt="Profile Image">
        </div>

        {{-- BASIC INFO --}}
        <div class="prop-wrapper">
            <div class="propFormGroup">
                <label class="propLabel">First Name</label>
                <input type="text" value="{{ $user->first_name }}" class="propInput" disabled>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Last Name</label>
                <input type="text" value="{{ $user->last_name }}" class="propInput" disabled>
            </div>
        </div>

        {{-- EMAIL & PHONE --}}
        <div class="prop-wrapper">
            <div class="propFormGroup">
                <label class="propLabel">Email Address</label>
                <input type="email" value="{{ $user->email }}" class="propInput" disabled>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Phone Number</label>
                <input type="text" value="{{ $user->phone_number ?? 'N/A' }}" class="propInput" disabled>
            </div>
        </div>

        {{-- ROLE & MEMBER SINCE --}}
        <div class="prop-wrapper">
            <div class="propFormGroup">
                <label class="propLabel">User Type</label>
                <input type="text"
                       value="{{ ucfirst($user->role) }}"
                       class="propInput"
                       disabled>
            </div>

            <div class="propFormGroup">
                <label class="propLabel">Member Since</label>
                <input type="text"
                       value="{{ $user->created_at->format('d-m-Y') }}"
                       class="propInput"
                       disabled>
            </div>
        </div>

        {{-- ADDRESS --}}
        <div class="propFormGroup">
            <label class="propLabel">Address</label>
            <input type="text" value="{{ $user->address ?? 'N/A' }}" class="propInput" disabled>
        </div>

        {{-- DESCRIPTION --}}
        <div class="propFormGroup">
            <label class="propLabel">Description</label>
            <textarea class="propTextarea" disabled>{{ $user->description ?? 'N/A' }}</textarea>
        </div>

    </div>

</section>
@endsection
