<?php
session_start();
header('Content-Type: application/json');

// Include database connection
require_once '../php/db.php';

// Logging function
function logMessageAction($conn, $sender_id, $receiver_id, $status, $details = null) {
    $logQuery = "INSERT INTO message_action_logs 
                 (sender_id, receiver_id, status, details, created_at) 
                 VALUES (:sender_id, :receiver_id, :status, :details, NOW())";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->execute([
        ':sender_id' => $sender_id,
        ':receiver_id' => $receiver_id,
        ':status' => $status,
        ':details' => $details ? json_encode($details) : null
    ]);
}

// Validate and sanitize message content
function validateMessageContent($content) {
    // Remove excessive whitespace
    $content = trim($content);

    // Check message length
    if (strlen($content) < 1) {
        return ['valid' => false, 'error' => 'Message cannot be empty'];
    }
    if (strlen($content) > 1000) {
        return ['valid' => false, 'error' => 'Message is too long (max 1000 characters)'];
    }

    // Basic content filtering
    $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    // Optional: Add more sophisticated content filtering
    // For example, block certain words or patterns
    $blockedPatterns = [
        '/fuck/i',
        '/shit/i',
        '/spam/i',
        // Add more blocked words/patterns
    ];

    foreach ($blockedPatterns as $pattern) {
        if (preg_match($pattern, $content)) {
            return ['valid' => false, 'error' => 'Inappropriate message content'];
        }
    }

    return ['valid' => true, 'content' => $content];
}

// Check message sending rate limit
function checkRateLimit($conn, $sender_id) {
    $limitQuery = "SELECT COUNT(*) as message_count 
                   FROM messages 
                   WHERE sender_id = :sender_id 
                   AND created_at > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
    $limitStmt = $conn->prepare($limitQuery);
    $limitStmt->execute([':sender_id' => $sender_id]);
    $result = $limitStmt->fetch(PDO::FETCH_ASSOC);

    // Limit to 10 messages per minute
    return $result['message_count'] < 10;
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized access', 401);
    }

    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method', 405);
    }

    // Validate and sanitize input
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'] ?? null;
    $content = $_POST['message_content'] ?? null;

    // Validate receiver ID
    if (!filter_var($receiver_id, FILTER_VALIDATE_INT)) {
        throw new Exception('Invalid receiver ID', 400);
    }

    // Validate message content
    $contentValidation = validateMessageContent($content);
    if (!$contentValidation['valid']) {
        throw new Exception($contentValidation['error'], 400);
    }
    $sanitizedContent = $contentValidation['content'];

    // Check rate limit
    if (!checkRateLimit($conn, $sender_id)) {
        throw new Exception('Too many messages. Please wait before sending again.', 429);
    }

    // Verify receiver exists
    $checkReceiverQuery = "SELECT id FROM users WHERE id = :receiver_id";
    $checkReceiverStmt = $conn->prepare($checkReceiverQuery);
    $checkReceiverStmt->execute([':receiver_id' => $receiver_id]);
    
    if ($checkReceiverStmt->rowCount() === 0) {
        throw new Exception('Receiver does not exist', 404);
    }

    // Begin transaction
    $conn->beginTransaction();

    // Insert message
    $insertQuery = "INSERT INTO messages (sender_id, receiver_id, content, created_at) 
                    VALUES (:sender_id, :receiver_id, :content, NOW())";
    $insertStmt = $conn->prepare($insertQuery);
    $insertResult = $insertStmt->execute([
        ':sender_id' => $sender_id,
        ':receiver_id' => $receiver_id,
        ':content' => $sanitizedContent
    ]);

    if (!$insertResult) {
        throw new Exception('Failed to send message', 500);
    }

    // Log successful message send
    logMessageAction($conn, $sender_id, $receiver_id, 'sent');

    // Commit transaction
    $conn->commit();

    // Prepare success response
    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully',
        'redirect' => "chat.php?creator_id=$receiver_id"
    ]);

} catch (Exception $e) {
    // Rollback transaction in case of error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // Log error
    logMessageAction($conn, 
        $_SESSION['user_id'] ?? null, 
        $_POST['receiver_id'] ?? null, 
        'failed', 
        ['error' => $e->getMessage()]
    );

    // Send error response
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
}

exit();