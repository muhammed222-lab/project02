<?php
session_start();
include('../php/db.php'); // Include the database connection

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

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = htmlspecialchars($_POST['name']);
    $department = htmlspecialchars($_POST['department']);
    $matric_number = htmlspecialchars($_POST['matric_number']);

    $updateQuery = "UPDATE users SET name = :name, department = :department, matric_number = :matric_number WHERE id = :user_id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':name', $name);
    $updateStmt->bindParam(':department', $department);
    $updateStmt->bindParam(':matric_number', $matric_number);
    $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating profile.";
    }
}

// Handle email update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_email'])) {
    $new_email = filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL);
    
    if ($new_email) {
        $updateEmailQuery = "UPDATE users SET email = :new_email WHERE id = :user_id";
        $updateEmailStmt = $conn->prepare($updateEmailQuery);
        $updateEmailStmt->bindParam(':new_email', $new_email);
        $updateEmailStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($updateEmailStmt->execute()) {
            $_SESSION['success_message'] = "Email updated successfully!";
            header("Location: edit_profile.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating email.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid email address.";
    }
}

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    $checkPasswordQuery = "SELECT password FROM users WHERE id = :user_id";
    $checkPasswordStmt = $conn->prepare($checkPasswordQuery);
    $checkPasswordStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkPasswordStmt->execute();
    $user_password = $checkPasswordStmt->fetchColumn();

    if (password_verify($current_password, $user_password)) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 8) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $updatePasswordQuery = "UPDATE users SET password = :new_password WHERE id = :user_id";
                $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
                $updatePasswordStmt->bindParam(':new_password', $hashed_password);
                $updatePasswordStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

                if ($updatePasswordStmt->execute()) {
                    $_SESSION['success_message'] = "Password updated successfully!";
                    header("Location: edit_profile.php");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Error updating password.";
                }
            } else {
                $_SESSION['error_message'] = "New password must be at least 8 characters long.";
            }
        } else {
            $_SESSION['error_message'] = "New passwords do not match.";
        }
    } else {
        $_SESSION['error_message'] = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .password-strength {
            height: 4px;
            transition: width 0.3s ease-in-out;
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
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 animate-fade-in" role="alert">
                <p class="text-green-700"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 animate-fade-in" role="alert">
                <p class="text-red-700"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Profile Settings</h1>
                        <p class="text-blue-100">Manage and update your personal information</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-8">
                <!-- Profile Information -->
                <section>
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-50 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Profile Information</h2>
                    </div>
                    <form id="profileForm" action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="<?php echo htmlspecialchars($user['name']); ?>"
                                required
                                minlength="3"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="Enter your full name"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <input 
                                type="text" 
                                name="department" 
                                value="<?php echo htmlspecialchars($user['department']); ?>"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="Your academic department"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Matric Number</label>
                            <input 
                                type="text" 
                                name="matric_number" 
                                value="<?php echo htmlspecialchars($user['matric_number']); ?>"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="Your student identification number"
                            >
                        </div>
                        <button 
                            type="submit" 
                            name="update_profile"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Update Profile
                        </button>
                    </form>
                </section>

                <!-- Email Settings -->
                <section>
                    <div class="flex items-center mb-6">
                        <div class="bg-purple-50 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Email Settings</h2>
                    </div>
                    <form id="emailForm" action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Email</label>
                            <input 
                                type="email" 
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg" 
                                disabled
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Email</label>
                            <input 
                                type="email" 
                                name="new_email"
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                placeholder="Enter new email address"
                            >
                        </div>
                        <button 
                            type="submit" 
                            name="update_email"
                            class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition-colors"
                        >
                            Update Email
                        </button>
                    </form>
                </section>

                <!-- Password Settings -->
                <section>
                    <div class="flex items-center mb-6">
                        <div class="bg-green-50 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Password Settings</h2>
                    </div>
                    <form id="passwordForm" action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input 
                                type="password" 
                                name="current_password"
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="Enter current password"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input 
                                type="password" 
                                name="new_password"
                                id="new_password"
                                required
                                minlength="8"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="Enter new password"
                            >
                            <div class="mt-2 h-1 w-full bg-gray-200 rounded-full">
                                <div id="password-strength" class="password-strength bg-red-500 rounded-full" style="width: 0;"></div>
                            </div>
                            <p id="password-strength-text" class="mt-2 text-sm text-gray-500">Password strength: Weak</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input 
                                type="password" 
                                name="confirm_password"
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="Confirm new password"
                            >
                        </div>
                        <button 
                            type="submit" 
                            name="update_password"
                            class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors"
                        >
                            Update Password
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const newPasswordInput = document.getElementById('new_password');
        const passwordStrengthBar = document.getElementById('password-strength');
        const passwordStrengthText = document.getElementById('password-strength-text');

        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let strengthText = 'Weak';
            let strengthColor = 'red-500';

            // Check password length
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;

            // Check for different character types
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Set strength text and color
            switch(strength) {
                case 0:
                case 1:
                case 2:
                    strengthText = 'Weak';
                    strengthColor = 'red-500';
                    break;
                case 3:
                case 4:
                    strengthText = 'Medium';
                    strengthColor = 'yellow-500';
                    break;
                default:
                    strengthText = 'Strong';
                    strengthColor = 'green-500';
            }

            // Update strength bar and text
            passwordStrengthBar.style.width = `${(strength / 5) * 100}%`;
            passwordStrengthBar.className = `password-strength bg-${strengthColor} rounded-full`;
            passwordStrengthText.textContent = `Password strength: ${strengthText}`;
        });

        // Form validation
        const forms = ['profileForm', 'emailForm', 'passwordForm'];
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            form.addEventListener('submit', function(e) {
                const inputs = form.querySelectorAll('input[required]');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        });
    });
    </script>
</body>
</html>