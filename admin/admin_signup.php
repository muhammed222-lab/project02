<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Signup | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/css/ionicons.min.css">
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-4">Admin Signup</h2>
        <form id="signupForm" action="admin_signup_handler.php" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold">Full Name:</label>
                <input type="text" id="name" name="name"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold">Email:</label>
                <input type="email" id="email" name="email"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold">Password:</label>
                <input type="password" id="password" name="password"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4 flex justify-between">
                <a href="admin_login.php" class="text-sm text-green-600 hover:underline">Already have an account?</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">Create
                    Account</button>
            </div>
        </form>
    </div>
</body>

</html>