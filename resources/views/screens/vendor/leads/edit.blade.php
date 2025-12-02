@extends('layouts.vendor.app')

@push('styles')
    <style>
        label.error {
            color: crimson;
            font-family: var(--outfit-font);
            margin: -10px;
        }
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <div class="propFormContainer">
            <h2 class="view-hd">Make An Appointment</h2>
            <form id="appointment-form">
                @csrf
                @method('PUT')
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="name" class="propLabel">Name</label>
                        <input type="text" id="name" name="name" value="{{ $lead->name }}" class="propInput" disabled />
                    </div>
                    <div class="propFormGroup">
                        <label for="number" class="propLabel">Number</label>
                        <input type="number" id="number" name="number" value="{{ $lead->phone_number }}" class="propInput"
                            disabled />
                    </div>
                </div>
                <div class="prop-wrapper">
                    <div class="propFormGroup">
                        <label for="email" class="propLabel">Email</label>
                        <input type="email" id="email" name="email" value="{{ $lead->email }}" class="propInput" disabled />
                    </div>
                    <div class="propFormGroup">
                        <label for="appointment-date" class="propLabel">Date & Time</label>
                        <input type="datetime-local" id="appointment-date" name="appointment_date" class="propInput" />
                    </div>
                </div>
                <div class="propFormGroup">
                    <label for="message" class="propLabel">Message</label>
                    <textarea id="message" name="message" class="propTextarea" placeholder="Write something..."
                        disabled>{{ $lead->message }}</textarea>
                </div>
                <button class="add-btn">Make Appointment</button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')

    {{-- Listing Form Validation --}}
    <script>
        $.validator.addMethod("futureDateTime", function (value, element) {
            if (this.optional(element)) {
                return true;
            }

            const selectedDateTime = new Date(value);
            const currentDateTime = new Date();

            return selectedDateTime > currentDateTime;
        }, "Please select a date and time in the future");

        $('#appointment-form').validate({
            rules: {
                appointment_date: {
                    required: true,
                    futureDateTime: true
                },
            },

            messages: {
                appointment_date: {
                    required: 'Please Provide Appointment Date & Time!',
                    futureDateTime: 'Appointment Date And Time Must Be In The Future!'
                },
            },
        });
    </script>
    {{-- Listing Form Validation --}}

    {{-- Appointment Form Submittion --}}
    <script>
        $(document).ready(function () {
            $('#appointment-form').on('submit', function (e) {
                if (!$(this).valid()) {
                    return false;
                }
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('vendor.lead.update', $lead->id) }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $.LoadingOverlay('show');
                    },
                    success: function (response) {
                        $.LoadingOverlay('hide');
                        if (response.status) {
                            Swal.fire({
                                title: "Info!",
                                text: response.message,
                                icon: 'info',
                                confirmButtonColor: '#295568',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = response.url;
                            });
                        }
                    },
                    error: function (error) {
                        $.LoadingOverlay('hide');
                        let message = (error.responseJSON?.message) ? error.responseJSON?.message : error.statusText;
                        Swal.fire({
                            title: 'Something Went Wrong!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
    {{-- Appointment Form Submittion --}}

@endpush
