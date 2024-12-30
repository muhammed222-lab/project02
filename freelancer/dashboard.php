<?php
// Start session and include connection
session_start();
require_once '../db.php';

// Check if user is logged in as a creator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'freelancer') {
    header("Location: ../login.php");
    exit();
}

// Get Creator Details
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, profile_picture FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get Freelancer's Gigs
$gigQuery = "SELECT * FROM gigs WHERE freelancer_email = :freelancer_email";
$gigStmt = $pdo->prepare($gigQuery);
$gigStmt->execute([':freelancer_email' => $user['email']]);
$gigs = $gigStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Freelancer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.5.2/collection/components/icon/icon.min.css"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.5.2/dist/cdn.min.js" defer></script>
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include 'nav.php'; ?>
    <!-- Container -->
    <div class="flex min-h-screen" x-data="{ open: false, editGig: false, gig: {} }">

        <!-- Sidebar and other layout code omitted for brevity -->

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-4">My Gigs</h2>
            <a href="create_project.php" class="bg-green-600 text-white py-2 px-4 rounded-md inline-block mb-4">
                <ion-icon name="add-outline" class="mr-2"></ion-icon>Create New Gig
            </a>

            <!-- Gigs Table -->
            <?php if (empty($gigs)): ?>
                <p class="text-gray-500">You haven't created any gigs yet.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 mt-4">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Gig Name</th>
                                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Description</th>
                                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Price</th>
                                <th class="px-4 py-2 text-center text-gray-700 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($gigs as $gig): ?>
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap"><?php echo htmlspecialchars($gig['gig_name']); ?>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap truncate max-w-xs">
                                        <?php echo htmlspecialchars($gig['description']); ?></td>
                                    <td class="px-4 py-2 whitespace-nowrap"><?php echo htmlspecialchars($gig['price']); ?></td>
                                    <td class="px-4 py-2 text-center">
                                        <button onclick="openEditGigPopup(<?php echo htmlspecialchars(json_encode($gig)); ?>)"
                                            class="text-blue-600 hover:underline">Edit</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <!-- Edit Gig Popup -->
            <div id="editGigPopup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded shadow-lg max-w-md w-full">
                    <form id="editGigForm" onsubmit="updateGig(); return false;">
                        <input type="hidden" id="gig_id" name="gig_id">
                        <label for="gig_name">Gig Name:</label>
                        <input type="text" id="gig_name" name="gig_name" class="w-full border mb-2 p-2">

                        <label for="description">Description:</label>
                        <textarea id="description" name="description" class="w-full border mb-2 p-2"></textarea>

                        <label for="price">Price:</label>
                        <input type="text" id="price" name="price" class="w-full border mb-2 p-2">

                        <button type="button" onclick="updateGig()"
                            class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                        <button type="button" onclick="closeEditGigPopup()"
                            class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancel</button>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        function openEditGigPopup(gig) {
            document.getElementById('editGigForm').reset();

            // Populate the form with gig data
            document.getElementById('gig_id').value = gig.gig_id;
            document.getElementById('gig_name').value = gig.gig_name;
            document.getElementById('description').value = gig.description;
            document.getElementById('price').value = gig.price;

            // Show the popup by removing 'hidden' class
            document.getElementById('editGigPopup').classList.remove('hidden');

            console.log(gig); // Debugging to confirm data passed
        }

        function closeEditGigPopup() {
            // Hide the popup by adding 'hidden' class
            document.getElementById('editGigPopup').classList.add('hidden');
        }

        function updateGig() {
            const formData = new FormData();
            formData.append('gig_id', document.getElementById('gig_id').value);
            formData.append('gig_name', document.getElementById('gig_name').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('price', document.getElementById('price').value);

            fetch('update_gig.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert('Gig updated successfully!');
                    closeEditGigPopup(); // Close popup after updating
                    location.reload(); // Reload page to reflect changes
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>