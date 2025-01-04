<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>Reset Password | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .form-container {
            background-color: var(--bg-secondary);
            border: 1px solid var(--accent-color);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
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

        /* Link Styles */
        .auth-link {
            color: var(--accent-color);
            transition: all 0.3s ease;
        }

        .auth-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Error Message */
        #errorMessage {
            background-color: rgba(0, 173, 181, 0.1);
            border-left: 4px solid var(--accent-color);
            color: var(--accent-color);
        }

        /* Loading Spinner */
        .loading-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
        
        <h2 class="text-2xl font-bold text-center mb-6 text-[var(--text-primary)]">Reset Your Password</h2>
        <p class="text-[var(--accent-color)] text-center mb-8 opacity-80">Enter your email and name to verify your identity</p>
        
        <form id="resetPasswordForm" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                        required>
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Your Name (First or Last)</label>
                    <input type="text" id="name" name="name"
                        class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                        required>
                    <p class="mt-1 text-sm text-[var(--accent-color)] opacity-80">Enter either your first name or last name as registered</p>
                </div>

                <div id="errorMessage" class="text-[var(--accent-color)] text-sm rounded-lg p-3 hidden"></div>

                <div class="flex items-center justify-between pt-2">
                    <a href="login.php" class="auth-link text-sm font-medium">
                        Back to Login
                    </a>
                    <button type="submit" id="submitButton"
                        class="submit-button py-2 px-6 rounded-lg transition-all duration-200">
                        Verify Identity
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $('#resetPasswordForm').on('submit', function(e) {
            e.preventDefault();

            const email = $('#email').val();
            const name = $('#name').val();
            
            // Show loading state
            $('#submitButton').prop('disabled', true).html(`
                <svg class="loading-spinner -ml-1 mr-3 h-5 w-5 text-[var(--text-primary)] inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Verifying...
            `);

            // AJAX request to validate email and name
            $.ajax({
                url: 'check_email_name.php',
                type: 'POST',
                data: {
                    email: email,
                    name: name
                },
                dataType: 'json',
                success: function(response) {
                    if (response.emailFound && response.nameMatches) {
                        window.location.href = `new_password.php?email=${encodeURIComponent(email)}`;
                    } else {
                        $('#errorMessage').removeClass('hidden').addClass('block');
                        if (!response.emailFound) {
                            $('#errorMessage').text('Email address not found in our records.');
                        } else if (!response.nameMatches) {
                            $('#errorMessage').text('The name provided does not match our records.');
                        }
                        // Reset button state
                        $('#submitButton').prop('disabled', false).html('Verify Identity');
                    }
                },
                error: function(xhr, status, error) {
                    $('#errorMessage')
                        .removeClass('hidden')
                        .addClass('block')
                        .text('An error occurred while verifying your identity. Please try again.');
                    // Reset button state
                    $('#submitButton').prop('disabled', false).html('Verify Identity');
                }
            });
        });
    });
    </script>
</body>

</html>