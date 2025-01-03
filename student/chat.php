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

// Fetch user details
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

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
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($creator['name']); ?> | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/student.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #222831 0%, #393E46 100%);
            min-height: 100vh;
            color: #EEEEEE;
        }
        .message-container {
            max-height: 70vh;
            overflow-y-auto;
            scroll-behavior: smooth;
        }
        .message-enter {
            opacity: 0;
            transform: translateY(10px);
        }
        .message-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: all 300ms ease-in-out;
        }
        .typing-indicator {
            animation: typing 1.4s infinite;
        }
        @keyframes typing {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body class="antialiased bg-[#222831] text-[#EEEEEE]">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 max-w-4xl">
            <!-- Chat container with blur background -->
            <div class="bg-[#393E46]/95 backdrop-blur-md rounded-2xl overflow-hidden border border-[#00ADB5]/20">
            <!-- Chat Header -->
            <div class="bg-gradient-to-r from-[#00ADB5] to-[#393E46] p-6 text-[#EEEEEE] flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="messages.php" class="hover:bg-white/20 p-2 rounded-full transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">
                                <?php echo strtoupper(substr($creator['name'], 0, 1)); ?>
                            </span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold"><?php echo htmlspecialchars($creator['name']); ?></h2>
                            <p id="typing-indicator" class="text-sm text-[#00ADB5] hidden">
                                <span class="typing-indicator">typing...</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="hover:bg-white/20 p-2 rounded-full transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <button class="hover:bg-white/20 p-2 rounded-full transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chat-messages" class="message-container bg-[#393E46] p-6 space-y-4 h-[500px] overflow-y-auto">
                <?php if (empty($messages)): ?>
                    <div class="flex items-center justify-center h-full text-center">
                        <div>
                            <div class="bg-[#00ADB5]/10 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-[#EEEEEE] mb-4">No Messages Yet</h3>
                            <p class="text-[#EEEEEE]/80">Start the conversation by sending a message below.</p>
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
                            <span class="bg-[#00ADB5]/10 text-[#EEEEEE] text-xs px-3 py-1 rounded-full">
                                <?php echo $displayDate; ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="message-enter <?php echo $message['sender_id'] == $user_id ? 'flex justify-end' : 'flex justify-start'; ?>">
                        <div class="<?php echo $message['sender_id'] == $user_id ? 'bg-[#00ADB5] text-[#EEEEEE]' : 'bg-[#393E46] text-[#EEEEEE] border border-[#00ADB5]/50'; ?> rounded-xl px-4 py-3 max-w-[70%] shadow-sm">
                            <p class="text-sm"><?php echo htmlspecialchars($message['content']); ?></p>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs <?php echo $message['sender_id'] == $user_id ? 'text-[#00ADB5]/80' : 'text-[#EEEEEE]/80'; ?>">
                                    <?php echo $messageDate->format('H:i'); ?>
                                </p>
                                <?php if ($message['sender_id'] == $user_id): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#00ADB5]/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Message Input -->
            <div class="bg-[#393E46] p-6 border-t border-[#00ADB5]/20">
                <form id="message-form" action="send_message.php" method="POST" class="flex space-x-4">
                    <input type="hidden" name="receiver_id" value="<?php echo $creator_id; ?>">
                    <div class="flex-1">
                        <textarea 
                            name="message_content" 
                            id="message-input"
                            placeholder="Type your message..." 
                            required
                            rows="3"
                            class="w-full px-4 py-3 border border-[#00ADB5]/50 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent resize-none bg-[#393E46] text-[#EEEEEE]"
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        class="bg-gradient-to-r from-[#00ADB5] to-[#393E46] text-[#EEEEEE] rounded-lg px-6 py-3 hover:from-[#00ADB5]/90 hover:to-[#393E46]/90 transition-all transform hover:scale-105 flex items-center"
                    >
                        <span>Send</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const chatMessages = document.getElementById('chat-messages');
        const typingIndicator = document.getElementById('typing-indicator');

        // Scroll to bottom on page load
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Typing indicator
        messageInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                typingIndicator.classList.remove('hidden');
            } else {
                typingIndicator.classList.add('hidden');
            }
        });

        // Form submission
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('send_message.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear input and hide typing indicator
                    messageInput.value = '';
                    typingIndicator.classList.add('hidden');

                    // Optionally, you could append the new message to the chat
                    // This would require a more complex implementation with WebSockets
                } else {
                    alert('Error sending message: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the message');
            });
        });
    });
    </script>
</body>
</html>