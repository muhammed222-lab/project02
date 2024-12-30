<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PROJECT 02 - Empowering Students with Final Year Projects</title>
    
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind and Additional CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            background: rgba(0, 0, 0, 0.5);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
        }

        .image-container img {
            transition: transform 0.5s ease;
        }

        .image-container:hover img {
            transform: scale(1.05);
        }

        .nav-blur {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        .gradient-text {
            background: linear-gradient(135deg, #1a5f7a 0%, #2d8bac 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Modern Navbar with Blur Effect -->
    <nav class="fixed w-full z-50 nav-blur border-b border-gray-200/30">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center space-x-3">
                    <img src="./favicon.png" alt="P02" class="w-8 h-8 rounded-lg">
                    <span class="text-2xl font-bold gradient-text">PROJECT 02</span>
                </a>
                <div class="flex space-x-4">
                    <a href="login.php" class="px-6 py-2 rounded-full text-gray-700 hover:bg-gray-100 transition-all">Login</a>
                    <a href="signup.php" class="px-6 py-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:shadow-lg transition-all">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Professional Background -->
    <header class="hero-background min-h-screen flex items-center relative overflow-hidden">
        <div class="absolute inset-0 hero-overlay"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 floating">
                    Need a Final Year Project Fast?
                </h1>
                <p class="text-xl md:text-2xl mb-10 bg-black/30 backdrop-blur-lg rounded-2xl p-6 inline-block">
                    Worry no more! PROJECT 02 connects you with skilled creators and experts to deliver the perfect final year project.
                </p>
                <a href="signup.php" class="inline-block px-8 py-4 rounded-full bg-blue-600 text-white font-semibold hover:shadow-xl transition-all transform hover:scale-105 hover:bg-blue-700">
                    Get Started Today →
                </a>
            </div>
        </div>
    </header>

    <!-- Features Section with Modern Cards -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 gradient-text">How It Works</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="card-hover bg-white rounded-2xl p-8 shadow-sm">
                    <div class="image-container mb-6">
                        <img src="./assets/images/search.png" alt="Browse" class="w-full h-48 object-cover">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 gradient-text">Create or Browse Projects</h3>
                    <p class="text-gray-600">Browse from hundreds of available projects or create your own if you're a freelancer or creator.</p>
                </div>
                
                <div class="card-hover bg-white rounded-2xl p-8 shadow-sm">
                    <div class="image-container mb-6">
                        <img src="./assets/images/done2.png" alt="Connect" class="w-full h-48 object-cover">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 gradient-text">Connect with Experts</h3>
                    <p class="text-gray-600">Interact with experienced creators to get quality projects tailored to your needs.</p>
                </div>
                
                <div class="card-hover bg-white rounded-2xl p-8 shadow-sm">
                    <div class="image-container mb-6">
                        <img src="./assets/images/search.png" alt="Purchase" class="w-full h-48 object-cover">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 gradient-text">Purchase & Download</h3>
                    <p class="text-gray-600">Easily purchase and download your chosen project with a secure payment system.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Why Choose Us Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h2 class="text-4xl font-bold mb-8 gradient-text">Why Choose PROJECT 02?</h2>
                    <div class="space-y-6">
                        <p class="text-gray-700 text-lg leading-relaxed">
                            PROJECT 02 is designed to empower students by connecting them with creators and experts who deliver high-quality final year projects. Our platform makes it easy to find projects quickly while ensuring quality and originality.
                        </p>
                        <p class="text-gray-700 text-lg leading-relaxed">
                            Freelancers can seamlessly switch between buying and selling projects, creating a dynamic marketplace for academic excellence. With our platform, meeting deadlines and achieving success becomes effortless.
                        </p>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="image-container floating">
                        <img src="./assets/images/control.png" alt="Platform Features" class="w-full rounded-2xl shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Cookie Consent -->
    <div id="cookieConsent" class="fixed bottom-5 left-5 max-w-md bg-white rounded-2xl shadow-xl p-6 z-50 transform transition-all duration-300 scale-0" style="display: none;">
        <h3 class="text-lg font-semibold mb-3">🍪 Cookie Preferences</h3>
        <p class="text-gray-600 text-sm mb-4">
            We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.
            <a href="./terms.php" class="text-blue-600 hover:underline">Learn more</a>
        </p>
        <div class="flex space-x-4">
            <button id="acceptCookies" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                Accept All
            </button>
            <button id="denyCookies" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition-colors">
                Reject All
            </button>
        </div>
    </div>
        <?php include 'includes/footer.php'; ?>

    <script src="./JavaScript/cookies.js"></script>
</body>
</html>