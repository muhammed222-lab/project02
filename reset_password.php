<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #059669;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='rgba(5, 150, 105, 0.05)' fill-opacity='0.5'%3E%3Cpath d='M11 18 C 19 26, 31 18, 39 26 C 47 34, 59 26, 67 34 C 75 42, 87 34, 95 42 L 95 100 L 5 100 L 5 10 C 13 18, 25 10, 33 18 C 41 26, 53 18, 61 26 C 69 34, 81 26, 89 34'/%3E%3Cpath d='M-9 42 C -1 50, 11 42, 19 50 C 27 58, 39 50, 47 58 C 55 66, 67 58, 75 66 C 83 74, 95 66, 103 74 L 103 100 L -15 100 L -15 34 C -7 42, 5 34, 13 42 C 21 50, 33 42, 41 50 C 49 58, 61 50, 69 58 C 77 66, 89 58, 97 66'/%3E%3C/g%3E%3C/svg%3E");
            background-color: #059669;
            background-attachment: fixed;
            background-repeat: repeat;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(5, 150, 105, 0.2);
        }
    </style>
</head>

<body class="min-h-screen flex justify-center items-center p-4">
    
    <div class="form-container w-full max-w-md rounded-2xl shadow-2xl p-8 relative z-10">
        <a href="index.php" class="flex items-center justify-center space-x-2 mb-8">
            <img src="./favicon.png" alt="P02" class="w-8 h-8">
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">PROJECT 02</span>
        </a>
        
        <h2 class="text-2xl font-bold text-center mb-6">Reset Your Password</h2>
        <p class="text-gray-600 text-center mb-8">Enter your email and name to verify your identity</p>
        
        <form id="resetPasswordForm" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name (First or Last)</label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                    <p class="mt-1 text-sm text-gray-500">Enter either your first name or last name as registered</p>
                </div>

                <div id="errorMessage" class="text-red-500 text-sm bg-red-50 rounded-lg p-3 hidden"></div>

                <div class="flex items-center justify-between pt-2">
                    <a href="login.php" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Back to Login
                    </a>
                    <button type="submit" id="submitButton"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition-colors duration-200">
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
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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