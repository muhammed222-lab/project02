<?php
if (!isset($_GET['email'])) {
    header('Location: reset_password.php'); // Redirect if accessed without email
    exit;
}

$email = $_GET['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Password | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">

    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-4">Set New Password</h2>
        <form id="newPasswordForm" method="POST" action="update_password.php">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-semibold">New Password:</label>
                <input type="password" id="new_password" name="new_password"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-semibold">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">Update
                    Password</button>
            </div>
        </form>
    </div>

</body>

</html>