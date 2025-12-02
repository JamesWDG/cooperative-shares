<section class="hero-banner hero-banner-2">
        <div class="video-area video-area-2 position-relative">
            <div>
                <video src="{{ asset('assets/web/images/banner-video.mp4') }}" autoplay="true" muted="true" loop="true" class="video1">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="banner-content-wrapper">
                <div class="banner-content-area text-center">
                    <h1 class="banner-hd">{{ $subHeading }}</h1>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ $route }}">{{ $heading }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $subHeading }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
