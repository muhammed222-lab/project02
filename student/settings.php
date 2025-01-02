<?php
session_start();
include('../php/db.php');

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user preferences (if exists)
$prefQuery = "SELECT * FROM user_preferences WHERE user_id = :user_id";
$prefStmt = $conn->prepare($prefQuery);
$prefStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$prefStmt->execute();
$preferences = $prefStmt->fetch(PDO::FETCH_ASSOC) ?: [];

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();

        // Update user preferences
        $upsertPrefQuery = "INSERT INTO user_preferences 
                            (user_id, theme_preference, notification_email, notification_sms, privacy_profile_visibility) 
                            VALUES (:user_id, :theme, :email_notif, :sms_notif, :profile_visibility)
                            ON DUPLICATE KEY UPDATE 
                            theme_preference = :theme,
                            notification_email = :email_notif,
                            notification_sms = :sms_notif,
                            privacy_profile_visibility = :profile_visibility";
        
        $upsertPrefStmt = $conn->prepare($upsertPrefQuery);
        $upsertPrefStmt->execute([
            ':user_id' => $user_id,
            ':theme' => $_POST['theme_preference'] ?? 'light',
            ':email_notif' => isset($_POST['email_notifications']) ? 1 : 0,
            ':sms_notif' => isset($_POST['sms_notifications']) ? 1 : 0,
            ':profile_visibility' => $_POST['profile_visibility'] ?? 'private'
        ]);

        $conn->commit();
        $_SESSION['success_message'] = "Settings updated successfully!";
        header("Location: settings.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error_message'] = "Error updating settings: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .settings-section {
            transition: all 0.3s ease-in-out;
        }
        .settings-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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

    <main class="container mx-auto px-4 py-16 max-w-4xl">
        <?php if(isset($_SESSION['success_message'])): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 animate-fade-in" role="alert">
            <p class="text-green-700"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_message'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 animate-fade-in" role="alert">
            <p class="text-red-700"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Account Settings</h1>
                        <p class="text-blue-100">Manage your account preferences and security</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                    </div>
                </div>
            </div>

            <form action="" method="POST" class="p-8 space-y-8">
                <!-- Profile Management -->
                <section class="settings-section bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-50 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Profile Management</h2>
                    </div>
                    <div class="space-y-4">
                        <a href="edit_profile.php" 
                           class="block bg-white border border-gray-200 p-4 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Edit Personal Information</h3>
                                    <p class="text-sm text-gray-600">Update your name, email, and profile details</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- Appearance Settings -->
                <section class="settings-section bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-purple-50 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Appearance</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-white border border-gray-200 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Theme Preference</h3>
                                    <p class="text-sm text-gray-600">Choose your preferred interface theme</p>
                                </div>
                                <select 
                                    name="theme_preference" 
                                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"
                                >
                                    <option value="light" <?php echo ($preferences['theme_preference'] ?? '') === 'light' ? 'selected' : ''; ?>>Light Theme</option>
                                    <option value="dark" <?php echo ($preferences['theme_preference'] ?? '') === 'dark' ? 'selected' : ''; ?>>Dark Theme</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Notification Preferences -->
                <section class="settings-section bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-50 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-white border border-gray-200 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Email Notifications</h3>
                                    <p class="text-sm text-gray-600">Receive email updates about your projects</p>
                                </div>
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="email_notifications" 
                                        class="hidden" 
                                        <?php echo ($preferences['notification_email'] ?? 0) ? 'checked' : ''; ?>
                                    >
                                    <div class="toggle-switch w-12 h-6 bg-gray-300 rounded-full shadow-inner">
                                        <div class="toggle-dot w-6 h-6 bg-white rounded-full shadow transform transition-transform"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">SMS Notifications</h3>
                                    <p class="text-sm text-gray-600">Receive SMS updates about your projects</p>
                                </div>
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="sms_notifications" 
                                        class="hidden" 
                                        <?php echo ($preferences['notification_sms'] ?? 0) ? 'checked' : ''; ?>
                                    >
                                    <div class="toggle-switch w-12 h-6 bg-gray-300 rounded-full shadow-inner">
                                        <div class="toggle-dot w-6 h-6 bg-white rounded-full shadow transform transition-transform"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Privacy Settings -->
                <section class="settings-section bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-red-50 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Privacy</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-white border border-gray-200 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Profile Visibility</h3>
                                    <p class="text-sm text-gray-600">Control who can view your profile</p>
                                </div>
                                <select 
                                    name="profile_visibility" 
                                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500"
                                >
                                    <option value="private" <?php echo ($preferences['privacy_profile_visibility'] ?? '') === 'private' ? 'selected' : ''; ?>>Private</option>
                                    <option value="public" <?php echo ($preferences['privacy_profile_visibility'] ?? '') === 'public' ? 'selected' : ''; ?>>Public</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="pt-4">
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-4 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-105"
                    >
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle switch functionality
        const toggleSwitches = document.querySelectorAll('.toggle-switch');
        toggleSwitches.forEach(toggleSwitch => {
            const checkbox = toggleSwitch.previousElementSibling;
            const toggleDot = toggleSwitch.querySelector('.toggle-dot');

            toggleSwitch.addEventListener('click', function() {
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    toggleSwitch.classList.add('bg-green-500');
                    toggleDot.style.transform = 'translateX(100%)';
                } else {
                    toggleSwitch.classList.remove('bg-green-500');
                    toggleDot.style.transform = 'translateX(0)';
                }
            });

            // Initial state
            if (checkbox.checked) {
                toggleSwitch.classList.add('bg-green-500');
                toggleDot.style.transform = 'translateX(100%)';
            }
        });
    });
    </script>
</body>
</html>