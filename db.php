<?php
$host = 'localhost';
$db = 'project_02'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password (empty for XAMPP default)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
  