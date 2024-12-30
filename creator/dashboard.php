<?php
// Start session and include connection
session_start();
require_once './db.php';

// Check if user is logged in as a creator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'creator') {
    header("Location: ../login.php");
    exit();
}

// Get Creator Details
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, profile_picture FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get Projects Created by the Creator
$projectQuery = "SELECT * FROM projects WHERE creator_id = :creator_id";
$projectStmt = $pdo->prepare($projectQuery);
$projectStmt->execute([':creator_id' => $user_id]);
$projects = $projectStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Creator Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.5.2/collection/components/icon/icon.min.css"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.5.2/dist/cdn.min.js" defer></script>
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include 'nav.php'; ?>
    <!-- Container -->
    <div class="flex min-h-screen" x-data="{ open: false }">

        <!-- Sidebar -->
        <aside class="bg-white w-64 md:block hidden border-l border-gray-200">
            <div class="p-4 text-center">
                <img src="../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"
                    class="w-32 h-32 rounded-full mx-auto">
                <p class="mt-4 text-lg font-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                <p class="email-more"><?php
                                        // Trim the email to 20 characters and add ellipsis if necessary
                                        $email = htmlspecialchars($user['email']);
                                        if (strlen($email) > 20) {
                                            echo substr($email, 0, 20) . '...';
                                            echo '<span class="hidden-full-email" style="display: none;">' . $email . '</span>';
                                            echo ' <a href="#" class="view-all" onclick="showFullEmail(event)">view all</a>';
                                        } else {
                                            echo $email;
                                        }
                                        ?></p>
            </div>
            <nav class="mt-6">
                <ul>
                    <li class="mb-4">
                        <a href="dashboard.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                            <ion-icon name="grid-outline" class="mr-3"></ion-icon> Dashboard
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="create_project.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                            <ion-icon name="add-circle-outline" class="mr-3"></ion-icon> Create Project
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="bank.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                            <ion-icon name="add-circle-outline" class="mr-3"></ion-icon> Account
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="clients.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                            <ion-icon name="people-outline" class="mr-3"></ion-icon> Clients
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="profile.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                            <ion-icon name="person-outline" class="mr-3"></ion-icon> Profile Settings
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="../logout.php" class="flex items-center p-2 text-red-500 hover:bg-red-50">
                            <ion-icon name="log-out-outline" class="mr-3"></ion-icon> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Mobile Sidebar -->
        <div class="md:hidden">
            <button @click="open = !open" class="p-4 text-green-700">
                <b>⇉</b>
            </button>
            <div x-show="open" class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
            <div x-show="open" class="fixed inset-y-0 left-0 w-64 bg-white border-l border-gray-200">
                <div class="p-4 text-center">
                    <img src="../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>"
                        alt="Profile Picture" class="w-32 h-32 rounded-full mx-auto">
                    <p class="mt-4 text-lg font-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                    <p class="text-gray-600 email-display">
                        <?php
                        // Trim the email to 20 characters and add ellipsis if necessary
                        $email = htmlspecialchars($user['email']);
                        if (strlen($email) > 20) {
                            echo substr($email, 0, 20) . '...';
                            echo '<span class="hidden-full-email" style="display: none;">f-' . $email . '</span>';
                            echo ' <a href="#" class="view-all" onclick="showFullEmail(event)">view all</a>';
                        } else {
                            echo $email;
                        }
                        ?>
                    </p>

                </div>
                <nav class="mt-6">
                    <ul>
                        <li class="mb-4">
                            <a href="dashboard.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                                <ion-icon name="grid-outline" class="mr-3"></ion-icon> Dashboard
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="create_project.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                                <ion-icon name="add-circle-outline" class="mr-3"></ion-icon> Create Project
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="clients.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                                <ion-icon name="people-outline" class="mr-3"></ion-icon> Clients
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="bank.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                                <ion-icon name="add-circle-outline" class="mr-3"></ion-icon> Account
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="profile.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                                <ion-icon name="person-outline" class="mr-3"></ion-icon> Profile Settings
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="../logout.php" class="flex items-center p-2 text-red-500 hover:bg-red-50">
                                <ion-icon name="log-out-outline" class="mr-3"></ion-icon> Logout
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-4">Your Projects</h2>
            <a href="create_project.php" class="bg-green-600 text-white py-2 px-4 rounded-md inline-block mb-4">
                <ion-icon name="add-outline" class="mr-2"></ion-icon>Create New Project
            </a>
            <div class="grid md:grid-cols-2 gap-4">
                <?php foreach ($projects as $project): ?>
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <!-- Updated border instead of shadow -->
                    <h3 class="text-xl font-bold"><?php echo htmlspecialchars($project['title']); ?></h3>
                    <p class="text-gray-700 mt-2"><?php echo htmlspecialchars($project['description']); ?></p>
                    <p class="text-green-700 font-semibold mt-4">Price:
                        $<?php echo htmlspecialchars($project['price']); ?></p>
                    <p class="text-gray-500 mt-1">Created on: <?php echo htmlspecialchars($project['created_date']); ?>
                    </p>

                    <!-- Display project file and provide a download button -->
                    <?php if (!empty($project['project_file'])): ?>
                    <div class="mt-4">
                        <p class="font-semibold text-gray-800">Project File:</p>
                        <a href="../creator/uploaded/<?php echo htmlspecialchars($project['project_file']); ?>" download
                            class="text-blue-600 hover:underline">Download Project File</a>
                    </div>
                    <?php endif; ?>

                    <!-- Display writeup file and provide a download button -->
                    <?php if (!empty($project['writeup_file'])): ?>
                    <div class="mt-4">
                        <p class="font-semibold text-gray-800">Writeup File:</p>
                        <a href="../creator/uploaded/<?php echo htmlspecialchars($project['writeup_file']); ?>" download
                            class="text-blue-600 hover:underline">Download Writeup</a>
                    </div>
                    <?php endif; ?>
                </div>

                <?php endforeach; ?>
            </div>
        </main>
    </div>
    <style>
    .email-display {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        word-break: break-all;
        /* Adjust based on your layout */
    }

    .hidden-full-email {
        display: none;
    }

    .view-all {
        color: #007bff;
        cursor: pointer;
        text-decoration: underline;
        font-size: 0.875rem;
    }

    .email-more {
        width: 180px;
        word-wrap: break-word;
        text-align: center;
    }
    </style>
    <script>
    function showFullEmail(event) {
        event.preventDefault();
        const emailElement = event.target.previousElementSibling;
        const emailDisplay = event.target.parentElement;

        // Replace the trimmed text with the full email
        emailDisplay.innerHTML = emailElement.innerHTML;
    }
    </script>

</body>

</html>