<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <title>Admin | Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php
    session_start();
    include 'nav.php';

    // Check if the admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin_login.php");
        exit();
    }

    // Retrieve admin data from session
    $admin = [
        'name' => $_SESSION['admin_name'] ?? 'Not set',
        'email' => $_SESSION['admin_email'] ?? 'Not set',
        'phone' => $_SESSION['admin_phone'] ?? 'Not set'
    ];
    ?>

    <div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-4">Admin Profile</h1>

        <!-- Admin Information -->
        <div class="mb-6">
            <p class="text-lg"><strong>Name:</strong> <?php echo htmlspecialchars($admin['name']); ?></p>
            <p class="text-lg"><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
            <p class="text-lg"><strong>Phone:</strong> <?php echo htmlspecialchars($admin['phone']); ?></p>
        </div>

        <!-- Edit Profile Button -->
        <button onclick="toggleEditForm()"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
            Edit Profile
        </button>

        <!-- Edit Profile Form Sidebar (hidden by default) -->
        <div id="editForm"
            class="fixed inset-y-0 right-0 w-full max-w-md bg-white border transition-transform transform translate-x-full p-6">
            <h2 class="text-2xl font-bold mb-4">Edit Profile</h2>
            <form action="update_admin.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>"
                        class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>"
                        class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div>
                    <label class="block text-gray-700">Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($admin['phone']); ?>"
                        class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>

                <!-- Update and Cancel Buttons -->
                <button type="submit"
                    class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition duration-200">
                    Update Profile
                </button>
                <button type="button" onclick="toggleEditForm()"
                    class="w-full bg-red-500 text-white py-2 mt-2 rounded hover:bg-red-600 transition duration-200">
                    Cancel
                </button>
            </form>
        </div>
    </div>

    <script>
    // Toggles the visibility of the edit form
    function toggleEditForm() {
        const editForm = document.getElementById('editForm');
        editForm.classList.toggle('translate-x-full');
    }
    </script>
</body>

</html>