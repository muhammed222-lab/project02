<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

// Check if a project_id is provided
if (!isset($_GET['project_id']) || empty($_GET['project_id'])) {
    header("Location: ./view_applications.php"); // Redirect if no project_id is given
    exit();
}

$project_id = intval($_GET['project_id']);

// Fetch applicants for the project
$query = "SELECT pa.id AS application_id, pa.creator_id, pa.creator_name, pa.creator_email, pa.why_choose_me, pa.qualification, pa.status, pa.applied_at, u.profile_picture 
          FROM project_applications pa 
          JOIN users u ON pa.creator_id = u.id
          WHERE pa.project_id = :project_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$stmt->execute();

$project_details = [];
if ($stmt->rowCount() > 0) {
    $project_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $no_applicants = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Applicants - Project02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>

    <div class="container mx-auto mt-20 px-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-800">Applicants for Project
            #<?php echo htmlspecialchars($project_id); ?></h1>

        <?php if (isset($no_applicants)): ?>
        <div class="bg-white p-6 rounded-lg border text-center">
            <p class="text-gray-700">No applicants found for this project.</p>
        </div>
        <?php else: ?>
        <div class="bg-white p-6 rounded-lg border">
            <?php foreach ($project_details as $applicant): ?>
            <div class="flex items-center justify-between border-b border-gray-300 py-4">
                <div class="flex items-center space-x-4">
                    <img src="../uploads/<?php echo htmlspecialchars($applicant['profile_picture']); ?>"
                        alt="Profile Picture" class="w-16 h-16 rounded-full">
                    <div>
                        <h2 class="text-lg font-bold"><?php echo htmlspecialchars($applicant['creator_name']); ?></h2>
                        <p class="text-gray-600">Email: <?php echo htmlspecialchars($applicant['creator_email']); ?></p>
                        <p class="text-gray-600">Qualification:
                            <?php echo htmlspecialchars($applicant['qualification']); ?></p>
                        <p class="text-gray-500">Why choose me:
                            <?php echo htmlspecialchars($applicant['why_choose_me']); ?></p>
                        <p class="text-green-500 font-bold">Status:
                            <?php echo htmlspecialchars($applicant['status']); ?></p>
                    </div>
                </div>
                <div>
                    <form method="post" action="process_application.php">
                        <input type="hidden" name="application_id" value="<?php echo $applicant['application_id']; ?>">
                        <button type="submit" name="action" value="approve"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Approve</button>
                        <button type="submit" name="action" value="dismiss"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Dismiss</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>