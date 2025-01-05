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
        --s: 200px;
        /* control the size */
        --bg-primary: #222831;
        --bg-secondary: #393E46;
        --accent-color: #00ADB5;
        --text-primary: #EEEEEE;
        --accent-gradient: linear-gradient(135deg, #00ADB5, #00E5FF);
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

    /* Hero Background */
    /* Hero Background */
    .hero-background {
        --s: 200px;
        /* Scale for the pattern */
        --accent-color: rgba(14, 165, 233, 0.2);
        /* Accent color with reduced opacity */
        --bg-primary: rgba(255, 255, 255, 0.05);
        /* Subtle white for primary background */
        --bg-secondary: rgba(0, 0, 0, 0.1);
        /* Subtle black for secondary background */
        --text-primary: rgba(255, 255, 255, 0.1);
        /* Very light white for text */

        background: repeating-conic-gradient(from 30deg,
                transparent 0 120deg,
                var(--accent-color) 0 180deg) calc(0.5 * var(--s)) calc(0.5 * var(--s) * 0.577),
            repeating-conic-gradient(from 30deg,
                var(--bg-primary) 0 60deg,
                var(--bg-secondary) 0 120deg,
                var(--text-primary) 0 180deg);
        background-size: var(--s) calc(var(--s) * 0.577);
        animation: gradientShift 6s infinite alternate ease-in-out;
        position: relative;
        backdrop-filter: blur(10px) saturate(150%);
        -webkit-backdrop-filter: blur(10px) saturate(150%);
        opacity: 0.7;
        /* Reduce overall brightness */
    }

    /* Gradient Shift Animation */
    @keyframes gradientShift {
        0% {
            background-position: 0 0, 50% 50%;
            background-size: var(--s) calc(var(--s) * 0.577);
        }

        50% {
            background-position: 25% 25%, 75% 75%;
            background-size: calc(var(--s) * 1.5) calc(var(--s) * 0.866);
        }

        100% {
            background-position: 50% 50%, 0 0;
            background-size: var(--s) calc(var(--s) * 0.577);
        }
    }


    /* Particle Animation */
    .particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        width: 2px;
        height: 2px;
        background-color: var(--accent-primary);
        border-radius: 50%;
        animation: float-particle 20s infinite linear;
        opacity: 0.3;
    }

    @keyframes float-particle {
        0% {
            transform: translateY(0) translateX(0);
            opacity: 0;
        }

        50% {
            opacity: 0.5;
        }

        100% {
            transform: translateY(-100vh) translateX(100vw);
            opacity: 0;
        }
    }

    /* Glassmorphism */
    .glass {
        background: rgba(57, 62, 70, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Animated Gradient Text */
    .gradient-text {
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 0 2px 4px rgba(0, 173, 181, 0.3);
    }

    /* Hero Text */
    .hero-text {
        position: relative;
        z-index: 10;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* Floating Animation */
    .float {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    /* Card Hover Effects */
    .feature-card {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
        perspective: 1000px;
    }

    .feature-card:hover {
        transform: translateY(-10px) rotateX(10deg);
    }

    .feature-card::before {
        content: '';
        position: absolute;
        inset: -1px;
        background: linear-gradient(45deg, var(--accent-primary), var(--accent-secondary));
        z-index: -1;
        border-radius: inherit;
        opacity: 0;
        transition: opacity 0.5s;
    }

    .feature-card:hover::before {
        opacity: 1;
    }

    /* Enhanced Button Styles */
    .btn-primary {
        background: linear-gradient(45deg, var(--accent-primary), var(--accent-secondary));
        position: relative;
        z-index: 1;
        overflow: hidden;
        transition: all 0.5s;
        box-shadow: 0 4px 15px rgba(0, 173, 181, 0.2);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, var(--accent-secondary), var(--accent-primary));
        z-index: -1;
        opacity: 0;
        transition: all 0.5s;
        transform: translateY(100%);
    }

    .btn-primary:hover::before {
        opacity: 1;
        transform: translateY(0);
    }

    .btn-primary:hover {
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        box-shadow: 0 8px 25px rgba(0, 173, 181, 0.4);
    }

    /* Button Pulse Animation */
    @keyframes button-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 173, 181, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 173, 181, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 173, 181, 0);
        }
    }

    .animate-button-pulse {
        animation: button-pulse 2s infinite;
    }

    /* Stats Counter Animation */
    .counter {
        animation: count-up 2s ease-out forwards;
    }

    @keyframes count-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }



    /* Scroll Reveal Animation */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 1s;
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-content {
            text-align: center;
        }
    }
    </style>
</head>

