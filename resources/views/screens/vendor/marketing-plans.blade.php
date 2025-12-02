@extends('layouts.vendor.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"/>

    <style>
        /* .swiper {
            width: 100%;
            margin: 0 auto;
            padding: 20px 0;
        }

        .swiper-slide {
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            padding: 30px;
        }

        .media-boost-wrapper {
            display: flex;
            justify-content: space-between;
            width: 100%;
        } */

        .media-boost,
        .Monthly-Plan {
            width: 48%;
        }

        /* .unique-price {
            font-size: 32px;
            font-weight: bold;
            color: #145;
        }

        .boost-para {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .boost-mini-para {
            font-size: 14px;
            color: #444;
        } */

        .current-plan {
            /* color: #c28a00; */
            /* font-weight: bold; */
            text-decoration: underline;
            margin-top: 10px;
            display: inline-block;
        }

        /* Swiper button styling (optional custom design) */
        .swiper-button-next,
        .swiper-button-prev {
            color: #365e63;
        }
        
        
        
        /* ABCD */
        
        /* Backdrop + z-index */
        .modal-backdrop {
            z-index: 1040 !important;
        }

        #stripePaymentModal {
            z-index: 1060 !important;
        }

        /* Dialog width + center */
        #stripePaymentModal .modal-dialog {
            max-width: 900px;
            margin: 1.75rem auto;
        }

        @media (max-width: 767.98px) {
            #stripePaymentModal .modal-dialog {
                max-width: 100%;
                margin: .5rem auto;
            }
        }

        /* ---------- PREMIUM STRIPE-STYLE LOOK ---------- */

        /* Modal content */
        .payment-modal .modal-content {
            border-radius: 14px;
            border: none;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 40%, #f3f4ff 100%);
        }

        /* Header */
        .payment-modal .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            background: linear-gradient(120deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
            color: #e5e7eb;
        }

        .payment-modal .modal-title {
            font-weight: 600;
            font-size: 1.05rem;
            letter-spacing: 0.02em;
        }

        .payment-modal .modal-header small {
            color: #9ca3af !important;
            display: block;
            margin-top: 2px;
            font-size: 0.8rem;
        }

        .payment-modal .btn-close {
            filter: invert(1) grayscale(100%);
            opacity: 0.7;
        }

        .payment-modal .btn-close:hover {
            opacity: 1;
        }

        /* Body + internal scroll */
        .payment-modal .modal-body {
            padding: 1.5rem 1.5rem 1.75rem;
            max-height: calc(100vh - 180px);
            overflow-y: auto;
        }

        /* ---------- PLAN SUMMARY (Selected Plan / Amount) ---------- */

        .payment-modal .plan-detail {
            background: #ffffff;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(79, 70, 229, 0.15);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.05);
        }

        .payment-modal .plan-detail label.control-label {
            margin-bottom: 6px;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #295568;
            font-weight: 700;
        }

        .payment-modal .plan-detail p {
            margin: 0;
            font-weight: 800 !important;
            color: #0f172a;
            font-size: 1.05rem;
        }

        /* Form labels */
        .payment-modal .fld-wrp label.control-label,
        .payment-modal .expiration label.control-label,
        .payment-modal .cvc label.control-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 4px;
        }

        /* Inputs */
        .payment-modal .form-control {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
            box-shadow: none;
            transition: all 0.15s ease;
            background-color: #ffffff;
        }

        .payment-modal .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.2);
            outline: none;
        }

        .payment-modal .mini-fld .form-control {
            font-size: 0.85rem;
        }

        /* Error alert */
        .payment-modal .alert-danger {
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        /* Submit button */
        .payment-modal #stripe-submit-btn {
            border-radius: 999px;
            padding: 0.65rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 40%, #22c55e 100%);
            box-shadow: 0 14px 30px rgba(79, 70, 229, 0.35);
        }

        .payment-modal #stripe-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 40px rgba(79, 70, 229, 0.45);
        }

        .payment-modal #stripe-submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
        }

        .payment-modal .secure-note {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 6px;
            text-align: center;
        }

        /* ---------- ACTIVE PLAN HIGHLIGHT ---------- */

        .unique-pricing-box.active-plan-box {
            border: 2px solid #295568;
            box-shadow: 0 18px 40px rgba(79, 70, 229, 0.25);
            position: relative;
            transform: translateY(-4px);
        }

        .unique-pricing-box.active-plan-box .unique-price {
            color: #295568;
        }

        .current-plan-badge {
            display: inline-block;
            margin-left: 8px;
            padding: 2px 8px;
            font-size: 0.7rem;
            border-radius: 999px;
            background: rgba(34, 197, 94, 0.12);
            color: #16a34a;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        @media (max-width: 575.98px) {
            .payment-modal .modal-body {
                padding: 1.1rem 1rem 1.25rem;
            }
        }
    </style>
    
