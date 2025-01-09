<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
        /* Fade-in effect */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        /* Theme Variables */
        :root {
            --bg-primary: #222831;
            --bg-secondary: #393E46;
            --accent-color: #00ADB5;
            --text-primary: #EEEEEE;
            --button-bg: rgba(0, 173, 181, 0.7);
            --button-hover-bg: rgba(0, 229, 255, 0.8);
            --card-bg: rgba(57, 62, 70, 0.8);
        }

        /* Global Styles */
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2300ADB5' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-primary);
            position: relative;
            display: inline-block;
        }

        .section-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color);
            border-radius: 4px 0 0 4px;
        }

        .icon-wrapper {
            background: linear-gradient(135deg, var(--accent-color), var(--bg-secondary));
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .list-item {
            transition: transform 0.2s ease;
            cursor: pointer;
        }

        .list-item:hover {
            transform: translateX(5px);
        }
    </style>
</head>

<body class="min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 section-title" style="color: var(--text-primary)">Terms and Conditions</h1>
            <p style="color: var(--text-primary)">Last updated: <strong>September 21, 2024</strong></p>
        </div>

        <div class="space-y-8">
            <!-- Definitions Section -->
            <div class="section-card p-6 fade-in">
                <div class="flex items-center mb-4">
                    <div class="icon-wrapper">
                        <i class="fas fa-book"></i>
                    </div>
                    <h2 class="text-2xl font-semibold section-title">1. Definitions</h2>
                </div>
                <ul class="space-y-3">
                    <li class="list-item flex items-center" style="color: var(--text-primary)">
                        <i class="fas fa-check-circle mr-2" style="color: var(--accent-color)"></i>
                        <span><strong>Service:</strong> refers to the PROJECT 02 platform and all its features</span>
                    </li>
                    <li class="list-item flex items-center" style="color: var(--text-primary)">
                        <i class="fas fa-check-circle mr-2" style="color: var(--accent-color)"></i>
                        <span><strong>User:</strong> refers to students, freelancers, instructors, and other platform users</span>
                    </li>
                    <li class="list-item flex items-center" style="color: var(--text-primary)">
                        <i class="fas fa-check-circle mr-2" style="color: var(--accent-color)"></i>
                        <span><strong>Project:</strong> refers to final year projects listed on the platform</span>
                    </li>
                </ul>
            </div>

            <!-- Account Registration Section -->
            <div class="section-card p-6 fade-in">
                <div class="flex items-center mb-4">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="text-2xl font-semibold section-title">2. Account Registration</h2>
                </div>
                <ol class="space-y-3">
                    <li class="list-item flex items-center" style="color: var(--text-primary)">
                        <i class="fas fa-arrow-right mr-2" style="color: var(--accent-color)"></i>
                        <span>You must register for an account to access certain features</span>
                    </li>
                    <li class="list-item flex items-center" style="color: var(--text-primary)">
                        <i class="fas fa-arrow-right mr-2" style="color: var(--accent-color)"></i>
                        <span>You are responsible for safeguarding your password</span>
                    </li>
                </ol>
            </div>

            <!-- User Roles Section -->
            <div class="section-card p-6 fade-in">
                <div class="flex items-center mb-4">
                    <div class="icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <h2 class="text-2xl font-semibold section-title">3. User Roles</h2>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg glass" style="background: var(--card-bg); backdrop-filter: blur(10px);">
                        <h3 class="font-semibold mb-2" style="color: var(--accent-color)">Students</h3>
                        <p style="color: var(--text-primary)">Can browse and purchase projects, submit inquiries</p>
                    </div>
                    <div class="p-4 rounded-lg glass" style="background: var(--card-bg); backdrop-filter: blur(10px);">
                        <h3 class="font-semibold mb-2" style="color: var(--accent-color)">Creators</h3>
                        <p style="color: var(--text-primary)">Can upload projects and manage listings</p>
                    </div>
                </div>
            </div>

            <!-- Additional sections following the same pattern -->
            <div class="section-card p-6 fade-in">
                <div class="flex items-center mb-4">
                    <div class="icon-wrapper">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h2 class="text-2xl font-semibold section-title">4. Intellectual Property</h2>
                </div>
                <p style="color: var(--text-primary)">The content uploaded by creators remains their property. Users may not copy or distribute without permission.</p>
            </div>

            <!-- Contact Section -->
            <div class="section-card p-6 fade-in">
                <div class="flex items-center mb-4">
                    <div class="icon-wrapper">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h2 class="text-2xl font-semibold section-title">Contact Us</h2>
                </div>
                <div class="p-4 rounded-lg glass" style="background: var(--card-bg); backdrop-filter: blur(10px);">
                    <p style="color: var(--text-primary)">If you have any questions, please contact us at:</p>
                    <p class="font-bold mt-2" style="color: var(--accent-color)">project02.fyp@gmail.com</p>
                </div>
            </div>
        </div>

        <footer class="mt-12 text-center" style="color: var(--text-primary)">
            <p class="animate-pulse">By using the Service, you acknowledge that you have read and understood these Terms and Conditions.</p>
        </footer>
    </div>

    <script>
        // Intersection Observer for fade-in animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Stop observing once visible
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(element => {
            observer.observe(element);
        });
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>