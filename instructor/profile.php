<?php
session_start();
require_once './db.php';

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Creator Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-green-700">Your Profile</h1>
        <div class="bg-white p-6 rounded-md shadow-md mt-6">
            <div class="flex">
                <div class="w-1/4">
                    <img src="../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>"
                        alt="Profile Picture" class="w-32 h-32 rounded-full">
                </div>
                <div class="w-3/4 pl-6">
                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p class="text-gray-700">Email: <?php echo htmlspecialchars($user['email']); ?></p>
                    <a href="edit_profile.php" class="text-green-700 hover:underline mt-4 block">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>