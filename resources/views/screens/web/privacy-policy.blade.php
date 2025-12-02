@extends('layouts.web.app')

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Privacy & Policy" :route="route('index')" />
    <section class="co-op-diff-sec">
        <div class="container">
            <p class="sec-para p-0"><b>Effective Date:</b> August 29, 2025</p>
            <p class="sec-para">At CooperativeShares.com, we respect your privacy and are committed to protecting the
                personal information you provide. This Privacy Policy explains what information we collect, how we use it,
                and your rights regarding your data when using our website. By accessing or using our site, you agree to the
                practices outlined below.</p>

            <!-- <h5 class="diff-para">1. Acceptance of Terms</h5>
                <h5 class="diff-para1">Members own shares, not individual units.</h5> -->

            <h5 class="diff-para">1. Information We Collect</h5>
            <h5 class="diff-para1 mb-4">We may collect the following information:</h5>
            <ul class="diff-listing">
                <li>
                    <p class="diff-para1 text-start"><b>Personal Information:</b> Name, email address, phone number, and any
                        details you provide when contacting us or subscribing to newsletters.</p>
                </li>
                <li>
                    <p class="diff-para1 text-start"><b>Technical Information:</b> IP address, browser type, operating
                        system, and
                        website usage information to help improve site functionality.</p>
                </li>
                <li>
                    <p class="diff-para1 text-start"><b>Optional Information:</b> Preferences or messages provided when
                        submitting
                        forms, inquiries, or feedback.</p>
                </li>
            </ul>
            <h5 class="diff-para">2. How We Use Your Information</h5>
            <h5 class="diff-para1 mb-4">Your information is used to:</h5>
            <ul class="diff-listing">
                <li>
                    <p class="diff-para1 text-start">Respond to questions, requests, and feedback.</p>
                </li>
                <li>
                    <p class="diff-para1 text-start">Provide and improve our services and website features.</p>
                </li>
                <li>
                    <p class="diff-para1 text-start">Send updates, newsletters, or important communications if you opt in.
                    </p>
                </li>
                <li>
                    <p class="diff-para1 text-start">Monitor and prevent fraudulent or unauthorized activity.</p>
                </li>
            </ul>
            <h5 class="diff-para">3. Sharing of Information</h5>
            <h5 class="diff-para1 mb-4">We do not sell, trade, or rent your personal information. We may share information
                only:</h5>
            <ul class="diff-listing">
                <li>
                    <p class="diff-para1 text-start">With service providers that support website operations or
                        communications.</p>
                </li>
                <li>
                    <p class="diff-para1 text-start">When required by law, legal process, or regulatory authorities.</p>
                </li>
                <li>
                    <p class="diff-para1 text-start">To protect the rights, property, or safety of our company or users.</p>
                </li>
            </ul>

            <h5 class="diff-para">4. Cookies and Tracking</h5>
            <h5 class="diff-para1">Our website uses cookies and similar technologies to enhance user experience, monitor
                site performance, and personalize content. You can adjust your browser settings to manage cookies.</h5>

            <h5 class="diff-para">5. Data Security</h5>
            <h5 class="diff-para1 mb-4">We implement appropriate technical and administrative measures to protect your
                information from unauthorized access, disclosure, alteration, or destruction. While we strive to secure your
                data, no method of online transmission can guarantee 100% security.</h5>

            <h5 class="diff-para">6. Third-Party Links</h5>
            <h5 class="diff-para1">Our website may include links to external websites. We are not responsible for the
                privacy practices or content of these third-party sites. We encourage you to review their privacy policies.
            </h5>

            <h5 class="diff-para">7. Children’s Privacy</h5>
            <h5 class="diff-para1">Our site is not intended for children under 13 years old. We do not knowingly collect
                personal information from children. If we become aware of such data, we will promptly delete it.</h5>
            <h5 class="diff-para">8. Your Rights</h5>
            <h5 class="diff-para1 mb-4">You may:</h5>
            <ul class="diff-listing">
                <li>
                    <p class="diff-para1 text-start">Access or update your personal information.</p>
                </li>
                <li>
                    <p class="diff-para1 text-start">Opt out of receiving marketing communications.</p>
                </li>
                <li>
                    <p class="diff-para1 text-start">Request deletion of your data, subject to legal or contractual
                        obligations.</p>
                </li>
            </ul>
            <h5 class="diff-para1">To exercise these rights, please contact us at <a
                    href="mailto:info@cooperativeshares.com" class="text-black">info@cooperativeshares.com</a></h5>

            <h5 class="diff-para">9. Policy Updates</h5>
            <h5 class="diff-para1">We may update this Privacy Policy occasionally. Any changes will be posted on this page
                with an updated effective date. We encourage users to review the policy periodically.</h5>

            <h5 class="diff-para">Contact Us</h5>
            <h5 class="diff-para1">For questions or concerns regarding this Privacy Policy, please reach out:</h5>
            <ul class="diff-listing">
                <li>
                    <p class="diff-para1 text-start"><b>Email : </b><a href="mailto:info@cooperativeshares.com"
                            class="text-black">info@cooperativeshares.com</a></p>
                </li>
                <li>
                    <p class="diff-para1 text-start"><b>Phone : </b><a href="tel:8165297022" class="text-black">(816)
                            529-7022</a></p>
                </li>
                <li>
                    <p class="diff-para1 text-start"><b>Address : </b>520 E. 4th St, Tonganoxie, KS 66086</p>
                </li>
            </ul>
        </div>
    </section>
    <x-partner-section />
@endsection
