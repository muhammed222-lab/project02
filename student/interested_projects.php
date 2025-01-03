<?php
session_start();
require_once '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query); // Changed $conn to $pdo
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch the user's email from the session if it exists
$buyer_email = $user['email'];

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

$stmt = $pdo->prepare($query); // Changed $conn to $pdo
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to calculate days since interest
function daysSinceInterest($interestDate) {
    $now = new DateTime();
    $interest = new DateTime($interestDate);
    return $interest->diff($now)->days;
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interested Projects | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .project-card {
        transition: all 0.3s ease-in-out;
        transform: translateY(0);
    }

    .project-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .animate-pulse-slow {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }
    </style>
</head>

<body class="antialiased">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 max-w-7xl">
        <header class="mb-12 text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Interested Projects</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Explore the projects that have caught your eye. Review
                details, contact creators, and take the next step towards your goals.</p>
        </header>

        <?php if (empty($projects)): ?>
        <div class="bg-white rounded-2xl shadow-xl p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="bg-blue-50 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-2v8m0 0l3-3m-3 3L9 9m-4 4h8a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">No Interested Projects Yet</h3>
                <p class="text-gray-600 mb-8">Explore our project marketplace and find projects that spark your
                    interest. Your journey starts here!</p>
                <a href="find_project.php"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Projects
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($projects as $project): 
                $daysSinceInterest = daysSinceInterest($project['interest_date']);
            ?>
            <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-xl font-bold text-gray-800 flex-1 mr-4">
                            <?php echo htmlspecialchars($project['title']); ?>
                        </h2>
                        <span class="bg-blue-50 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                            <?php echo $daysSinceInterest; ?> days ago
                        </span>
                    </div>

                    <p class="text-gray-600 mb-4 line-clamp-3">
                        <?php echo htmlspecialchars($project['description']); ?>
                    </p>

                    <div class="space-y-3 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Budget: $<?php echo number_format($project['price'], 2); ?></span>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Creator: <?php echo htmlspecialchars($project['creator_name']); ?></span>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button
                            onclick="openMessagePopup('<?php echo htmlspecialchars($project['creator_id']); ?>', '<?php echo htmlspecialchars($project['creator_email']); ?>')"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Contact Creator
                        </button>
                        <button
                            onclick="startPayment('<?php echo $project['price']; ?>', '<?php echo htmlspecialchars($project['title']); ?>')"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Purchase
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">Send Message</h3>
                    <button onclick="closeMessageModal()" class="hover:bg-white/20 rounded-full p-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <form action="send_message.php" method="POST" class="p-6 space-y-4">
                <input type="hidden" id="receiver_id" name="receiver_id">
                <input type="hidden" id="receiver_email" name="receiver_email">
                <div>
                    <label for="message_content" class="block text-sm font-medium text-gray-700 mb-2">Your
                        Message</label>
                    <textarea id="message_content" name="message_content" rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Write your message to the project creator..." required></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeMessageModal()"
                        class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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
        document.getElementById('messageModal').classList.remove('hidden');
    }

    function closeMessageModal() {
        document.getElementById('messageModal').classList.add('hidden');
    }

    function startPayment(amount, projectTitle) {
        window.location.href = `process_payment.php?amount=${amount}&title=${encodeURIComponent(projectTitle)}`;
    }
    </script>
</body>

</html>