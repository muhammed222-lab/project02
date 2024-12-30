<?php
session_start();
include('../db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_title = $_POST['project_title'];
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];
    $deadline = $_POST['deadline'];
    $budget = $_POST['budget'];
    $student_email = $_POST['student_email'];
    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];

    // Handle file upload
    $allowed_extensions = ['docx', 'txt', 'jpg', 'jpeg', 'png'];
    $project_proposal = $_FILES['project_proposal'];
    $file_extension = pathinfo($project_proposal['name'], PATHINFO_EXTENSION);

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "Invalid file type. Only DOCX, TXT, JPG, JPEG, PNG files are allowed. <br> <a href='../create_project.php'>
            <button style='padding:10px 20px; margin-top:20px; border-radius:5px; cursor:pointer; outline:none; border:none; background:darkgreen; color:white;'>Retry</button>
            </a>";
        exit();
    }

    $new_filename = uniqid() . "." . $file_extension; // Generate a unique filename
    $upload_directory = "../uploads/"; // Make sure this directory is writable
    $upload_path = $upload_directory . $new_filename;

    if (move_uploaded_file($project_proposal['tmp_name'], $upload_path)) {
        try {
            // Insert project data into the database
            $query = "INSERT INTO custom_projects (project_title, description, keywords, deadline, budget, project_proposal, student_email, student_name, student_id) 
                      VALUES (:project_title, :description, :keywords, :deadline, :budget, :project_proposal, :student_email, :student_name, :student_id)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':project_title', $project_title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':keywords', $keywords);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->bindParam(':budget', $budget);
            $stmt->bindParam(':project_proposal', $new_filename);
            $stmt->bindParam(':student_email', $student_email);
            $stmt->bindParam(':student_name', $student_name);
            $stmt->bindParam(':student_id', $student_id);

            if ($stmt->execute()) {
                header("Location: ../my_projects.php?success=1");
                exit();
            } else {
                echo "Error saving project.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Failed to upload file.";
    }
}