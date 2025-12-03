<section class="partner-sec position-relative" id="pmc-cards">
    <div class="container">
        <h6 class="sec-hd-mini text-center">Our Partners</h6>
        <h2 class="sec-hd text-center">Supporting Cooperative Communities Together</h2>
        <div class="main-logo-area">
            <div class="border-div">
                <div class="row">
                    @if (isset($adsByPackage['Front Page & Footer Sponsor Slot']))
                        @foreach ($adsByPackage['Front Page & Footer Sponsor Slot'] as $frontPageFooterSponsorSlot)
                            @php
                                $logo =
                                    $frontPageFooterSponsorSlot?->vendor?->profile_logo != null
                                        ? asset($frontPageFooterSponsorSlot?->vendor?->profile_logo)
                                        : asset('assets/web/images/pmc1.png');
                                $uniqueCitiesCount = $frontPageFooterSponsorSlot?->vendor?->listings
                                    ?->pluck('city')
                                    ->map(fn($city) => strtolower($city))
                                    ->unique()
                                    ->count();
                            @endphp
                            <div class="col-lg-4 border-class px-0">
                                <a href="{{ route('realtor.profile', $frontPageFooterSponsorSlot?->vendor?->id) }}">
                                    <div title="Mac Properties" class="mortar-card tier2 interactive sharpCorner">
                                        <img src="{{ $logo }}" alt="" class="img-fluid pmc-img">
                                    </div>
                                    <div class="pmc-crd-detail pmc-crd-detail-1">
                                        <div class="pmc-crd-img">
                                            <img src="{{ $logo }}" alt="">
                                        </div>
                                        <ul class="section">
                                            <li class="column">
                                                <div class="totalInnerContainer">
                                                    <p class="total-number">
                                                        {{ count($frontPageFooterSponsorSlot?->vendor?->listings) }}
                                                    </p>
                                                    <p class="total-label">
                                                        properties
                                                    </p>
                                                </div>
                                            </li>
                                            <li class="column">
                                                <div class="totalInnerContainer totalInnerContainer3">
                                                    <p class="total-number">
                                                        {{ $uniqueCitiesCount }}
                                                    </p>
                                                    <p class="total-label">
                                                        Cities
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                        @php

                            $frontPageAdCount = 6 - count($adsByPackage['Front Page & Footer Sponsor Slot']);
                        @endphp
                        @if ($frontPageAdCount)
                            @for ($i = 1; $i <= $frontPageAdCount; $i++)
                                <div class="col-lg-4 border-class px-0 remove-border advertise-class">
                                    <a href="#">
                                        <div title="Mac Properties"
                                            class="mortar-card tier2 interactive sharpCorner remove-border">
                                            <img src="{{ asset('assets/web/images/pmc6.png') }}" alt=""
                                                class="img-fluid pmc-img">
                                        </div>
                                        <div class="pmc-crd-detail">
                                            <div class="pmc-crd-img">
                                                <img src="{{ asset('assets/web/images/pmc6.png') }}" alt="">
                                            </div>
                                            <ul class="section">
                                                <li class="column">
                                                    <div class="totalInnerContainer">
                                                        <p class="total-number">
                                                            28
                                                        </p>
                                                        <p class="total-label">
                                                            properties
                                                        </p>
                                                    </div>
                                                </li>
                                                <li class="column">
                                                    <div class="totalInnerContainer totalInnerContainer3">
                                                        <p class="total-number">
                                                            3
                                                        </p>
                                                        <p class="total-label">
                                                            Cities
                                                        </p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </a>
                                </div>
                            @endfor
                        @endif
                    @else
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="col-lg-4 border-class px-0 remove-border advertise-class">
                                <a href="#">
                                    <div title="Mac Properties"
                                        class="mortar-card tier2 interactive sharpCorner remove-border">
                                        <img src="{{ asset('assets/web/images/pmc6.png') }}" alt=""
                                            class="img-fluid pmc-img">
                                    </div>
                                    <div class="pmc-crd-detail">
                                        <div class="pmc-crd-img">
                                            <img src="{{ asset('assets/web/images/pmc6.png') }}" alt="">
                                        </div>
                                        <ul class="section">
                                            <li class="column">
                                                <div class="totalInnerContainer">
                                                    <p class="total-number">
                                                        28
                                                    </p>
                                                    <p class="total-label">
                                                        properties
                                                    </p>
                                                </div>
                                            </li>
                                            <li class="column">
                                                <div class="totalInnerContainer totalInnerContainer3">
                                                    <p class="total-number">
                                                        3
                                                    </p>
                                                    <p class="total-label">
                                                        Cities
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </a>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </div>
</section>
