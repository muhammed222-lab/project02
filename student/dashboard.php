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
            <!-- Student Details -->
            <div class="bg-white p-6 shadow rounded">
                <h3 class="text-xl font-semibold mb-4">Your Details</h3>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($user['department']); ?></p>
                <p><strong>Matric Number:</strong> <?php echo htmlspecialchars($user['matric_number']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['join_date']); ?></p>
                <a href="profile.php" class="text-green-700 hover:underline">Edit Profile</a>
            </div>

            <!-- Find Projects -->
            <div class="bg-white p-6 shadow rounded">
                <h3 class="text-xl font-semibold mb-4">Find Projects</h3>
                <p>Explore and find projects suited to your needs.</p>
                <a href="find_project.php"
                    class="mt-4 inline-block bg-green-700 text-white py-2 px-4 rounded hover:bg-green-800">Find
                    Projects</a>
            </div>

            <!-- Interested and Bought Projects -->
            <div class="bg-white p-6 shadow rounded">
                <h3 class="text-xl font-semibold mb-4">Your Projects</h3>
                <p><strong>Interested Projects:</strong> View all the projects you are interested in.</p>
                <a href="interested_projects.php" class="text-green-700 hover:underline">View Interested Projects</a>
                <p class="mt-4"><strong>Bought Projects:</strong> View all your purchased projects.</p>
                <a href="bought_projects.php" class="text-green-700 hover:underline">View Bought Projects</a>
            </div>
        </div>
    </main>
</body>

</html>