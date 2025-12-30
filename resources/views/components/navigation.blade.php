<nav class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side: Logo and main navigation -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <!-- Manchester United logo placeholder -->
                        <img src="https://upload.wikimedia.org/wikipedia/en/7/7a/Manchester_United_FC_crest.svg" 
                             alt="FundHive" 
                             class="h-8 w-8 mr-3">
                        <span class="text-2xl font-bold text-gray-900">FundHive</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:ml-8 md:flex md:items-center md:space-x-6">
                    <a href="{{ url('/') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition duration-200">
                        Home
                    </a>
                    <a href="{{ route('campaigns.index') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition duration-200">
                        Donate
                    </a>
                    <a href="{{ route('pricing') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition duration-200">
                        Pricing
                    </a>
                    
                    <!-- Resources Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium flex items-center transition duration-200">
                            Resources
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95">
                            <a href="{{ route('code-of-practice') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                Code of Practice
                            </a>
                            <a href="{{ route('contact') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side: Auth buttons / User menu -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User dropdown menu -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none transition duration-200">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                             x-transition>
                            <a href="{{ route('dashboard') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login/Signup buttons -->
                    <a href="{{ route('login') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 shadow-sm">
                        Start a fundraiser
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" 
                        class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden" x-show="open" x-cloak>
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
            <a href="{{ url('/') }}" 
               class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Home</a>
            <a href="{{ route('campaigns.index') }}" 
               class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Donate</a>
            <a href="{{ route('pricing') }}" 
               class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Pricing</a>
            <a href="{{ route('code-of-practice') }}" 
               class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Code of Practice</a>
            <a href="{{ route('contact') }}" 
               class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Contact Us</a>
            
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Login</a>
                <a href="{{ route('register') }}" 
                   class="block px-3 py-2 text-base font-medium text-blue-600 hover:bg-blue-50">Start a fundraiser</a>
            @endauth
        </div>
    </div>
</nav>

<script>
    // Alpine.js for dropdown functionality
    document.addEventListener('alpine:init', () => {
        Alpine.data('dropdown', () => ({
            open: false,
            toggle() {
                this.open = !this.open;
            }
        }));
    });
</script>


