<?php
// Include database connection
include '../php/db.php';

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
define('FLW_SECRET_KEY', 'FLWSECK_TEST-0a93444ae09378f3732b3b131af4f572-X');
// Get payment details from the query string
$project_id = $_GET['project_id'] ?? null;
$price = $_GET['price'] ?? null;
$title = $_GET['title'] ?? null;

if (!$project_id || !$price || !$title) {
    die("Invalid payment request.");
}

// Save project_id in session
$_SESSION['project_id'] = $project_id;

// Fetch user details from the database
$stmt = $conn->prepare("SELECT email, name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User details not found.");
}

// Prepare the payload for Flutterwave
$payload = [
    'tx_ref' => uniqid(), // Unique transaction reference
    'amount' => $price,
    'currency' => 'NGN',
    'redirect_url' => 'http://localhost/project_02/student/payment_confirmation.php', // Redirect after payment
    'payment_options' => 'card, banktransfer, ussd',
    'customer' => [
        'email' => $user['email'], // Use email fetched from the database
        'name' => $user['name'],   // Use name fetched from the database
    ],
];

// Send the payment request to Flutterwave
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.flutterwave.com/v3/payments');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . FLW_SECRET_KEY,
    'Content-Type: application/json',
]);

$response = curl_exec($ch);
curl_close($ch);

$response_data = json_decode($response, true);

// Check if the payment link was created successfully
if (isset($response_data['status']) && $response_data['status'] === 'success') {
    $payment_link = $response_data['data']['link'];

    // Redirect the user to the payment link
    header("Location: $payment_link");
    exit();
} else {
    echo "Error: Unable to initiate payment. " . ($response_data['message'] ?? "Unknown error.");
}
?>