<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email and new password from the form
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the email is present
    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Ensure passwords match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the password in the database
    $query = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $query->bindParam(':password', $hashed_password);
    $query->bindParam(':email', $email);

    if ($query->execute()) {
        echo "Password updated successfully. You can now log in.";
        header("Location: login.php"); // Redirect to login page after success
        exit;
    } else {
        echo "Failed to update password. Please try again.";
    }
} else {
    echo "Invalid request method.";
}