<?php
session_start();
$host = 'localhost'; // Database host
$db = 'project_02'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password (usually empty for XAMPP)

// Set up the DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    // Create a PDO instance
    $conn = new PDO($dsn, $user, $pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
    exit();
}
// Ensure this points to the correct path for your db.php file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT DISTINCT c.creator_id, u.email
          FROM clients c 
          JOIN users u ON c.creator_id = u.id 
          WHERE c.buyer_email = :buyer_email";

$stmt = $conn->prepare($query);
$stmt->bindParam(':buyer_email', $_SESSION['buyer_email'], PDO::PARAM_STR);
$stmt->execute();
$creators = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>

    <div class="container mx-auto mt-8 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Messages</h1>
                <div class="relative">
                    <input type="text" id="search-conversations" placeholder="Search conversations..." 
                           class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-xl border border-gray-100">
                <?php if (empty($creators)): ?>
                    <div class="p-8 text-center">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Messages Yet</h3>
                        <p class="text-gray-500 mb-6">Start a conversation by exploring projects and connecting with creators.</p>
                        <a href="find_project.php" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Browse Projects
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($creators as $creator): 
                            // Fetch last message for this conversation
                            $lastMessageQuery = "SELECT content, created_at, sender_id 
                                              FROM messages 
                                              WHERE (sender_id = :user_id AND receiver_id = :creator_id)
                                                 OR (sender_id = :creator_id AND receiver_id = :user_id)
                                              ORDER BY created_at DESC 
                                              LIMIT 1";
                            $stmt = $conn->prepare($lastMessageQuery);
                            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                            $stmt->bindParam(':creator_id', $creator['creator_id'], PDO::PARAM_INT);
                            $stmt->execute();
                            $lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                            <a href="chat.php?creator_id=<?php echo $creator['creator_id']; ?>" 
                               class="flex items-center p-4 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-medium text-lg">
                                            <?php echo strtoupper(substr($creator['email'], 0, 1)); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-sm font-medium text-gray-900 truncate">
                                            <?php echo htmlspecialchars($creator['email']); ?>
                                        </h2>
                                        <?php if ($lastMessage): ?>
                                            <span class="text-xs text-gray-500">
                                                <?php 
                                                    $messageDate = new DateTime($lastMessage['created_at']);
                                                    $now = new DateTime();
                                                    $diff = $now->diff($messageDate);
                                                    
                                                    if ($diff->d == 0) {
                                                        echo $messageDate->format('H:i');
                                                    } elseif ($diff->d == 1) {
                                                        echo 'Yesterday';
                                                    } else {
                                                        echo $messageDate->format('M d');
                                                    }
                                                ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($lastMessage): ?>
                                        <p class="text-sm text-gray-500 truncate">
                                            <?php 
                                                echo $lastMessage['sender_id'] == $_SESSION['user_id'] ? 
                                                    'You: ' : '';
                                                echo htmlspecialchars(substr($lastMessage['content'], 0, 50)) . 
                                                    (strlen($lastMessage['content']) > 50 ? '...' : '');
                                            ?>
                                        </p>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-400 italic">No messages yet</p>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>