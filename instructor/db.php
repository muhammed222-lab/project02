<?php
// db.php - Database connection file

try {
    // Replace with your own database credentials
    $dsn = 'mysql:host=localhost;dbname=project_02;charset=utf8mb4';
    $username = 'root';  // Replace with your database username
    $password = '';      // Replace with your database password

    // Establish a connection to the database using PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}