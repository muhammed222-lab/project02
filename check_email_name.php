<?php
require 'db.php';

$email = $_POST['email'];
$name = strtolower($_POST['name']); // Convert name input to lowercase

$response = [
    'emailFound' => false,
    'nameMatches' => false
];

// Check if the email exists in the database
$query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$query->execute(['email' => $email]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $response['emailFound'] = true;

    // Check if either the first or last name matches
    $storedNames = explode(" ", strtolower($user['name'])); // Split DB name into parts
    if (in_array($name, $storedNames)) {
        $response['nameMatches'] = true;
    }
}

echo json_encode($response);