@extends('layouts.vendor.app')

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Dashboard</h1>

        <div class="chart-mega-wrapper">
            <div class="listing-widget-card">
                <div class="listing-widget-left">
                    <p class="listing-widget-number">{{ $totalListings }}</p>
                    <p class="listing-widget-title">Listings</p>
                    <p class="listing-widget-subtitle">New Project</p>
                </div>
                <img src="{{ asset('assets/vendor/images/side-bar-img3.png') }}" alt="" class="widget-card">
            </div>
            <div class="listing-widget-card">
                <div class="listing-widget-left">
                    <p class="listing-widget-number">{{ $totalLeads }}</p>
                    <p class="listing-widget-title">Leads</p>
                    <p class="listing-widget-subtitle">{{ $totalLeads }}%</p>
                </div>
               <img src="{{ asset('assets/vendor/images/side-bar-img4.png') }}" alt="" class="widget-card">
            </div>
            <div class="listing-widget-card last">
                <div class="listing-widget-left">
                    <p class="listing-widget-number">{{ $totalAppointments }}</p>
                    <p class="listing-widget-title">Appointments</p>
                    <p class="listing-widget-subtitle">{{ $totalAppointments }}%</p>
                </div>
                <img src="{{ asset('assets/vendor/images/side-bar-img4.png') }}" alt="" class="widget-card">
            </div>
        </div>
        <h1 class="dashboard-hd">Analytics</h1>
        <div class="table-chart-mega-wrapper">
            <div class="custom-chart-card">
                <div class="padding-box">
                    <div class="custom-chart-header">
                        <p class="custom-chart-title">Traffic</p>
                        <p class="custom-chart-subtitle">11% High then last month</p>
                    </div>
                    <div class="custom-chart-stats">
                        <div>
                            <p class="custom-label">Overall</p>
                            <p class="custom-value">70.40%</p>
                        </div>
                        <div>
                            <p class="custom-label">Montly</p>
                            <p class="custom-value">25.80%</p>
                        </div>
                        <div>
                            <p class="custom-label">Day</p>
                            <p class="custom-value">12.50%</p>
                        </div>
                    </div>
                </div>
                <canvas id="trafficChart"></canvas>
            </div>

            <!-- Conversions Chart -->
            <div class="custom-chart-card">
                <div class="padding-box">
                    <div class="custom-chart-header">
                        <p class="custom-chart-title">Conversions</p>
                        <p class="custom-chart-subtitle">18% High then last month</p>
                    </div>
                    <div class="custom-chart-stats">
                        <div>
                            <p class="custom-label">Overall</p>
                            <p class="custom-value">70.40%</p>
                        </div>
                        <div>
                            <p class="custom-label">Montly</p>
                            <p class="custom-value">25.80%</p>
                        </div>
                        <div>
                            <p class="custom-label">Day</p>
                            <p class="custom-value">12.50%</p>
                        </div>
                    </div>
                </div>
                <canvas id="conversionChart"></canvas>
            </div>
        </div>

        <h1 class="dashboard-hd">Subscription Plans</h1>
        <div class="pricing-box-unique-wrapper">
            <div class="unique-pricing-box selected">
                <div class="unique-plan-header">BASIC</div>
                <div class="unique-price">$399</div>
                <div class="unique-per-user">Per User/Month</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Up to 5 Active Listings</p>
                        </li>
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Standard Analytics</p>
                        </li>
                        <li><i class="fa-solid fa-xmark"></i>
                            <p class="unique-para">Email Support</p>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('vendor.payment.methods') }}" class="unique-upgrade-btn active disabled" aria-disabled="true">UPGRADE NOW</a>

            </div>

            <div class="unique-pricing-box">
                <div class="unique-plan-header">ENHANCED</div>
                <div class="unique-price">$699</div>
                <div class="unique-per-user">Per User/Month</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Unlimited Listings</p>
                        </li>
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">3 Featured Slots</p>
                        </li>
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Advanced Analytics</p>
                        </li>
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Priority Support</p>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('vendor.payment.methods') }}" class="unique-upgrade-btn">UPGRADE</a>
            </div>

            <div class="unique-pricing-box last">
                <div class="unique-plan-header">PREMIUM</div>
                <div class="unique-price">$999</div>
                <div class="unique-per-user">Per User/Month</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Unlimited Listings</p>
                        </li>
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">5 Featured Slots</p>
                        </li>
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Advanced Analytics</p>
                        </li>
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Priority Support</p>
                        </li>
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para">Custom Co-op Page Design</p>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('vendor.payment.methods') }}" class="unique-upgrade-btn">UPGRADE</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

    <script>
        // Traffic Line Chart
        new Chart(document.getElementById('trafficChart'), {
            type: 'line',
            data: {
                labels: Array.from({ length: 14 }, (_, i) => i + 1),
                datasets: [{
                    label: 'Traffic',
                    data: [20, 35, 25, 40, 50, 30, 45, 28, 40, 33, 48, 38, 42, 30],
                    borderColor: '#144b5d',
                    backgroundColor: 'rgba(20, 75, 93, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#fff',
                        titleColor: '#000',
                        bodyColor: '#000',
                        borderColor: '#ccc',
                        borderWidth: 1,
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                        }
                    }
                },
                scales: {
                    x: { display: false },
                    y: {
                        display: false,
                        beginAtZero: true
                    }
                }
            }
        });

        // Conversion Bar Chart
        new Chart(document.getElementById('conversionChart'), {
            type: 'bar',
            data: {
                labels: Array.from({ length: 14 }, (_, i) => i + 1),
                datasets: [
                    {
                        label: 'Background',
                        data: Array(14).fill(50),
                        backgroundColor: '#eaeaea',
                        borderRadius: 6,
                        barThickness: 12,
                        order: 1
                    },
                    {
                        label: 'Conversions',
                        data: [12, 25, 20, 10, 50, 15, 45, 18, 12, 40, 10, 18, 24, 16],
                        backgroundColor: '#e3a94d',
                        borderRadius: 6,
                        barThickness: 12,
                        order: 2
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#fff',
                        titleColor: '#000',
                        bodyColor: '#000',
                        borderColor: '#ccc',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: { display: false },
                    y: {
                        display: false,
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const circles = document.querySelectorAll('.listing-widget-progress');
            const texts = document.querySelectorAll('.listing-widget-progress-text');

            const radius = 57;
            const circumference = 2 * Math.PI * radius;

            // Set dash array and offset first for all
            circles.forEach((circle, index) => {
                const value = parseFloat(texts[index].textContent.trim());
                const percent = isNaN(value) ? 0 : value;
                const offset = circumference - (percent / 100) * circumference;

                circle.style.strokeDasharray = circumference;
                circle.style.strokeDashoffset = circumference;

                // Store offset
                circle.dataset.offset = offset;
            });

            // Delay start to allow smooth sync
            setTimeout(() => {
                circles.forEach(circle => {
                    circle.style.strokeDashoffset = circle.dataset.offset;
                });
            }, 200); // adjust if needed
        });
    </script>
@endpush
