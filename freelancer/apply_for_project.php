<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header("Location: ../index.php");
    exit();
}

$freelancer_id = $_SESSION['user_id'];

// Ensure the name and email are set in the session or retrieve them from the database
if (isset($_SESSION['name']) && isset($_SESSION['email'])) {
    $freelancer_name = $_SESSION['name'];
    $freelancer_email = $_SESSION['email'];
} else {
    // Fetch the freelancer's name and email from the database
    $query = "SELECT name, email FROM users WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $freelancer_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $freelancer_name = $user['name'];
        $freelancer_email = $user['email'];
    } else {
        // Handle error if the user is not found
        die("Freelancer details not found.");
    }
}

// Retrieve the form data from the POST request
$project_id = $_POST['project_id'];
$why_choose_me = $_POST['why_choose_me'];
$qualification = $_POST['qualification'];

// Prepare the SQL query to insert the application data
$query = "INSERT INTO project_applications (project_id, creator_id, creator_name, creator_email, why_choose_me, qualification, applied_at) 
          VALUES (:project_id, :creator_id, :creator_name, :creator_email, :why_choose_me, :qualification, NOW())";

$stmt = $conn->prepare($query);
$stmt->execute([
    ':project_id' => $project_id,
    ':creator_id' => $freelancer_id,
    ':creator_name' => $freelancer_name,
    ':creator_email' => $freelancer_email,
    ':why_choose_me' => $why_choose_me,
    ':qualification' => $qualification,
]);

header("Location: success_page.php"); // Redirect to a success page or desired page
exit();