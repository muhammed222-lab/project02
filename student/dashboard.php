<?php
session_start();
include('../db.php');

// Verify if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// Fetch user data using PDO
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle case where user data could not be fetched
if (!$user) {
    echo "User data not found.";
    exit();
}

// Fetch project counts
$interestedQuery = "SELECT COUNT(*) FROM project_interests WHERE user_id = :user_id AND is_bought = 0";
$stmt = $pdo->prepare($interestedQuery);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$interestedCount = $stmt->fetchColumn();

$boughtQuery = "SELECT COUNT(*) FROM project_interests WHERE user_id = :user_id AND is_bought = 1";
$stmt = $pdo->prepare($boughtQuery);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$boughtCount = $stmt->fetchColumn();

// Determine greeting based on time of day
function getTimeBasedGreeting() {
    $hour = date('H');
    if ($hour < 12) return "Good Morning";
    if ($hour < 17) return "Good Afternoon";
    return "Good Evening";
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/student.css" rel="stylesheet">
    <style>
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
        
        .welcome-gradient {
            background: linear-gradient(to right, #00ADB5, #393E46);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-hover:hover {
            animation: pulse 1.5s infinite;
        }
    </style>
</head>
<body class="antialiased bg-[#222831] text-[#EEEEEE]">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 pt-24">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <header class="mb-10 border border-[#00ADB5]/20 rounded-xl p-6 transform hover:scale-[1.02] transition-all duration-300 animate-[fadeIn_0.6s_ease-out]">
                <div class="flex items-center mb-4">
                    <h1 class="text-4xl font-bold flex items-center">
                        <span class="mr-4 text-[#00ADB5]">👋</span>
                        <span class="welcome-gradient">
                            <?php echo htmlspecialchars(getTimeBasedGreeting()); ?>, 
                            <?php echo htmlspecialchars($user['name']); ?>!
                        </span>
                    </h1>
                </div>
                <p class="text-[#EEEEEE] text-lg">Your personal project management dashboard</p>
            </header>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="rounded-xl p-6 border border-[#00ADB5]/20 bg-[#393E46] hover:bg-[#393E46]/90 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-lg hover:shadow-[#00ADB5]/10 animate-[slideIn_0.3s_ease-out]">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-[#00ADB5]/10 rounded-full mr-4 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-[#EEEEEE]">Your Profile</h2>
                    </div>
                    <div class="space-y-4 text-[#EEEEEE] mt-2">
                        <div class="p-3 rounded-lg border border-[#00ADB5]/10 hover:border-[#00ADB5]/30 hover:bg-[#00ADB5]/5 transition-all duration-300 group">
                            <div class="flex items-center space-x-3 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00ADB5] group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="font-medium text-sm text-[#00ADB5]">Department</span>
                            </div>
                            <span class="block pl-8 text-lg"><?php echo htmlspecialchars($user['department']); ?></span>
                        </div>
                        
                        <div class="p-3 rounded-lg border border-[#00ADB5]/10 hover:border-[#00ADB5]/30 hover:bg-[#00ADB5]/5 transition-all duration-300 group">
                            <div class="flex items-center space-x-3 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00ADB5] group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                                <span class="font-medium text-sm text-[#00ADB5]">Matric Number</span>
                            </div>
                            <span class="block pl-8 text-lg"><?php echo htmlspecialchars($user['matric_number']); ?></span>
                        </div>
                        
                        <div class="p-3 rounded-lg border border-[#00ADB5]/10 hover:border-[#00ADB5]/30 hover:bg-[#00ADB5]/5 transition-all duration-300 group">
                            <div class="flex items-center space-x-3 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00ADB5] group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium text-sm text-[#00ADB5]">Email</span>
                            </div>
                            <span class="block pl-8 text-lg"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        
                        <div class="p-3 rounded-lg border border-[#00ADB5]/10 hover:border-[#00ADB5]/30 hover:bg-[#00ADB5]/5 transition-all duration-300 group">
                            <div class="flex items-center space-x-3 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00ADB5] group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium text-sm text-[#00ADB5]">Joined</span>
                            </div>
                            <span class="block pl-8 text-lg"><?php echo htmlspecialchars($user['join_date']); ?></span>
                        </div>
                    </div>
                    <a href="edit_profile.php" class="mt-8 w-full block text-center bg-[#00ADB5] text-[#EEEEEE] py-3 rounded-lg hover:bg-[#00ADB5]/90 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#00ADB5]/20 relative overflow-hidden group">
                        <div class="relative z-10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="font-medium">Edit Profile</span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#00ADB5]/0 via-[#00ADB5]/10 to-[#00ADB5]/0 group-hover:translate-x-full transition-transform duration-1000"></div>
                    </a>
                </div>

                <!-- Projects Overview Card -->
                <div class="rounded-xl p-6 border border-[#00ADB5]/20 bg-[#393E46] hover:bg-[#393E46]/90 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-lg hover:shadow-[#00ADB5]/10 animate-[slideIn_0.4s_ease-out]">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-[#00ADB5]/10 rounded-full mr-4 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-[#EEEEEE]">Project Overview</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-[#00ADB5]/10 p-4 rounded-lg flex justify-between items-center hover:bg-[#00ADB5]/20 transform hover:scale-[1.02] transition-all duration-300 border border-[#00ADB5]/10 hover:border-[#00ADB5]/30">
                            <div>
                                <h3 class="font-medium text-[#00ADB5] group-hover:text-[#00ADB5] transition-colors">Interested Projects</h3>
                                <p class="text-[#EEEEEE] group-hover:text-[#00ADB5]/90 transition-colors"><?php echo $interestedCount; ?> Project<?php echo $interestedCount != 1 ? 's' : ''; ?></p>
                            </div>
                            <a href="interested_projects.php" class="text-[#00ADB5] hover:text-[#00ADB5]/80 transition-colors flex items-center group">
                                View Details <span class="transform group-hover:translate-x-1 transition-transform inline-block ml-1">→</span>
                            </a>
                        </div>
                        <div class="bg-[#00ADB5]/10 p-4 rounded-lg flex justify-between items-center hover:bg-[#00ADB5]/20 transform hover:scale-[1.02] transition-all duration-300 border border-[#00ADB5]/10 hover:border-[#00ADB5]/30">
                            <div>
                                <h3 class="font-medium text-[#00ADB5] group-hover:text-[#00ADB5] transition-colors">Purchased Projects</h3>
                                <p class="text-[#EEEEEE] group-hover:text-[#00ADB5]/90 transition-colors"><?php echo $boughtCount; ?> Project<?php echo $boughtCount != 1 ? 's' : ''; ?></p>
                            </div>
                            <a href="bought_projects.php" class="text-[#00ADB5] hover:text-[#00ADB5]/80 transition-colors flex items-center group">
                                View Details <span class="transform group-hover:translate-x-1 transition-transform inline-block ml-1">→</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="rounded-xl p-6 border border-[#00ADB5]/20 bg-[#393E46] hover:bg-[#393E46]/90 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-lg hover:shadow-[#00ADB5]/10 animate-[slideIn_0.5s_ease-out]">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-[#00ADB5]/10 rounded-full mr-4 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-[#EEEEEE]">Quick Actions</h2>
                    </div>
                    <div class="space-y-4">
                        <a href="create_project.php" class="block bg-[#00ADB5]/10 p-4 rounded-lg border border-[#00ADB5]/20 hover:bg-[#00ADB5]/20 transform hover:translate-x-1 transition-all duration-300 group">
                            <div class="flex justify-between items-center">
                                <span class="text-[#EEEEEE] font-medium group-hover:text-[#00ADB5]">Create New Project</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00ADB5] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                        <a href="find_project.php" class="block bg-[#00ADB5]/10 p-4 rounded-lg border border-[#00ADB5]/20 hover:bg-[#00ADB5]/20 transform hover:translate-x-1 transition-all duration-300 group">
                            <div class="flex justify-between items-center">
                                <span class="text-[#EEEEEE] font-medium group-hover:text-[#00ADB5]">Browse Projects</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00ADB5] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-[#222831] text-[#EEEEEE] py-6 mt-10 border-t border-[#00ADB5]/20 animate-fade-in">
        <div class="container mx-auto text-center">
            <p>&copy; <?php echo date('Y'); ?> Project Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>