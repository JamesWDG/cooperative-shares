@extends('layouts.user.app')

@push('styles')
    <style>
        label.error {
            display: block;
            width: 100%;
            text-align: center;
            color: crimson;
        }
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        
        <div class="profile-info-wrapper">
            <div class="row">
                <!-- Left Side: Profile Info Form -->
                <div class="col-xxl-9 col-xl-8">
                    <h5 class="Pro-info-hd"><strong>Profile Information</strong></h5>
                    <form id="profile-form">
                        @csrf
                        <div class="form-group mb-3">
                            <label>First Name<span class="text-danger">*</span></label>
                            <div>:</div>
                            <input class="input-area" type="text" name="first_name" value="{{ $user->first_name }}" class="form-control" placeholder="First Name">
                        </div>
                        <label id="first_name-error" class="error" style="display:none"></label>
                        <div class="form-group mb-3">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <div>:</div>
                            <input class="input-area" type="text" name="last_name" value="{{ $user->last_name }}" class="form-control" placeholder="Last Name">
                        </div>
                        <label id="last_name-error" class="error" style="display:none"></label>
                        <div class="form-group mb-3">
                            <label>Email address<span class="text-danger">*</span></label>
                            <div>:</div>
                            <input class="input-area" type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="Email Address">
                        </div>
                        <label id="email-error" class="error" style="display:none"></label>
                        <div class="form-group mb-3">
                            <label>Phone Number<span class="text-danger">*</span></label>
                            <div>:</div>
                            <input class="input-area" type="text" name="phone_number" value="{{ $user->phone_number }}" class="form-control" placeholder="Phone Number">
                        </div>
                        <label id="phone_number-error" class="error" style="display:none"></label>
                        <div class="form-group mb-3">
                            <label>Address</label>
                            <div>:</div>
                            <input class="input-area" type="text" name="address" value="{{ $user->address }}" class="form-control" placeholder="Address">
                        </div>
                        <label id="address-error" class="error" style="display:none"></label>
                        <div class="form-group form-textarea-group mb-3">
                            <label>Description</label>
                            <div>:</div>
                            <textarea class="input-area" class="form-control" name="description" placeholder="Description">{{ $user->description }}</textarea>
                        </div>
                        <label id="description-error" class="error" style="display:none"></label>
                        <button type="submit" class="btn-dark">SAVE</button>
                    </form>
                </div>
                <div class="col-xxl-3 col-xl-4">
                    <div class="profile-card shadow-sm text-center">
                        <!-- Profile Image -->
                        <img id="profileImage"
                            onclick="document.getElementById('imageInput').click()"
                            title="Click Here To Upload Image"
                            src="{{ $user->profile_image ? asset($user->profile_image) : asset('assets/dummy_avatar/avatar.jpg') }}"
                            alt="Profile"
                            class="img-fluid rounded mb-2"
                            style="max-height: 180px; object-fit: cover;">


                        <!-- Upload & Delete Buttons -->
                        <h6 class="change-pro-hd">Change Profile Picture</h6>
                        <div class="mb-2 d-flex justify-content-center profile-pic-form">
                            <form id="profile-pic-form">
                                @csrf
                                <input type="file" id="imageInput" name="profile_image" style="display: none;">
                                <button class="btn-sm upload-btn-primary">SAVE IMAGE</button>
                            </form>
                            <button class="btn-sm btn-danger" {{ $user->profile_image ? '' : 'disabled' }} id="deleteProfilePic">DELETE</button>
                        </div>

                        <!-- Password Reset Fields -->
                        <h6 class="mt-3 password-hd">Password Reset</h6>
                        <form id="update-password-form">
                            @csrf
                            <input type="password" class="form-control password-input" name="password" id="password" placeholder="New Password"/>
                            <input type="password" class="form-control password-input" name="password_confirmation" placeholder="Confirm Password"/>
                            <button class="btn btn-dark btn-dark2">UPDATE</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </section>
@endsection

