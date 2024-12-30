<?php
session_start();
require_once './db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch bought projects with creator information
$query = "SELECT p.*, pi.interest_date, u.name as creator_name, u.email as creator_email
          FROM project_interests pi
          JOIN projects p ON pi.project_id = p.id
          JOIN users u ON p.creator_id = u.id
          WHERE pi.user_id = :user_id AND pi.is_bought = 1
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
    <title>Purchased Projects - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>

    <div class="container mx-auto mt-10 px-4">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Your Purchased Projects</h1>

            <?php if (empty($projects)): ?>
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Purchased Projects Yet</h3>
                    <p class="text-gray-500 mb-6">Start exploring and purchase your first project!</p>
                    <a href="find_project.php" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Browse Available Projects
                        <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($projects as $project): ?>
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="p-2 bg-green-50 rounded-full mr-3">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($project['title']); ?></h2>
                                </div>
                                
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($project['description']); ?></p>
                                
                                <div class="space-y-2">
                                    <p class="flex items-center text-gray-600">
                                        <span class="font-medium mr-2">Creator:</span>
                                        <span><?php echo htmlspecialchars($project['creator_name']); ?></span>
                                    </p>
                                    <p class="flex items-center text-gray-500 text-sm">
                                        <span class="font-medium mr-2">Purchased on:</span>
                                        <span><?php echo date('M j, Y', strtotime($project['interest_date'])); ?></span>
                                    </p>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                <div class="flex space-x-3">
                                    <a href="<?php echo htmlspecialchars($project['project_file']); ?>" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200"
                                       download>
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download Project
                                    </a>
                                    <button onclick="openMessagePopup('<?php echo htmlspecialchars($project['creator_id']); ?>', '<?php echo htmlspecialchars($project['creator_email']); ?>')"
                                            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Contact Creator
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Message Popup -->
    <div id="messagePopup" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-xl p-6 w-96 max-w-full mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Send Message</h3>
                <button onclick="closeMessagePopup()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="send_message.php" method="POST" class="space-y-4">
                <input type="hidden" id="receiver_id" name="receiver_id">
                <input type="hidden" id="receiver_email" name="receiver_email">
                <div>
                    <label for="message_content" class="block text-sm font-medium text-gray-700 mb-1">Your Message</label>
                    <textarea id="message_content" name="message_content" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="Write your message here..." required></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeMessagePopup()"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Send Message
                    </button>
                </div>
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
</body>

</html>