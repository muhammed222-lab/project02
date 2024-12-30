<?php
// Database configuration
$host = 'localhost';      // Server host (usually 'localhost')
$db_name = 'project_02';  // Name of the database
$db_user = 'root';        // Database username
$db_pass = '';            // Database password (set this if your database has one)

// Create a new MySQLi connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}