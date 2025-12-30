<footer class="bg-slate-100 border-t border-slate-200 mt-20">

    <!-- Help Banner -->
    <div class="bg-rose-600 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-semibold mb-4">
                Need help to set up your free fundraiser?
            </h3>
            <a href="#"
               class="inline-flex items-center gap-2 bg-white text-rose-600 px-6 py-3 rounded-full font-medium shadow help-btn">
                <i class="fas fa-phone"></i>
                Request a call
            </a>
        </div>
    </div>

    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <!-- Column 1 -->
            <div>
                <h4 class="font-semibold text-slate-900 mb-4">FundHive</h4>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="#" class="footer-link">About us</a></li>
                    <li><a href="#" class="footer-link">Team</a></li>
                    <li><a href="#" class="footer-link">Careers</a></li>
                    <li><a href="#" class="footer-link">Contact</a></li>
                    <li><a href="#" class="footer-link">Resources</a></li>
                    <li><a href="#" class="footer-link">Blog</a></li>
                </ul>
            </div>

            <!-- Column 2 -->
            <div>
                <h4 class="font-semibold text-slate-900 mb-4"> Address</h4>
                <p class="text-sm text-slate-600 leading-relaxed">
                    FundHive <br>
                    Kathmandu, Nepal<br>
                    Support: support@fundhive.com
                </p>

                <div class="mt-6">
    <h5 class="font-medium text-slate-800 mb-2 text-sm">Supported payments</h5>
    <div class="flex gap-3 items-center">
        <img src="{{ asset('assets/img/esewa.png') }}" alt="eSewa" class="payment-icon">
        <img src="{{ asset('assets/img/khalti.png') }}" alt="Khalti" class="payment-icon">
        <img src="{{ asset('assets/img/fonepay.jpeg') }}" alt="FonePay" class="payment-icon">
    </div>
</div>

            </div>

            <!-- Column 3 -->
            <div>
                <h4 class="font-semibold text-slate-900 mb-4">Start fundraising</h4>
                <a href="{{ route('campaigns.create') }}"
                   class="inline-block bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-md font-medium mb-4 transition">
                    Start a fundraiser
                </a>

                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="#" class="footer-link">Pricing</a></li>
                    <li><a href="#" class="footer-link">Reviews</a></li>
                    <li><a href="#" class="footer-link">FAQs & tips</a></li>
                </ul>

                <div class="flex gap-4 mt-6">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="bg-slate-900 text-slate-300 text-sm py-4">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-3">
            <p>
                © {{ date('Y') }} FundHive. All rights reserved.
            </p>
            <div class="flex gap-6">
                <a href="#" class="footer-link text-slate-300 hover:text-white">Security & Privacy</a>
                <a href="#" class="footer-link text-slate-300 hover:text-white">Terms of use</a>
            </div>
        </div>
    </div>

</footer>
