<?php
session_start();
require_once './db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$creator_id = $_GET['creator_id'] ?? null;

// Fetch creator details
$query = "SELECT * FROM users WHERE id = :creator_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':creator_id', $creator_id, PDO::PARAM_INT);
$stmt->execute();
$creator = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if creator exists
if (!$creator) {
    echo "Error: Creator not found.";
    exit();
}

// Fetch messages
$query = "SELECT * FROM messages 
          WHERE (sender_id = :user_id AND receiver_id = :creator_id) 
             OR (sender_id = :creator_id AND receiver_id = :user_id) 
          ORDER BY created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':creator_id', $creator_id, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($creator['email']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>

    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Chat with <?php echo htmlspecialchars($creator['email']); ?></h1>

        <div class="bg-white shadow-md rounded-lg p-4 h-96 overflow-auto">
            <?php foreach ($messages as $message): ?>
            <div class="<?php echo $message['sender_id'] == $user_id ? 'text-right' : 'text-left'; ?>">
                <p class="font-semibold">
                    <?php echo htmlspecialchars($message['sender_id'] == $user_id ? 'You' : 'Creator'); ?></p>
                <p class="text-gray-700"><?php echo htmlspecialchars($message['content']); ?></p>
                <p class="text-gray-500 text-xs"><?php echo htmlspecialchars($message['created_at']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <form action="send_message.php" method="POST" class="mt-4">
            <input type="hidden" name="receiver_id" value="<?php echo $creator_id; ?>">
            <textarea name="message_content" placeholder="Write your message here..." required
                class="border rounded p-2 w-full"></textarea>
            <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Send Message</button>
        </form>
    </div>
</body>

</html>