<?php
session_start();
header('Content-Type: application/json');

// Include database connection and error logging
include './db.php';

// Function to validate input
function validateInput($input, $type = 'string') {
    $input = trim($input);
    
    if (empty($input)) {
        return false;
    }
    
    switch ($type) {
        case 'email':
            return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
        case 'phone':
            return preg_match('/^[0-9\-\(\)\/\+\s]*$/', $input);
        default:
            return strlen($input) >= 2 && strlen($input) <= 255;
    }
}
try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not authenticated');
    }

    // Get form data
    $projectTitle = $_POST['project_title'] ?? null;
    $buyerName = $_POST['buyer_name'] ?? null;
    $buyerEmail = $_POST['buyer_email'] ?? null;
    $buyerPhone = $_POST['buyer_phone'] ?? null;
    $deliveryDate = $_POST['delivery_date'] ?? null;
    $creatorId = $_POST['creator_id'] ?? null;
    $creatorEmail = $_POST['creator_email'] ?? null;

    // Validate inputs
    $errors = [];

    if (!validateInput($projectTitle)) {
        $errors[] = 'Invalid project title';
    }
    if (!validateInput($buyerName)) {
        $errors[] = 'Invalid buyer name';
    }
    if (!validateInput($buyerEmail, 'email')) {
        $errors[] = 'Invalid email address';
    }
    if (!validateInput($buyerPhone, 'phone')) {
        $errors[] = 'Invalid phone number';
    }
    if (!validateInput($creatorId)) {
        $errors[] = 'Invalid creator ID';
    }
    if (!validateInput($creatorEmail, 'email')) {
        $errors[] = 'Invalid creator email';
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit();
    }

    // Begin transaction for data integrity
    $conn->beginTransaction();

    // Check if project already purchased
    $checkQuery = $conn->prepare("SELECT * FROM clients WHERE project_title = :projectTitle AND buyer_email = :buyerEmail");
    $checkQuery->execute([
        ':projectTitle' => $projectTitle,
        ':buyerEmail' => $buyerEmail
    ]);

    if ($checkQuery->rowCount() > 0) {
        throw new Exception('Project already purchased');
    }

    // Prepare and execute the statement
    $stmt = $conn->prepare("
        INSERT INTO clients (
            project_title, 
            buyer_name, 
            buyer_email, 
            buyer_phone, 
            delivery_date, 
            creator_id, 
            creator_email, 
            purchase_date
        ) VALUES (
            :projectTitle, 
            :buyerName, 
            :buyerEmail, 
            :buyerPhone, 
            :deliveryDate, 
            :creatorId, 
            :creatorEmail, 
            NOW()
        )
    ");

    $result = $stmt->execute([
        ':projectTitle' => $projectTitle,
        ':buyerName' => $buyerName,
        ':buyerEmail' => $buyerEmail,
        ':buyerPhone' => $buyerPhone,
        ':deliveryDate' => $deliveryDate,
        ':creatorId' => $creatorId,
        ':creatorEmail' => $creatorEmail
    ]);

    // Update project_interests table to mark as bought
    $updateInterestQuery = $conn->prepare("
        UPDATE project_interests 
        SET is_bought = 1 
        WHERE project_title = :projectTitle AND user_id = :userId
    ");
    $updateInterestQuery->execute([
        ':projectTitle' => $projectTitle,
        ':userId' => $_SESSION['user_id']
    ]);

    // Commit transaction
    $conn->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Project purchased successfully',
        'redirect' => 'bought_projects.php'
    ]);

} catch (Exception $e) {
    // Rollback transaction in case of error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // Log the error (you might want to implement proper logging)
    error_log('Project Purchase Error: ' . $e->getMessage());

    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'errors' => ['An unexpected error occurred']
    ]);
}