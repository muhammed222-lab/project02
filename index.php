<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PROJECT 02 - Empowering Students with Final Year Projects</title>
    
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind and Additional CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-icons@0.321.0/dist/lucide.min.css">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .hero-background {
            background-image: url('./assets/images/secondary.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .hero-overlay {
            background: rgba(5, 150, 105, 0.6);
        }
        .emerald-gradient {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-blur {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            box-shadow: 0 2px 15px rgba(5, 150, 105, 0.1);
        }
        .feature-card {
            transition: all 0.3s ease;
            transform-origin: center;
        }
        .feature-card:hover {
            transform: scale(1.03);
            box-shadow: 0 15px 30px rgba(5, 150, 105, 0.15);
        }
    </style>
</head>

<body>
    <!-- Modern Navbar with Emerald Theme -->
    <nav class="fixed w-full z-50 nav-blur border-b border-green-100">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center space-x-3">
                    <img src="./favicon.png" alt="P02" class="w-10 h-10 rounded-xl">
                    <span class="text-2xl font-bold text-emerald-700">PROJECT 02</span>
                </a>
                <div class="flex space-x-4 items-center">
                    <a href="login.php" class="px-4 py-2 text-emerald-800 hover:bg-green-50 rounded-lg transition-colors">
                        Login
                    </a>
                    <a href="signup.php" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors shadow-md">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Background Image -->
    <header class="hero-background min-h-screen flex items-center relative">
        <div class="absolute inset-0 hero-overlay"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    Your Final Year Project, Simplified
                </h1>
                <p class="text-xl md:text-2xl mb-10 bg-black/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    Connect with expert creators, find the perfect project, and excel in your academic journey.
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="signup.php" class="px-8 py-3 bg-white text-black border-4 border-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors shadow-lg">
                        Get Started
                    </a>
                    <a href="#how-it-works" class="px-8 py-3 border-4 border-emerald-600 text-black rounded-lg hover:bg-emerald-50 transition-colors">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="how-it-works" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 emerald-gradient">How It Works</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="feature-card bg-white rounded-2xl p-8 shadow-md border border-green-50">
                    <div class="mb-6 text-center">
                        <img src="./assets/images/search.png" alt="Browse" class="w-32 h-32 mx-auto object-contain">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-emerald-800 text-center">Create or Browse</h3>
                    <p class="text-gray-600 text-center">
                        Explore hundreds of projects or create your own with our intuitive platform.
                    </p>
                </div>
                
                <div class="feature-card bg-white rounded-2xl p-8 shadow-md border border-green-50">
                    <div class="mb-6 text-center">
                        <img src="./assets/images/connect.png" alt="Connect" class="w-32 h-32 mx-auto object-contain">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-emerald-800 text-center">Connect with Experts</h3>
                    <p class="text-gray-600 text-center">
                        Collaborate with experienced creators who understand your project needs.
                    </p>
                </div>
                
                <div class="feature-card bg-white rounded-2xl p-8 shadow-md border border-green-50">
                    <div class="mb-6 text-center">
                        <img src="./assets/images/control.png" alt="Purchase" class="w-32 h-32 mx-auto object-contain">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-emerald-800 text-center">Purchase & Download</h3>
                    <p class="text-gray-600 text-center">
                        Securely purchase and download your perfect final year project.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-green-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h2 class="text-4xl font-bold mb-8 emerald-gradient">Why PROJECT 02?</h2>
                    <div class="space-y-6">
                        <p class="text-emerald-900 text-lg leading-relaxed">
                            We're more than a marketplace. PROJECT 02 is a comprehensive platform designed to transform your academic project experience.
                        </p>
                        <p class="text-emerald-900 text-lg leading-relaxed">
                            From students seeking quality projects to creators offering their expertise, we create a dynamic ecosystem that simplifies project acquisition and development.
                        </p>
                        <div class="flex space-x-4 mt-6">
                            <div class="bg-emerald-100 p-4 rounded-lg text-center">
                                <h4 class="text-2xl font-bold text-emerald-700">500+</h4>
                                <p class="text-emerald-600">Projects</p>
                            </div>
                            <div class="bg-emerald-100 p-4 rounded-lg text-center">
                                <h4 class="text-2xl font-bold text-emerald-700">200+</h4>
                                <p class="text-emerald-600">Creators</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <img src="./assets/images/hero2.jpg" alt="Platform Features" class="w-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Cookie Consent -->
    <div id="cookieConsent" class="fixed bottom-5 right-5 max-w-md bg-white rounded-2xl shadow-xl p-6 z-50 border border-green-100">
        <h3 class="text-lg font-semibold mb-3 text-emerald-900">🍪 Cookie Preferences</h3>
        <p class="text-gray-600 text-sm mb-4">
            We use cookies to enhance your experience. By continuing to visit this site, you agree to our use of cookies.
            <a href="./terms.php" class="text-emerald-600 hover:underline">Learn more</a>
        </p>
        <div class="flex space-x-4">
            <button id="acceptCookies" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                Accept All
            </button>
            <button id="denyCookies" class="px-4 py-2 bg-green-100 text-emerald-800 rounded-lg hover:bg-green-200 transition-colors">
                Reject All
            </button>
        </div>
    </div>

    <script src="./JavaScript/cookies.js"></script>
</body>
</html>