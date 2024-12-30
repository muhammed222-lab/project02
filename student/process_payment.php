<?php
session_start();
require_once '../db.php'; // Ensure this points to the correct path for your db.php file
require_once './db.php';
// Load environment variables
require_once '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get the secret key from environment variables
$secretKey = $_ENV['FLW_SECRET_KEY']; // Replace with your actual key name from the .env file

// Get the payment amount and project title from the query parameters
$amount = $_GET['amount'];
$projectTitle = $_GET['title'];


$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$buyerEmail = $user['email'];
if (!$buyerEmail) {
    die("Error: Buyer email not found.");
}

// Get the buyer's email from the session

if (!$buyerEmail) {
    die("Error: Buyer email not found.");
}

// Create a transaction payload
$data = [
    "tx_ref" => "project02_" . uniqid(),
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
        "description" => "Payment for project: " . $projectTitle
    ]
];

// Make an HTTP request using cURL
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
    echo "cURL Error: " . $err;
} else {
    $responseData = json_decode($response, true);

    // Redirect to payment URL or show an error
    if ($responseData['status'] === 'success') {
        $paymentUrl = $responseData['data']['link'];
        header("Location: $paymentUrl");
        exit();
    } else {
        echo "Error initiating payment: " . $responseData['message'];
    }
}