@push('scripts')

    {{-- Profile Form Validation --}}
    <script>
        $("#profile-form").validate({
            rules: {

                first_name: {
                    required: true,
                },

                last_name: {
                    required: true,
                },

                email: {
                    required: true,
                    email: true
                },

                phone_number: {
                    required: true,
                },

                address: {
                    required: true,
                },

                description: {
                    required: true,
                },
            },

            messages: {

                first_name: {
                    required: "Please Enter Your First Name!",
                },

                last_name: {
                    required: "Please Enter Your Last Name!",
                },

                email: {
                    required: "Please Enter Your Email!",
                    email: "Please Enter A Valid Email!"
                },

                phone_number: {
                    required: "Please Enter Your Phone Number!"
                },

                address: {
                    required: "Please Enter Your Address!",

                },

                description: {
                    required: "Please Enter Your Description!",
                },
            },

            errorPlacement: function (error, element) {
                error.insertAfter($(element).closest('.form-group'));
            }
        });
    </script>
    {{-- Profile Form Validation --}}

    {{-- Profile Form Submittion --}}
    <script>
        $(document).ready(function () {
            $('#profile-form').on('submit', function (e) {
                if (!$(this).valid()) {
                    return false;
                }
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('user.profile.store') }}",
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
                                window.location.reload();
                            })
                        }
                    },
                    error: function (error) {
                        $.LoadingOverlay('hide');
                        if (error.status == 422) {
                            let errors = error.responseJSON.errors;
                            let labelErrors = $('label.error');
                            labelErrors.each((index, labelElement) => {
                                let label = $(labelElement);
                                let name = label.attr('id').split('-')[0];
                                if (errors[name]) {
                                    $(`#${name}-error`).html(errors[name]).css('display', 'block');
                                }
                                else {
                                    $(`#${name}-error`).html('').css('display', 'none');
                                }
                            })
                        }
                        else {
                            Swal.fire({
                                title: 'Something Went Wrong!',
                                text: error.statusText,
                                icon: 'error',
                                confirmButtonColor: '#295568',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                })
            });
        });
    </script>
    {{-- Profile Form Submittion --}}

    {{-- Profile Picture Form Submittion --}}
    <script>
        $(document).ready(function () {
            $('#profile-pic-form').on('submit', function (e) {
                e.preventDefault();

                let imageInput = $('#imageInput')[0];
                let allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
                if(!imageInput.files.length)
                {
                    Swal.fire({
                        title: "Error!",
                        text: "Please Upload A Image First!",
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    })
                    return;
                }
                else
                {
                    let imageType = imageInput.files[0].type;
                    if(!allowedTypes.includes(imageType))
                    {
                        Swal.fire({
                            title: "Error!",
                            text: "Please Upload A Image, Accepted Types Are (jpeg, png, jpg, gif) ",
                            icon: 'error',
                            confirmButtonColor: '#295568',
                            confirmButtonText: 'OK'
                        })
                        return;
                    }
                }

                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('user.profileImage.store') }}",
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
                                window.location.reload();
                            })
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
    {{-- Profile Picture Form Submittion --}}

    {{-- Delete Profile Picture --}}
    <script>
        $(document).ready(function () {
            $('#deleteProfilePic').on('click', function(){

                let formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('_method', 'delete');
                $.ajax({
                    url: "{{ route('user.profileImage.destory') }}",
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
                                window.location.reload();
                            })
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
                })
            });
        });
    </script>
    {{-- Delete Profile Picture --}}

    {{-- Update Password Form Validation --}}
    <script>
        $("#update-password-form").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                },

                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                },
            },

            messages: {

                password: {
                    required: "Please Enter Your Password!",
                    minLength: "Password Should Be 8 Characters Long!",
                },

                password_confirmation: {
                    required: "Please Re-Enter Your Password!",
                    equalTo: "Password Do Not Match!",
                },
            },
        });
    </script>
    {{-- Update Password Form Validation --}}

    {{-- Update Password Form Submittion --}}
    <script>
        $(document).ready(function () {
            $('#update-password-form').on('submit', function (e) {
                if (!$(this).valid()) {
                    return false;
                }
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('user.password.update') }}",
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
                                window.location.reload();
                            })
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
                })
            });
        });
    </script>
    {{-- Image preview on change --}}
    <script>
        $(document).ready(function () {
            $('#imageInput').on('change', function (e) {
                const file = this.files[0];
                if (!file) return;

                const allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        title: "Error!",
                        text: "Please upload an image (jpeg, png, jpg, gif).",
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    });
                    $(this).val(''); // reset file input
                    return;
                }

                // Preview
                const reader = new FileReader();
                reader.onload = function (event) {
                    $('#profileImage').attr('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
    {{-- Update Password Form Submittion --}}

@endpush
