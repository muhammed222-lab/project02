<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

// Check if application_id and action are provided
if (!isset($_POST['application_id']) || !isset($_POST['action'])) {
    header("Location: view_applications.php");
    exit();
}

$application_id = intval($_POST['application_id']);
$action = $_POST['action'];

// Prepare the appropriate query based on the action
if ($action === 'approve') {
    $query = "UPDATE project_applications SET status = 'approved' WHERE id = :application_id";
} elseif ($action === 'dismiss') {
    $query = "UPDATE project_applications SET status = 'dismissed' WHERE id = :application_id";
} else {
    // If the action is not recognized, redirect with an error
    $_SESSION['error'] = "Invalid action provided.";
    header("Location: view_applications.php?project_id=" . $_SESSION['current_project_id']);
    exit();
}

try {
    // Prepare the statement using PDO
    $stmt = $conn->prepare($query);

    // Bind the application_id parameter
    $stmt->bindParam(':application_id', $application_id, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['success'] = ($action === 'approve') ? "Application approved successfully!" : "Application dismissed successfully!";
    } else {
        $_SESSION['error'] = "Error processing the application. Please try again.";
    }
} catch (PDOException $e) {
    // Catch and display any PDO exceptions
    $_SESSION['error'] = "Database error: " . $e->getMessage();
}

// Redirect back to view applications
header("Location: my_projects.php?project_id=" . $_SESSION['current_project_id']);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

exit();