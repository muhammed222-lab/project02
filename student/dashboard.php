<!-- dashboard.php -->
<?php
session_start();
include('./db.php'); // Assuming this includes the PDO connection setup

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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="icon" href="../img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>
    <!-- Header -->


    <!-- Main Content -->
    <main class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Student Details Card -->
            <div class="bg-white p-6 shadow-lg rounded-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-50 rounded-full mr-3">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Your Details</h3>
                </div>
                <div class="space-y-3">
                    <p class="flex items-center text-gray-600">
                        <span class="font-medium w-32">Department:</span>
                        <span><?php echo htmlspecialchars($user['department']); ?></span>
                    </p>
                    <p class="flex items-center text-gray-600">
                        <span class="font-medium w-32">Matric Number:</span>
                        <span><?php echo htmlspecialchars($user['matric_number']); ?></span>
                    </p>
                    <p class="flex items-center text-gray-600">
                        <span class="font-medium w-32">Email:</span>
                        <span><?php echo htmlspecialchars($user['email']); ?></span>
                    </p>
                    <p class="flex items-center text-gray-600">
                        <span class="font-medium w-32">Joined:</span>
                        <span><?php echo htmlspecialchars($user['join_date']); ?></span>
                    </p>
                </div>
                <a href="edit_profile.php" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700">
                    <span>Edit Profile</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Find Projects Card -->
            <div class="bg-white p-6 shadow-lg rounded-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-green-50 rounded-full mr-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Find Projects</h3>
                </div>
                <p class="text-gray-600 mb-6">Explore and discover projects that match your interests and skills. Browse through various categories and find the perfect project for you.</p>
                <a href="find_project.php" class="inline-flex items-center justify-center w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Available Projects
                </a>
            </div>

            <!-- Your Projects Card -->
            <div class="bg-white p-6 shadow-lg rounded-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-purple-50 rounded-full mr-3">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Your Projects</h3>
                </div>
                
                <!-- Interested Projects Section -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-700 mb-2">Interested Projects</h4>
                    <?php
                    // Query to check interested (but not bought) projects
                    $interestedQuery = "SELECT COUNT(*) FROM project_interests 
                                      WHERE user_id = :user_id AND is_bought = 0";
                    $stmt = $conn->prepare($interestedQuery);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $interestedCount = $stmt->fetchColumn();
                    ?>
                    <?php if ($interestedCount > 0): ?>
                        <a href="interested_projects.php" class="flex items-center justify-between p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                            <span class="text-purple-700">View <?php echo $interestedCount; ?> interested project<?php echo $interestedCount != 1 ? 's' : ''; ?></span>
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <div class="p-3 bg-gray-50 rounded-lg text-gray-500 text-sm">
                            <p>No interested projects yet.</p>
                            <p class="mt-1">Start by browsing available projects!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Bought Projects Section -->
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Purchased Projects</h4>
                    <?php
                    // Query to check bought projects
                    $boughtQuery = "SELECT COUNT(*) FROM project_interests 
                                  WHERE user_id = :user_id AND is_bought = 1";
                    $stmt = $conn->prepare($boughtQuery);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $boughtCount = $stmt->fetchColumn();
                    ?>
                    <?php if ($boughtCount > 0): ?>
                        <a href="bought_projects.php" class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200">
                            <span class="text-green-700">View <?php echo $boughtCount; ?> purchased project<?php echo $boughtCount != 1 ? 's' : ''; ?></span>
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <div class="p-3 bg-gray-50 rounded-lg text-gray-500 text-sm">
                            <p>No purchased projects yet.</p>
                            <p class="mt-1">Find and purchase your first project!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>