<?php
session_start();
include('../php/db.php');

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

// Check if a project_id is provided
if (!isset($_GET['project_id']) || empty($_GET['project_id'])) {
    header("Location: my_projects.php");
    exit();
}

$project_id = intval($_GET['project_id']);

// Fetch project details
$projectQuery = "SELECT title FROM custom_projects WHERE id = :project_id";
$projectStmt = $conn->prepare($projectQuery);
$projectStmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$projectStmt->execute();
$project = $projectStmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    header("Location: my_projects.php");
    exit();
}

// Fetch applicants for the project with filtering
$statusFilter = $_GET['status'] ?? null;
$query = "SELECT pa.id AS application_id, pa.creator_id, u.name AS creator_name, 
                 u.email AS creator_email, pa.why_choose_me, 
                 pa.qualification, pa.status, pa.applied_at, 
                 u.profile_picture 
          FROM project_applications pa 
          JOIN users u ON pa.creator_id = u.id
          WHERE pa.project_id = :project_id";

if ($statusFilter) {
    $query .= " AND pa.status = :status";
}

$query .= " ORDER BY pa.applied_at DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);

if ($statusFilter) {
    $stmt->bindParam(':status', $statusFilter, PDO::PARAM_STR);
}

$stmt->execute();
$project_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Applications | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .applicant-card {
            transition: all 0.3s ease-in-out;
            transform: translateY(0);
        }
        .applicant-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .status-badge {
            transition: background-color 0.3s ease;
        }
        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="antialiased">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 max-w-7xl">
        <header class="mb-12 text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Project Applications</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Review and manage applications for your project: 
                <span class="font-semibold text-blue-600"><?php echo htmlspecialchars($project['title']); ?></span>
            </p>
        </header>

        <!-- Filtering Section -->
        <section class="bg-white shadow-xl rounded-2xl p-8 mb-12">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Filter by Status:</span>
                    <div class="flex space-x-2">
                        <a href="?project_id=<?php echo $project_id; ?>" 
                           class="px-4 py-2 <?php echo empty($statusFilter) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'; ?> rounded-lg transition-colors">
                            All
                        </a>
                        <a href="?project_id=<?php echo $project_id; ?>&status=pending" 
                           class="px-4 py-2 <?php echo $statusFilter === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700'; ?> rounded-lg transition-colors">
                            Pending
                        </a>
                        <a href="?project_id=<?php echo $project_id; ?>&status=approved" 
                           class="px-4 py-2 <?php echo $statusFilter === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'; ?> rounded-lg transition-colors">
                            Approved
                        </a>
                        <a href="?project_id=<?php echo $project_id; ?>&status=dismissed" 
                           class="px-4 py-2 <?php echo $statusFilter === 'dismissed' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700'; ?> rounded-lg transition-colors">
                            Dismissed
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-600">Total Applications:</span>
                    <span class="bg-blue-50 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        <?php echo count($project_details); ?>
                    </span>
                </div>
            </div>
        </section>

        <?php if (empty($project_details)): ?>
        <div class="bg-white rounded-2xl shadow-xl p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="bg-blue-50 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">No Applications Yet</h3>
                <p class="text-gray-600 mb-8">Your project hasn't received any applications. Keep promoting your project!</p>
            </div>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($project_details as $applicant): 
                $statusColor = match($applicant['status']) {
                    'approved' => 'green',
                    'dismissed' => 'red',
                    default => 'yellow'
                };
            ?>
            <div class="applicant-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center space-x-4">
                            <img 
                                src="../uploads/<?php echo htmlspecialchars($applicant['profile_picture'] ?? 'default_profile.png'); ?>" 
                                alt="<?php echo htmlspecialchars($applicant['creator_name']); ?>"
                                class="w-16 h-16 rounded-full object-cover"
                            >
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">
                                    <?php echo htmlspecialchars($applicant['creator_name']); ?>
                                </h2>
                                <p class="text-sm text-gray-600">
                                    <?php echo htmlspecialchars($applicant['creator_email']); ?>
                                </p>
                            </div>
                        </div>
                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium bg-<?php echo $statusColor; ?>-100 text-<?php echo $statusColor; ?>-800">
                            <?php echo htmlspecialchars($applicant['status']); ?>
                        </span>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Qualification</h3>
                            <p class="text-gray-600 line-clamp-2">
                                <?php echo htmlspecialchars($applicant['qualification']); ?>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Why Choose Me</h3>
                            <p class="text-gray-600 line-clamp-3">
                                <?php echo htmlspecialchars($applicant['why_choose_me']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <form action="process_application.php" method="POST" class="flex space-x-3 w-full">
                            <input type="hidden" name="application_id" value="<?php echo $applicant['application_id']; ?>">
                            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                            
                            <button 
                                type="submit" 
                                name="action" 
                                value="approve" 
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Approve
                            </button>
                            
                            <button 
                                type="submit" 
                                name="action" 
                                value="dismiss" 
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Dismiss
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>