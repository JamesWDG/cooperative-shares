@extends('layouts.vendor.app')

@push('styles')
<style>
    .listing-widget-progress-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
    }

    .big-dashboard-icon {
        font-size: 48px;     /* Bigger icon */
        color: #293241;      /* You can change color */
    }
</style>
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Analytics</h1>

        <div class="table-chart-mega-wrapper">
            {{-- TRAFFIC CARD --}}
            <div class="custom-chart-card">
                <div class="padding-box">
                    <div class="custom-chart-header">
                        <p class="custom-chart-title">Traffic</p>
                        <p class="custom-chart-subtitle">
                            {{ $trafficChangePercent }}% {{ $trafficTrendText }} than last month
                        </p>
                    </div>
                    <div class="custom-chart-stats">
                        <div>
                            <p class="custom-label">Overall</p>
                            <p class="custom-value">{{ $overallVisits }}</p>
                        </div>
                        <div>
                            <p class="custom-label">Monthly</p>
                            <p class="custom-value">{{ $monthlyVisits }}</p>
                        </div>
                        <div>
                            <p class="custom-label">Day</p>
                            <p class="custom-value">{{ $dailyVisits }}</p>
                        </div>
                    </div>
                </div>
                <canvas id="trafficChart"></canvas>
            </div>

            {{-- CONVERSIONS CARD (LEADS) --}}
            <div class="custom-chart-card">
                <div class="padding-box">
                    <div class="custom-chart-header">
                        <p class="custom-chart-title">Conversions</p>
                        <p class="custom-chart-subtitle">
                            {{ $conversionChangePercent }}% {{ $conversionTrendText }} than last month
                        </p>
                    </div>
                    <div class="custom-chart-stats">
                        <div>
                            <p class="custom-label">Overall</p>
                            <p class="custom-value">{{ $overallLeads }}</p>
                        </div>
                        <div>
                            <p class="custom-label">Monthly</p>
                            <p class="custom-value">{{ $monthlyLeads }}</p>
                        </div>
                        <div>
                            <p class="custom-label">Day</p>
                            <p class="custom-value">{{ $dailyLeads }}</p>
                        </div>
                    </div>
                </div>
                <canvas id="conversionChart"></canvas>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Chart.js include agar layout me nahi hai to yahan uncomment karo --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

    <script>
        // =======================
        // TRAFFIC LINE CHART
        // =======================
        const trafficLabels = @json($trafficChartLabels);
        const trafficData   = @json($trafficChartData);

        new Chart(document.getElementById('trafficChart'), {
            type: 'line',
            data: {
                labels: trafficLabels,
                datasets: [{
                    label: 'Traffic',
                    data: trafficData,
                    borderColor: '#144b5d',
                    backgroundColor: 'rgba(20, 75, 93, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 3,
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
                            label: ctx => ` ${ctx.parsed.y} visits`
                        }
                    }
                },
                scales: {
                    x: { display: true },
                    y: {
                        display: true,
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        // =======================
        // CONVERSIONS CHART
        // Gray = Traffic, Yellow = Leads
        // =======================
        const convLabels      = @json($trafficChartLabels);      // same labels
        const convTrafficData = @json($trafficChartData);        // gray line
        const convLeadsData   = @json($conversionChartData);     // yellow line

        new Chart(document.getElementById('conversionChart'), {
            type: 'line',
            data: {
                labels: convLabels,
                datasets: [
                    {
                        label: 'Traffic',
                        data: convTrafficData,
                        borderColor: '#e0e0e0',                    // Gray
                        backgroundColor: 'rgba(224,224,224,0.2)',
                        fill: false,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 3,
                    },
                    {
                        label: 'Leads',
                        data: convLeadsData,
                        borderColor: '#e3a94d',                    // Yellow
                        backgroundColor: 'rgba(227,169,77,0.2)',
                        fill: false,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
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
                    x: { display: true },
                    y: {
                        display: true,
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    </script>

    {{-- old circle progress JS ab zaroori nahi, chaho to hata sakte ho --}}
@endpush
