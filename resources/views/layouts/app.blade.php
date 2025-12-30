<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FundHive - Crowdfunding with AI Fraud Detection')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://images.pexels.com/photos/1054655/pexels-photo-1054655.jpeg?cs=srgb&dl=pexels-hsapir-1054655.jpg&fm=jpg">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }
        
       .fundhive-gradient {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
}
        
        .campaign-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        .campaign-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .nav-link {
            position: relative;
            padding-bottom: 4px;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #0066cc;
            border-radius: 3px 3px 0 0;
        }
        
        .category-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        
        .verified-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0066cc 0%, #00aaff 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 102, 204, 0.2);
        }
        
        .btn-secondary {
            background: white;
            color: #0066cc;
            border: 2px solid #0066cc;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #0066cc;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-sm">
                <i class="fas fa-shield-alt mr-2"></i>
                <strong>AI Fraud Detection Active:</strong> Every campaign is verified by our machine learning algorithm
                <a href="#" class="ml-2 underline font-medium">Learn more →</a>
            </p>
        </div>
    </div>

  

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Column 1 -->
                <div>
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-hive text-blue-600"></i>
                        </div>
                        <span class="text-2xl font-bold">FundHive</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-6">
                        India's most trusted crowdfunding platform with AI-powered fraud detection.
                        We help people raise funds for medical emergencies, education, and social causes.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2 -->
                <div>
                    <h4 class="font-bold text-lg mb-6">Fundraise for</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">Medical</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Education</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Memorial</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Emergency</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Nonprofits</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Creative</a></li>
                    </ul>
                </div>

                <!-- Column 3 -->
                <div>
                    <h4 class="font-bold text-lg mb-6">Learn more</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">How FundHive works</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Success stories</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Fraud Protection</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Pricing & fees</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Support</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Careers</a></li>
                    </ul>
                </div>

                <!-- Column 4 -->
                <div>
                    <h4 class="font-bold text-lg mb-6">Resources</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Help center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Trust & Safety</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact us</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-gray-400 text-sm">
                            © 2024 FundHive. All rights reserved. | Crafted with AI Fraud Protection
                        </p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-shield-alt text-green-500"></i>
                            <span class="text-sm text-gray-400">SSL Secure</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-lock text-green-500"></i>
                            <span class="text-sm text-gray-400">PCI DSS Compliant</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-brain text-blue-500"></i>
                            <span class="text-sm text-gray-400">AI Fraud Detection</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Live Chat Widget -->
    <div class="fixed bottom-6 right-6 z-40">
        <button class="bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition">
            <i class="fas fa-comment-dots text-xl"></i>
        </button>
    </div>
</body>
</html>