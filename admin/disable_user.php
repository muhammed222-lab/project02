<?php
include 'php/db.php';

$id = $_POST['id'];
$stmt = $conn->prepare("UPDATE users SET status = 'disabled' WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "User has been disabled successfully.";
} else {
    echo "Error disabling user: " . $stmt->error;
}
