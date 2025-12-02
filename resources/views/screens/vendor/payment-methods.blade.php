@extends('layouts.vendor.app')

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Payment Method</h1>
        <!-- Price Options -->

        <div class="propFormContainer">
            <h2 class="view-hd">Payment Details</h2>
            <div class="propFormGroup">
                <label for="property-title" class="propLabel requiredMark">Card Holder Name</label>
                <input type="text" id="property-title" name="property-title" class="propInput" placeholder="Card Holder Name" />
            </div>

            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label for="description" class="propLabel requiredMark">Card Number</label>
                    <input type="number" id="property-title" name="property-title" class="propInput" placeholder="Card Number" />

                </div>
                <div class="propFormGroup">
                    <label for="description" class="propLabel requiredMark">Expiry Date</label>
                    <input type="date" id="property-title" name="property-title"  class="propInput" />
                </div>
            </div>
            <div class="prop-wrapper">
                <div class="propFormGroup">
                    <label for="description" class="propLabel requiredMark">CCV</label>
                    <input type="number" id="property-title" name="property-title" class="propInput"
                        placeholder="CCV">

                </div>
                <div class="propFormGroup">
                    <label for="description" class="propLabel requiredMark">Zip/Postal Code</label>
                    <input type="number" id="property-title" name="property-title" class="propInput" placeholder="Zip/Postal Code" />
                </div>
            </div>
            <button type="submit" class="add-btn">Pay $149</button>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- <script>
        const fileInput = document.getElementById("custom-file-input");
        const fileList = document.getElementById("custom-file-list");

        fileInput.addEventListener("change", function () {
            const files = Array.from(fileInput.files);

            files.forEach((file) => {
                const item = document.createElement("div");
                item.className = "custom-file-item";

                const fileName = document.createElement("span");
                fileName.textContent = file.name;

                const removeBtn = document.createElement("button");
                removeBtn.innerHTML = "×";
                removeBtn.onclick = () => item.remove();

                item.appendChild(fileName);
                item.appendChild(removeBtn);
                fileList.appendChild(item);
            });

            fileInput.value = ""; // Clear input
        });
    </script> --}}
@endpush
