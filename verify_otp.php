<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = htmlspecialchars(trim($_POST['otp']));
    $user_id = $_SESSION['user_id'];

    try {
        // Retrieve OTP from database
        $stmt = $conn->prepare("SELECT otp FROM users WHERE id = :id");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['otp'] === $entered_otp) {
            // OTP matches, mark user as verified
            $updateStmt = $conn->prepare("UPDATE users SET otp = NULL WHERE id = :id");
            $updateStmt->execute(['id' => $user_id]);

            // Redirect to dashboard or login page
            header('Location: login.php');
            exit;
        } else {
            echo "Invalid OTP. Please try again.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}