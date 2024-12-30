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

// Fetch interested (not bought) projects
$query = "SELECT p.*, pi.interest_date, u.name as creator_name, u.email as creator_email
          FROM project_interests pi
          JOIN projects p ON pi.project_id = p.id
          JOIN users u ON p.creator_id = u.id
          WHERE pi.user_id = :user_id AND pi.is_bought = 0
          ORDER BY pi.interest_date DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($projects as $project): ?>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-purple-50 rounded-full mr-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($project['title']); ?></h2>
                    </div>
                    
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($project['description']); ?></p>
                    
                    <div class="space-y-2">
                        <p class="flex items-center text-gray-600">
                            <span class="font-medium mr-2">Price:</span>
                            <span class="text-green-600 font-semibold">$<?php echo number_format($project['price'], 2); ?></span>
                        </p>
                        <p class="flex items-center text-gray-600">
                            <span class="font-medium mr-2">Creator:</span>
                            <span><?php echo htmlspecialchars($project['creator_name']); ?></span>
                        </p>
                        <p class="flex items-center text-gray-500 text-sm">
                            <span class="font-medium mr-2">Interested since:</span>
                            <span><?php echo date('M j, Y', strtotime($project['interest_date'])); ?></span>
                        </p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="flex space-x-3">
                        <button onclick="openMessagePopup('<?php echo htmlspecialchars($project['creator_id']); ?>', '<?php echo htmlspecialchars($project['creator_email']); ?>')"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Contact Creator
                        </button>
                        <button onclick="startPayment('<?php echo $project['price']; ?>', '<?php echo htmlspecialchars($project['title']); ?>')"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Purchase
                        </button>
                    </div>
                </div>
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