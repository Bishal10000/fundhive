<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FundHive - Crowdfunding with Trust & Transparency')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://milaap.org/assets/milaap-favicon-41a3d4e2db0c2d0c7e463aa5f9b2b7e0a6795d233207e97a44a0dd0c9daa4f38.png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ========= Color system - simplified to feel like Milaap ========= */
        :root {
            --primary: #a83244;       /* main maroon/red */
            --primary-dark: #8b2535;
            --accent: #ffefef;       /* very pale pink */
            --muted: #6b7280;        /* gray for secondary text */
            --success: #00a857;
            --card-border: #eef2f5;
            --soft-shadow: 0 6px 18px rgba(16,24,40,0.06);
        }

        /* Basic typography */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #111827;
            line-height: 1.6;
            background: #ffffff;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
        }

        /* --- Top info bar (calmer, not gradient) --- */
        .top-info-bar {
            background: var(--accent);
            color: var(--primary);
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid #f3e9ea;
        }

        .top-info-bar a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            margin-left: 8px;
        }

        /* --- Buttons (solid) --- */
        .btn-milaap {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform .12s ease, box-shadow .12s ease;
            text-decoration: none;
        }
        .btn-milaap:hover { background: var(--primary-dark); transform: translateY(-2px); }

        .btn-milaap-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 9px 18px;
            border-radius: 10px;
            font-weight: 700;
        }
        .btn-milaap-outline:hover { background: var(--primary); color: #fff; }

        /* --- Cards & shadows (light and minimal) --- */
        .campaign-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--soft-shadow);
            transition: transform .16s ease, box-shadow .16s ease;
            border: 1px solid var(--card-border);
        }
        .campaign-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(16,24,40,0.08); }

        /* --- Progress bar --- */
        .progress-bar {
            height: 6px;
            border-radius: 999px;
            background: #eef2f1;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--success) 0%, #4cd964 100%);
        }

        /* --- Verified badge (simple) --- */
        .verified-badge {
            background: #0f8f4a;
            color: #fff;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* --- Navigation links --- */
        .nav-link {
            color: #111827;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 8px;
            transition: background .12s ease, color .12s ease;
            text-decoration: none;
        }
        .nav-link:hover { background: #f8fafb; color: var(--primary); }

        /* --- Category icons --- */
        .category-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        /* --- Reduced hero gradient (move to page-level hero) --- */
        .hero-gradient { background: transparent; }

        /* --- Stat numbers (calmer color) --- */
        .stat-number {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary);
            font-size: 36px;
            line-height: 1;
            margin-bottom: 6px;
        }

        /* --- How it works step --- */
        .how-it-works-step {
            background: #fff;
            border-radius: 12px;
            padding: 22px;
            box-shadow: var(--soft-shadow);
            height: 100%;
        }
        .step-number {
            width: 44px;
            height: 44px;
            background: var(--primary);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        /* --- Footer --- */
        .footer-link {
            color: #9aa1a8;
            text-decoration: none;
            transition: color .12s ease;
            display: block;
            margin-bottom: 10px;
        }
        .footer-link:hover { color: #dfe7ea; }

        /* --- Trust badge --- */
        .trust-badge {
            background: #fff7f8;
            border: 1px solid #fdecea;
            border-radius: 10px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* --- Floating chat --- */
        .floating-chat {
            background: var(--primary);
            color: #fff;
            width: 56px;
            height: 56px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(168, 50, 68, 0.12);
            transition: transform .12s ease;
        }
        .floating-chat:hover { transform: translateY(-6px); }

        /* --- Scrollbar accent --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #fafafa; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-dark); }

        /* --- Mobile optimizations --- */
        @media (max-width: 768px) {
            .stat-number { font-size: 28px; }
            .how-it-works-step { padding: 16px; }
            .campaign-card { margin-bottom: 18px; }
        }
    </style>
</head>
<body>
    <!-- Top info bar: calmer, less 'AI-ad' tone -->
    <div class="top-info-bar">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center">
                <i class="fas fa-shield-alt mr-2"></i>
                <span class="text-sm font-medium">Donations protected and monitored to help keep fundraisers genuine</span>
                <a href="#" class="ml-3">Learn more →</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                <!-- Logo - simplified to avoid strong gradients -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#fef0f1] rounded-lg flex items-center justify-center border border-[#f5d3d6]">
                            <!-- softer icon -->
                            <i class="fas fa-hive text-[--primary] text-2xl" style="color:var(--primary)"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900" style="font-family: 'Poppins', sans-serif;">FundHive</div>
                            <!-- Removed subtitle to match Milaap's clean logo -->
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('campaigns.index') }}" class="nav-link {{ request()->routeIs('campaigns.index') ? 'text-[#a83244] bg-[#fff0f1]' : '' }}">
                        Explore
                    </a>
                    <a href="{{ route('campaigns.index') }}?category=medical" class="nav-link">Medical</a>
                    <a href="{{ route('campaigns.index') }}?category=education" class="nav-link">Education</a>
                    <a href="{{ route('campaigns.index') }}?category=emergency" class="nav-link">Emergency</a>
                    <a href="#" class="nav-link">How it works</a>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('campaigns.create') }}" class="btn-milaap">
                                <i class="fas fa-plus-circle"></i> <span>Start fundraiser</span>
                            </a>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=a83244&color=fff&bold=true" 
                                         class="w-10 h-10 rounded-full border-2 border-[#fff0f1]">
                                    <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-50">Dashboard</a>
                                    <a href="{{ route('dashboard.campaigns') }}" class="block px-4 py-2 hover:bg-gray-50">My Campaigns</a>
                                    <a href="{{ route('dashboard.donations') }}" class="block px-4 py-2 hover:bg-gray-50">My Donations</a>
                                    <hr class="my-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-50">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-[#a83244] font-medium">Log in</a>
                            <a href="{{ route('register') }}" class="btn-milaap-outline">Sign up</a>
                            <a href="{{ route('campaigns.create') }}" class="btn-milaap">
                                <i class="fas fa-plus-circle"></i> <span>Start fundraiser</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile menu button -->
                <button class="lg:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#111827] text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Column 1 -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-hive text-[#a83244] text-xl"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">FundHive</div>
                            <div class="text-sm text-gray-400">Trusted crowdfunding in India</div>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-8 max-w-md">
                        We help individuals, NGOs, and organisations raise funds for medical emergencies, education, disaster relief and social causes — transparently and securely.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-[#0b1220] rounded-full flex items-center justify-center hover:bg-[#162433]">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-[#0b1220] rounded-full flex items-center justify-center hover:bg-[#0b355f]">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-[#0b1220] rounded-full flex items-center justify-center hover:bg-[#6d1f3a]">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-[#0b1220] rounded-full flex items-center justify-center hover:bg-[#0b355f]">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2 -->
                <div>
                    <h4 class="font-bold text-lg mb-6">Fundraise for</h4>
                    <a href="#" class="footer-link">Medical</a>
                    <a href="#" class="footer-link">Education</a>
                    <a href="#" class="footer-link">Memorial</a>
                    <a href="#" class="footer-link">Emergency</a>
                    <a href="#" class="footer-link">Nonprofits</a>
                    <a href="#" class="footer-link">Creative</a>
                </div>

                <!-- Column 3 -->
                <div>
                    <h4 class="font-bold text-lg mb-6">Learn more</h4>
                    <a href="#" class="footer-link">How FundHive works</a>
                    <a href="#" class="footer-link">Success stories</a>
                    <a href="#" class="footer-link">Fraud Protection</a>
                    <a href="#" class="footer-link">Pricing & fees</a>
                    <a href="#" class="footer-link">Support</a>
                    <a href="#" class="footer-link">Careers</a>
                </div>

                <!-- Column 4 -->
                <div>
                    <h4 class="font-bold text-lg mb-6">Resources</h4>
                    <a href="#" class="footer-link">Blog</a>
                    <a href="#" class="footer-link">Help center</a>
                    <a href="#" class="footer-link">Trust & Safety</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Contact us</a>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="trust-badge">
                    <i class="fas fa-shield-alt text-[#0f8f4a] text-2xl"></i>
                    <div>
                        <div class="font-bold">AI-backed monitoring</div>
                        <div class="text-sm text-gray-600">Campaigns are reviewed for donor safety</div>
                    </div>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-lock text-[#0b4f8f] text-2xl"></i>
                    <div>
                        <div class="font-bold">Secure payments</div>
                        <div class="text-sm text-gray-600">PCI compliant processing</div>
                    </div>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-headset text-[#7a3f6e] text-2xl"></i>
                    <div>
                        <div class="font-bold">Support</div>
                        <div class="text-sm text-gray-600">We help fundraisers & donors</div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-gray-500 text-sm">
                            © {{ date('Y') }} FundHive. All rights reserved.
                        </p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <img src="https://milaap.org/assets/pci-dss-03ec96f86c64e6af2ae8f4f41164db2faa3df5e4fe311758b311a9f0ecf1b03a.png" 
                             alt="PCI DSS" class="h-8">
                        <img src="https://milaap.org/assets/ssl-secure-06530ae1b1d99c08adbeef97c9b83e8a0cf7fc81417082c29ea6f3b8d8cf2c0b.png" 
                             alt="SSL Secure" class="h-8">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Chat -->
    <div class="fixed bottom-8 right-8 z-50">
        <button class="floating-chat" aria-label="Chat with support">
            <i class="fas fa-comment-dots"></i>
        </button>
    </div>

    <script>
        // Simple animations - keep them subtle so page feels natural (not AI-ad)
        document.addEventListener('DOMContentLoaded', function() {
            const stats = document.querySelectorAll('.stat-number');
            stats.forEach(stat => {
                const digits = stat.textContent.replace(/[^0-9]/g, '');
                if (!digits) return;
                const finalValue = parseInt(digits);
                if (isNaN(finalValue)) return;
                let startValue = 0;
                const duration = 1200;
                const step = Math.max(1, Math.floor(finalValue / (duration / 16)));
                const timer = setInterval(() => {
                    startValue += step;
                    if (startValue >= finalValue) {
                        stat.textContent = stat.textContent.replace(/[0-9,]+/g, finalValue.toLocaleString());
                        clearInterval(timer);
                    } else {
                        stat.textContent = stat.textContent.replace(/[0-9,]+/g, Math.floor(startValue).toLocaleString());
                    }
                }, 16);
            });
        });
    </script>
</body>
</html>
