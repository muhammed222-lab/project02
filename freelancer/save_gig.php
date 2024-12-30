<?php
session_start(); // Start the session
require_once 'db.php'; // Include your database connection

// Check if the user is logged in as a freelancer
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirect to login if not logged in
    exit;
}

// Get freelancer ID from session
$freelancer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data and sanitize inputs
    $gig_name = htmlspecialchars(trim($_POST['gig_name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = floatval($_POST['price']);
    $tag = htmlspecialchars(trim($_POST['tag']));
    $category = htmlspecialchars(trim($_POST['category']));

    // Initialize variables for file uploads
    $screenshot1 = $screenshot2 = null;

    // Define a directory to save uploads
    $uploadDir = 'uploads/';

    // Handle file uploads securely
    if (isset($_FILES['screenshot1']) && $_FILES['screenshot1']['error'] === UPLOAD_ERR_OK) {
        $screenshot1 = $uploadDir . uniqid() . '_' . basename($_FILES['screenshot1']['name']);
        move_uploaded_file($_FILES['screenshot1']['tmp_name'], $screenshot1);
    }

    if (isset($_FILES['screenshot2']) && $_FILES['screenshot2']['error'] === UPLOAD_ERR_OK) {
        $screenshot2 = $uploadDir . uniqid() . '_' . basename($_FILES['screenshot2']['name']);
        move_uploaded_file($_FILES['screenshot2']['tmp_name'], $screenshot2);
    }

    try {
        // Insert the gig into the database
        $stmt = $pdo->prepare("INSERT INTO gigs (freelancer_id, gig_name, description, screenshot1, screenshot2, price, tag, category) VALUES (:freelancer_id, :gig_name, :description, :screenshot1, :screenshot2, :price, :tag, :category)");

        $stmt->execute([
            ':freelancer_id' => $freelancer_id,
            ':gig_name' => $gig_name,
            ':description' => $description,
            ':screenshot1' => $screenshot1,
            ':screenshot2' => $screenshot2,
            ':price' => $price,
            ':tag' => $tag,
            ':category' => $category
        ]);

        echo "<script>alert('Gig created successfully!'); window.location.href = 'dashboard.php';</script>";
    } catch (PDOException $e) {
        // Display error message if database insertion fails
        echo "<script>alert('Error creating gig: " . $e->getMessage() . "');</script>";
    }
}