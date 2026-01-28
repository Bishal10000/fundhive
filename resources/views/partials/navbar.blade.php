<nav class="bg-white border-b border-slate-200 sticky top-0 z-50" x-data="{ open: false, profileOpen: false }">
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
                <a href="{{ route('campaigns.index', ['filter' => 'successful']) }}" class="nav-link">Success Stories</a>
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
                    @php
                        $userRating = app('App\Services\UserProfileRatingService')->calculate(auth()->user());
                    @endphp

                    <!-- Trust Rating Badge -->
                    <div class="flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full border border-blue-200">
                        <span class="text-xs font-bold text-blue-600">🏆 {{ $userRating['score'] }}/100</span>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" @click.away="profileOpen = false">
                        <button @click="profileOpen = !profileOpen" 
                                class="flex items-center gap-2 px-3 py-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                            <i class="fas fa-user-circle text-xl"></i>
                            <i class="fas fa-chevron-down text-xs" :class="{'rotate-180': profileOpen}" style="transition: transform 0.2s;"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="profileOpen" 
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-2">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">{{ $userRating['label'] }}</p>
                            </div>

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                <i class="fas fa-chart-line text-blue-600"></i> Dashboard
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                <i class="fas fa-user-edit text-purple-600"></i> Edit Profile
                            </a>

                            <a href="{{ route('dashboard.campaigns') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                <i class="fas fa-bullhorn text-green-600"></i> My Campaigns
                            </a>

                            <a href="{{ route('dashboard.donations') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                <i class="fas fa-heart text-red-600"></i> My Donations
                            </a>

                            <div class="border-t border-slate-100 my-2"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-medium">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
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
            <a href="{{ route('campaigns.index', ['filter' => 'successful']) }}" class="mobile-link">Success Stories</a>
            <a href="{{ route('pricing') }}" class="mobile-link">Pricing</a>
            <a href="{{ route('code.of.practice') }}" class="mobile-link">Code of Practice</a>
            <a href="{{ route('contact') }}" class="mobile-link">Contact us</a>

            <a href="{{ route('campaigns.create') }}"
               class="block bg-rose-600 text-white text-center py-2 rounded-lg font-semibold">
                Start a fundraiser
            </a>

            @auth
                @php
                    $userRating = app('App\Services\UserProfileRatingService')->calculate(auth()->user());
                @endphp

                <!-- Trust Rating Badge (Mobile) -->
                <div class="px-3 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                    <p class="text-xs font-bold text-blue-600">🏆 Trust Rating: {{ $userRating['score'] }}/100</p>
                    <p class="text-xs text-blue-500">{{ $userRating['label'] }}</p>
                </div>

                <!-- Mobile Profile Menu -->
                <div class="border-t pt-3 mt-3 space-y-2">
                    <p class="text-xs font-semibold text-slate-600 px-2">{{ auth()->user()->name }}</p>

                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 mobile-link">
                        <i class="fas fa-chart-line text-blue-600"></i> Dashboard
                    </a>

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 mobile-link">
                        <i class="fas fa-user-edit text-purple-600"></i> Edit Profile
                    </a>

                    <a href="{{ route('dashboard.campaigns') }}" class="flex items-center gap-2 mobile-link">
                        <i class="fas fa-bullhorn text-green-600"></i> My Campaigns
                    </a>

                    <a href="{{ route('dashboard.donations') }}" class="flex items-center gap-2 mobile-link">
                        <i class="fas fa-heart text-red-600"></i> My Donations
                    </a>

                    <!-- Mobile Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-2 text-red-600 font-semibold mobile-link">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="mobile-link">Login</a>
                <a href="{{ route('register') }}" class="mobile-link">Sign up</a>
            @endauth
        </div>
    </div>
</nav>
