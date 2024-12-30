<?php
session_start();
require_once './db.php'; // Ensure this points to the correct path for your db.php file

// Check if the user is logged in and is a creator
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$creator_id = $_SESSION['user_id']; // Get the creator's ID from the session
$creator_email = $_SESSION['email'] ?? null; // Get the creator's email if needed

// Fetch clients for the creator
$query = "SELECT c.buyer_name, c.buyer_email, c.project_title, c.payment_status, c.urgent, c.created_at, c.status 
          FROM clients c 
          WHERE c.creator_id = :creator_id OR c.creator_email = :creator_email";

$stmt = $pdo->prepare($query);
$stmt->execute([':creator_id' => $creator_id, ':creator_email' => $creator_email]);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include 'nav.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-green-700 mb-6">Your Clients</h1>

        <?php if (empty($clients)): ?>
            <p class="text-gray-600">You have no clients yet.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($clients as $client): ?>
                    <div class="bg-white rounded-lg border border-gray-300 p-4 transition-all duration-300 hover:scale-105">
                        <h2 class="text-xl font-semibold text-green-700">
                            <?php echo htmlspecialchars($client['project_title']); ?></h2>
                        <p class="text-gray-600">Buyer Name: <?php echo htmlspecialchars($client['buyer_name']); ?></p>
                        <p class="text-gray-600">Buyer Email: <?php echo htmlspecialchars($client['buyer_email']); ?></p>
                        <p class="text-gray-500">Payment Status: <?php echo htmlspecialchars($client['payment_status']); ?></p>
                        <p class="text-gray-500">Urgent: <?php echo htmlspecialchars($client['urgent'] ? 'True' : 'False'); ?>
                        </p>
                        <p class="text-gray-500">Date Ordered: <?php echo htmlspecialchars($client['created_at']); ?></p>
                        <p class="p-2 text-green-600 font-bold capitalize"><?php echo htmlspecialchars($client['status']); ?>
                        </p>
                        <div class="mt-4">
                            <button class="bg-green-500 text-white rounded px-4 py-2 hover:bg-green-600 accept-btn"
                                data-project-title="<?php echo htmlspecialchars($client['project_title']); ?>"
                                data-buyer-email="<?php echo htmlspecialchars($client['buyer_email']); ?>"
                                data-action="accept">Accept</button>
                            <button class="bg-red-500 text-white rounded px-4 py-2 hover:bg-red-600 reject-btn"
                                data-project-title="<?php echo htmlspecialchars($client['project_title']); ?>"
                                data-buyer-email="<?php echo htmlspecialchars($client['buyer_email']); ?>"
                                data-action="reject">Reject</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="./javascript/projectStatus.js"></script>

</body>

</html>