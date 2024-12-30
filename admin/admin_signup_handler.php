<?php
// Database connection
include 'php/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        header("Location: admin_signup.php?error=email_exists");
        exit();
    }

    // Insert new admin into the database
    $stmt = $conn->prepare("INSERT INTO admins (name, email, password, status) VALUES (?, ?, ?, 'active')");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        header("Location: admin_login.php?signup=success");
    } else {
        echo "Error: " . $stmt->error;
    }
}