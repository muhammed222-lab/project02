<?php
header('Content-Type: application/json');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed.']);
    exit();
}

// Get the raw POST data and decode it
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['transaction_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Transaction ID is required.']);
    exit();
}

$transaction_id = $data['transaction_id'];

// Connect to the database and fetch the receipt
require_once '../db.php';

$stmt = $pdo->prepare("
    SELECT pa.*, u.name AS user_name, u.email AS user_email
    FROM payments pa
    JOIN users u ON pa.user_id = u.id
    WHERE pa.transaction_id = :transaction_id
");
$stmt->bindParam(':transaction_id', $transaction_id, PDO::PARAM_STR);
$stmt->execute();

$receipt = $stmt->fetch(PDO::FETCH_ASSOC);

if ($receipt) {
    echo json_encode(['status' => 'success', 'data' => $receipt]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Receipt not found.']);
}
?>