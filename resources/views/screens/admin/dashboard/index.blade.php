@extends('layouts.admin.app')
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
        <h1 class="dashboard-hd">Dashboard</h1>

        <div class="chart-mega-wrapper">
            
            <!-- Property Listings Widget -->
            <div class="listing-widget-card">
                <div class="listing-widget-left">
                    <p class="listing-widget-number">{{ $totalListings }}</p>
                    <p class="listing-widget-title">Property Listings</p>
                    <p class="listing-widget-subtitle">by Vendors</p>
                </div>

                <!-- REPLACED CIRCLE WITH HOME ICON -->
                <div class="listing-widget-progress-icon">
                    <i class="fa-solid fa-home big-dashboard-icon"></i>
                </div>
            </div>
            
            <!-- Property Leads Widget -->
            <div class="listing-widget-card">
                <div class="listing-widget-left">
                    <p class="listing-widget-number">{{ $totalLeads }}</p>
                    <p class="listing-widget-title">Property Leads</p>
                    <p class="listing-widget-subtitle">by Customers</p>
                </div>

                <!-- REPLACED CIRCLE WITH LEADS ICON -->
                <div class="listing-widget-progress-icon">
                    <i class="fa-solid fa-users big-dashboard-icon"></i>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    {{-- No circle JS needed anymore --}}
@endpush
