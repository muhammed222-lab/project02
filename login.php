<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <style>
    /* CSS Variables for Theme */
    :root {
        --bg-primary: #222831;
        --bg-secondary: #393E46;
        --accent-color: #00ADB5;
        --text-primary: #EEEEEE;
    }

    * {
        font-family: 'Inter', sans-serif;
    }

    body {
        background: linear-gradient(135deg, var(--bg-primary), var(--bg-secondary));
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--text-primary);
    }

    /* Advanced Animations */


    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.02);
        }
    }

    @keyframes gradientShift {
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

    .form-container {
        background-color: var(--bg-secondary);
        border: 1px solid var(--accent-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        animation: float 3s ease-in-out infinite;
    }

    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, var(--accent-color), transparent);
        transform: translateX(-100%);
        transition: 0.5s;
        opacity: 0.1;
        z-index: 1;
    }

    .form-container:hover::before {
        transform: translateX(100%);
    }

    /* Input Styles */
    .custom-input {
        background-color: var(--bg-primary);
        border-color: var(--accent-color);
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .custom-input:focus {
        box-shadow: 0 0 15px rgba(0, 173, 181, 0.3);
        border-color: var(--accent-color);
        outline: none;
    }

    /* Button Styles */
    .submit-button {
        background: linear-gradient(to right, var(--accent-color), var(--bg-secondary));
        color: var(--text-primary);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .submit-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: 0.5s;
    }

    .submit-button:hover::before {
        left: 100%;
    }

    .submit-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 173, 181, 0.3);
    }

    /* Link Styles */
    .auth-link {
        color: var(--accent-color);
        transition: all 0.3s ease;
    }

    .auth-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .form-container {
            width: 100%;
            margin: 1rem;
        }
    }
    </style>
</head>

<body class="antialiased">
    <div class="form-container w-full max-w-md rounded-2xl p-8 relative z-10">
        <a href="index.php" class="flex items-center justify-center space-x-2 mb-8">
            <span class="text-2xl font-bold text-[var(--accent-color)]">PROJECT 02</span>
        </a>

        <h2 class="text-2xl font-bold text-center mb-6 text-[var(--text-primary)]">Welcome Back</h2>
        <form id="loginForm" action="./php/login.php" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Email
                        Address</label>
                    <input type="email" id="email" name="email"
                        class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                        required>
                </div>
                <div>
                    <label for="password"
                        class="block text-sm font-medium text-[var(--text-primary)] mb-1">Password</label>
                    <input type="password" id="password" name="password"
                        class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                        required>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <button type="submit" class="submit-button w-full py-2 px-4 rounded-lg transition-all duration-200">
                    Sign In
                </button>

                <div class="flex items-center justify-between text-sm">
                    <a href="signup.php" class="auth-link font-medium">Create an account</a>
                    <a href="reset_password.php" class="auth-link font-medium">Forgot password?</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>