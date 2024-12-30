<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-4">Admin Login</h2>

        <?php
        if (isset($_GET['error'])) {
            echo '<p class="text-red-500 text-center mb-4">';
            if ($_GET['error'] == 'incorrect_password') echo "Incorrect password.";
            if ($_GET['error'] == 'admin_not_found') echo "Admin account not found or inactive.";
            echo '</p>';
        }
        ?>

        <form action="admin_login_handler.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold">Email:</label>
                <input type="email" id="email" name="email" required
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold">Password:</label>
                <input type="password" id="password" name="password" required
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500">
            </div>
            <div class="mb-4 flex justify-between">
                <a href="admin_signup.php" class="text-sm text-green-600 hover:underline">Create an admin account</a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">Login</button>
            </div>
        </form>
    </div>
</body>

</html>