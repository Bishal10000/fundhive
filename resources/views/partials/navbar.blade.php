<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo + Desktop Links -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-9 h-9 bg-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hive text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">FundHive</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('campaigns.index') }}"
                       class="text-gray-700 hover:text-red-600 font-medium">
                        Browse Campaigns
                    </a>

                    <a href="{{ route('campaigns.index') }}?category=medical"
                       class="text-gray-700 hover:text-red-600 font-medium">
                        Medical
                    </a>

                    <a href="{{ route('campaigns.index') }}?category=education"
                       class="text-gray-700 hover:text-red-600 font-medium">
                        Education
                    </a>

                    <a href="#"
                       class="text-gray-700 hover:text-red-600 font-medium">
                        How it works
                    </a>
                </div>
            </div>

            <!-- Right Section (Desktop) -->
            <div class="hidden md:flex items-center space-x-4">

                <a href="{{ route('campaigns.create') }}"
                   class="bg-red-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-red-700">
                    Start a fundraiser
                </a>

                @auth
                    <div class="relative" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown"
                                class="flex items-center space-x-2 text-gray-700 font-medium focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <div x-show="dropdown" @click.away="dropdown = false"
                             class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                            <a href="{{ route('dashboard') }}"
                               class="block px-4 py-2 hover:bg-gray-100">
                                Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 hover:bg-gray-100">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="text-gray-700 font-medium hover:text-red-600">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 font-medium">
                        Sign up
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" @click.away="open = false"
         class="md:hidden bg-white border-t">
        <div class="px-4 py-4 space-y-3">

            <a href="{{ route('home') }}" class="block text-gray-700">Home</a>
            <a href="{{ route('campaigns.index') }}" class="block text-gray-700">Browse Campaigns</a>
            <a href="{{ route('campaigns.index') }}?category=medical" class="block text-gray-700">Medical</a>
            <a href="{{ route('campaigns.index') }}?category=education" class="block text-gray-700">Education</a>
            <a href="#" class="block text-gray-700">How it works</a>

            <a href="{{ route('campaigns.create') }}"
               class="block bg-red-600 text-white text-center py-2 rounded-lg font-semibold">
                Start a fundraiser
            </a>

            @auth
                <a href="{{ route('dashboard') }}" class="block text-gray-700">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="block text-gray-700">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-700">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-gray-700">Login</a>
                <a href="{{ route('register') }}" class="block text-gray-700">Sign up</a>
            @endauth

        </div>
    </div>
</nav>
