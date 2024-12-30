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

    <div class="container mx-auto mt-8 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Chat Header -->
            <div class="bg-white shadow-lg rounded-t-xl border border-gray-100 p-4">
                <div class="flex items-center">
                    <a href="messages.php" class="mr-4">
                        <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 font-medium text-lg">
                                <?php echo strtoupper(substr($creator['email'], 0, 1)); ?>
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($creator['email']); ?></h2>
                        <p class="text-sm text-gray-500" id="typing-indicator" style="display: none;">typing...</p>
                    </div>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="bg-gray-50 border-l border-r border-gray-100 h-[500px] overflow-y-auto p-4" id="chat-messages">
                <?php if (empty($messages)): ?>
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Messages Yet</h3>
                            <p class="text-gray-500">Start the conversation by sending a message below.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php 
                    $currentDate = null;
                    foreach ($messages as $message): 
                        $messageDate = new DateTime($message['created_at']);
                        $dateStr = $messageDate->format('Y-m-d');
                        
                        if ($currentDate !== $dateStr):
                            $currentDate = $dateStr;
                            $today = new DateTime();
                            $yesterday = new DateTime('yesterday');
                            
                            if ($messageDate->format('Y-m-d') === $today->format('Y-m-d')) {
                                $displayDate = 'Today';
                            } elseif ($messageDate->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                                $displayDate = 'Yesterday';
                            } else {
                                $displayDate = $messageDate->format('F j, Y');
                            }
                    ?>
                        <div class="flex justify-center mb-4">
                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">
                                <?php echo $displayDate; ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4 <?php echo $message['sender_id'] == $user_id ? 'flex justify-end' : 'flex justify-start'; ?>">
                        <div class="<?php echo $message['sender_id'] == $user_id ? 'bg-blue-600 text-white' : 'bg-white text-gray-900'; ?> rounded-lg px-4 py-2 max-w-[70%] shadow-sm">
                            <p class="text-sm"><?php echo htmlspecialchars($message['content']); ?></p>
                            <p class="text-xs <?php echo $message['sender_id'] == $user_id ? 'text-blue-100' : 'text-gray-500'; ?> mt-1">
                                <?php echo $messageDate->format('H:i'); ?>
                                <?php if ($message['sender_id'] == $user_id): ?>
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Message Input -->
            <div class="bg-white shadow-lg rounded-b-xl border border-t-0 border-gray-100 p-4">
                <form action="send_message.php" method="POST" id="message-form" class="flex items-end space-x-4">
                    <input type="hidden" name="receiver_id" value="<?php echo $creator_id; ?>">
                    <div class="flex-1">
                        <textarea name="message_content" 
                                placeholder="Type your message..." 
                                required
                                class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                rows="3"></textarea>
                    </div>
                    <button type="submit" 
                            class="bg-blue-600 text-white rounded-lg px-6 py-3 font-medium hover:bg-blue-700 transition-colors duration-200 flex items-center">
                        <span>Send</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>