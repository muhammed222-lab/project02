<?php
include 'php/db.php';

$creators = $conn->query("SELECT * FROM users WHERE role = 'Creator'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <title>Admin | Creators </title>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Creators</h1>
        <table class="min-w-full border border-gray-300 mt-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-700 font-semibold">Name</th>
                    <th class="px-4 py-2 text-left text-gray-700 font-semibold">Email</th>
                    <th class="px-4 py-2 text-center text-gray-700 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($creator = $creators->fetch_assoc()): ?>
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap"><?php echo htmlspecialchars($creator['name']); ?></td>
                    <td class="px-4 py-2 whitespace-nowrap"><?php echo htmlspecialchars($creator['email']); ?></td>
                    <td class="px-4 py-2 text-center">
                        <button onclick="viewUserDetails(<?php echo htmlspecialchars(json_encode($creator)); ?>)"
                            class="text-blue-600 hover:underline">View</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
    function viewUserDetails(user) {
        alert(`Name: ${user.name}\nEmail: ${user.email}\nRole: ${user.role}`);
    }
    </script>
</body>

</html>