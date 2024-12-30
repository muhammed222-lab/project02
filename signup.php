<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up | PROJECT 02</title>
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
    
    <div class="form-container w-full max-w-2xl rounded-2xl shadow-2xl p-8 relative z-10 my-8">
        <a href="index.php" class="flex items-center justify-center space-x-2 mb-8">
            <img src="./favicon.png" alt="P02" class="w-8 h-8">
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">PROJECT 02</span>
        </a>
        
        <h2 class="text-2xl font-bold text-center mb-6">Create Your Account</h2>
        <form id="signupForm" action="php/signup.php" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="userRole" class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                    <select id="userRole" name="userRole"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                        <option value="student">Student</option>
                        <option value="freelancer">Freelancer</option>
                        <option value="instructor">Instructor</option>
                        <option value="creator">Creator</option>
                    </select>
                </div>

                <div id="dynamicFields"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            required>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" id="phone" name="phone"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            required>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                </div>

                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            pattern="@.*[A-Z].*[0-9]"
                            title="Must include '@' followed by at least one uppercase letter and one number"
                            required>
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 focus:outline-none">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Password must include '@', an uppercase letter, and a number</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="login.php" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Already have an account?</a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition-colors duration-200">
                    Create Account
                </button>
            </div>
        </form>
    </div>

    <script>
    const roleSelect = document.getElementById('userRole');
    const dynamicFields = document.getElementById('dynamicFields');

    roleSelect.addEventListener('change', (event) => {
        const role = event.target.value;
        let fields = '';

        switch (role) {
            case 'student':
                fields = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                            <input type="text" id="department" name="department"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                        </div>
                        <div>
                            <label for="matricNumber" class="block text-sm font-medium text-gray-700 mb-1">Matric Number</label>
                            <input type="text" id="matricNumber" name="matricNumber"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                        </div>
                    </div>`;
                break;
            case 'freelancer':
                fields = `
                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                        <input type="text" id="skills" name="skills"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            required>
                        <p class="mt-1 text-sm text-gray-500">Separate multiple skills with commas</p>
                    </div>`;
                break;
            case 'instructor':
                fields = `
                    <div>
                        <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Experience (Years)</label>
                        <input type="number" id="experience" name="experience"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            required>
                    </div>`;
                break;
            case 'creator':
                fields = `
                    <div>
                        <label for="portfolio" class="block text-sm font-medium text-gray-700 mb-1">Portfolio URL</label>
                        <input type="url" id="portfolio" name="portfolio"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            required>
                        <p class="mt-1 text-sm text-gray-500">Link to your portfolio or previous work</p>
                    </div>`;
                break;
        }

        dynamicFields.innerHTML = fields;
    });

    // Trigger change to load fields for the default selection
    roleSelect.dispatchEvent(new Event('change'));

    // Password visibility toggle
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle SVG icon state
        eyeIcon.innerHTML = type === 'password' 
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
    });
    </script>
</body>

</html>