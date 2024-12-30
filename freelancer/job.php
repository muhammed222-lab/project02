<?php
session_start();
include('../php/db.php'); // Include the database connection
include('./db.php'); // Include the database connection

// Check if the user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header("Location: ../index.php");
    exit();
}

$freelancer_id = $_SESSION['user_id'];

// Fetch all projects
$query = "SELECT * FROM custom_projects";
$stmt = $conn->prepare($query);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);


$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, profile_picture FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Available Projects - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <script>
    function openApplyPopup(projectId) {
        document.getElementById('project_id').value = projectId;
        document.getElementById('applyPopup').classList.remove('hidden');
    }

    function closeApplyPopup() {
        document.getElementById('applyPopup').classList.add('hidden');
    }
    </script>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>

    <div class="container mx-auto mt-8 px-4">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Available Projects</h1>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($projects as $project): ?>
            <div class="bg-white border rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        <?php echo htmlspecialchars($project['project_title']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($project['description']); ?></p>
                    <p class="text-gray-500 mb-4"><strong>Student Name:</strong>
                        <?php echo htmlspecialchars($project['student_name']); ?></p>
                    <p class="text-gray-500 mb-4"><strong>Email:</strong>
                        <?php echo htmlspecialchars($project['student_email']); ?></p>
                    <p class="text-gray-500 mb-4"><strong>Budget:</strong>
                        <?php echo htmlspecialchars($project['budget']); ?></p>
                    <p class="text-gray-500 mb-4"><strong>Date Created:</strong>
                        <?php echo htmlspecialchars($project['created_at']); ?></p>
                    <p class="text-gray-500 mb-4"><strong>Deadline:</strong>
                        <?php echo htmlspecialchars($project['deadline']); ?></p>

                    <!-- Number of Applications -->
                    <?php
                        $project_id = $project['id'];
                        $application_query = "SELECT COUNT(*) as apply_count FROM project_applications WHERE project_id = :project_id";
                        $application_stmt = $conn->prepare($application_query);
                        $application_stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
                        $application_stmt->execute();
                        $application_result = $application_stmt->fetch(PDO::FETCH_ASSOC);
                        $apply_count = $application_result['apply_count'];
                        ?>
                    <p class="text-gray-500 mb-4"><strong>Number of Applications:</strong> <?php echo $apply_count; ?>
                    </p>

                    <!-- Download Proposal -->
                    <?php if (!empty($project['project_proposal'])): ?>
                    <a href="../creator/uploaded/<?php echo htmlspecialchars($project['project_proposal']); ?>" download
                        class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mb-4">Download
                        Proposal</a>
                    <?php endif; ?>

                    <!-- Apply Button -->
                    <button onclick="openApplyPopup(<?php echo $project_id; ?>)"
                        class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4">Apply</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Apply Popup -->
    <div id="applyPopup" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4">Apply for Project</h2>
            <form method="POST" action="apply_for_project.php">
                <!-- Hidden input for Project ID -->
                <input type="hidden" name="project_id" id="project_id">

                <!-- Hidden input to capture logged-in freelancer ID -->
                <input type="hidden" name="freelancer_id" id="freelancer_id"
                    value="<?php echo htmlspecialchars($user['id']); ?>">

                <!-- Auto-captured Freelancer Name (read-only) -->
                <div class="mb-4">
                    <label class="block text-gray-700">Freelancer Name:</label>
                    <input type="text" name="freelancer_name" class="w-full border rounded-lg p-2"
                        value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                </div>

                <!-- Auto-captured Freelancer Email (read-only) -->
                <div class="mb-4">
                    <label class="block text-gray-700">Freelancer Email:</label>
                    <input type="email" name="freelancer_email" class="w-full border rounded-lg p-2"
                        value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>

                <!-- Why Choose Me Section -->
                <div class="mb-4">
                    <label class="block text-gray-700">Why Choose Me:</label>
                    <textarea name="why_choose_me" rows="4" class="w-full border rounded-lg p-2" required></textarea>
                </div>

                <!-- Qualification Section -->
                <div class="mb-4">
                    <label class="block text-gray-700">Qualification:</label>
                    <input type="text" name="qualification" class="w-full border rounded-lg p-2" required>
                </div>

                <!-- Form Buttons -->
                <div class="flex justify-between">
                    <button type="button" onclick="closeApplyPopup()"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Submit
                        Application</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>