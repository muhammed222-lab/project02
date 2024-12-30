<!-- profile.php -->
<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
    <link rel="icon" href="../img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <?php include 'nav.php'; ?>

    <!-- Main Content -->
    <main class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">Profile Details</h2>

        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-xl font-semibold mb-4">Your Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($user['department']); ?></p>
            <p><strong>Matric Number:</strong> <?php echo htmlspecialchars($user['matric_number']); ?></p>
            <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['join_date']); ?></p>

            <a href="edit_profile.php"
                class="mt-4 inline-block bg-green-700 text-white py-2 px-4 rounded hover:bg-green-800">Edit Profile</a>
        </div>
    </main>
</body>

</html>