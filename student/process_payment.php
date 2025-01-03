<?php
session_start();
header('Content-Type: application/json');

// Include database and environment configurations
require_once '../php/db.php';
require_once '../vendor/autoload.php';

// Load environment variables
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (Exception $e) {
    error_log('Dotenv Error: ' . $e->getMessage());
    die('Environment configuration error');
}

// Logging function
function logTransaction($conn, $user_id, $project_title, $amount, $status, $details = null) {
    $logQuery = "INSERT INTO transaction_logs 
                 (user_id, project_title, amount, status, details, created_at) 
                 VALUES (:user_id, :project_title, :amount, :status, :details, NOW())";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->execute([
        ':user_id' => $user_id,
        ':project_title' => $project_title,
        ':amount' => $amount,
        ':status' => $status,
        ':details' => $details ? json_encode($details) : null
    ]);
}

// Validate input
function validatePaymentInput($amount, $projectTitle) {
    $errors = [];

    // Validate amount
    if (!is_numeric($amount) || $amount <= 0) {
        $errors[] = 'Invalid payment amount';
    }

    // Validate project title
    if (empty($projectTitle) || strlen($projectTitle) > 255) {
        $errors[] = 'Invalid project title';
    }

    return $errors;
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized access', 401);
    }

    // Validate and sanitize input
    $amount = $_GET['amount'] ?? null;
    $projectTitle = $_GET['title'] ?? null;

    $inputErrors = validatePaymentInput($amount, $projectTitle);
    if (!empty($inputErrors)) {
        throw new Exception(implode(', ', $inputErrors), 400);
    }

    // Fetch user details
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found', 404);
    }

    $buyerEmail = $user['email'];
    if (!filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid buyer email', 400);
    }

    // Get Flutterwave secret key
    $secretKey = $_ENV['FLW_SECRET_KEY'] ?? null;
    if (!$secretKey) {
        throw new Exception('Payment gateway configuration error', 500);
    }

    // Create transaction payload
    $transactionRef = "project02_" . uniqid();
    $data = [
        "tx_ref" => $transactionRef,
        "amount" => $amount,
        "currency" => "NGN",
        "redirect_url" => "http://localhost/project_02/student/verify_transaction.php",
        "payment_type" => "card",
        "customer" => [
            "email" => $buyerEmail,
            "name" => $user['name']
        ],
        "customizations" => [
            "title" => "Payment for " . $projectTitle,
            "description" => "Project Purchase: " . $projectTitle
        ]
    ];

    // Initiate payment via cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.flutterwave.com/v3/payments",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $secretKey",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        // Log cURL error
        logTransaction($conn, $user_id, $projectTitle, $amount, 'failed', ['error' => $err]);
        throw new Exception('Payment gateway communication error', 500);
    }

    $responseData = json_decode($response, true);

    // Handle payment initiation response
    if ($responseData['status'] === 'success') {
        $paymentUrl = $responseData['data']['link'];
        
        // Log successful payment initiation
        logTransaction($conn, $user_id, $projectTitle, $amount, 'initiated', [
            'transaction_ref' => $transactionRef,
            'payment_link' => $paymentUrl
        ]);

        // Redirect to payment URL
        header("Location: $paymentUrl");
        exit();
    } else {
        // Log payment initiation failure
        logTransaction($conn, $user_id, $projectTitle, $amount, 'failed', $responseData);
        throw new Exception('Payment initiation failed: ' . $responseData['message'], 400);
    }

} catch (Exception $e) {
    // Log any exceptions
    error_log('Payment Processing Error: ' . $e->getMessage());

    // Redirect to error page with message
    $_SESSION['payment_error'] = $e->getMessage();
    header("Location: payment_error.php");
    exit();
}