<body class="antialiased">
    <!-- Particle Effect -->
    <div class="particles" id="particles"></div>

    <!-- Navigation -->
    <nav
        class="fixed w-full z-50 top-0 left-0 right-0 bg-[var(--bg-primary)]/90 backdrop-blur-md border-b border-[var(--accent-color)]/20 shadow-md transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center space-x-3 group">
                    <img src="./favicon.png" alt="P02"
                        class="w-10 h-10 rounded-2xl transform transition-all duration-300 group-hover:rotate-12 group-hover:scale-110">
                    <span class="text-2xl font-bold text-[var(--accent-color)]">PROJECT 02</span>
                </a>
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <a href="login.php"
                        class="text-white/90 text-sm font-medium px-3 py-1.5 rounded-full hover:text-[var(--accent-color)] transition-all duration-300 hover:-translate-y-1 hover:bg-[var(--accent-color)]/10 hover:shadow-lg hover:shadow-[var(--accent-color)]/20">
                        Login
                    </a>
                    <a href="signup.php"
                        class="bg-[var(--accent-color)]/90 text-white text-sm font-medium px-4 py-1.5 rounded-full transform transition-all duration-300 hover:scale-105 hover:bg-[var(--accent-color)] hover:shadow-[0_0_20px_rgba(0,229,255,0.3)] hover:-translate-y-1 relative overflow-hidden group">
                        <span class="relative z-10">Get Started</span>
                        <span
                            class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity duration-300"></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
<<<<<<< HEAD
    <!-- Hero Section -->
    <header class="min-h-screen flex items-center relative overflow-hidden hero-background">
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center z-index-2">
=======
    <!-- Main Content -->
    <main class="relative">
        <!-- Hero Section -->
        <header class="min-h-screen flex items-center relative overflow-hidden hero-background pt-14 sm:pt-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
