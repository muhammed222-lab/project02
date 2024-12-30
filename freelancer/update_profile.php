<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Set the content type to JSON

session_start(); // Ensure the session is started
require_once '../db.php';

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);
error_log(print_r($input, true)); // Log the input data for debugging
$about_me = $input['about_me'] ?? null;
$skills = $input['skills'] ?? null;
$portfolio = $input['portfolio'] ?? null;
$user_id = $_SESSION['user_id'] ?? null; // Ensure you have the user ID from the session

// Validate inputs
if ($about_me === null || $skills === null || $portfolio === null || $user_id === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Prepare and execute your SQL statement to update the profile
$stmt = $pdo->prepare("UPDATE freelancers SET about_me = ?, skills = ?, portfolio = ? WHERE user_id = ?");
$success = $stmt->execute([$about_me, $skills, $portfolio, $user_id]);

// Return a JSON response
if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed']);
}