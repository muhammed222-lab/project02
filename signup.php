<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup | Project 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/css/ionicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
</head>
<style>
* {
    font-family: 'Poppins', sans-serif !important;
}
</style>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-sm p-8">
        <h2 class="text-2xl font-bold text-center mb-4">Signup to Project 02</h2>
        <form id="signupForm" action="php/signup.php" method="POST">
            <div class="mb-4">
                <label for="userRole" class="block text-sm font-semibold">Select Account Type:</label>
                <select id="userRole" name="userRole"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
                    <option value="student">Student</option>
                    <option value="freelancer">Freelancer</option>
                    <option value="instructor">Instructor</option>
                    <option value="creator">Creator</option>
                </select>
            </div>
            <div id="dynamicFields" class="mb-4"></div>
            <div class="flex gap-1">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold">Full Name:</label>
                    <input type="text" id="name" name="name"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-semibold">Phone:</label>
                    <input type="text" id="phone" name="phone"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                        required>
                </div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold">Email Address:</label>
                <input type="email" id="email" name="email"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4 relative">
                <label for="password" class="block text-sm font-semibold">Password:</label>
                <div class="flex items-center">
                    <input type="password" id="password" name="password"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                        pattern="@.*[A-Z].*[0-9]"
                        title="Must include '@' followed by at least one uppercase letter and one number" required>
                    <button type="button" id="togglePassword" class="absolute right-2 mr-2 focus:outline-none">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="mb-4 flex items-center justify-between">
                <a href="login.php" class="text-sm text-green-600 hover:underline">Already have an account</a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">Signup</button>
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
                        <div class="flex gap-1">
                            <div class="mb-4">
                                <label for="department" class="block text-sm font-semibold">Department:</label>
                                <input type="text" id="department" name="department"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500" required>
                            </div>
                            <div class="mb-4">
                                <label for="matricNumber" class="block text-sm font-semibold">Matric Number:</label>
                                <input type="text" id="matricNumber" name="matricNumber"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500" required>
                            </div>
                        </div>`;
                break;
            case 'freelancer':
                fields = `
                        <div class="mb-4">
                            <label for="skills" class="block text-sm font-semibold">Skills:</label>
                            <input type="text" id="skills" name="skills"
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500" required>
                        </div>`;
                break;
            case 'instructor':
                fields = `
                        <div class="mb-4">
                            <label for="experience" class="block text-sm font-semibold">Experience (Years):</label>
                            <input type="number" id="experience" name="experience"
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500" required>
                        </div>`;
                break;
            case 'creator':
                fields = `
                        <div class="mb-4">
                            <label for="portfolio" class="block text-sm font-semibold">Portfolio URL:</label>
                            <input type="url" id="portfolio" name="portfolio"
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500" required>
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
        eyeIcon.setAttribute('d', type === 'password' ?
            'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z' :
            'M12 12c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4 4 1.79 4 4zm1.732 1.732l-2.83-2.83a2 2 0 1 1 2.83 2.83z'
            );
    });
    </script>
</body>

</html>