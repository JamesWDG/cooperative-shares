@extends('layouts.vendor.app')

@push('styles')
    {{-- Toast CSS --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"/>

    <style>
        
        .modal-backdrop {
            z-index: 1040 !important;
        }
        #stripePaymentModal {
            z-index: 1060 !important;
        }
        #stripePaymentModal .modal-dialog {
            max-width: 900px; /* Wider modal */
            margin: 1.75rem auto; /* CENTER horizontally */
        }
        @media (max-width: 767.98px) {
            #stripePaymentModal .modal-dialog {
                max-width: 100%;
                margin: .5rem auto;
            }
        }
        
    </style>
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">My Subscription</h1>

        <div class="media-boost-wrapper">
            <div class="media-boost">
                <p class="boost-para">
                    @if($activePlan)
                        Current Subscription ({{ $activePlan->name }})
                    @else
                        No Active Subscription
                    @endif
                </p>

                {{-- Bullet points for active plan --}}
                @if($activePlan && !empty($activePlanBullets))
                    <ul style="margin-top: 8px; padding-left: 20px;">
                        @foreach($activePlanBullets as $bullet)
                            <li class="boost-mini-para">{{ $bullet }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="boost-mini-para">
                        You don't have an active subscription yet. Please choose a plan below to start.
                    </p>
                @endif
            </div>

            <div class="Monthly-Plan">
                <div class="montly-price-wrapper">
                    <div class="unique-price">
                        @if($activePlan)
                            ${{ number_format($activePlan->price, 2) }}
                        @else
                            $0.00
                        @endif
                    </div>
                    <div>
                        <p class="boost-para">Monthly Plan</p>

                        <p class="boost-mini-para">
                            @if($activeSubscription && $activeSubscription->expires_at)
                                Your Subscription will be expired at
                                {{ $activeSubscription->expires_at->format('F jS, Y') }}
                            @else
                                No active expiry date found.
                            @endif
                        </p>

                        @if($activePlan)
                            <a href="#subscription-plans" class="current-plan">Change Plan</a>
                        @else
                            <a href="#subscription-plans" class="current-plan">Choose Plan</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <h1 class="dashboard-hd" id="subscription-plans">Subscription Plans</h1>

        <div class="pricing-box-unique-wrapper">
            @foreach($plans as $plan)
                @php
                    $isCurrent = $activePlan && $activePlan->id === $plan->id;

                    if ($activePlan) {
                        if ($isCurrent) {
                            $btnLabel = 'Current Plan';
                        } elseif ($plan->price > $activePlan->price) {
                            $btnLabel = 'Upgrade';
                        } else {
                            $btnLabel = 'Downgrade';
                        }
                    } else {
                        $btnLabel = 'Choose Plan';
                    }

                    $nameLower = strtolower($plan->name);
                @endphp

                <div class="unique-pricing-box {{ $loop->first ? 'selected' : '' }} {{ $loop->last ? 'last' : '' }}">
                    <div class="unique-plan-header">{{ strtoupper($plan->name) }}</div>
                    <div class="unique-price">${{ number_format($plan->price, 2) }}</div>
                    <div class="unique-per-user">Per User/Month</div>

                    <div class="unique-feature-wrapper">
                        <ul class="unique-features">
                            {{-- Standard listings --}}
                            <li>
                                <i class="fa-solid fa-check"></i>
                                <p class="unique-para">
                                    @if(!is_null($plan->standard_limit))
                                        Up to {{ $plan->standard_limit }} Active Listings
                                    @else
                                        Unlimited Listings
                                    @endif
                                </p>
                            </li>

                            {{-- Featured rule --}}
                            <li>
                                <i class="fa-solid fa-check"></i>
                                <p class="unique-para">
                                    @if($plan->featured_free_limit == 0)
                                        Featured listings available as pay-per-slot
                                    @else
                                        {{ $plan->featured_free_limit }} Featured Slots (free)
                                    @endif
                                </p>
                            </li>

                            {{-- Analytics --}}
                            <li>
                                <i class="fa-solid fa-check"></i>
                                <p class="unique-para">
                                    @if($nameLower === 'basic')
                                        Standard Analytics
                                    @else
                                        Advanced Analytics
                                    @endif
                                </p>
                            </li>

                            {{-- Support --}}
                            <li>
                                <i class="fa-solid fa-check"></i>
                                <p class="unique-para">
                                    @if($nameLower === 'basic')
                                        Email Support
                                    @else
                                        Priority Support
                                    @endif
                                </p>
                            </li>

                            {{-- Co-Op only if allow_coop = 1 (Premium) --}}
                            @if($plan->allow_coop)
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    <p class="unique-para">Custom Co-op Page Design (blogs posting)</p>
                                </li>
                            @endif
                        </ul>
                    </div>

                    {{-- Button: current plan disabled, others upgrade/downgrade --}}
                    @if($isCurrent)
                        <button class="unique-upgrade-btn active disabled" aria-disabled="true">
                            {{ $btnLabel }}
                        </button>
                    @else
                        <button type="button"
                                class="unique-upgrade-btn js-open-stripe-modal"
                                data-plan-id="{{ $plan->id }}"
                                data-plan-name="{{ $plan->name }}"
                                data-plan-price="{{ $plan->price }}">
                            {{ $btnLabel }}
                        </button>
                    @endif

                </div>
            @endforeach
        </div>

    </section>
    <div class="modal fade payment-modal"
     id="stripePaymentModal"
     tabindex="-1"
     aria-hidden="true"
     data-bs-backdrop="static"
     data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg justify-content-center">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Subscribe with Card</h5>
                    <small class="text-muted" style="font-size: 13px;">
                        Secure payment powered by Stripe
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form
                    role="form"
                    action="{{ route('vendor.stripe.post') }}" {{-- route name from vendor.php --}}
                    method="post"
                    class="require-validation"
                    data-cc-on-file="false"
                    data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                    id="payment-form">

                    @csrf

                    {{-- hidden plan id --}}
                    <input type="hidden" name="plan_id" id="stripe_plan_id">

                    {{-- Hidden but still filled for reference in JS --}}
                    <input type="hidden" id="stripe_plan_name" />
                    <input type="hidden" id="stripe_plan_price" />

                    <div class="required plan-detail">
                        <label class='control-label'>Selected Plan:</label>
                        <p class="mb-1">
                            <span id="stripe_plan_name_text">—</span>
                        </p>
                    </div>

                    <div class="mb-3 required plan-detail">
                        <label class='control-label'>Amount:</label>
                        <p class=" mb-0" style="font-size: 13px;">
                            <span id="stripe_plan_price_text">—</span>
                        </p>
                    </div>

                    <div class="mb-3 required fld-wrp">
                        <label class='control-label'>Name on Card</label>
                        <input class='form-control' type='text' name="card_name" placeholder="John Doe">
                    </div> 
 
                    <div class="mb-3 required fld-wrp">
                        <label class='control-label'>Card Number</label>
                        <input
                            autocomplete='off'
                            class='form-control card-number'
                            type='text'
                            placeholder="4242 4242 4242 4242">
                    </div>

                    <div class="row g-3 mini-fld">
                        <div class='col-6 col-md-4 required expiration'>
                            <label class='control-label'>Expiration Month</label>
                            <input
                                class='form-control card-expiry-month'
                                placeholder='MM'
                                type='text'>
                        </div>

                        <div class='col-6 col-md-4 required expiration'>
                            <label class='control-label'>Expiration Year</label>
                            <input
                                class='form-control card-expiry-year'
                                placeholder='YYYY'
                                type='text'>
                        </div>

                        <div class='col-12 col-md-4 required cvc'>
                            <label class='control-label'>CVC</label>
                            <input
                                autocomplete='off'
                                class='form-control card-cvc'
                                placeholder='CVC'
                                type='text'>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class='col-md-12 error form-group hide'>
                            <div class='alert-danger alert'>
                                Please correct the errors and try again.
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary w-100 justify-content-center" type="submit" id="stripe-submit-btn">
                            Pay &amp; Subscribe
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
    </div> 
@endsection

{{-- Stripe Payment Modal OUTSIDE section taake properly center ho --}} 


@push('scripts')
    {{-- Toast JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        function showToastjQuery(title_text, message, iconClass) {
            $.toast({
                heading: title_text,
                text: message,
                position: 'top-right',
                showHideTransition: 'slide',
                icon: iconClass,
                loaderBg: '#30809B',
            });
        }

        // Plan button click → modal open + plan set
        $(document).on('click', '.js-open-stripe-modal', function () {
            const planId    = $(this).data('plan-id');
            const planName  = $(this).data('plan-name');
            const planPrice = $(this).data('plan-price');

            const formattedPrice = '$' + parseFloat(planPrice).toFixed(2);

            // hidden fields
            $('#stripe_plan_id').val(planId);
            $('#stripe_plan_name').val(planName + ' Plan');
            $('#stripe_plan_price').val(formattedPrice);

            // display text
            $('#stripe_plan_name_text').text(planName + ' Plan');
            $('#stripe_plan_price_text').text(formattedPrice);

            const modalEl = document.getElementById('stripePaymentModal');
            const modal   = new bootstrap.Modal(modalEl);
            modal.show();
        });

        $(function() {

            var $form = $(".require-validation");

            $('form.require-validation').on('submit', function(e) {
                e.preventDefault(); // Always prevent default

                var inputSelector = [
                        'input[type=email]',
                        'input[type=password]',
                        'input[type=text]',
                        'input[type=file]',
                        'textarea'
                    ].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;

                $errorMessage.addClass('hide');
                $('.has-error').removeClass('has-error');

                // simple required validation
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        valid = false;
                    }
                });

                if (!valid) {
                    showToastjQuery("Validation Error", "Please fill in all required fields.", "error");
                    return;
                }

                $('#stripe-submit-btn').prop('disabled', true).text('Processing...');

                Stripe.setPublishableKey($form.data('stripe-publishable-key'));

                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);

                    $('#stripe-submit-btn').prop('disabled', false).text('Pay & Subscribe');

                    // Toast for Stripe error
                    showToastjQuery("Payment Error", response.error.message, "error");
                } else {
                    var token = response['id'];

                    // Add token field to form
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");

                    // Optional: info toast right before AJAX
                    showToastjQuery("Processing", "Completing your subscription...", "info");

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: $form.serialize(),
                        success: function (response) {
                            $('#stripe-submit-btn').prop('disabled', false).text('Pay & Subscribe');

                            if (response.status) {
                                showToastjQuery("Success", response.msg, "success");
                                if (response.redirect_url) {
                                    setTimeout(() => {
                                        window.location.href = response.redirect_url;
                                    }, 1000);
                                }
                            } else {
                                showToastjQuery("Error", response.msg || "Something went wrong.", "error");
                            }
                        },
                        error: function (xhr) {
                            $('#stripe-submit-btn').prop('disabled', false).text('Pay & Subscribe');

                            let message = "Unexpected error.";
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.msg) {
                                message = xhr.responseJSON.msg;
                            } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                                message = xhr.responseJSON.msg;
                            }

                            showToastjQuery("Payment Error", message, "error");
                        }
                    });
                }
            }

        });
    </script>
@endpush
