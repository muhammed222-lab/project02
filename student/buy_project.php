<?php
// connect to the database
include './db.php';

try {
    // Get form data
    $projectTitle = $_POST['project_title'];
    $buyerName = $_POST['buyer_name'];
    $buyerEmail = $_POST['buyer_email'];
    $buyerPhone = $_POST['buyer_phone'];
    $deliveryDate = $_POST['delivery_date'] ?? null; // Delivery date can be null if "buy now" is checked
    $creatorId = $_POST['creator_id'];
    $creatorEmail = $_POST['creator_email'];

    // Prepare and execute the statement
    $stmt = $conn->prepare("INSERT INTO clients (project_title, buyer_name, buyer_email, buyer_phone, delivery_date, creator_id, creator_email) 
                            VALUES (:projectTitle, :buyerName, :buyerEmail, :buyerPhone, :deliveryDate, :creatorId, :creatorEmail)");

    $stmt->bindParam(':projectTitle', $projectTitle);
    $stmt->bindParam(':buyerName', $buyerName);
    $stmt->bindParam(':buyerEmail', $buyerEmail);
    $stmt->bindParam(':buyerPhone', $buyerPhone);
    $stmt->bindParam(':deliveryDate', $deliveryDate);
    $stmt->bindParam(':creatorId', $creatorId);
    $stmt->bindParam(':creatorEmail', $creatorEmail);

    if ($stmt->execute()) {
        // Redirect to the interested projects page
        header("Location: http://localhost/project_02/student/interested_projects.php");
        exit();
    } else {
        echo "Error: Could not execute query.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}