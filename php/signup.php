<?php
// process_signup.php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $role = $_POST['userRole'];
    $department = isset($_POST['department']) ? htmlspecialchars(trim($_POST['department'])) : null;
    $matric_number = isset($_POST['matricNumber']) ? htmlspecialchars(trim($_POST['matricNumber'])) : null;
    $phone = htmlspecialchars(trim($_POST['phone']));
    $join_date = date("Y-m-d");

    // Generate a 6-digit OTP
    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            echo "Email is already in use. <br> <a href='../signup.php'>
            <button style='padding:10px 20px; margin-top:20px; border-radius:5px; cursor:pointer; outline:none; border:none; background:darkgreen; color:white;'>Retry</button>
            </a>";
            exit;
        }

        // Insert user details and OTP
        $sql = "INSERT INTO users (name, email, password, role, department, matric_number, phone, join_date, otp_code, otp_verified) VALUES 
                (:name, :email, :password, :role, :department, :matric_number, :phone, :join_date, :otp, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'department' => $department,
            'matric_number' => $matric_number,
            'phone' => $phone,
            'join_date' => $join_date,
            'otp' => $otp
        ]);

        // Prepare OTP data for EmailJS
        $data = [
            "service_id" => "service_semm4np",
            "template_id" => "template_wyjdjml",
            "user_id" => "3Iw2QmaVuCwdoZyq3",
            "template_params" => [
                "to_name" => $name,
                "to_email" => $email,
                "otp" => $otp
            ]
        ];

        // Send OTP via EmailJS using fetch API
        echo "<script>
                fetch('https://api.emailjs.com/api/v1.0/email/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(" . json_encode($data) . ")
                })
                .then(response => {
                    if (response.status === 200) {
                        // Redirect to confirm OTP after sending email
                        window.location.href = '../confirm_otp.php';
                    } else {
                        return response.json().then(error => Promise.reject(error));
                    }
                })
                .catch(error => {
                    alert('Failed to send OTP: ' + JSON.stringify(error));
                });
            </script>";

        // Start session and store user ID
        $_SESSION['user_id'] = $conn->lastInsertId();
        $_SESSION['role'] = $role;
        $_SESSION['otp_verified'] = false;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}