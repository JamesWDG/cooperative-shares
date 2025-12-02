@extends('layouts.vendor.app')

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Analytics</h1>
        <div class="table-chart-mega-wrapper">
            <div class="custom-chart-card">
                <div class="padding-box">
                    <div class="custom-chart-header">
                        <div class="traffic-wrapper">
                            <p class="custom-chart-title">Traffic</p>
                            <button class="leads-download-btn">Download Report</button>
                        </div>
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
                        <div class="traffic-wrapper">
                            <p class="custom-chart-title">Conversions</p>
                            <button class="leads-download-btn">Download Report</button>
                        </div>
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

        <div class="leads-chart-card">
            <div class="leads-chart-header">
                <h3 class="lead-para">Leads</h3>
                <div class="lead-select-wrapper">
                    <select name="" id="">
                        <option value="">This Month</option>
                        <option value="">This Week</option>
                        <option value="">This Day</option>
                    </select>
                    <button class="leads-download-btn">Download Report</button>
                </div>
            </div>


            <canvas id="leadsChart" class="leads-chart-canvas"></canvas>
        </div>
    </section>
@endsection

@push('scripts')
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
        const ctx = document.getElementById('leadsChart').getContext('2d');

        const leadsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'High Leads',
                        data: [12000, 24000, 36000, 52000, 60000, 48000, 45000, 49000, 55000, 58000, 62000, 70000],
                        backgroundColor: 'rgba(35, 99, 151, 0.1)',
                        borderColor: '#236397',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                    },
                    {
                        label: 'Low Leads',
                        data: [5000, 10000, 18000, 22000, 26000, 18000, 12000, 17000, 19000, 22000, 24000, 25000],
                        backgroundColor: 'rgba(218, 121, 60, 0.1)',
                        borderColor: '#da793c',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => `$${value / 1000}k`
                        },
                        grid: {
                            color: '#eee'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
