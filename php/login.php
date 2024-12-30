<?php
// login-handler.php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    try {
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful, store session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role']; // Make sure you're setting the correct session variable name

            // Redirect based on user role
            switch ($user['role']) {
                case 'student':
                    header('Location: ../student/dashboard.php');
                    break;
                case 'freelancer':
                    header('Location: ../freelancer/dashboard.php');
                    break;
                case 'instructor':
                    header('Location: ../instructor/dashboard.php');
                    break;
                case 'creator':
                    header('Location: ../creator/dashboard.php');
                    break;
                default:
                    echo "Unknown role. Please contact support.";
            }
            exit();
        } else {
            echo "Invalid email or password. <br> <a href='../login.php'>
            <button style='padding:10px 20px; margin-top:20px; border-radius:5px; cursor:pointer; outline:none; border:none; background:darkgreen; color:white;'>Retry</button>
            </a>";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}