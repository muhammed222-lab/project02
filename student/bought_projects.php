<?php
session_start();
$host = 'localhost'; // Database host
$db = 'project_02'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password (usually empty for XAMPP)

// Set up the DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    // Create a PDO instance
    $conn = new PDO($dsn, $user, $pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
    exit();
}
// Ensure this points to the correct path for your db.php file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT DISTINCT c.creator_id, u.email
          FROM clients c 
          JOIN users u ON c.creator_id = u.id 
          WHERE c.buyer_email = :buyer_email";

$stmt = $conn->prepare($query);
$stmt->bindParam(':buyer_email', $_SESSION['buyer_email'], PDO::PARAM_STR);
$stmt->execute();
$creators = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bought Project - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>


</body>

</html>