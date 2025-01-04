<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>Sign Up | PROJECT 02</title>
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

    .form-container {
        background-color: var(--bg-secondary);
        border: 1px solid var(--accent-color);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
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
    <div class="form-container w-full max-w-2xl rounded-2xl p-8 relative z-10 my-8"
        style="animation: float 3s ease-in-out infinite;">
        <a href="index.php" class="flex items-center justify-center space-x-2 mb-8">
            <span class="text-2xl font-bold text-[var(--accent-color)]">PROJECT 02</span>
        </a>

        <h2 class="text-2xl font-bold text-center mb-6 text-[var(--text-primary)]">Create Your Account</h2>
        <form id="signupForm" action="php/signup.php" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="userRole" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Account
                        Type</label>
                    <select id="userRole" name="userRole"
                        class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
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
                        <label for="name" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Full
                            Name</label>
                        <input type="text" id="name" name="name"
                            class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                            required>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Phone
                            Number</label>
                        <input type="text" id="phone" name="phone"
                            class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                            required>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Email
                        Address</label>
                    <input type="email" id="email" name="email"
                        class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                        required>
                </div>

                <div class="relative">
                    <label for="password"
                        class="block text-sm font-medium text-[var(--text-primary)] mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                            pattern="@.*[A-Z].*[0-9]"
                            title="Must include '@' followed by at least one uppercase letter and one number" required>
                        <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--accent-color)] focus:outline-none">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-[var(--accent-color)] opacity-80">Password must include '@', an
                        uppercase letter, and a number</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="login.php" class="text-[var(--accent-color)] hover:opacity-80 text-sm font-medium">Already have
                    an account?</a>
                <button type="submit" class="submit-button py-2 px-6 rounded-lg transition-all duration-200">
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
                            <label for="department" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Department</label>
                            <input type="text" id="department" name="department"
                                class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                                required>
                        </div>
                        <div>
                            <label for="matricNumber" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Matric Number</label>
                            <input type="text" id="matricNumber" name="matricNumber"
                                class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                                required>
                        </div>
                    </div>`;
                break;
            case 'freelancer':
                fields = `
                    <div>
                        <label for="skills" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Skills</label>
                        <input type="text" id="skills" name="skills"
                            class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                            required>
                        <p class="mt-1 text-sm text-[var(--accent-color)] opacity-80">Separate multiple skills with commas</p>
                    </div>`;
                break;
            case 'instructor':
                fields = `
                    <div>
                        <label for="experience" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Experience (Years)</label>
                        <input type="number" id="experience" name="experience"
                            class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                            required>
                    </div>`;
                break;
            case 'creator':
                fields = `
                    <div>
                        <label for="portfolio" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Portfolio URL</label>
                        <input type="url" id="portfolio" name="portfolio"
                            class="custom-input w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-[var(--accent-color)] transition-colors"
                            required>
                        <p class="mt-1 text-sm text-[var(--accent-color)] opacity-80">Link to your portfolio or previous work</p>
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
        eyeIcon.innerHTML = type === 'password' ?
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />' :
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
    });
    </script>
</body>

</html>