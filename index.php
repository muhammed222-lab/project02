<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>PROJECT 02 - Empowering Students with Final Year Projects</title>

    <!-- Modern Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Core Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <style>
    /* Theme Variables */
    :root {
        --bg-primary: #222831; /* Dark background */
        --bg-secondary: #393E46; /* Dark gray */
        --accent-color: #00ADB5; /* Teal */
        --text-primary: #EEEEEE; /* Light gray */
        --button-bg: rgba(0, 173, 181, 0.7); /* Glass effect for buttons */
        --button-hover-bg: rgba(0, 229, 255, 0.8); /* Lighter shade on hover */
        --card-bg: rgba(57, 62, 70, 0.8); /* Background color for feature cards */
    }

    /* Global Styles */
    * {
        font-family: 'Plus Jakarta Sans', sans-serif;
        scroll-behavior: smooth;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        overflow-x: hidden;
    }

    /* Glassmorphism Effect */
    .glass {
        background: rgba(57, 62, 70, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Navigation Bar */
    nav {
        background-color: rgba(34, 40, 49, 0.9);
        border-bottom: 2px solid var(--accent-color);
    }

    /* Hero Background */
    .hero-background {
        background: var(--bg-secondary);
        position: relative;
        opacity: 0.9;
    }

    /* Hero Text */
    .hero-text {
        position: relative;
        z-index: 10;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* Button Styles */
    .btn-primary {
        background: var(--button-bg);
        color: white;
        border-radius: 20px;
        padding: 12px 24px;
        transition: transform 0.3s, background 0.3s;
        box-shadow: 0 4px 15px rgba(0, 173, 181, 0.5);
    }

    .btn-primary:hover {
        transform: scale(1.05);
        background: var(--button-hover-bg); /* Lighter shade on hover */
    }

    /* Feature Card Styles */
    .feature-card {
        background: var(--card-bg); /* Updated background color for feature cards */
        border-radius: 12px;
        padding: 30px; /* Increased padding for larger cards */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        height: 300px; /* Set a fixed height for the cards */
    }

    .feature-card img {
        width: 80px; /* Increased image size */
        height: 80px; /* Increased image size */
    }

    .feature-card:hover {
        transform: translateY(-5px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-text {
            font-size: 1.5rem;
        }

        .btn-primary {
            padding: 10px 20px;
        }

        .feature-card {
            height: auto; /* Allow height to adjust on smaller screens */
        }

        .feature-card img {
            width: 60px; /* Adjust image size for smaller screens */
            height: 60px; /* Adjust image size for smaller screens */
        }
    }
    </style>
</head>

<body class="antialiased">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 top-0 left-0 right-0 shadow-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center space-x-3">
                    <img src="./favicon.png" alt="P02" class="w-10 h-10 rounded-full">
                    <span class="text-2xl font-bold text-[var(--accent-color)]">PROJECT 02</span>
                </a>
                <div class="flex items-center space-x-3">
                    <a href="login.php" class="text-[var(--accent-color)] text-sm font-medium px-3 py-1.5 rounded-full hover:bg-[var(--accent-color)]/10 transition-all duration-300">
                        Login
                    </a>
                    <a href="signup.php" class="btn-primary">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="min-h-screen flex items-center hero-background">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold mb-4 hero-text">
                Transform Your <span class="gradient-text">Final Year Project</span> Journey
            </h1>
            <p class="text-lg mb-8 hero-text">
                Connect with expert creators, discover innovative projects, and bring your academic vision to life.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="signup.php" class="btn-primary">Start Your Journey</a>
                <a href="#features" class="btn-primary">Explore Features</a>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-[var(--accent-color)]">Revolutionizing Project Development</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="feature-card glass" data-aos="fade-up">
                    <h3 class="text-xl font-semibold mb-4 text-center text-[var(--accent-color)]">Smart Project Discovery</h3>
                    <p class="text-center">Browse through our curated collection of innovative projects or create your own masterpiece.</p>
                    <img src="./assets/images/search.png" alt="Browse" class="mx-auto">
                </div>

                <!-- Feature Card 2 -->
                <div class="feature-card glass" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xl font-semibold mb-4 text-center text-[var(--accent-color)]">Expert Collaboration</h3>
                    <p class="text-center">Connect with experienced creators who understand your vision and can bring it to life.</p>
                    <img src="./assets/images/connect.png" alt="Connect" class="mx-auto">
                </div>

                <!-- Feature Card 3 -->
                <div class="feature-card glass" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xl font-semibold mb-4 text-center text-[var(--accent-color)]">Seamless Experience</h3>
                    <p class="text-center">Secure transactions and instant project delivery for a worry-free experience.</p>
                    <img src="./assets/images/control.png" alt="Control" class="mx-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });
    </script>
</body>

</html>
