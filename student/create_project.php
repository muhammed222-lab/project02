<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Project | Project 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/css/ionicons.min.css">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100  justify-center items-center h-screen">
    <?php include 'nav.php'; ?>

    <div class="m-auto w-full max-w-2xl bg-white border-2 border-solid border-gray-200 p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Create a Custom Project</h2>
        <form action="php/submit_project.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="project_title" class="block text-sm font-semibold">Project Title:</label>
                <input type="text" id="project_title" name="project_title"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-semibold">Description:</label>
                <textarea id="description" name="description"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    rows="4" required></textarea>
            </div>
            <div class="mb-4">
                <label for="keywords" class="block text-sm font-semibold">Keywords:</label>
                <input type="text" id="keywords" name="keywords"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4">
                <label for="deadline" class="block text-sm font-semibold">Deadline:</label>
                <input type="date" id="deadline" name="deadline"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4">
                <label for="budget" class="block text-sm font-semibold">Budget:</label>
                <input type="number" id="budget" name="budget"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4">
                <label for="project_proposal" class="block text-sm font-semibold">Upload Proposal File (docx, txt,
                    image):</label>
                <input type="file" id="project_proposal" name="project_proposal"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>

            <!-- Hidden fields for student information -->
            <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($user['email']); ?>">
            <input type="hidden" name="student_name" value="<?php echo htmlspecialchars($user['name']); ?>">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user['id']); ?>">

            <div class="mb-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">Submit
                    Project</button>
            </div>
        </form>
    </div>
</body>

</html>