>>>>>>> 6dc2037811c124d1ac5042d980fdacb9dee5071f
                <h1 class="text-6xl md:text-7xl font-bold mb-8 hero-text text-white" data-aos="fade-up">
                    Transform Your <span class="gradient-text">Final Year Project</span> Journey
                </h1>
                <p class="text-xl md:text-2xl mb-12 text-white/90 hero-text" data-aos="fade-up" data-aos-delay="100">
                    Connect with expert creators, discover innovative projects, and bring your academic vision to life.
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-6 sm:space-y-0 sm:space-x-8 md:space-x-10"
                    data-aos="fade-up" data-aos-delay="200">
                    <a href="signup.php"
                        class="btn-primary px-8 py-4 rounded-xl text-lg font-semibold shadow-xl hover:shadow-[var(--accent-primary)]/30 transform hover:scale-105 transition-all duration-300 hover:-translate-y-2 animate-button-pulse">
                        Start Your Journey
                    </a>
                    <a href="#features"
                        class="px-8 py-4 rounded-xl text-lg font-semibold border-2 border-[var(--accent-primary)] hover:bg-[var(--accent-primary)]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:shadow-[var(--accent-primary)]/20 group">
                        Explore Features
                    </a>
                </div>
            </div>
        </div>
        <!-- Floating Elements -->
        <div class="absolute top-1/4 left-10 w-20 h-20 bg-[var(--accent-primary)]/20 rounded-full blur-xl float"></div>
        <div class="absolute bottom-1/4 right-10 w-32 h-32 bg-[var(--accent-secondary)]/20 rounded-full blur-xl float"
            style="animation-delay: -2s"></div>
    </header>


    <!-- Features Section -->
    <section id="features" class="py-20 relative">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-[var(--accent-color)]" data-aos="fade-up">
                Revolutionizing Project Development
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="feature-card glass p-8 rounded-2xl" data-aos="fade-up">
                    <div class="mb-6">
                        <img src="./assets/images/search.png" alt="Browse" class="w-43 h-43 mx-auto object-contain">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-center text-[var(--accent-color)]">Smart Project
                        Discovery</h3>
                    <p class="text-[var(--text-secondary)] text-center">
                        Browse through our curated collection of innovative projects or create your own masterpiece.
                    </p>
                </div>

                <!-- Feature Card 2 -->
                <div class="feature-card glass p-8 rounded-2xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="mb-6">
                        <img src="./assets/images/connect.png" alt="Connect" class="w-43 h-43 mx-auto object-contain">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-center text-[var(--accent-color)]">Expert Collaboration
                    </h3>
                    <p class="text-[var(--text-secondary)] text-center">
                        Connect with experienced creators who understand your vision and can bring it to life.
                    </p>
                </div>

                <!-- Feature Card 3 -->
                <div class="feature-card glass p-8 rounded-2xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="mb-6">
                        <img src="./assets/images/control.png" alt="Control" class="w-43 h-43 mx-auto object-contain">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-center text-[var(--accent-color)]">Seamless Experience
                    </h3>
                    <p class="text-[var(--text-secondary)] text-center">
                        Secure transactions and instant project delivery for a worry-free experience.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 glass">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div data-aos="fade-up">
                    <h4 class="text-4xl font-bold text-[var(--accent-color)] counter">500+</h4>
                    <p class="text-[var(--text-secondary)]">Projects Available</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-4xl font-bold text-[var(--accent-color)] counter">200+</h4>
                    <p class="text-[var(--text-secondary)]">Expert Creators</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-4xl font-bold text-[var(--accent-color)] counter">1000+</h4>
                    <p class="text-[var(--text-secondary)]">Happy Students</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <h4 class="text-4xl font-bold text-[var(--accent-color)] counter">24/7</h4>
                    <p class="text-[var(--text-secondary)]">Support Available</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 relative">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2" data-aos="fade-right">
                    <h2 class="text-4xl font-bold mb-8 text-[var(--accent-color)]">Why Choose PROJECT 02?</h2>
                    <div class="space-y-6">
                        <p class="text-[var(--text-secondary)] text-lg leading-relaxed">
                            We're not just another project marketplace. We're a comprehensive platform designed to
                            transform your academic journey.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-[var(--accent-primary)]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Verified Expert Creators</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-[var(--accent-primary)]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Secure Payment System</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-[var(--accent-primary)]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>24/7 Customer Support</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="md:w-1/2" data-aos="fade-left">
                    <div class="relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-[var(--accent-primary)] to-[var(--accent-secondary)] opacity-20 blur-2xl rounded-3xl">
                        </div>
                        <img src="./assets/images/hero2.jpg" alt="Platform Features"
                            class="relative rounded-3xl shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="py-20 glass">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-12 text-[var(--accent-color)]" data-aos="fade-up">Trusted by Students &
                Institutions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8" data-aos="fade-up" data-aos-delay="100">
                <div class="p-6">
                    <img src="./assets/images/done.png" alt="Trust 1"
                        class="w-24 h-24 mx-auto filter grayscale hover:grayscale-0 transition-all">
                </div>
                <div class="p-6">
                    <img src="./assets/images/done2.png" alt="Trust 2"
                        class="w-24 h-24 mx-auto filter grayscale hover:grayscale-0 transition-all">
                </div>
                <div class="p-6">
                    <img src="./assets/images/control.png" alt="Trust 3"
                        class="w-24 h-24 mx-auto filter grayscale hover:grayscale-0 transition-all">
                </div>
                <div class="p-6">
                    <img src="./assets/images/connect.png" alt="Trust 4"
                        class="w-24 h-24 mx-auto filter grayscale hover:grayscale-0 transition-all">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Cookie Consent -->
    <div id="cookieConsent"
        class="fixed bottom-5 right-5 w-[400px] md:w-auto glass rounded-2xl p-6 z-50 transform transition-transform duration-500 translate-y-full opacity-0 md:translate-y-0 md:opacity-100 shadow-lg">
        <h3 class="text-lg font-semibold mb-3 text-[var(--accent-color)] mr-3 block">🍪 Cookie Preferences</h3>
        <p class="text-[var(--text-secondary)] text-sm mb-4">
            We use cookies to enhance your experience. By continuing, you agree to our use of cookies.
            <br /> <a href="./terms.php" class="text-[var(--accent-primary)] hover:underline">Learn more</a>
        </p>
        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
            <button id="acceptCookies"
                class="btn-primary px-4 py-2 rounded-xl bg-[var(--accent-primary)] text-white hover:bg-[var(--accent-primary-dark)] transition-colors">
                Accept All
            </button>
            <button id="denyCookies"
                class="px-4 py-2 border border-[var(--accent-primary)] rounded-xl text-[var(--accent-primary)] hover:bg-[var(--accent-primary)]/10 transition-colors">
                Reject All
            </button>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="./JavaScript/cookies.js"></script>
    <script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });

    // Create particles
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        const particleCount = 50;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + 'vw';
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.opacity = Math.random() * 0.5;
            particlesContainer.appendChild(particle);
        }
    }

    // Initialize particles
    createParticles();

    // Show cookie consent with animation
    setTimeout(() => {
        const cookieConsent = document.getElementById('cookieConsent');
        cookieConsent.style.transform = 'translateY(0)';
        cookieConsent.style.opacity = '1';
    }, 1000);
    </script>
</body>

</html>