<?php
// Start session and include connection
session_start();
require_once '../db.php';
require_once '../vendor/autoload.php'; // Assuming Flutterwave SDK is installed in 'vendor' folder

// Check if user is logged in as a creator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'creator') {
    header("Location: ../login.php");
    exit();
}

// Get user ID and POST data
$user_id = $_SESSION['user_id'];
$account_number = $_POST['account_number'];
$account_bank = $_POST['account_bank'];

try {
    // Initialize Flutterwave
    $flw = new \Flutterwave\Payment(getenv('FLW_SECRET_KEY'));
    $accountService = new \Flutterwave\Banks(); // Assuming Banks is the class for handling bank-related tasks.
    if (!class_exists('Flutterwave\Rave')) {
        echo "Class Flutterwave\Rave not found";
    } else {
        echo "Class Flutterwave\Rave found!";
    }

    if (!class_exists('Flutterwave\Misc')) {
        echo "Class Flutterwave\Misc not found";
    } else {
        echo "Class Flutterwave\Misc found!";
    }


    // Verify Account Details
    $details = [
        "account_number" => $account_number,
        "account_bank" => $account_bank
    ];
    $response = $accountService->verifyAccount($details);

    if ($response->status === "success") {
        $account_name = $response->data->account_name;

        // Save bank details to database
        $sql = "INSERT INTO bank_details (user_id, account_number, account_bank, account_name, created_at) 
                VALUES (:user_id, :account_number, :account_bank, :account_name, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':account_number' => $account_number,
            ':account_bank' => $account_bank,
            ':account_name' => $account_name
        ]);

        // Display success message
        echo "<script>
                alert('Account verified successfully! Account Name: " . htmlspecialchars($account_name) . "');
                window.location.href = 'dashboard.php';
              </script>";
    } else {
        // Display error message
        echo "<script>
                alert('Error: " . htmlspecialchars($response->message) . "');
                window.history.back();
              </script>";
    }
} catch (Exception $e) {
    echo "<script>
            alert('An error occurred: " . htmlspecialchars($e->getMessage()) . "');
            window.history.back();
          </script>";
}