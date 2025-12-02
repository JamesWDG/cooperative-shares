@extends('layouts.vendor.app')

@section('section')
<section class="main-content-area">
    <h1 class="dashboard-hd">Invoices</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Invoices No</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Totals</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                <tr data-id="1" data-description="Lorem Ipsum" data-date="02-04-2025" data-total="$230.00">
                    <td>$199/Monthly</td>
                    <td>Lorem Ipsum</td>
                    <td>02-04-2025</td>
                    <td class="status-completed">$230.00</td>
                    <td class="position-relative custom-action-cell">
                        <i class="fa-solid fa-ellipsis-vertical custom-toggle" style="cursor: pointer;"></i>
                        <div class="custom-dropdown-menu" style="display: none;">
                            <div class="custom-dropdown-item view-btn">View</div>
                            <div class="custom-dropdown-item text-danger">Delete</div>
                        </div>
                    </td>
                </tr>

                <tr data-id="2" data-description="Lorem Ipsum 2" data-date="05-04-2025" data-total="$150.00">
                    <td>$199/Monthly</td>
                    <td>Lorem Ipsum 2</td>
                    <td>05-04-2025</td>
                    <td class="status-pending">$150.00</td>
                    <td class="position-relative custom-action-cell">
                        <i class="fa-solid fa-ellipsis-vertical custom-toggle" style="cursor: pointer;"></i>
                        <div class="custom-dropdown-menu" style="display: none;">
                            <div class="custom-dropdown-item view-btn">View</div>
                            <div class="custom-dropdown-item text-danger">Delete</div>
                        </div>
                    </td>
                </tr>
                 <tr data-id="2" data-description="Lorem Ipsum 2" data-date="05-04-2025" data-total="$150.00">
                    <td>$199/Monthly</td>
                    <td>Lorem Ipsum 2</td>
                    <td>05-04-2025</td>
                    <td class="status-pending">$150.00</td>
                    <td class="position-relative custom-action-cell">
                        <i class="fa-solid fa-ellipsis-vertical custom-toggle" style="cursor: pointer;"></i>
                        <div class="custom-dropdown-menu" style="display: none;">
                            <div class="custom-dropdown-item view-btn">View</div>
                            <div class="custom-dropdown-item text-danger">Delete</div>
                        </div>
                    </td>
                </tr>
                 <tr data-id="2" data-description="Lorem Ipsum 2" data-date="05-04-2025" data-total="$150.00">
                    <td>$199/Monthly</td>
                    <td>Lorem Ipsum 2</td>
                    <td>05-04-2025</td>
                    <td class="status-pending">$150.00</td>
                    <td class="position-relative custom-action-cell">
                        <i class="fa-solid fa-ellipsis-vertical custom-toggle" style="cursor: pointer;"></i>
                        <div class="custom-dropdown-menu" style="display: none;">
                            <div class="custom-dropdown-item view-btn">View</div>
                            <div class="custom-dropdown-item text-danger">Delete</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination-wrapper pagination-wrapper-apo">
            <div class="pag-para">
                <p>Showing <span>1</span> of <span>6</span> Results</p>
            </div>

            <div class="pagination-btns">
                <button><i class="fa-solid fa-chevron-left"></i></button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div id="invoicePdfModal" class="custom-modal" style="display:none;">
    <div class="custom-modal-content">
        <span class="close-modal">&times;</span>

        <!-- PDF Button -->
        <button id="downloadPdfBtn" style="
            padding:10px 16px;
            background:#3b3b8f;
            color:#fff;
            border:none;
            border-radius:6px;
            cursor:pointer;
            margin:15px;
            font-size:15px;
        ">Download PDF</button>

        <div id="invoicePdfContent" class="canvas-wrapper">
            <div class="invoice-template">

                <div class="invoice-header">
                    <div class="left"><h1>INVOICE</h1></div>
                    <img src="{{ asset('assets/vendor/images/logo.png') }}" alt="">
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th>QTY</th>
                            <th>DESCRIPTION</th>
                            <th>UNIT PRICE</th>
                            <th>AMOUNT</th>
                        </tr>
                    </thead>

                    <tbody id="invoiceItems">
                        <tr>
                            <td>1</td>
                            <td id="canvasDescription"></td>
                            <td>100.00</td>
                            <td id="canvasTotal"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="total-section">
                    <p class="grand-total">TOTAL: <strong id="grandTotal"></strong></p>
                </div>

                <div class="footer-note">
                    <h3>Thank You</h3>
                    <p>Payment is due within 15 days</p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* SAME — No changes */
.custom-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; display: flex; justify-content: center; align-items: center; }
.custom-modal-content { width: 90%; background: transparent; position: relative; }
.close-modal { position: absolute; top: 10px; right: 20px; color: #fff; font-size: 30px; cursor: pointer; }
.invoice-template { width: 850px; background: #fff; padding: 40px; border-radius: 12px; font-family: Arial; }
.invoice-header { display: flex; justify-content: space-between; border-bottom: 2px solid #ddd; padding-bottom: 20px; align-items: center; }
.invoice-logo { width: 130px; }
.items-table { width: 100%; border-collapse: collapse; margin-top: 25px; }
.items-table th, .items-table td { padding: 12px; border-bottom: 1px solid #ddd; }
.total-section { text-align: right; margin-top: 20px; }
.footer-note { text-align: center; margin-top: 40px; }
</style>
@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".custom-toggle").forEach(toggle => {
        toggle.addEventListener("click", function (e) {
            e.stopPropagation();
            const menu = this.parentElement.querySelector(".custom-dropdown-menu");
            document.querySelectorAll(".custom-dropdown-menu").forEach(m => {
                if (m !== menu) m.style.display = "none";
            });
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });
    });

    document.addEventListener("click", () => {
        document.querySelectorAll(".custom-dropdown-menu").forEach(m => m.style.display = "none");
    });

    document.querySelectorAll(".view-btn").forEach(btn => {
        btn.addEventListener("click", function () {

            const row = this.closest("tr");

            document.getElementById("canvasDescription").textContent =
                row.getAttribute("data-description");

            document.getElementById("canvasTotal").textContent =
                row.getAttribute("data-total");

            document.getElementById("grandTotal").textContent =
                row.getAttribute("data-total");

            document.getElementById("invoicePdfModal").style.display = "flex";
        });
    });

    document.querySelector(".close-modal").addEventListener("click", () => {
        document.getElementById("invoicePdfModal").style.display = "none";
    });

    document.getElementById("downloadPdfBtn").addEventListener("click", function () {

        const element = document.getElementById("invoicePdfContent");

        html2pdf()
            .set({
                margin: 0,
                filename: "Invoice.pdf",
                html2canvas: { scale: 3 },
                jsPDF: { unit: "in", format: "a4", orientation: "portrait" }
            })
            .from(element)
            .save();
    });

});
</script>
@endpush
