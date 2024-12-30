<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gig_id = $_POST['gig_id'];
    $gig_name = $_POST['gig_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $updateQuery = "UPDATE gigs SET gig_name = :gig_name, description = :description, price = :price WHERE gig_id = :gig_id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute([
        ':gig_name' => $gig_name,
        ':description' => $description,
        ':price' => $price,
        ':gig_id' => $gig_id
    ]);

    echo "Gig updated successfully!";
}