<?php
session_start();
include('./db.php');

// Verify if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

// Fetch user data using PDO
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle case where user data could not be fetched
if (!$user) {
    echo "User data not found.";
    exit();
}

// Fetch project counts
$interestedQuery = "SELECT COUNT(*) FROM project_interests WHERE user_id = :user_id AND is_bought = 0";
$stmt = $conn->prepare($interestedQuery);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$interestedCount = $stmt->fetchColumn();

$boughtQuery = "SELECT COUNT(*) FROM project_interests WHERE user_id = :user_id AND is_bought = 1";
$stmt = $conn->prepare($boughtQuery);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$boughtCount = $stmt->fetchColumn();

// Determine greeting based on time of day
function getTimeBasedGreeting() {
    $hour = date('H');
    if ($hour < 12) return "Good Morning";
    if ($hour < 17) return "Good Afternoon";
    return "Good Evening";
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            min-height: 100vh;
        }
        .card-hover {
            transition: all 0.3s ease-in-out;
            transform: translateY(0);
        }
        .card-hover:hover {
            transform: translateY(-5px);
        }
        .welcome-gradient {
            background: linear-gradient(to right, #10b981, #047857);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 pt-24">
        <div class="max-w-7xl mx-auto">
            <header class="mb-10 animate-fade-in">
                <div class="flex items-center mb-4">
                    <h1 class="text-4xl font-bold text-gray-900 flex items-center">
                        <span class="mr-4 text-emerald-600">👋</span>
                        <span class="welcome-gradient">
                            <?php echo htmlspecialchars(getTimeBasedGreeting()); ?>, 
                            <?php echo htmlspecialchars($user['name']); ?>!
                        </span>
                    </h1>
                </div>
                <p class="text-gray-600 text-lg">Your personal project management dashboard</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-xl p-6 border border-emerald-100 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full mr-4 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-800">Your Profile</h2>
                    </div>
                    <div class="space-y-3 text-gray-700">
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium">Department</span>
                            <span><?php echo htmlspecialchars($user['department']); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium">Matric Number</span>
                            <span><?php echo htmlspecialchars($user['matric_number']); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium">Email</span>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Joined</span>
                            <span><?php echo htmlspecialchars($user['join_date']); ?></span>
                        </div>
                    </div>
                    <a href="edit_profile.php" class="mt-6 w-full block text-center bg-emerald-500 text-white py-2 rounded-lg hover:bg-emerald-600 transition-colors">
                        Edit Profile
                    </a>
                </div>

                <!-- Projects Overview Card -->
                <div class="bg-white rounded-xl p-6 border border-emerald-100 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full mr-4 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <h2 class=  "text-2xl font-semibold text-gray-800">Project Overview</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-emerald-50 p-4 rounded-lg flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-emerald-800">Interested Projects</h3>
                                <p class="text-emerald-600"><?php echo $interestedCount; ?> Project<?php echo $interestedCount != 1 ? 's' : ''; ?></p>
                            </div>
                            <a href="interested_projects.php" class="text-emerald-600 hover:text-emerald-800 transition-colors">
                                View Details →
                            </a>
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-lg flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-emerald-800">Purchased Projects</h3>
                                <p class="text-emerald-600"><?php echo $boughtCount; ?> Project<?php echo $boughtCount != 1 ? 's' : ''; ?></p>
                            </div>
                            <a href="bought_projects.php" class="text-emerald-600 hover:text-emerald-800 transition-colors">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl p-6 border border-emerald-100 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full mr-4 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-800">Quick Actions</h2>
                    </div>
                    <div class="space-y-4">
                        <a href="create_project.php" class="block bg-emerald-50 p-4 rounded-lg hover:bg-emerald-100 transition-colors group">
                            <div class="flex justify-between items-center">
                                <span class="text-emerald-800 font-medium group-hover:text-emerald-900">Create New Project</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                        <a href="find_project.php" class="block bg-emerald-50 p-4 rounded-lg hover:bg-emerald-100 transition-colors group">
                            <div class="flex justify-between items-center">
                                <span class="text-emerald-800 font-medium group-hover:text-emerald-900">Browse Projects</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-emerald-900 text-white py-6 mt-10">
        <div class="container mx-auto text-center">
            <p>&copy; <?php echo date('Y'); ?> Project Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>