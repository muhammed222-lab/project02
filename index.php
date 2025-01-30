<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project02</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            color: white;
        }

        #root {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }

        .logo {
            height: 6em;
            padding: 1.5em;
            will-change: filter;
            transition: filter 300ms;
        }

        .logo:hover {
            filter: drop-shadow(0 0 2em #646cffaa);
        }

        .logo.react:hover {
            filter: drop-shadow(0 0 2em #61dafbaa);
        }

        @keyframes logo-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            a:nth-of-type(2) .logo {
                animation: logo-spin infinite 20s linear;
            }
        }

        .card {
            padding: 2em;
        }

        .read-the-docs {
            color: #888;
        }

        @layer base {
            :root {
                --background: 222.2 84% 4.9%;
                --foreground: 210 40% 98%;
                --card: 222.2 84% 4.9%;
                --card-foreground: 210 40% 98%;
                --popover: 222.2 84% 4.9%;
                --popover-foreground: 210 40% 98%;
                --primary: #00ADB5;
                --primary-foreground: 222.2 47.4% 11.2%;
                --secondary: #393E46;
                --secondary-foreground: 210 40% 98%;
                --muted: 217.2 32.6% 17.5%;
                --muted-foreground: 215 20.2% 65.1%;
                --accent: #00ADB5;
                --accent-foreground: 210 40% 98%;
                --destructive: 0 62.8% 30.6%;
                --destructive-foreground: 210 40% 98%;
                --border: 217.2 32.6% 17.5%;
                --input: 217.2 32.6% 17.5%;
                --ring: 212.7 26.8% 83.9%;
            }
        }

    
        .fade-in {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 1s ease-in-out, transform 1s ease-in-out;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .animate-scaleIn {
            animation: scaleIn 1s ease-in-out;
        }
        @keyframes scaleIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        body {
            @apply bg-[#222831] text-foreground;
        }
    </style>
</head>

<body class="min-h-screen bg-[#222831] fade-out">
    <?php include 'navigation.php'; ?>
    <?php include 'hero.php'; ?>
    <?php include 'about.php'; ?>
    <?php include 'features.php'; ?>
    <?php include 'service.php'; ?>
    <?php include 'testimonials.php'; ?>
    <?php include 'footer.php'; ?>

    <!-- Include JavaScript -->
    <script src="JavaScript/main.js"></script>
</body>

</html>