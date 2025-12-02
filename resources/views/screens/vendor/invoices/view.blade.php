@extends('layouts.vendor.app')
@section('title', 'Invoice Details')

@push('styles')
<style>
    .invoice-page-wrapper {
        padding-top: 10px;
    }

    .invoice-card {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        padding: 24px 24px 20px;
    }

    .invoice-header {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 16px;
        margin-bottom: 18px;
    }

    .invoice-title {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }

    .invoice-subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    .invoice-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    .invoice-badge-paid {
        background: #e6f6ec;
        color: #217a3c;
    }

    .invoice-badge-pending {
        background: #fff4e5;
        color: #b4690e;
    }

    .invoice-badge-failed,
    .invoice-badge-refunded {
        background: #fdecea;
        color: #b42318;
    }

    .invoice-amount-chip {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .invoice-amount-chip small {
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
    }

    .invoice-meta-heading {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .invoice-meta-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-meta-table td {
        padding: 6px 4px;
        font-size: 13px;
        vertical-align: top;
    }

    .invoice-meta-table td.label {
        width: 160px;
        font-weight: 600;
        color: #4b5563;
    }

    .invoice-meta-table td.value {
        color: #111827;
    }

    .invoice-section-card {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 14px 16px;
        background: #f9fafb;
        margin-bottom: 14px;
    }

    .invoice-note {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .invoice-actions .btn {
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .invoice-card {
            padding: 18px 16px;
        }

        .invoice-title {
            font-size: 18px;
        }

        .invoice-amount-chip {
            font-size: 16px;
        }
    }
</style>
@endpush

@section('section')
<section class="main-content-area">
    <div class="profile-info-wrapper invoice-page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="invoice-card shadow-sm">

                    {{-- Header --}}
                    <div class="invoice-header d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <div class="invoice-title">
                                Invoice #{{ $invoice->invoice_number }}
                            </div>

                            @php
                                $typeLabel = match($invoice->type) {
                                    'subscription'      => 'Subscription',
                                    'featured_listing'  => 'Featured Listing',
                                    'marketing_plan'    => 'Marketing Plan',
                                    default             => ucfirst(str_replace('_', ' ', $invoice->type)),
                                };

                                $badgeClass = 'invoice-badge-status';
                                if ($invoice->status === 'paid') {
                                    $badgeClass .= ' invoice-badge-paid';
                                } elseif ($invoice->status === 'pending') {
                                    $badgeClass .= ' invoice-badge-pending';
                                } else {
                                    $badgeClass .= ' invoice-badge-failed';
                                }
                            @endphp

                            <div class="invoice-subtitle">
                                {{ $typeLabel }}
                                <span class="{{ $badgeClass }} ms-2">
                                    <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>

                            @if($invoice->issued_at)
                                <div class="text-muted small mt-1">
                                    Issued on {{ $invoice->issued_at->format('M d, Y h:i A') }}
                                </div>
                            @endif
                        </div>

                        <div class="invoice-actions d-flex flex-wrap gap-2">
                            <!-- <button type="button"
                                    class="btn btn-outline-secondary btn-sm"
                                    onclick="window.print()">
                                <i class="fa-solid fa-print"></i> &nbsp; Print Invoice
                            </button> -->

                            <a href="{{ route('vendor.invoices') }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-arrow-left"></i> &nbsp; Back to Invoices
                            </a>
                        </div>
                    </div>

                    {{-- Top Summary Row --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="invoice-section-card h-100">
                                <div class="invoice-meta-heading">Amount</div>
                                <div class="invoice-amount-chip">
                                    {{ number_format($invoice->amount, 2) }}
                                    <small>{{ $invoice->currency ?? 'USD' }}</small>
                                </div>
                                <div class="invoice-note">
                                    Payment Method: {{ strtoupper($invoice->payment_method ?? 'stripe') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="invoice-section-card h-100">
                                <div class="invoice-meta-heading">Invoice Details</div>
                                <div style="font-size:13px;">
                                    <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
                                    <div><strong>Type:</strong> {{ $typeLabel }}</div>
                                    <div><strong>Status:</strong>
                                        <span class="{{ $badgeClass }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="invoice-section-card h-100">
                                <div class="invoice-meta-heading">Reference</div>
                                <div style="font-size:13px;">
                                    <div><strong>Transaction ID:</strong> {{ $invoice->transaction_id ?? '—' }}</div>
                                    <div class="mt-1">
                                        <strong>Reference ID:</strong> {{ $invoice->reference_id ?? '—' }}
                                    </div>
                                    <div class="invoice-note">
                                        Internal link to subscription / listing / marketing plan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detailed Meta Table --}}
                    <div class="invoice-detail-card">
                        <div class="invoice-meta-heading mb-2">
                            Timeline & System Info
                        </div>

                        <table class="invoice-meta-table">
                            <tr>
                                <td class="label">Invoice Number</td>
                                <td class="value">{{ $invoice->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td class="label">Type</td>
                                <td class="value">{{ $typeLabel }}</td>
                            </tr>
                            <tr>
                                <td class="label">Amount</td>
                                <td class="value">
                                    {{ number_format($invoice->amount, 2) }}
                                    {{ $invoice->currency ?? 'USD' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Payment Method</td>
                                <td class="value">{{ strtoupper($invoice->payment_method ?? 'stripe') }}</td>
                            </tr>
                            <tr>
                                <td class="label">Transaction ID</td>
                                <td class="value">{{ $invoice->transaction_id ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Reference ID</td>
                                <td class="value">
                                    {{ $invoice->reference_id ?? '—' }}
                                    <span class="text-muted small d-block">
                                        (Internal link to subscription / listing / marketing plan)
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Status</td>
                                <td class="value">
                                    <span class="{{ $badgeClass }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Issued At</td>
                                <td class="value">{{ optional($invoice->issued_at)->format('M d, Y h:i A') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Paid At</td>
                                <td class="value">{{ optional($invoice->paid_at)->format('M d, Y h:i A') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Created</td>
                                <td class="value">{{ optional($invoice->created_at)->format('M d, Y h:i A') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Last Updated</td>
                                <td class="value">{{ optional($invoice->updated_at)->format('M d, Y h:i A') ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>

                </div>{{-- /invoice-card --}}
            </div>
        </div>
    </div>
</section>
@endsection
