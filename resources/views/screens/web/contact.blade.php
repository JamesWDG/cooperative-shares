@extends('layouts.web.app')

@php
    // Fallbacks so view doesn't break if controller doesn’t pass something
    $page         = $page         ?? false;
    $contactCards = $contactCards ?? [];
    $heroText     = $heroText     ?? [];
    $mapSection   = $mapSection   ?? [];
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Contact Us" :route="route('index')" />

    {{-- CONTACT CARDS --}}
    <section class="contact-sec1">
        <div class="container">
            <div class="contact-card-wrapper">
                {{-- Phone --}}
                <div class="conact-card-prnt">
                    <div class="con-card1">
                        <div class="contact-crd-img">
                            <img src="{{ asset('assets/web/images/con-icon1.png') }}" alt="" class="contact-icon">
                        </div>
                        <div class="contact-crd-content">
                            <h6 class="contact-chld-hd">Phone Number</h6>
                            <p class="contact-chld-para">
                                {{ $contactCards['phone'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="conact-card-prnt">
                    <div class="con-card1">
                        <div class="contact-crd-img">
                            <img src="{{ asset('assets/web/images/con-icon2.png') }}" alt="" class="contact-icon">
                        </div>
                        <div class="contact-crd-content">
                            <h6 class="contact-chld-hd">Email Address</h6>
                            <p class="contact-chld-para">
                                {{ $contactCards['email'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="conact-card-prnt">
                    <div class="con-card1">
                        <div class="contact-crd-img">
                            <img src="{{ asset('assets/web/images/con-icon4.png') }}" alt="" class="contact-icon">
                        </div>
                        <div class="contact-crd-content">
                            <h6 class="contact-chld-hd">Address</h6>
                            <p class="contact-chld-para">
                                {{ $contactCards['address'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- HERO TEXT + FORM --}}
    <section class="form-section1">
        <div class="container">
            <div class="row">
                {{-- Left: dynamic text + form --}}
                <div class="col-lg-6">
                    <h2 class="sec-hd">
                        {{ $heroText['heading'] ?? '' }}
                    </h2>

                    @if(!empty($heroText['paragraph_1']))
                        <p class="diff-para1">
                            {{ $heroText['paragraph_1'] }}
                        </p>
                    @endif

                    @if(!empty($heroText['paragraph_2']))
                        <p class="diff-para1">
                            {{ $heroText['paragraph_2'] }}
                        </p>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="contact-form102">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="contact-field-wrapper">
                                    <label>Full Name:</label>
                                    <input type="text" name="full_name" placeholder="Full Name" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="contact-field-wrapper">
                                    <label>Your Email:</label>
                                    <input type="email" name="email" placeholder="Your Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="contact-field-wrapper">
                                    <label>Phone Number:</label>
                                    <input type="text" name="phone_number" placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="contact-field-wrapper">
                                    <label>Select Service</label>
                                    <select name="service">
                                        <option value="">Select Service</option>
                                        <option value="Service 2">Service 2</option>
                                        <option value="Service 3">Service 3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="contact-field-wrapper">
                                    <label>Write Message:</label>
                                    <textarea name="message" placeholder="Message..." required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="contact-field-wrapper contact-field-wrapper2">
                                    <button class="primary-btn" type="submit">Submit Now</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                {{-- Right: dynamic hero image (from CMS) or fallback --}}
                <div class="col-lg-6">
                    <div class="contact-img">
                        @if(!empty($heroText['image']))
                            <img
                                src="{{ asset('storage/cms/contact-settings/hero_text/' . $heroText['image']) }}"
                                alt="Contact"
                                class="img-fluid contact-main-img">
                        @else
                            <img
                                src="{{ asset('assets/web/images/contact-img.png') }}"
                                alt="Contact"
                                class="img-fluid contact-main-img">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAP SECTION FROM CMS --}}
    <section class="map-sec">
        @if(!empty($mapSection['iframe_src']))
            {!! $mapSection['iframe_src'] !!}
        @endif
    </section>
@endsection
