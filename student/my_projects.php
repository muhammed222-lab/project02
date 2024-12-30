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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="bg-white shadow-sm rounded-xl p-6 mb-8 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Welcome to Your Projects</h1>
                    <p class="text-gray-600 mt-1">Create and manage your custom projects</p>
                </div>
                <a href="create_project.php" 
                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create New Project
                </a>
            </div>
        </div>

        <?php if (empty($projects)): ?>
        <div class="bg-white shadow-sm rounded-xl p-8 text-center border border-gray-100">
            <div class="max-w-md mx-auto">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No projects yet</h3>
                <p class="mt-1 text-gray-500">Get started by creating your first project</p>
                <a href="create_project.php"
                   class="mt-6 inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create New Project
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($projects as $project): ?>
            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">
                        <?php echo htmlspecialchars($project['project_title']); ?>
                    </h2>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        <?php echo htmlspecialchars($project['description']); ?>
                    </p>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?php echo htmlspecialchars($project['deadline']); ?>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                $<?php echo htmlspecialchars($project['budget']); ?>
                            </div>
                        </div>

                        <?php
                        $project_id = $project['id'];
                        $application_query = "SELECT COUNT(*) as apply_count FROM project_applications WHERE project_id = :project_id";
                        $application_stmt = $conn->prepare($application_query);
                        $application_stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
                        $application_stmt->execute();
                        $application_result = $application_stmt->fetch(PDO::FETCH_ASSOC);
                        $apply_count = $application_result['apply_count'];
                        ?>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center text-gray-600 text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <?php echo $apply_count; ?> Applications
                            </div>
                            
                            <?php if ($apply_count > 0): ?>
                            <a href="view_applications.php?project_id=<?php echo $project_id; ?>"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors duration-200">
                                View Applications
                            </a>
                            <?php endif; ?>
                        </div>

                        <form method="POST" class="mt-4" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            <input type="hidden" name="delete_project_id" value="<?php echo $project_id; ?>">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>