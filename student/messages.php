<?php
session_start();
require_once '../php/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch conversations
$query = "SELECT DISTINCT 
            CASE 
                WHEN m.sender_id = :user_id THEN m.receiver_id 
                ELSE m.sender_id 
            END as contact_id,
            u.name, 
            u.email,
            MAX(m.created_at) as last_message_time,
            m.content as last_message
          FROM messages m
          JOIN users u ON u.id = CASE 
                WHEN m.sender_id = :user_id THEN m.receiver_id 
                ELSE m.sender_id 
            END
          WHERE m.sender_id = :user_id OR m.receiver_id = :user_id
          GROUP BY contact_id, u.name, u.email
          ORDER BY last_message_time DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to format message time
function formatMessageTime($timestamp) {
    $messageTime = new DateTime($timestamp);
    $now = new DateTime();
    $diff = $now->diff($messageTime);

    if ($diff->d == 0) return $messageTime->format('H:i');
    if ($diff->d == 1) return 'Yesterday';
    if ($diff->y == 0) return $messageTime->format('M d');
    return $messageTime->format('M d, Y');
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom Theme Styles */
        :root {
            --bg-primary: #222831;
            --bg-secondary: #393E46;
            --accent-color: #00ADB5;
            --text-primary: #EEEEEE;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Conversation Card Styles */
        .conversation-card {
            background-color: var(--bg-secondary);
            transition: all 0.3s ease-in-out;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .conversation-card:hover {
            background-color: rgba(57, 62, 70, 0.8);
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.2);
        }

        /* Search Input Styles */
        #conversation-search {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--accent-color);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        /* Empty State Styles */
        .empty-state {
            background-color: var(--bg-secondary);
            border-radius: 1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 640px) {
            .conversation-card {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body class="antialiased">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 max-w-4xl">
        <header class="mb-12 text-center animate-fade-in">
            <h1 class="text-5xl font-bold mb-4" style="color: var(--accent-color);">Your Conversations</h1>
            <p class="text-xl max-w-2xl mx-auto text-gray-300">
                Connect, collaborate, and communicate with project creators and fellow students.
            </p>
        </header>

        <div class="bg-[var(--bg-secondary)] shadow-2xl rounded-2xl overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-[rgba(255,255,255,0.1)]">
                <div class="relative">
                    <input 
                        type="text" 
                        id="conversation-search" 
                        placeholder="Search conversations..." 
                        class="w-full px-4 py-3 pl-10 rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] transition-all"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-4 text-[var(--accent-color)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <?php if (empty($conversations)): ?>
            <div class="p-16 text-center empty-state">
                <div class="bg-[var(--accent-color)] rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4">No Conversations Yet</h3>
                <p class="text-gray-300 mb-8">Start exploring projects and connect with creators to begin messaging.</p>
                <a href="find_project.php" 
                   class="inline-flex items-center px-6 py-3 bg-[var(--accent-color)] hover:bg-opacity-80 text-white rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Projects
                </a>
            </div>
            <?php else: ?>
            <div id="conversations-list">
                <?php foreach ($conversations as $conversation): ?>
                <a href="chat.php?creator_id=<?php echo $conversation['contact_id']; ?>" 
                   class="conversation-card block hover:bg-opacity-80 transition-colors">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-12 h-12 bg-[var(--accent-color)] rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">
                                    <?php echo strtoupper(substr($conversation['name'], 0, 1)); ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h2 class="text-lg font-semibold truncate">
                                    <?php echo htmlspecialchars($conversation['name']); ?>
                                </h2>
                                <span class="text-sm text-gray-400">
                                    <?php echo formatMessageTime($conversation['last_message_time']); ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-300 truncate">
                                <?php echo htmlspecialchars($conversation['last_message']); ?>
                            </p>
                        </div>
                        <div class="ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--accent-color)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('conversation-search');
        const conversationsList = document.getElementById('conversations-list');

        // Conversation search functionality with debounce
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const conversations = conversationsList.querySelectorAll('a');

                conversations.forEach(conversation => {
                    const name = conversation.querySelector('h2').textContent.toLowerCase();
                    const lastMessage = conversation.querySelector('p').textContent.toLowerCase();

                    if (name.includes(searchTerm) || lastMessage.includes(searchTerm)) {
                        conversation.style.display = 'block';
                        conversation.classList.add('animate-fade-in');
                    } else {
                        conversation.style.display = 'none';
                    }
                });
            }, 300);
        });
    });
    </script>
</body>
</html>