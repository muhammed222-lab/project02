<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM custom_projects WHERE student_id = :student_id ORDER BY created_at DESC";
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
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Function to calculate project progress
function calculateProjectProgress($deadline, $budget) {
    $deadline_date = new DateTime($deadline);
    $now = new DateTime();
    $total_days = $deadline_date->diff(new DateTime($deadline))->days;
    $remaining_days = $now->diff($deadline_date)->days;
    
    $progress = 100 - (($remaining_days / $total_days) * 100);
    return max(0, min(100, round($progress)));
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 173, 181, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(0, 173, 181, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 173, 181, 0);
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #222831;
            color: #EEEEEE;
            min-height: 100vh;
        }

        .project-card {
            transition: all 0.3s ease-in-out;
            transform: translateY(0);
            border: 1px solid rgba(0, 173, 181, 0.2);
            background-color: #393E46;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 173, 181, 0.1), 0 10px 10px -5px rgba(0, 173, 181, 0.04);
            animation: pulse 1.5s infinite;
        }

        .progress-bar {
            transition: width 0.5s ease-in-out;
            background-color: #00ADB5;
        }

        .status-badge {
            background-color: rgba(0, 173, 181, 0.2);
            color: #00ADB5;
            transition: background-color 0.3s ease;
        }

        .delete-btn {
            background-color: #00ADB5;
            color: #EEEEEE;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #00ADB5;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 173, 181, 0.4);
        }
    </style>
</head>
<body class="antialiased">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 max-w-7xl">
        <header class="mb-12 text-center animate-[fadeIn_0.6s_ease-out]">
            <h1 class="text-5xl font-bold text-[#00ADB5] mb-4">Your Project Portfolio</h1>
            <p class="text-xl text-[#EEEEEE] max-w-2xl mx-auto">Manage, track, and grow your projects with ease. Create, monitor, and collaborate seamlessly.</p>
        </header>

        <section class="bg-[#393E46] shadow-2xl rounded-2xl p-8 mb-12 flex justify-between items-center animate-[slideIn_0.4s_ease-out]">
            <div>
                <h2 class="text-2xl font-bold text-[#00ADB5] mb-2">Project Management</h2>
                <p class="text-[#EEEEEE]">Track and manage your custom projects efficiently</p>
            </div>
            <a href="create_project.php" 
               class="inline-flex items-center px-6 py-3 bg-[#00ADB5] text-[#EEEEEE] rounded-lg hover:bg-[#00ADB5]/90 transition-all transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Project
            </a>
        </section>

        <?php if (empty($projects)): ?>
        <div class="bg-[#393E46] rounded-2xl shadow-xl p-16 text-center animate-[fadeIn_0.8s_ease-out]">
            <div class="max-w-md mx-auto">
                <div class="bg-[#00ADB5]/10 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-[#EEEEEE] mb-4">No Projects Yet</h3>
                <p class="text-[#EEEEEE]/80 mb-8">Start your journey by creating your first project. Explore opportunities, showcase your skills, and connect with potential collaborators.</p>
                <a href="create_project.php" 
                   class="inline-flex items-center px-6 py-3 bg-[#00ADB5] text-[#EEEEEE] rounded-lg hover:bg-[#00ADB5]/90 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Your First Project
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($projects as $project): 
                $project_id = $project['id'];
                $progress = calculateProjectProgress($project['deadline'], $project['budget']);
                
                // Determine project status
                $status = 'In Progress';
                $status_color = 'yellow';
                if ($progress >= 100) {
                    $status = 'Completed';
                    $status_color = 'green';
                } elseif ($progress <= 25) {
                    $status = 'Starting';
                    $status_color = 'blue';
                }

                // Fetch application count
                $application_query = "SELECT COUNT(*) as apply_count FROM project_applications WHERE project_id = :project_id";
                $application_stmt = $conn->prepare($application_query);
                $application_stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
                $application_stmt->execute();
                $application_result = $application_stmt->fetch(PDO::FETCH_ASSOC);
                $apply_count = $application_result['apply_count'];
            ?>
            <div class="project-card rounded-2xl shadow-lg overflow-hidden animate-[slideIn_0.5s_ease-out]">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-xl font-bold text-[#EEEEEE] flex-1 mr-4">
                            <?php echo htmlspecialchars($project['project_title']); ?>
                        </h2>
                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium">
                            <?php echo $status; ?>
                        </span>
                    </div>

                    <p class="text-[#EEEEEE]/80 mb-4 line-clamp-3">
                        <?php echo htmlspecialchars($project['description']); ?>
                    </p>

                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-[#EEEEEE]/70 mb-2">
                            <span>Project Progress</span>
                            <span><?php echo $progress; ?>%</span>
                        </div>
                        <div class="w-full bg-[#222831] rounded-full h-2.5">
                            <div 
                                class="progress-bar h-2.5 rounded-full" 
                                style="width: <?php echo $progress; ?>%"
                            ></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <div class="flex items-center text-sm text-[#EEEEEE]/70">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Deadline
                            </div>
                            <span class="font-medium text-[#EEEEEE]"><?php echo htmlspecialchars($project['deadline']); ?></span>
                        </div>
                        <div>
                            <div class="flex items-center text-sm text-[#EEEEEE]/70">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Budget
                            </div>
                            <span class="font-medium text-[#EEEEEE]">$<?php echo htmlspecialchars($project['budget']); ?></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-[#00ADB5]/20">
                        <div class="flex items-center text-[#EEEEEE]/70 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <?php echo $apply_count; ?> Applications
                        </div>
                        
                        <?php if ($apply_count > 0): ?>
                        <a href="view_applications.php?project_id=<?php echo $project_id; ?>"
                           class="inline-flex items-center px-3 py-1.5 bg-[#00ADB5] text-[#EEEEEE] text-sm rounded-lg hover:bg-[#00ADB5]/90 transition-colors">
                            View Applications
                        </a>
                        <?php endif; ?>
                    </div>

                    <form method="POST" class="mt-4" onsubmit="return confirm('Are you sure you want to delete this project?');">
                        <input type="hidden" name="delete_project_id" value="<?php echo $project_id; ?>">
                        <button type="submit"
                                class="delete-btn w-full inline-flex items-center justify-center px-4 py-2 rounded-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Project
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>