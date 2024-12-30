<?php
// confirm_otp.php
require 'db.php';
require 'php/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = trim($_POST['otp']);
    $user_id = $_SESSION['user_id'];

    try {
        // Fetch the OTP from the database
        $stmt = $conn->prepare("SELECT otp_code, role FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['otp_code'] === $entered_otp) {
            // Mark OTP as verified
            $stmt = $conn->prepare("UPDATE users SET otp_verified = 1 WHERE id = :user_id");
            $stmt->execute(['user_id' => $user_id]);

            $_SESSION['otp_verified'] = true;

            // Redirect based on user role
            switch ($user['role']) {
                case 'student':
                    header('Location: ./student/dashboard.php');
                    break;
                case 'freelancer':
                    header('Location: ./freelancer/dashboard.php');
                    break;
                case 'instructor':
                    header('Location: ./instructor/dashboard.php');
                    break;
                case 'creator':
                    header('Location: ./creator/dashboard.php');
                    break;
                default:
                    echo "Invalid user role.";
                    exit;
            }
            exit;
        } else {
            echo "Invalid OTP. Please try again.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // echo "Invalid request method.";
}
?>

<!-- Confirm OTP form styled with Tailwind CSS -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-6 text-center">Enter OTP sent to you mail address.</h2>
        <form action="confirm_otp.php" method="POST">
            <div class="mb-4">
                <label for="otp" class="block text-gray-700">OTP</label>
                <input type="text" name="otp" id="otp"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-green-500">
            </div>
            <button type="submit"
                class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:ring-green-500">Confirm
                OTP</button>
        </form>
    </div>
</body>

</html>