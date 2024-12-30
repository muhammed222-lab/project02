<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .hero-background {
            background-image: url('./assets/images/secondary.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="hero-background min-h-screen flex justify-center items-center p-4">
    <div class="absolute inset-0 bg-black/50"></div>
    
    <div class="form-container w-full max-w-md rounded-2xl shadow-2xl p-8 relative z-10">
        <a href="index.php" class="flex items-center justify-center space-x-2 mb-8">
            <img src="./favicon.png" alt="P02" class="w-8 h-8">
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">PROJECT 02</span>
        </a>
        
        <h2 class="text-2xl font-bold text-center mb-6">Welcome Back</h2>
        <form id="loginForm" action="./php/login.php" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors duration-200">
                    Sign In
                </button>
                
                <div class="flex items-center justify-between text-sm">
                    <a href="signup.php" class="text-blue-600 hover:text-blue-700 font-medium">Create an account</a>
                    <a href="reset_password.php" class="text-blue-600 hover:text-blue-700 font-medium">Forgot password?</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>