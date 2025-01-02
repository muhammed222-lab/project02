<?php
session_start();

// Ensure logs directory exists
$logDir = '../logs';
if (!is_dir($logDir)) {
    // Include the logs directory creation script
    include_once('../create_logs_directory.php');
}

// Log logout attempt
$logout_time = date('Y-m-d H:i:s');
$user_id = $_SESSION['user_id'] ?? 'Unknown';
$log_entry = "Logout: User ID $user_id at $logout_time\n";

// Attempt to write log with error handling
$logFile = $logDir . '/logout.log';
try {
    if (file_put_contents($logFile, $log_entry, FILE_APPEND) === false) {
        error_log("Failed to write to logout log file: $logFile");
    }
} catch (Exception $e) {
    error_log("Exception writing logout log: " . $e->getMessage());
}

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
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            min-height: 100vh;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-2xl p-8 text-center animate-fade-in">
        <div class="bg-emerald-50 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Logged Out Successfully</h1>
        <p class="text-gray-600 mb-6">You have been securely logged out of your account. We hope to see you again soon!</p>
        
        <div class="flex justify-center space-x-4">
            <a href="../index.php" 
               class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Return to Home
            </a>
        </div>

        <p id="redirect-timer" class="text-sm text-gray-500 mt-4"></p>
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