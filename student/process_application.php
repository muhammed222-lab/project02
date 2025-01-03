<?php
session_start();
header('Content-Type: application/json');

// Include database connection
include('../php/db.php');

// Function to log application actions
function logApplicationAction($conn, $user_id, $application_id, $action) {
    $logQuery = "INSERT INTO application_action_logs 
                 (user_id, application_id, action, timestamp) 
                 VALUES (:user_id, :application_id, :action, NOW())";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->execute([
        ':user_id' => $user_id,
        ':application_id' => $application_id,
        ':action' => $action
    ]);
}

// Validate and sanitize input
function validateInput($input) {
    return filter_var($input, FILTER_VALIDATE_INT) !== false;
}

try {
    // Check if user is logged in and is a student
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        throw new Exception('Unauthorized access', 401);
    }

    // Validate required POST parameters
    $requiredParams = ['application_id', 'action', 'project_id'];
    foreach ($requiredParams as $param) {
        if (!isset($_POST[$param]) || empty($_POST[$param])) {
            throw new Exception("Missing required parameter: $param", 400);
        }
    }

    $user_id = $_SESSION['user_id'];
    $application_id = intval($_POST['application_id']);
    $action = $_POST['action'];
    $project_id = intval($_POST['project_id']);

    // Validate input values
    if (!in_array($action, ['approve', 'dismiss'])) {
        throw new Exception('Invalid action', 400);
    }

    // Begin transaction for data integrity
    $conn->beginTransaction();

    // Verify application belongs to the current project and user
    $verifyQuery = "SELECT id FROM project_applications 
                    WHERE id = :application_id 
                    AND project_id = :project_id 
                    AND status = 'pending'";
    $verifyStmt = $conn->prepare($verifyQuery);
    $verifyStmt->execute([
        ':application_id' => $application_id,
        ':project_id' => $project_id
    ]);

    if ($verifyStmt->rowCount() === 0) {
        throw new Exception('Invalid application or project', 403);
    }

    // Prepare the update query based on the action
    $updateQuery = $action === 'approve' 
        ? "UPDATE project_applications SET status = 'approved' WHERE id = :application_id" 
        : "UPDATE project_applications SET status = 'dismissed' WHERE id = :application_id";

    $updateStmt = $conn->prepare($updateQuery);
    $updateResult = $updateStmt->execute([':application_id' => $application_id]);

    if (!$updateResult) {
        throw new Exception('Failed to update application status', 500);
    }

    // Log the application action
    logApplicationAction($conn, $user_id, $application_id, $action);

    // Commit transaction
    $conn->commit();

    // Prepare success response
    $response = [
        'success' => true,
        'message' => $action === 'approve' 
            ? 'Application approved successfully!' 
            : 'Application dismissed successfully!',
        'action' => $action,
        'application_id' => $application_id
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // Rollback transaction in case of error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // Log the error (you might want to implement proper logging)
    error_log('Application Processing Error: ' . $e->getMessage());

    // Send error response
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
}

exit();