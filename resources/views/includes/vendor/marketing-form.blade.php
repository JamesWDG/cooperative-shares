<form role="form" action="{{ route('vendor.marketing.plans.purchase.ad',$advertisement?->id) }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form" enctype="multipart/form-data">
    @csrf

    {{-- Hidden Add id --}}
    <input type="hidden" name="add_id" value="{{ $advertisement?->id }}" id="stripe_plan_id">

    {{-- Selected Plan --}}
    <div class="required plan-detail">
        <label class="control-label">Selected Advertisement:</label>
        <p class="mb-1">
            <span id="add_name_text">{{ $advertisement?->package_name }}</span>
        </p>
    </div>

    {{-- Amount --}}
    <div class="mb-3 required plan-detail">
        <label class="control-label">Amount:</label>
        <p class="mb-0">
            <span id="add_price">{{ $advertisement?->amount }}</span>
        </p>
    </div>
    <div class="mb-3 required plan-detail" style="display: block !important;">
        @if($advertisement?->type == "monthly")
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <label for="monthOnly" style="font-size: 0.8rem; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Select Month</label>
            <select name="month" id="monthOnly" class="w-100"></select>
        </div>
        @else
        <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 25px;">
            <label for="monthWithWeeks" style="font-size: 0.8rem; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Select Month</label>
            <select name="month" id="monthWithWeeks" class="w-100">
                <option value="">Select Month</option>
            </select>
        </div>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <label for="weeks" style="font-size: 0.8rem; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Select Week</label>
            <select name="week" id="weeks" class="w-100" disabled>
                <option value="">Select Week</option>
            </select>
        </div>    
        @endif
    </div>
    <div class="mb-3 required plan-detail" style="display: flex; flex-direction: column; gap: 10px; align-items: start;">
        <label for="listt" style="font-size: 0.8rem; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Select List for Marketing</label>
        <select name="listing_id" id="listt" class="w-100" required>
            @foreach(auth()?->user()?->listings as $listing)
            <option value="{{ $listing?->id }}">
                {{ $listing?->property_title }}
            </option>
            @endforeach
        </select>
    </div>
    @if($advertisement?->require_banner)
        <div class="mb-3 required plan-detail" style="flex-direction: column;gap:10px !important;">
            <label for="listImage" class="image-label" style="font-size: 0.8rem !important;font-weight: 600 !important;color: #4b5563;margin-bottom: 4px;width: 100%;">
                Width : {{ $advertisement?->banner_width ?? '' }} & Height : {{ $advertisement?->banner_height ?? '' }}
            </label>
            <input type="file" id="listImage" name="listing_image" required/>
            
            <!-- div bnani h for image preview -->
        </div>
    @endif
    {{-- Name on Card --}}
    <div class="mb-3 required fld-wrp">
        <label class="control-label">Name on Card</label>
        <input class="form-control" type="text" name="card_name" placeholder="John Doe">
    </div>

    {{-- Card Number --}}
    <div class="mb-3 required fld-wrp">
        <label class="control-label">Card Number</label>
        <input
            autocomplete="off"
            class="form-control card-number"
            type="text"
            placeholder="4242 4242 4242 4242">
    </div>

    <div class="row g-3 mini-fld">
        {{-- Expiry Month --}}
        <div class="col-6 col-md-4 required expiration">
            <label class="control-label">Expiration Month</label>
            <input
                class="form-control card-expiry-month"
                placeholder="MM"
                type="text">
        </div>

        {{-- Expiry Year --}}
        <div class="col-6 col-md-4 required expiration">
            <label class="control-label">Expiration Year</label>
            <input
                class="form-control card-expiry-year"
                placeholder="YYYY"
                type="text">
        </div>

        {{-- CVC --}}
        <div class="col-12 col-md-4 required cvc">
            <label class="control-label">CVC</label>
            <input autocomplete="off" class="form-control card-cvc" placeholder="CVC" type="text">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 error form-group hide">
            <div class="alert alert-danger">
                Please correct the errors and try again.
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary w-100 justify-content-center"
                type="submit"
                id="stripe-submit-btn">
            Pay &amp; Purchase AD
        </button>
        {{-- <p class="secure-note">Your payment details are encrypted and processed securely by Stripe.</p> --}}
    </div>

</form>