<?php
session_start();
require_once '../db.php'; // Ensure this points to the correct path for your db.php file
require_once './db.php'; // Ensure this points to the correct path for your db.php file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch the user's email from the session if it exists
$buyer_email = $user['email']; // Get email from session

// Ensure the email is available before proceeding
if (!$buyer_email) {
    echo "Error: User email not found.";
    exit();
}

// Fetch projects the user interacted with
$query = "SELECT p.title, p.description, p.price, c.delivery_date, c.created_at, c.status, p.creator_id, p.creator_email 
          FROM clients c 
          JOIN projects p ON c.project_title = p.title 
          WHERE c.buyer_email = :buyer_email OR p.creator_email = :buyer_email"; // Check if the creator email matches

$stmt = $pdo->prepare($query);
$stmt->execute([':buyer_email' => $buyer_email]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interested Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-green-700 mb-6">Your Interested Projects</h1>

        <?php if (empty($projects)): ?>
        <p class="text-gray-600">You have not interacted with any projects yet.</p>
        <?php else: ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p class="font-bold">Pending Warning:</p>
            <p>Please check the delivery dates for your projects!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($projects as $project): ?>
            <div
                class="bg-white rounded-lg border-2 border-solid border-gray-200 p-4 border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-green-700"><?php echo htmlspecialchars($project['title']); ?></h2>
                <p class="text-gray-600"><?php echo htmlspecialchars($project['description']); ?></p>
                <p class="font-bold text-green-600">Price: $<?php echo htmlspecialchars($project['price']); ?></p>
                <p class="text-gray-500">Delivery Date:
                    <?php echo htmlspecialchars($project['delivery_date'] ?? 'Not specified'); ?></p>
                <p class="text-gray-500 text-sm">Interacted on: <?php echo htmlspecialchars($project['created_at']); ?>
                </p>

                <!-- Display status message -->
                <p class="font-semibold text-gray-700">
                    Status:
                    <?php
                            echo htmlspecialchars(ucfirst($project['status']));
                            ?>
                </p>

                <!-- Show "Connect with Creator" and "Pay" buttons if accepted -->
                <?php if ($project['status'] === 'accepted'): ?>
                <button class="connect-btn bg-blue-500 text-white px-4 py-2 rounded mt-2 hover:bg-blue-700"
                    onclick="openMessagePopup('<?php echo htmlspecialchars($project['creator_id']); ?>', '<?php echo htmlspecialchars($project['creator_email']); ?>')">
                    Connect with Creator
                </button>
                <button class="pay-btn bg-green-500 text-white px-4 py-2 rounded mt-2 hover:bg-green-700"
                    onclick="startPayment('<?php echo $project['price']; ?>', '<?php echo $project['title']; ?>')">
                    Pay
                </button>

                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Popup for sending message -->
    <div id="messagePopup" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white rounded-lg p-6">
            <form action="send_message.php" method="POST">
                <input type="hidden" id="receiver_id" name="receiver_id">
                <input type="hidden" id="receiver_email" name="receiver_email">
                <textarea name="message_content" placeholder="Write your message here..." required
                    class="w-36"></textarea>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 hover:bg-blue-700">Send
                    Message</button>
                <button type="button" onclick="closeMessagePopup()"
                    class="bg-gray-500 text-white px-4 py-2 rounded mt-2 hover:bg-gray-700">Cancel</button>
            </form>
        </div>
    </div>

    <script>
    function openMessagePopup(creatorId, creatorEmail) {
        document.getElementById('receiver_id').value = creatorId;
        document.getElementById('receiver_email').value = creatorEmail;
        document.getElementById('messagePopup').classList.remove('hidden');
    }

    function closeMessagePopup() {
        document.getElementById('messagePopup').classList.add('hidden');
    }
    </script>
    <script>
    function startPayment(amount, projectTitle) {
        // Redirect to the payment processing script with project details
        window.location.href = `process_payment.php?amount=${amount}&title=${encodeURIComponent(projectTitle)}`;
    }
    </script>

</body>

</html>