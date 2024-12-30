<?php
session_start();
require_once '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update About Me
    if (isset($_POST['about_me'])) {
        $about_me = $_POST['about_me'];
        $stmt = $pdo->prepare("UPDATE users SET about_me = :about_me WHERE id = :id");
        $stmt->execute([':about_me' => $about_me, ':id' => $user_id]);
        echo json_encode(['success' => true]);
        exit();
    }

    // Update Open to Work
    if (isset($_POST['open_to_work'])) {
        $open_to_work = filter_var($_POST['open_to_work'], FILTER_VALIDATE_BOOLEAN);
        $stmt = $pdo->prepare("UPDATE users SET open_to_work = :open_to_work WHERE id = :id");
        $stmt->execute([':open_to_work' => $open_to_work, ':id' => $user_id]);
        echo json_encode(['success' => true]);
        exit();
    }
}
echo json_encode(['success' => false, 'message' => 'Invalid request.']);