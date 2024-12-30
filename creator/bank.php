<!-- Save this inside your Creator Dashboard (e.g., `creator/bank_details.php`) -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify Bank Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.5.2/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">Verify Your Bank Details</h2>
        <form action="verify_bank_details.php" method="POST" class="bg-white p-6 rounded-md shadow-md w-full max-w-lg mx-auto">
            <div class="mb-4">
                <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number:</label>
                <input type="text" id="account_number" name="account_number" class="mt-1 block w-full border border-gray-300 p-2 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="account_bank" class="block text-sm font-medium text-gray-700">Bank Name:</label>
                <input type="text" id="account_bank" name="account_bank" class="mt-1 block w-full border border-gray-300 p-2 rounded-md" required>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md">Verify Account</button>
        </form>

        <!-- Display Result -->
        <div id="result" class="mt-6"></div>
    </div>
</body>

</html>