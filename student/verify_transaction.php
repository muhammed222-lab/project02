<?php
require_once '../vendor/autoload.php';
require_once '../db.php'; // Ensure correct path to your db.php file

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve transaction status and ID from query parameters
if (!isset($_GET['status']) || !isset($_GET['transaction_id']) || !isset($_GET['tx_ref'])) {
    die("Error: Invalid parameters.");
}

$status = $_GET['status'];
$transactionId = $_GET['transaction_id'];
$txRef = $_GET['tx_ref'];

// Check if the transaction was successful
if ($status !== 'successful') {
    die("Payment was not successful. Status: $status");
}

// Verify the transaction with Flutterwave
$secretKey = "FLWSECK_TEST-0a93444ae09378f3732b3b131af4f572-X"; // Get the secret key from .env

$url = "https://api.flutterwave.com/v3/transactions/$transactionId/verify";

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $secretKey",
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);
if (curl_errno($curl)) {
    die("cURL Error: " . curl_error($curl));
}

$responseData = json_decode($response, true);
curl_close($curl);

// Check if the transaction verification was successful
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($responseData['status'] === 'success') {
    // Extract payment info
    $paymentData = $responseData['data'];
    $amountPaid = $paymentData['amount'];
    $currency = $paymentData['currency'];
    $email = $user['email'];
    $paymentDate = $paymentData['created_at'];

    // Save to database
    $buyerEmail = $_SESSION['user_email']; // Ensure you have this from session
    $projectTitle = "Your Project Title"; // Replace with the actual project title

    // Insert payment data into your database
    $stmt = $pdo->prepare("INSERT INTO payments (transaction_id, amount, currency, buyer_email, project_title, payment_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$transactionId, $amountPaid, $currency, $email, $projectTitle, $paymentDate]);

    // Display the payment information in a table
    echo "<h2>Payment Successful!</h2>";
    echo "<table border='1'>
            <tr>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Buyer Email</th>
                <th>Project Title</th>
                <th>Payment Date</th>
            </tr>
            <tr>
                <td>{$transactionId}</td>
                <td>{$amountPaid}</td>
                <td>{$currency}</td>
                <td>{$email}</td>
                <td>{$projectTitle}</td>
                <td>{$paymentDate}</td>
            </tr>
          </table>";
} else {
    echo "Error: Transaction verification failed. " . $responseData['message'];
    echo " Response: " . json_encode($responseData); // Output the full response for debugging
}