@endpush

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">My Marketing Plans</h1>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @forelse(auth()?->user()?->purchasedAds as $purchasedAd)
                    <div class="swiper-slide">
                        <div class="media-boost-wrapper">
                            <div class="media-boost">
                                <p class="boost-para"><strong>{{ $purchasedAd?->advertisement?->package_name }}</strong></p>
                                <p class="boost-mini-para">
                                    Listing Name: <strong>{{ $purchasedAd?->listing?->property_title ?? '' }}</strong>
                                </p>
                                <a href="{{ route('listing.detail', $purchasedAd?->listing?->id) }}" class="current-plan">View</a>
                            </div>
                            <div class="Monthly-Plan">
                                <div class="montly-price-wrapper">
                                    <div class="unique-price">${{ $purchasedAd?->amount ?? '' }}</div>
                                    <div>
                                        <p class="boost-para">{{ $purchasedAd?->advertisement?->type }} Plan</p>
                                        <!-- July 12th, 2025 -->
                                        <p class="boost-mini-para">Your subscription duration <strong>{{ \Carbon\Carbon::parse($purchasedAd?->from_date)->isoFormat('MMMM Do, YYYY') }} - {{ \Carbon\Carbon::parse($purchasedAd?->to_date)->isoFormat('MMMM Do, YYYY') }}</strong></p>
                                        <a href="#" class="current-plan">Current Plan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>

            <div class="d-flex justify-content-between align-items-center spec-hd">
                <h1 class="dashboard-hd">Marketing Add-ons</h1>
                @if(count(auth()?->user()?->purchasedAds) > 1)
                    <div class="boost-button-slide">
                        <div class="swiper-button-prev">
                            <i class="fa-solid fa-caret-left"></i>
                        </div>
                        <div class="swiper-button-next">
                            <i class="fa-solid fa-caret-right"></i>
                        </div>
                    </div>
                @endif
            </div>
        <div class="pricing-box-unique-wrapper">
            @forelse($advertisements->where('type','weekly') as $advertisement)
                <div class="unique-pricing-box">
                    <div class="unique-plan-header">{{ $advertisement?->package_name ?? '' }}</div>
                    <div class="unique-price">${{ $advertisement?->amount ?? '' }}</div>
                    <div class="unique-per-user">{{ $advertisement?->package_duration ?? '' }}</div>
                    <div class="unique-feature-wrapper">
                        <ul class="unique-features new-li">
                            @forelse($advertisement?->promotions as $promotion)
                            <li><i class="fa-solid fa-check"></i>
                                
                                <p class="unique-para new-css">{{ $promotion?->promotions ?? '' }}</p>
                            </li>
                            @empty
                            @endforelse
    
                        </ul>
                    </div>
                    <button type="button"
                            class="unique-upgrade-btn js-open-stripe-modal"
                            data-add-id="{{ $advertisement?->id ?? '' }}">
                        BUY NOW
                    </button>
    
                </div>
            @empty
            @endforelse
            {{-- <div class="unique-pricing-box">
                <div class="unique-plan-header">Social Media Boost</div>
                <div class="unique-price">$199</div>
                <div class="unique-per-user">Per User/listing</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features new-li">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para new-css">Promoted across social platforms <br> (Instagram,
                                Facebook, <br> LinkedIn)</p>
                        </li>

                    </ul>
                </div>
                <button type="button"
                        class="unique-upgrade-btn js-open-stripe-modal"
                        data-add-id="1">
                    BUY NOW
                </button>

            </div>

            <div class="unique-pricing-box">
                <div class="unique-plan-header">Newsletter Feature</div>
                <div class="unique-price">$249</div>
                <div class="unique-per-user">Per User/listing</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features new-li">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para new-css">Featured in weekly <br> email to 10k+ targeted <br>
                                subscribers</p>
                        </li>

                    </ul>
                </div>
                <button type="button"
                        class="unique-upgrade-btn js-open-stripe-modal"
                        data-add-id="2">
                    BUY NOW
                </button>
            </div>

            <div class="unique-pricing-box last">
                <div class="unique-plan-header">Homepage Spotlight Banner</div>
                <div class="unique-price">$499</div>
                <div class="unique-per-user">Per User/Week</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features new-li">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para new-css">Prime banner placement <br> on homepage</p>
                        </li>

                    </ul>
                </div>
                <button type="button"
                        class="unique-upgrade-btn js-open-stripe-modal"
                        data-add-id="3">
                    BUY NOW
                </button>
                
            </div> --}}
        </div>

        <h1 class="dashboard-hd">Partnership Advertising</h1>
        <div class="pricing-box-unique-wrapper">
            @forelse($advertisements->where('type','monthly') as $advertisement)
                <div class="unique-pricing-box">
                    <div class="unique-plan-header">{{ $advertisement?->package_name ?? '' }}</div>
                    <div class="unique-price">${{ $advertisement?->amount ?? '' }}</div>
                    <div class="unique-per-user">{{ $advertisement?->package_duration ?? '' }}</div>
                    <div class="unique-feature-wrapper">
                        <ul class="unique-features new-li">
                            @forelse($advertisement?->promotions as $promotion)
                            <li><i class="fa-solid fa-check"></i>
                                
                                <p class="unique-para new-css">{{ $promotion?->promotions ?? '' }}</p>
                            </li>
                            @empty
                            @endforelse
    
                        </ul>
                    </div>
                    <button type="button"
                            class="unique-upgrade-btn js-open-stripe-modal"
                            data-add-id="{{ $advertisement?->id ?? '' }}">
                        BUY NOW
                    </button>
    
                </div>
            @empty
            @endforelse
            {{-- <div class="unique-pricing-box">
                <div class="unique-plan-header">Sidebar Ad</div>
                <div class="unique-price">$749</div>
                <div class="unique-per-user">per user/Month</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features new-li">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para new-css">Displayed on search & <br> listings pages</p>
                        </li>

                    </ul>
                </div>
                <button type="button"
                        class="unique-upgrade-btn js-open-stripe-modal"
                        data-add-id="4">
                    BUY NOW
                </button>
            </div>

            <div class="unique-pricing-box">
                <div class="unique-plan-header">Front Page & Footer Sponsor Slot</div>
                <div class="unique-price">$499</div>
                <div class="unique-per-user">per user/Month</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features new-li">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para new-css">Logo + backlink on <br> every page</p>
                        </li>

                    </ul>
                </div>
                <button type="button"
                        class="unique-upgrade-btn js-open-stripe-modal"
                        data-add-id="5">
                    BUY NOW
                </button>
            </div>

            <div class="unique-pricing-box last">
                <div class="unique-plan-header">Category Exclusive Ad</div>
                <div class="unique-price">$999</div>
                <div class="unique-per-user">Per User/Month</div>
                <div class="unique-feature-wrapper">
                    <ul class="unique-features new-li">
                        <li><i class="fa-solid fa-check"></i>
                            <p class="unique-para new-css">Only one partner per <br> service category</p>
                        </li>

                    </ul>
                </div>
                <button type="button"
                        class="unique-upgrade-btn js-open-stripe-modal"
                        data-add-id="6">
                    BUY NOW
                </button>
            </div> --}}
        </div>
    </section>
    
    {{-- Stripe Payment Modal --}}
    <div class="modal fade payment-modal" id="stripePaymentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">

        <div class="modal-dialog modal-dialog-centered modal-lg justify-content-center">
            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Subscribe with Card</h5>
                        <small class="text-muted">
                            Secure payment powered by Stripe
                        </small>
                    </div>
                    <button type="button" class="btn-close btn-dark" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body fetch-form">
                    
                </div>

            </div>
        </div>
    </div>
    
