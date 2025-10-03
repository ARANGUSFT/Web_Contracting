<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="container py-5">
            <div class="text-center mb-5">
                <h5 class="fw-bold text-primary">Welcome back</h5>
                <p class="text-muted mb-3">Please log in to access your dashboard</p>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-person-plus me-1"></i> Create account
                    </a>
                @endif
            </div>

            <!-- Login Card -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-body p-4">

                            @if (session('status'))
                                <div class="alert alert-success mb-3" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" name="password" class="form-control" required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">Remember me</label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">Forgot your password?</a>
                                @endif

                                <button type="submit" class="btn btn-primary px-4">Log in</button>
                            </div>

                            <!-- Enlace que abre el Modal -->
                            <div class="text-center mt-4">
                                <a href="#" class="text-muted small text-decoration-none" data-bs-toggle="modal" data-bs-target="#privacyModal">
                                    Privacy Policy
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal de Política de Privacidad -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        {{-- Aquí puedes cargar contenido estático o dinámico --}}
                     <p>By using or accessing this website, you acknowledge and accept the Privacy Policy set forth below. If you do not agree to this policy, please do not use this website. Contracting Alliance may revise this policy at any time by updating this publication, and your use after such change means that you accept the modified terms. Please check this policy periodically for changes. This policy is intended to help you understand how Contracting Alliance collects, uses, and protects the information you provide on our website. This Privacy Policy does not apply to the use or disclosure of information that we collect or obtain through means other than this website.</p>

                        <p>
                            <strong>Personal Information.</strong> If you browse our website, you can generally do so anonymously without providing any personal information. However, there are cases where we collect personal data; you will know that this data is collected because you will have to fill out a form. 
                            <br><br>
                            We collect personally identifiable and transactional information when you: 
                            <br>(1) purchase products or services from our site; 
                            <br>(2) register for an account; 
                            <br>(3) request to receive additional information about our products and services; 
                            <br>(4) subscribe to our newsletter; 
                            <br>(5) send us a question. 
                            <br><br>
                            If you choose not to provide the information we request, you can still visit most of our website, but you may not be able to access certain options, offers, and services. 
                            <br><br>
                            In the event that you change your mind or wish to update or delete your personal information, we will do our best to correct, update, or delete the personal data you provide to us. You can do this by contacting us at the contact points specified in the contact section.
                        </p>

                        <p><strong>Orders.</strong> When you place an order for a product, we need to know your contact/shipping information, including email address. This information is necessary for us to process and complete your order and send you an order confirmation, as well as to notify you of the status of your order.</p>

                        <p><strong>Marketing Messages.</strong> When you purchase products from us online, request product information, or provide us with personal information, we may place you on our contact list. We may send you direct mail and emails related to our company, our products or services, special offers, or important matters. If you do not wish to receive these messages, you can use the opt-out method detailed in the message, reply to any emails indicating that you do not wish to receive communications in the future, or contact us at the specified points of contact.</p>

                        <p><strong>Projects for Customers.</strong> In some cases, Contracting Alliance will have been engaged to collect personal information on behalf of a customer, such as through a survey. In such cases, we will provide the information we collect to our client in accordance with their instructions. We are not responsible for the content of any survey (or other information provided by our client) or the use of information collected by our client (or the privacy practices of that client).</p>

                        <p><strong>Use and Sharing of Personal Data.</strong> We use the personal information that is collected through our website to better serve our customers and users, personalize your website experience, and improve the content of our website. Contracting Alliance will use the personal information you provide to us for marketing and promotional purposes only. We may share your personal information in the following ways: with a Contracting Alliance customer if that customer has engaged us to collect personal information on their behalf, such as through a survey; with external agents that we have contracted to help us provide a good or service that you have requested; or in the Special Cases detailed below. We do not rent or sell your personally identifiable information entered on this website to third parties. We may use personal information about you and your visits to our website to send you advertisements on our web pages or in emails related to Contracting Alliance or its products or services. We may share non-personal or aggregated statistical information about our users with advertisers, business partners, sponsors, and other third parties. This data is used to personalize the content and advertising on our website to provide a better experience for our users.</p>

                        <p><strong>Security.</strong> While we use reasonable efforts to safeguard the confidentiality of your information, we cannot guarantee that data will always remain secure due to transmission errors, outside events, third-party hacking, or other causes. We will comply with all privacy laws and make any legally required disclosures regarding breaches of the security, confidentiality, or integrity of personal data consistent with our ability to determine the scope of a breach and our obligations to law enforcement.</p>

                        <p><strong>Cookies, Web Analytics, and IP Tracking.</strong> Our web servers collect general data pertaining to each website user, including their IP address, domain name, referring web page, and the length of time spent and the pages accessed while visiting this website. Some of this information may be used to infer your geographic location. Web usage information is collected to help us manage and administer our website, improve the content of our website, and customize and improve the website user experience. Web analytic information is gathered using the following methods: (1) cookies, (2) conversion tracking, and (3) general detection and use of your internet protocol (IP) address or domain name.</p>

                        <p><strong>Cookies.</strong> A cookie is a small file stored on your computer by a website to give you a unique ID. We use cookies to track new visitors to this site and to recognize past users so that we may customize and personalize content. Cookies used by this site do not contain any personally identifiable information. If for any reason you don’t want to take advantage of cookies, you may set your browser to not accept them, although this may disable or render unusable some of the features of our site.</p>

                        <p><strong>Conversion Tracking.</strong> Search engines offer a feature called “conversion tracking,” which is a way to track clicks to sales from either search results or ads on search engines. Using either web beacons or visible images, depending upon the search engine, the search engine notes and saves information in a cookie with non-personal information such as time of day, browser type, browser language, and IP address with each query. Information is gathered in the aggregate, without unique personal data. Conversion tracking allows the search engine company and Contracting Alliance to track clicks to sales, including the number of clicks (“visits”) it takes before a purchase is made and permits us to measure the effectiveness of our search engine participation.</p>

                        <p><strong>Consent to Transfer.</strong> This website is operated in the United States. If you are located in the European Union, Canada, or elsewhere outside of the United States, please be aware that any information you provide to Contracting Alliance will be transferred to the United States. By using this website or providing us with your information, you consent to this transfer.</p>

                        <p><strong>Special Cases.</strong> Contracting Alliance reserves the right to disclose user information in special cases, when we have reason to believe that disclosing this information is necessary to identify, contact, or bring legal action against someone who may be causing injury to or interference with (either intentionally or unintentionally) our rights or property, other Contracting Alliance website users, or anyone else who could be harmed by such activities. We may disclose personal information without notice to you in response to a subpoena or when we believe in good faith that the law permits it or to respond to an emergency situation. In the event Contracting Alliance or its subsidiaries or affiliates or their assets are sold, merged, or otherwise involved in a corporate transaction, your personal information will likely be transferred as part of that transaction. We reserve the right to transfer your information without your consent in such a situation; provided that we will make reasonable efforts to see that your privacy preferences are honored by the transferee. Specific areas or pages of this website may include additional or different provisions relating to collection and disclosure of personal information. In the event of a conflict between such provisions and this Privacy Policy, such specific terms shall control.</p>

                        <p><strong>Policies for Children.</strong> Contracting Alliance does not knowingly collect or use any personal information from users under 18 years of age. No information should be submitted to this site by guests under 18 years of age, and guests under 18 years old are not allowed to register for our accounts, contests, newsletters, or activities.</p>

                        <p><strong>Linked Sites.</strong> Please be advised that our website contains links to third-party websites. The linked sites are not under our control, and we are not responsible for the contents or privacy practices of any linked site or any link on a linked site.</p>

                        <p><strong>Changes to This Policy.</strong> Contracting Alliance reserves the right to change or update this policy, or any other policy or practice, at any time, with reasonable notice to users of its website. Any changes or updates will be effective immediately upon posting to our website.</p>

                        <p><strong>Contact Information:</strong> CONTRACTING ALLIANCE INC — Attn: President.</p>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
