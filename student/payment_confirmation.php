<?php
// Include database connection
include '../php/db.php';

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

// Ensure the project_id exists in the session
if (!isset($_SESSION['project_id'])) {
    die("Project ID not found in session.");
}

$project_id = $_SESSION['project_id'];

// Flutterwave API Keys
define('FLW_SECRET_KEY', 'FLWSECK_TEST-0a93444ae09378f3732b3b131af4f572-X');

// Get transaction details from query string
$status = $_GET['status'] ?? null;
$transaction_id = $_GET['transaction_id'] ?? null;
$tx_ref = $_GET['tx_ref'] ?? null;

if ($status !== 'successful' || !$transaction_id || !$tx_ref) {
    die("Invalid payment confirmation.");
}

// Verify the transaction with Flutterwave API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.flutterwave.com/v3/transactions/$transaction_id/verify");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . FLW_SECRET_KEY,
    'Content-Type: application/json',
]);

$response = curl_exec($ch);
curl_close($ch);

$response_data = json_decode($response, true);

if (isset($response_data['status']) && $response_data['status'] === 'success') {
    $transaction_data = $response_data['data'];

    $amount = $transaction_data['amount'];
    $currency = $transaction_data['currency'];
    $payment_status = $transaction_data['status'];
    $payment_date = $transaction_data['created_at'];

    // Save payment details to the database
    $stmt = $conn->prepare("
        INSERT INTO payments (transaction_id, tx_ref, user_id, project_id, amount, currency, status, payment_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $transaction_id,
        $tx_ref,
        $user_id,
        $project_id,
        $amount,
        $currency,
        $payment_status,
        $payment_date
    ]);

    // Mark the project as purchased in the project_interests table
    $stmt = $conn->prepare("UPDATE project_interests SET is_bought = 1 WHERE user_id = ? AND project_id = ?");
    $stmt->execute([$user_id, $project_id]);

    // Display payment details to the user
    echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Payment Confirmation</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body class='bg-light'>
            <div class='container py-5'>
                <div class='card border'>
                    <div class='card-header bg-success text-white text-center'>
                        <h1>Payment Successful!</h1>
                    </div>
                    <div class='card-body'>
                        <table class='table table-bordered'>
                            <thead class='table-light'>
                                <tr>
                                    <th>Transaction Details</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Transaction ID</td>
                                    <td>{$transaction_id}</td>
                                </tr>
                                <tr>
                                    <td>Reference</td>
                                    <td>{$tx_ref}</td>
                                </tr>
                                <tr>
                                    <td>Amount Paid</td>
                                    <td>{$amount} {$currency}</td>
                                </tr>
                                <tr>
                                    <td>Payment Date</td>
                                    <td>{$payment_date}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class='text-center'>
                            <a href='dashboard.php' class='btn btn-primary mt-3'>Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
    ";
} else {
    echo "Error verifying payment: " . ($response_data['message'] ?? "Unknown error.");
}
?>