@endsection

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
            $(".fetch-form").empty()
            let addId    = $(this).data('add-id');
            
            $.ajax({
                url: "{{ route('vendor.get.modal.form', ['advertisement' => ':id']) }}".replace(':id', addId),
                type: 'GET',
                // data: {
                //     _token: "{{ csrf_token() }}"
                // },
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $.LoadingOverlay('show');
                },
                success: function (response) {
                    $.LoadingOverlay('hide');
                    console.log(response)
                    if (response.status) {
                        $(".fetch-form").html(response.html);
                        if(response.advertisementType == "monthly"){
                            generateMarketingPeriod("month", "monthOnly");
                        }else{
                            generateMarketingPeriod("month-week", "monthWithWeeks", "weeks");
                        }
                        initStripeForm();
                    }
                },
                error: function (error) {
                    $.LoadingOverlay('hide');
                    let message = error.responseJSON?.message ?? error.statusText;
        
                    Swal.fire({
                        title: 'Something Went Wrong!',
                        text: message,
                        icon: 'error',
                        confirmButtonColor: '#295568',
                        confirmButtonText: 'OK'
                    });
                }
            });
            
            const modalEl = document.getElementById('stripePaymentModal');
            const modal   = new bootstrap.Modal(modalEl);
            modal.show();
        });
        
        // ---------------- MAIN FUNCTION ----------------
        function generateMarketingPeriod(type = "month-week", monthSelectId, weekSelectId) {
            const monthSelect = document.getElementById(monthSelectId);
            const weekSelect = document.getElementById(weekSelectId);
         
            const today = new Date();
            today.setDate(1);
         
            // Generate next 6 months
            for (let i = 0; i < 6; i++) {
                let m = today.getMonth() + 1;
                let y = today.getFullYear();
         
                const option = document.createElement("option");
                option.value = `${m}-${y}`;
                option.textContent = today.toLocaleString('default', { month: 'long' }) + " " + y;
                option.setAttribute("data-month", m);
                option.setAttribute("data-year", y);
         
                monthSelect.appendChild(option);
         
                today.setMonth(today.getMonth() + 1);
            }
         
            // On month select → load weeks
            monthSelect.addEventListener("change", function () {
                if (this.value === "") {
                    weekSelect.innerHTML = '<option value="">Select Week</option>';
                    weekSelect.disabled = true;
                    return;
                }
         
                weekSelect.disabled = false;
         
                const [selectedMonth, selectedYear] = this.value.split("-").map(Number);
                loadWeeksCorrect(selectedYear, selectedMonth, weekSelect);
            });
        }
         
        // ---------------- WEEK GENERATOR ----------------
        function loadWeeksCorrect(year, month, weekSelect) {
            weekSelect.innerHTML = '<option value="">Select Week</option>';
         
            const today = new Date();
            today.setHours(0,0,0,0);
         
            let date = new Date(year, month - 1, 1);
         
            // Find first Monday inside this month
            while (date.getDay() !== 1) {
                date.setDate(date.getDate() + 1);
                if (date.getMonth() !== month - 1) return;
            }
         
            let weekNumber = 1;
         
            while (date.getMonth() === month - 1) {
                const weekStart = new Date(date);
                const weekEnd = new Date(date);
                weekEnd.setDate(weekStart.getDate() + 6);
         
                // ❌ Skip if whole week is before today (no future)
                if (weekEnd < today) {
                    date.setDate(date.getDate() + 7);
                    continue;
                }
         
                const option = document.createElement("option");
                option.value = `week-${weekNumber}`;
                option.textContent =
                    `Week ${weekNumber} (${formatDate(weekStart)} to ${formatDate(weekEnd)})`;
         
                option.setAttribute("data-week-start", formatDate(weekStart));
                option.setAttribute("data-week-end", formatDate(weekEnd));
         
                weekSelect.appendChild(option);
         
                date.setDate(date.getDate() + 7);
                weekNumber++;
            }
        }
         
        // ---------------- FORMAT DATE ----------------
        function formatDate(date) {
            let d = date.getDate().toString().padStart(2, '0');
            let m = (date.getMonth() + 1).toString().padStart(2, '0');
            let y = date.getFullYear();
            return `${d}-${m}-${y}`;
        }


        function initStripeForm() {
            
            var $form = $(".require-validation");
            var $closeBtn = $('#stripePaymentModal .btn-close');
            
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
                $closeBtn.prop('disabled', true).addClass('disabled');

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
                    $closeBtn.prop('disabled', false).removeClass('disabled');

                    // Toast for Stripe error
                    showToastjQuery("Payment Error", response.error.message, "error");
                } else {
                    var token = response['id'];

                    // Add token field to form
                    var formData = new FormData($form[0]);
                    formData.append('stripeToken', token);

                    // $ //form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");

                    // Optional: info toast right before AJAX
                    showToastjQuery("Processing", "Completing your advertisement subscription...", "info");

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,   // ⛔ VERY IMPORTANT
                        contentType: false,   // ⛔ VERY IMPORTANT
                        success: function (response) {
                            $('#stripe-submit-btn').prop('disabled', false).text('Pay & Purchase Advertisement');
                            
                            if (response.status) {
                                showToastjQuery("Success", response.msg, "success");
                                if (response.redirect_url) {
                                    setTimeout(() => {
                                        window.location.href = response.redirect_url;
                                    }, 1000);
                                }
                            } else {
                                $closeBtn.prop('disabled', false).removeClass('disabled');
                                showToastjQuery("Error", response.msg || "Something went wrong.", "error");
                            }
                        },
                        error: function (xhr) {
                            $('#stripe-submit-btn').prop('disabled', false).text('Pay & Subscribe');
                            $closeBtn.prop('disabled', false).removeClass('disabled');
                            let message = "Unexpected error.";
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.msg) {
                                message = xhr.responseJSON?.message ?? xhr.responseJSON.msg;
                            } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                                message = xhr.responseJSON?.message ?? xhr.responseJSON.msg;
                            }

                            showToastjQuery("Payment Error", message, "error");
                        }
                    });
                }
            }

        }
    </script>

    <script>
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            slidesPerView: 1,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
@endpush
