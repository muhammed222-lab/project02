<?php
session_start();
require_once './db.php'; // Ensure this points to the correct path for your db.php file

// Check if the user is logged in and is a creator
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get the data sent via AJAX
$data = json_decode(file_get_contents('php://input'), true);
$projectTitle = $data['projectTitle'];
$buyerEmail = $data['buyerEmail'];
$status = $data['status'];

try {
    // Prepare the update statement
    $query = "UPDATE clients SET status = :status WHERE project_title = :projectTitle AND buyer_email = :buyerEmail";
    $stmt = $pdo->prepare($query);

    // Bind parameters and execute
    $stmt->execute([':status' => $status, ':projectTitle' => $projectTitle, ':buyerEmail' => $buyerEmail]);

    echo json_encode(['success' => true, 'message' => "Project has been $status."]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
