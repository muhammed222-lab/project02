<?php
// Start session and include connection
session_start();
require_once '../db.php';

// Check if user is logged in as a creator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
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
                <p class="text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
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
                            <ion-icon name="add-circle-outline" class="mr-3"></ion-icon> Create Course
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="job.php" class="flex items-center p-2 text-green-700 hover:bg-green-50">
                            <ion-icon name="people-outline" class="mr-3"></ion-icon> Find Gig
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

        <!-- Main Content -->
        <!-- Freelancer Dashboard Header -->
        <main class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-4">Your Instructor Profile</h2>

            <!-- "Open to Work" Toggle Button -->
            <div class="flex justify-end mb-4">
                <!-- Container for the entire Dashboard page -->
                <div x-data="{ openToWork: false, aboutMeModal: false }">

                    <!-- Toggle Button for Open to Work -->
                    <button @click="openToWork = !openToWork" class="bg-gray-200 text-green-700 mt-4">
                        <span x-text="openToWork ? 'Open to Work' : 'Not Open to Work'"></span>
                    </button>

                    <!-- "About Me" Section with Pop-up Modal -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold">About Me</h3>
                        <p class="text-gray-600"><?php echo htmlspecialchars($user['about_me']); ?></p>
                        <button @click="aboutMeModal = true" class="text-green-700 mt-2">Edit</button>

                        <!-- Modal for Editing About Me -->
                        <div x-show="aboutMeModal"
                            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                                <h2 class="text-2xl font-bold mb-4">Edit About Me</h2>
                                <textarea id="aboutMeTextarea" class="w-full p-2 border rounded-md"
                                    rows="4"><?php echo htmlspecialchars($user['about_me']); ?></textarea>
                                <button @click="aboutMeModal = false; updateAboutMe();"
                                    class="mt-4 bg-green-600 text-white py-2 px-4 rounded-md">Save</button>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Add similar sections for Skills, Experience, Stack, Portfolio, etc. -->
        </main>

    </div>

    <script>
    // Example: AJAX request to update "About Me"
    function updateAboutMe() {
        let aboutMeText = document.querySelector('#aboutMeTextarea').value;

        fetch('update_freelancer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `about_me=${encodeURIComponent(aboutMeText)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('About Me updated successfully');
                    // Optionally update the UI here
                } else {
                    alert('Failed to update About Me');
                }
            });
    }
    </script>

</body>

</html>