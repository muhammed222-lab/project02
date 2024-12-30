<!-- edit_profile.php -->
<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $name = htmlspecialchars($_POST['name']);
    $department = htmlspecialchars($_POST['department']);
    $matric_number = htmlspecialchars($_POST['matric_number']);

    // Update the user details in the database
    $updateQuery = "UPDATE users SET name = :name, department = :department, matric_number = :matric_number WHERE id = :user_id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':name', $name);
    $updateStmt->bindParam(':department', $department);
    $updateStmt->bindParam(':matric_number', $matric_number);
    $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="icon" href="../img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <?php include 'nav.php'; ?>

    <!-- Main Content -->
    <main class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>

        <form action="" method="POST" class="bg-white p-6 shadow rounded">
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                    class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Department:</label>
                <input type="text" name="department" value="<?php echo htmlspecialchars($user['department']); ?>"
                    class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Matric Number:</label>
                <input type="text" name="matric_number" value="<?php echo htmlspecialchars($user['matric_number']); ?>"
                    class="w-full p-2 border border-gray-300 rounded">
            </div>
            <button type="submit" class="bg-green-700 text-white py-2 px-4 rounded hover:bg-green-800">Save
                Changes</button>
        </form>
    </main>
</body>

</html>