<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Completely destroy the session
session_unset();
session_destroy();

// Clear any remember me cookies if used
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables for Theme */
        :root {
            --bg-primary: #222831;
            --bg-secondary: #393E46;
            --accent-color: #00ADB5;
            --text-primary: #EEEEEE;
        }

        /* Global Styles */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            min-height: 100vh;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Animations */
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(20px) scale(0.95);
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        /* Logout Container */
        .logout-container {
            background-color: var(--bg-secondary);
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2rem;
            max-width: 28rem;
            width: 100%;
            text-align: center;
        }

        /* Icon Styles */
        .logout-icon {
            background-color: rgba(0, 173, 181, 0.2);
            border-radius: 9999px;
            width: 6rem;
            height: 6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        /* Button Styles */
        .home-button {
            background: linear-gradient(to right, var(--accent-color), var(--bg-secondary));
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .home-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* Redirect Timer */
        .redirect-timer {
            color: var(--accent-color);
            opacity: 0.7;
        }
    </style>
</head>
<body class="antialiased">
    <div class="logout-container animate-fade-in">
        <div class="logout-icon animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[var(--accent-color)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold mb-4">Logged Out Successfully</h1>
        <p class="text-opacity-80 mb-6">You have been securely logged out of your account. We hope to see you again soon!</p>
        
        <div class="flex justify-center space-x-4 mb-6">
            <a href="../index.php" 
               class="home-button inline-flex items-center px-6 py-3 rounded-lg hover:opacity-90 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Return to Home
            </a>
        </div>

        <p id="redirect-timer" class="redirect-timer text-sm mt-4"></p>
    </div>

    <script>
    // Automatic redirection countdown
    const redirectTimer = document.getElementById('redirect-timer');
    let countdown = 10;

    function updateTimer() {
        redirectTimer.textContent = `Redirecting to home page in ${countdown} seconds...`;
        
        if (countdown > 0) {
            countdown--;
            setTimeout(updateTimer, 1000);
        } else {
            window.location.href = '../index.php';
        }
    }

    // Start the countdown
    updateTimer();
    </script>
</body>
</html>