<?php
// Include the database connection
include 'php/db.php';

// Start the session to access admin details
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input fields
    $admin_id = $_SESSION['admin_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Prepare the SQL query to update the admin details
    $sql = "UPDATE admins SET name='$name', email='$email', phone='$phone' WHERE id='$admin_id'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Update session variables
        $_SESSION['admin_name'] = $name;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_phone'] = $phone;

        // Redirect with success message
        header("Location: admin_profile.php?message=Profile updated successfully");
    } else {
        // Show error message if the update fails
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);