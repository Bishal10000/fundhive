<nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('assets/img/logo.png') }}"
                     alt="FundHive"
                     class="w-9 h-9 object-contain">
                <span class="text-xl font-bold text-slate-900">FundHive</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
                <a href="{{ route('campaigns.index') }}" class="nav-link">Donate</a>
                <a href="{{ route('pricing') }}" class="nav-link">Pricing</a>

            

                <a href="{{ route('code.of.practice') }}" class="nav-link">Code of Practice</a>
                <a href="{{ route('contact') }}" class="nav-link">Contact us</a>
            </div>

            <!-- Right Actions -->
            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('campaigns.create') }}"
                   class="bg-rose-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-rose-700 transition">
                    Start a fundraiser
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-slate-700 hover:text-rose-600">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-700">Login</a>
                    <a href="{{ route('register') }}"
                       class="bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800">
                        Sign up
                    </a>
                @endauth
            </div>

            <!-- Mobile Button -->
            <button @click="open = !open" class="md:hidden text-slate-700">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="md:hidden border-t bg-white">
        <div class="px-4 py-4 space-y-3 text-sm">
            <a href="{{ route('home') }}" class="mobile-link">Home</a>
            <a href="{{ route('campaigns.index') }}" class="mobile-link">Donate</a>
            <a href="{{ route('pricing') }}" class="mobile-link">Pricing</a>
            <a href="{{ route('blog') }}" class="mobile-link">Blog</a>
            <a href="{{ route('faqs') }}" class="mobile-link">FAQs</a>
            <a href="{{ route('guides') }}" class="mobile-link">Fundraising guides</a>
            <a href="{{ route('code.of.practice') }}" class="mobile-link">Code of Practice</a>
            <a href="{{ route('contact') }}" class="mobile-link">Contact us</a>

            <a href="{{ route('campaigns.create') }}"
               class="block bg-rose-600 text-white text-center py-2 rounded-lg font-semibold">
                Start a fundraiser
            </a>
        </div>
    </div>
</nav>
