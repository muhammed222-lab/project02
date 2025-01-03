<?php
session_start();
include('../db.php'); // Adjusted the path to include the correct file

// Check if user is logged in as a creator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// Get Creator Details
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, profile_picture, department, matric_number, join_date FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle case where user data could not be fetched
if (!$user) {
    echo "User data not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="icon" href="../img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>
    <!-- Header -->

    <!-- Main Content -->
    <main class="container mx-auto mt-8">
        <h2 class="text-3xl font-bold mb-6 text-center">Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Student Details -->
            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-xl font-semibold mb-4"><i class="fas fa-user-graduate text-green-700"></i> Your Details
                </h3>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($user['department']); ?></p>
                <p><strong>Matric Number:</strong> <?php echo htmlspecialchars($user['matric_number']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['join_date']); ?></p>
                <a href="profile.php" class="text-green-700 hover:underline mt-4 inline-block"><i
                        class="fas fa-edit"></i> Edit Profile</a>
            </div>

            <!-- Find Projects -->
            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-xl font-semibold mb-4"><i class="fas fa-search text-green-700"></i> Find Projects</h3>
                <p>Explore and find projects suited to your needs.</p>
                <a href="find_project.php"
                    class="mt-4 inline-block bg-green-700 text-white py-2 px-4 rounded hover:bg-green-800"><i
                        class="fas fa-search"></i> Find Projects</a>
            </div>

            <!-- Interested and Bought Projects -->
            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-xl font-semibold mb-4"><i class="fas fa-folder-open text-green-700"></i> Your Projects
                </h3>
                <p><strong>Interested Projects:</strong> View all the projects you are interested in.</p>
                <a href="interested_projects.php" class="text-green-700 hover:underline"><i class="fas fa-eye"></i> View
                    Interested Projects</a>
                <p class="mt-4"><strong>Bought Projects:</strong> View all your purchased projects.</p>
                <a href="bought_projects.php" class="text-green-700 hover:underline"><i
                        class="fas fa-shopping-cart"></i> View Bought Projects</a>
            </div>
        </div>
    </main>
</body>

</html>