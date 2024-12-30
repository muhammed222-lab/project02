<?php
session_start();
$host = 'localhost'; // Your database host
$db = 'project_02'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password
$charset = 'utf8mb4';

// Set up the DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Set the default fetch mode to associative array
    PDO::ATTR_EMULATE_PREPARES   => false, // Disable emulated prepared statements
];

try {
    // Create a PDO instance
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
    exit();
} // Ensure this points to the correct path for your db.php file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = $_POST['receiver_id'];
    $content = $_POST['message_content'];
    $sender_id = $_SESSION['user_id'];

    // Check if the message content is empty
    if (empty($content)) {
        echo "Error: Message content cannot be empty.";
        exit();
    }

    // Verify that the receiver_id exists in the users table
    try {
        $query = "SELECT id FROM users WHERE id = :receiver_id LIMIT 1";
        $stmt = $conn->prepare($query); // Use $conn instead of $pdo
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->execute();
        $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$receiver) {
            echo "Error: The selected creator does not exist.";
            exit();
        }

        // If receiver_id is valid, proceed with inserting the message
        $query = "INSERT INTO messages (sender_id, receiver_id, content, created_at) 
                  VALUES (:sender_id, :receiver_id, :content, NOW())"; // Correct field name 'content'

        $stmt = $conn->prepare($query); // Use $conn instead of $pdo
        $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: chat.php?creator_id=" . $receiver_id); // Redirect back to chat
            exit();
        } else {
            echo "Error: Unable to send message.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If not a POST request, you can handle accordingly or redirect
    header("Location: messages.php"); // Redirect if accessed directly
    exit();
}