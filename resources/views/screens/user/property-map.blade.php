@extends('layouts.user.app')

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Appointments</h1>
        <div class="profile-info-wrapper listing-info">
            <div class="map-div-wrapper">
                <div class="dropdown-wrapper">
                    <div class="location-map-div">
                        <h3 class="location-hd">Properties Map Location</h3>
                    </div>
                    <!-- <div class="dropdown">

                  <button class="btn dropdown-btn" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-toggle">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                  </ul>
                </div> -->
                    <div class="custom-dropdown-wrapper">
                        <button class="dropdown-toggle-btn">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="custom-dropdown-menu">
                            <li><a href="#">Edit</a></li>
                            <li><a href="#">Delete</a></li>
                        </ul>
                    </div>
                </div>
                <div class="map-wrapper2">
                    <div class="progress-bar-div">
                        <div class="progress-wrapper">
                            <p class="progress-para">Europe <span>653 Unit</span></p>
                            <div class="progress-126 progressbar"></div>
                        </div>
                        <div class="progress-wrapper">
                            <p class="progress-para">Europe <span>653 Unit</span></p>
                            <div class="progress-178 progressbar"></div>
                        </div>
                        <div class="progress-wrapper">
                            <p class="progress-para">Europe <span>653 Unit</span></p>
                            <div class="progress-126 progressbar"></div>
                        </div>
                        <div class="progress-wrapper">
                            <p class="progress-para">Europe <span>653 Unit</span></p>
                            <div class="progress-62 progressbar"></div>
                        </div>
                        <div class="progress-wrapper">
                            <p class="progress-para">Europe <span>653 Unit</span></p>
                            <div class="progress-110 progressbar"></div>
                        </div>
                    </div>
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD28UEoebX1hKscL3odt2TiTRVfe5SSpwE&callback=initMap" async
        defer></script>

    <script>
        function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 2,
                center: { lat: 20, lng: 0 },
                styles: [], // Optional styles
            });

            const locations = [
                { lat: 6.5, lng: 3.3, number: 224, main: true },     // Africa
                { lat: 40.7, lng: -74.0, number: 234 },              // USA
                { lat: -33.9, lng: 151.2, number: 234 },             // Australia
                { lat: 60.0, lng: 100.0, number: 653 },              // Russia
                { lat: 48.8, lng: 2.3, number: 25 },                 // Europe
                { lat: -15.6, lng: -47.9, number: 67 },              // South America
            ];

            locations.forEach((loc) => {
                class CustomMarker extends google.maps.OverlayView {
                    constructor(position, text, isMain) {
                        super();
                        this.position = position;
                        this.text = text;
                        this.isMain = isMain;
                        this.div = null;
                    }

                    onAdd() {
                        const div = document.createElement("div");
                        div.className = "custom-marker" + (this.isMain ? " main" : "");
                        div.textContent = this.text;

                        this.div = div;
                        const panes = this.getPanes();
                        panes.overlayMouseTarget.appendChild(div);
                    }

                    draw() {
                        const projection = this.getProjection();
                        const pos = projection.fromLatLngToDivPixel(this.position);

                        if (this.div) {
                            this.div.style.position = "absolute";
                            this.div.style.left = pos.x - this.div.offsetWidth / 2 + "px";
                            this.div.style.top = pos.y - this.div.offsetHeight / 2 + "px";
                        }
                    }

                    onRemove() {
                        if (this.div) {
                            this.div.remove();
                            this.div = null;
                        }
                    }
                }

                const marker = new CustomMarker(
                    new google.maps.LatLng(loc.lat, loc.lng),
                    loc.number,
                    loc.main
                );
                marker.setMap(map);
            });
        }

        window.initMap = initMap;
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggles = document.querySelectorAll('.dropdown-toggle-btn');

            toggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;

                    // Close other menus
                    document.querySelectorAll('.custom-dropdown-menu').forEach(m => {
                        if (m !== menu) m.style.display = 'none';
                    });

                    // Toggle current menu
                    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
                });
            });

            // Close dropdown on outside click
            document.addEventListener('click', function () {
                document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            });
        });
    </script>
@endpush
