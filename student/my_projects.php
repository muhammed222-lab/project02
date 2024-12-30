<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM custom_projects WHERE student_id = :student_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':student_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete project action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_project_id'])) {
    $delete_project_id = $_POST['delete_project_id'];
    $delete_query = "DELETE FROM custom_projects WHERE id = :project_id AND student_id = :student_id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':project_id', $delete_project_id, PDO::PARAM_INT);
    $delete_stmt->bindParam(':student_id', $user_id, PDO::PARAM_INT);

    if ($delete_stmt->execute()) {
        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Projects - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>

    <div class="container mx-auto mt-8 px-4">
        <div class="bg-white border rounded-lg p-4">
            <p class="text-gray-700">You need a custom project?</p>
            <a href="create_project.php" class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded">Create New
                Project Post</a>
        </div>
        <h1 class="text-3xl font-bold mb-6 text-gray-800">My Projects</h1>

        <?php if (empty($projects)): ?>
        <div class="bg-white border rounded-lg p-6 text-center">
            <p class="text-gray-700">You have no projects yet. Start creating!</p>
            <a href="create_project.php"
                class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Create New
                Project</a>
        </div>
        <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($projects as $project): ?>
            <div class="bg-white border rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        <?php echo htmlspecialchars($project['project_title']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($project['description']); ?></p>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-gray-500"><strong>Deadline:</strong>
                            <?php echo htmlspecialchars($project['deadline']); ?></p>
                        <p class="text-gray-500"><strong>Budget:</strong>
                            $<?php echo htmlspecialchars($project['budget']); ?></p>
                    </div>

                    <!-- Fetch the number of applications for this project -->
                    <?php
                            $project_id = $project['id'];
                            $application_query = "SELECT COUNT(*) as apply_count FROM project_applications WHERE project_id = :project_id";
                            $application_stmt = $conn->prepare($application_query);
                            $application_stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
                            $application_stmt->execute();
                            $application_result = $application_stmt->fetch(PDO::FETCH_ASSOC);
                            $apply_count = $application_result['apply_count'];
                            ?>

                    <div class="flex justify-between items-center mb-4">
                        <p class="text-gray-500"><strong>Number of Applications:</strong> <?php echo $apply_count; ?>
                        </p>
                        <?php if ($apply_count > 0): ?>
                        <a href="view_applications.php?project_id=<?php echo $project_id; ?>"
                            class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">View
                            Applications</a>
                        <?php endif; ?>
                    </div>

                    <!-- Delete Button -->
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                        <input type="hidden" name="delete_project_id" value="<?php echo $project_id; ?>">
                        <button type="submit"
                            class="inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete
                            Project</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>