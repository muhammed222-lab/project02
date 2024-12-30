<?php
session_start();
require_once './db.php';

// Check if user is logged in as a creator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'freelancer') {
    header("Location: ../login.php");
    exit();
}

// Get Creator Details
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, profile_picture FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Update Profile Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Handle Profile Picture Upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profilePicture = $_FILES['profile_picture']['name'];
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($profilePicture);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile);
    } else {
        $profilePicture = $user['profile_picture'];
    }

    // Update User Information
    $updateQuery = "UPDATE users SET name = :name, email = :email, profile_picture = :profile_picture WHERE id = :id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':profile_picture' => $profilePicture,
        ':id' => $user_id
    ]);

    // Redirect to profile page
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include 'nav.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-green-700">Edit Profile</h1>
        <form action="edit_profile.php" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-md shadow-md mt-6">
            <div class="mb-4">
                <label class="block text-lg font-semibold" for="name">Full Name</label>
                <input type="text" id="name" name="name" class="w-full border border-gray-300 p-2 rounded-md"
                    value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-semibold" for="email">Email</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 p-2 rounded-md"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-semibold" for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture"
                    class="w-full border border-gray-300 p-2 rounded-md">
            </div>
            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md">Update Profile</button>
        </form>
    </div>
</body>

</html>