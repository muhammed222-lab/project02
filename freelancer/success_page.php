<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header("Location: ../index.php");
    exit();
}

$freelancer_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Freelancer';

// Success message to be displayed
$success_message = "Thank you, $freelancer_name! Your application has been successfully submitted.";

// Include additional database interactions or other PHP code as necessary
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Application Successful - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>

    <div class="container mx-auto mt-20 px-4">
        <div class="bg-white p-6 rounded-lg border text-center">
            <h1 class="text-3xl font-bold mb-4 text-green-600">Application Successful!</h1>
            <p class="text-gray-700 mb-4"><?php echo $success_message; ?></p>
            <a href="job.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-4">View
                More
                Projects</a>
        </div>
    </div>
</body>

</html>