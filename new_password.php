<?php
if (!isset($_GET['email'])) {
    header('Location: reset_password.php'); // Redirect if accessed without email
    exit;
}

$email = $_GET['email'];
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>New Password | PROJECT 02</title>
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
            perspective: 1000px;
        }

        /* Advanced Animations */
        @keyframes backgroundShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes subtleFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        .form-container {
            background-color: var(--bg-secondary);
            border: 1px solid var(--accent-color);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: subtleFloat 3s ease-in-out infinite;
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
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: 0.5s;
        }

        .submit-button:hover::before {
            left: 100%;
        }

        .submit-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 173, 181, 0.3);
        }

        /* Password Requirements */
        .password-requirements {
            background-color: var(--bg-primary);
            border-left: 4px solid var(--accent-color);
            color: var(--text-primary);
            opacity: 0.8;
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
            <img src="./favicon.png" alt="P02" class="w-8 h-8">
            <span class="text-2xl font-bold text-[var(--accent-color)]">PROJECT 02</span>
        </a>
        
        <h2 class="text-2xl font-bold text-center mb-6 text-[var(--text-primary)]">Set New Password</h2>
        <p class="text-[var(--accent-color)] text-center mb-8 opacity-80">Create a strong password for your account</p>
        
        <form id="newPasswordForm" method="POST" action="update_password.php" class="space-y-4">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            
            <div>
                <label for="new_password" class="block text-sm font-medium text-[var(--text-primary)] mb-1">New Password</label>
                <div class="relative">
                    <input type="password" id="new_password" name="new_password"
                        class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                        required>
                    <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-[var(--accent-color)] focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                        required>
                    <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-[var(--accent-color)] focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="password-requirements rounded-lg p-4 text-sm">
                <p class="font-medium mb-1 text-[var(--accent-color)]">Password Requirements:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>At least 8 characters long</li>
                    <li>Include at least one uppercase letter</li>
                    <li>Include at least one number</li>
                    <li>Include at least one special character</li>
                </ul>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="login.php" class="auth-link text-sm font-medium">
                    Back to Login
                </a>
                <button type="submit" 
                    class="submit-button py-2 px-6 rounded-lg transition-all duration-200">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Toggle icon
            const svg = this.querySelector('svg');
            if (type === 'password') {
                svg.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                `;
            } else {
                svg.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            }
        });
    });

    // Form validation
    document.getElementById('newPasswordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return;
        }

        // Password strength validation
        const hasUpperCase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const isLongEnough = password.length >= 8;

        if (!hasUpperCase || !hasNumber || !hasSpecialChar || !isLongEnough) {
            e.preventDefault();
            alert('Password does not meet the requirements!');
            return;
        }
    });
    </script>
</body>

</html>