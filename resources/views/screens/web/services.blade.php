@extends('layouts.web.app')

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Our Services" :route="route('index')" />
    <section class="offer-sec">
        <div class="container">
            <div class="services-sec-header">
                <div>
                    <h2 class="sec-hd">What We Offer</h2>
                </div>
                <div class="pro-icon-dev">
                    <div class="our-pro-icons">
                        <button class="our-pro-button" data-value="listView" id="listViewBtn"><i
                                class="fa-solid fa-list-ul"></i></button>
                        <button class="our-pro-button" data-value="gridView" id="gridViewBtn"><i
                                class="fas fa-th-large"></i></button>
                    </div>
                </div>
            </div>
            <div class="service-card-wrapper">
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer1.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-01</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer2.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-02</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer3.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-03</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer4.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-04</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer5.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-05</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer6.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-06</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer7.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-07</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer8.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-08</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('services.detail') }}">
                    <div class="service-card">
                        <div class="service-card-img">
                            <img src="{{ asset('assets/web/images/offer9.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-content-prnt">
                            <div class="card-content">
                                <h2 class="sec-hd service-card-hd">Services-09</h2>
                                <p class="para service-card-para">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                                    the 1500s, when an unknown printer.</p>
                                <img src="{{ asset('assets/web/images/ser-card-icon.png') }}" alt="" class="ser-card-icon">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- <div class="row">
                <div class="col-lg-12">
                    <div class="pagination-btns">
                        <button>1</button>
                        <button>2</button>
                        <button>3</button>
                        <button class="active">Next
                            <i><i class="fa-solid fa-arrow-right"></i></i>
                        </button>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
    <x-partner-section />
@endsection

@push('scripts')
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const listViewBtn = document.getElementById("listViewBtn");
        const gridViewBtn = document.getElementById("gridViewBtn");
        const serviceCards = document.querySelectorAll(".service-card");
        const serviceCardsImage = document.querySelectorAll(".service-card img.img-fluid");
        const serviceWrappers = document.querySelectorAll(".service-card-wrapper a");
        const cardContentPrnts = document.querySelectorAll(".card-content-prnt");
        const serviceCardIcon = document.querySelectorAll(".ser-card-icon");

        // List View
        listViewBtn.addEventListener("click", function () {
            serviceCards.forEach(card => {
                card.style.display = "flex";
            });

            serviceWrappers.forEach(wrapper => {
                if (window.innerWidth <= 1024) {
                    wrapper.style.width = "100%";
                } else {
                    wrapper.style.width = "calc(100% / 2 - 12px)";
                }
            });

            cardContentPrnts.forEach(content => {
                content.style.marginTop = "0px";
                content.style.padding = "0px";
                content.style.width = "80%";
            });

            serviceCardsImage.forEach(content => {
                content.style.height = "100%";
            });

            serviceCardIcon.forEach(content => {
                content.style.left = "unset";
                content.style.right = "0px";
            });
        });

        // Grid View
        gridViewBtn.addEventListener("click", function () {
            serviceCards.forEach(card => {
                card.style.display = "block";
            });

            serviceWrappers.forEach(wrapper => {
                if (window.innerWidth <= 991) {
                    wrapper.style.width = "100%";
                } else if (window.innerWidth <= 1024) {
                    wrapper.style.width = "calc(100% / 2 - 12px)";
                } else {
                    wrapper.style.width = "calc(100% / 3 - 12px)";
                }
            });

            cardContentPrnts.forEach(content => {
                content.style.marginTop = "-70px";
                content.style.padding = "0px 16px";
                content.style.width = "100%";
            });

            serviceCardIcon.forEach(content => {
                content.style.right = "unset";
                content.style.left = "30px";
            });
        });
    });
</script>
@endpush