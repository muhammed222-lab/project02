<?php
session_start();
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

// Logging function for transactions
function logTransactionVerification($conn, $user_id, $transaction_id, $status, $details = null) {
    $logQuery = "INSERT INTO transaction_verification_logs 
                 (user_id, transaction_id, status, details, created_at) 
                 VALUES (:user_id, :transaction_id, :status, :details, NOW())";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->execute([
        ':user_id' => $user_id,
        ':transaction_id' => $transaction_id,
        ':status' => $status,
        ':details' => $details ? json_encode($details) : null
    ]);
}

// Validate transaction parameters
function validateTransactionParams($status, $transaction_id, $tx_ref) {
    $errors = [];

    if (empty($status)) {
        $errors[] = 'Transaction status is missing';
    }
    if (empty($transaction_id)) {
        $errors[] = 'Transaction ID is missing';
    }
    if (empty($tx_ref)) {
        $errors[] = 'Transaction reference is missing';
    }

    return $errors;
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized access', 401);
    }

    // Validate input parameters
    $status = $_GET['status'] ?? null;
    $transactionId = $_GET['transaction_id'] ?? null;
    $txRef = $_GET['tx_ref'] ?? null;

    $paramErrors = validateTransactionParams($status, $transactionId, $txRef);
    if (!empty($paramErrors)) {
        throw new Exception(implode(', ', $paramErrors), 400);
    }

    // Check if the transaction was successful
    if ($status !== 'successful') {
        throw new Exception("Payment was not successful. Status: $status", 402);
    }

    // Fetch user details
    $user_id = $_SESSION['user_id'];
    $userQuery = "SELECT * FROM users WHERE id = :user_id";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $userStmt->execute();
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found', 404);
    }

    // Get Flutterwave secret key
    $secretKey = $_ENV['FLW_SECRET_KEY'] ?? null;
    if (!$secretKey) {
        throw new Exception('Payment gateway configuration error', 500);
    }

    // Verify transaction with Flutterwave
    $url = "https://api.flutterwave.com/v3/transactions/$transactionId/verify";
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $secretKey",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        throw new Exception("cURL Error: " . curl_error($curl), 500);
    }

    $responseData = json_decode($response, true);
    curl_close($curl);

    // Check transaction verification status
    if ($responseData['status'] !== 'success') {
        throw new Exception("Transaction verification failed: " . $responseData['message'], 402);
    }

    // Extract payment details
    $paymentData = $responseData['data'];
    $amountPaid = $paymentData['amount'];
    $currency = $paymentData['currency'];
    $paymentDate = $paymentData['created_at'];

    // Begin transaction for data integrity
    $conn->beginTransaction();

    // Insert payment record
    $paymentQuery = "INSERT INTO payments 
                     (user_id, transaction_id, amount, currency, status, payment_date) 
                     VALUES (:user_id, :transaction_id, :amount, :currency, 'completed', :payment_date)";
    $paymentStmt = $conn->prepare($paymentQuery);
    $paymentStmt->execute([
        ':user_id' => $user_id,
        ':transaction_id' => $transactionId,
        ':amount' => $amountPaid,
        ':currency' => $currency,
        ':payment_date' => $paymentDate
    ]);

    // Log successful transaction verification
    logTransactionVerification($conn, $user_id, $transactionId, 'success', $paymentData);

    // Commit transaction
    $conn->commit();

} catch (Exception $e) {
    // Rollback transaction in case of error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // Log transaction verification error
    logTransactionVerification(
        $conn, 
        $_SESSION['user_id'] ?? null, 
        $transactionId ?? 'unknown', 
        'failed', 
        ['error' => $e->getMessage()]
    );

    // Store error in session for display
    $_SESSION['transaction_error'] = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Verification | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 animate-fade-in">
        <?php if (isset($_SESSION['transaction_error'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <?php 
                            echo htmlspecialchars($_SESSION['transaction_error']); 
                            unset($_SESSION['transaction_error']); 
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">Transaction Verified Successfully</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Transaction ID</span>
                        <span class="text-sm text-gray-800"><?php echo htmlspecialchars($transactionId); ?></span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Amount Paid</span>
                        <span class="text-sm text-green-600">
                            <?php echo number_format($amountPaid, 2) . ' ' . htmlspecialchars($currency); ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Payment Date</span>
                        <span class="text-sm text-gray-800">
                            <?php echo date('M j, Y H:i', strtotime($paymentDate)); ?>
                        </span>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <a href="bought_projects.php" 
                       class="flex-1 bg-blue-600 text-white py-3 rounded-lg text-center hover:bg-blue-700 transition-colors">
                        View Purchased Projects
                    </a>
                    <a href="dashboard.php" 
                       class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg text-center hover:bg-gray-300 transition-colors">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>