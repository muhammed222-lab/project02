<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Settings</h1>
        <div class="bg-white shadow-md rounded-lg p-4">
            <form>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                        placeholder="your-email@example.com">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                        placeholder="Enter new password">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Settings</button>
            </form>
        </div>
    </div>
</body>

</html>