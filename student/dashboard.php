<?php
// Start session and include connection
session_start();
require_once './db.php';

// Check if user is logged in as a creator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
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
    <style>
    /* Custom styles for modern look */
    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #374151;
        border-radius: 0.5rem;
        transition: all 0.15s ease-in-out;
        margin-bottom: 0.25rem;
    }

    .sidebar-link:hover {
        background-color: #F0FDF4;
        color: #16A34A;
    }

    .sidebar-link.active {
        background-color: #F0FDF4;
        color: #16A34A;
        font-weight: 500;
    }

    .sidebar-link ion-icon {
        margin-right: 0.75rem;
        font-size: 1.25rem;
    }

    .profile-container {
        position: relative;
        display: inline-block;
    }

    .status-indicator {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 12px;
        height: 12px;
        background-color: #16A34A;
        border: 2px solid white;
        border-radius: 50%;
    }

    .project-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #F3F4F6;
        transition: all 0.2s ease-in-out;
        height: 100%;
    }

    .project-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .download-button {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
    }

    .download-button-primary {
        background-color: #EFF6FF;
        color: #2563EB;
    }

    .download-button-primary:hover {
        background-color: #DBEAFE;
    }

    .download-button-secondary {
        background-color: #F3F4F6;
        color: #4B5563;
    }

    .download-button-secondary:hover {
        background-color: #E5E7EB;
    }

    .price-badge {
        background-color: #F0FDF4;
        color: #16A34A;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .create-button {
        display: inline-flex;
        align-items: center;
        padding: 0.625rem 1.25rem;
        background-color: #16A34A;
        color: white;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .create-button:hover {
        background-color: #15803D;
    }

    .create-button ion-icon {
        margin-right: 0.5rem;
    }

    /* Email display styles */
    .email-more {
        max-width: 200px;
        margin: 0 auto;
        font-size: 0.875rem;
        color: #6B7280;
    }

    .hidden-full-email {
        display: none;
    }

    .view-all {
        color: #2563EB;
        cursor: pointer;
        text-decoration: underline;
        font-size: 0.75rem;
    }

    /* Mobile sidebar improvements */
    @media (max-width: 768px) {
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 256px;
            height: 100vh;
            background-color: white;
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-sidebar.open {
            transform: translateX(0);
        }

        .mobile-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }

        .mobile-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }
    }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <?php include 'nav.php'; ?>

    <div class="flex min-h-screen" x-data="{ open: false }">
        <!-- Desktop Sidebar -->
        <aside class="bg-white w-64 md:block hidden border-r border-gray-100">
            <div class="p-6 text-center">
                <div class="profile-container">
                    <img src="../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>"
                        alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto ring-4 ring-green-50">
                    <div class="status-indicator"></div>
                </div>
                <p class="mt-4 text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($user['name']); ?></p>
                <p class="email-more">
                    <?php
                    $email = htmlspecialchars($user['email']);
                    if (strlen($email) > 20) {
                        echo substr($email, 0, 20) . '...';
                        echo '<span class="hidden-full-email">' . $email . '</span>';
                        echo ' <a href="#" class="view-all" onclick="showFullEmail(event)">view all</a>';
                    } else {
                        echo $email;
                    }
                    ?>
                </p>
            </div>

            <nav class="mt-6 px-4">
                <a href="dashboard.php" class="sidebar-link active">
                    <ion-icon name="grid-outline"></ion-icon> Dashboard
                </a>
                <a href="create_project.php" class="sidebar-link">
                    <ion-icon name="add-circle-outline"></ion-icon> Create Project
                </a>
                <a href="bank.php" class="sidebar-link">
                    <ion-icon name="wallet-outline"></ion-icon> Account
                </a>
                <a href="clients.php" class="sidebar-link">
                    <ion-icon name="people-outline"></ion-icon> Clients
                </a>
                <a href="profile.php" class="sidebar-link">
                    <ion-icon name="person-outline"></ion-icon> Profile Settings
                </a>
                <a href="../logout.php" class="sidebar-link text-red-600 hover:bg-red-50 mt-6">
                    <ion-icon name="log-out-outline"></ion-icon> Logout
                </a>
            </nav>
        </aside>

        <!-- Mobile Sidebar -->
        <div class="md:hidden">
            <button @click="open = !open" class="p-4 text-gray-600">
                <ion-icon name="menu-outline" class="text-2xl"></ion-icon>
            </button>

            <div x-show="open" @click="open = false" class="mobile-overlay"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div x-show="open" class="mobile-sidebar" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full">
                <!-- Mobile sidebar content - same as desktop -->
                <div class="p-6 text-center">
                    <div class="profile-container">
                        <img src="../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>"
                            alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto ring-4 ring-green-50">
                        <div class="status-indicator"></div>
                    </div>
                    <p class="mt-4 text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($user['name']); ?>
                    </p>
                    <p class="email-more">
                        <!-- Same email logic as desktop -->
                    </p>
                </div>

                <nav class="mt-6 px-4">
                    <!-- Same navigation links as desktop -->
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Your Projects</h2>
                    <a href="create_project.php" class="create-button">
                        <ion-icon name="add-outline"></ion-icon>
                        Create New Project
                    </a>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($projects as $project): ?>
                    <div class="project-card">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-bold text-gray-900">
                                    <?php echo htmlspecialchars($project['title']); ?></h3>
                                <span class="price-badge">
                                    $<?php echo htmlspecialchars($project['price']); ?>
                                </span>
                            </div>

                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($project['description']); ?></p>

                            <p class="text-sm text-gray-500 mb-4">
                                Created on: <?php echo htmlspecialchars($project['created_date']); ?>
                            </p>

                            <?php if (!empty($project['project_file'])): ?>
                            <div class="mb-3">
                                <a href="../creator/uploaded/<?php echo htmlspecialchars($project['project_file']); ?>"
                                    download class="download-button download-button-primary">
                                    <ion-icon name="download-outline" class="mr-2"></ion-icon>
                                    Download Project
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($project['writeup_file'])): ?>
                            <div>
                                <a href="../creator/uploaded/<?php echo htmlspecialchars($project['writeup_file']); ?>"
                                    download class="download-button download-button-secondary">
                                    <ion-icon name="document-outline" class="mr-2"></ion-icon>
                                    Download Writeup
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
    function showFullEmail(event) {
        event.preventDefault();
        const emailElement = event.target.previousElementSibling;
        const emailDisplay = event.target.parentElement;
        emailDisplay.innerHTML = emailElement.innerHTML;
    }
    </script>
</body>

</html>