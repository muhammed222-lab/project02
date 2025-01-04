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
            background: #222831;
            min-height: 100vh;
            color: #EEEEEE;
        }

        /* ======================
           Animations
           ====================== */
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

        /* ======================
           Password Strength Indicator
           ====================== */
        .password-strength {
            height: 4px;
            transition: width 0.3s ease-in-out;
        }

        /* ======================
           Input Group Styling
           ====================== */
        .input-group {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #393E46;
            border: 1px solid rgba(0, 173, 181, 0.2);
            position: relative;
            overflow: hidden;
        }

        .input-group::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(0, 173, 181, 0.1) 10%, transparent 10.01%);
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.5s ease;
            pointer-events: none;
        }

        .input-group:hover {
            transform: translateY(-5px);
            background-color: rgba(57, 62, 70, 0.95);
        }

        .input-group:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        /* ======================
           Loading Animation
           ====================== */
        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #00ADB5;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ======================
           Fade-in Animation
           ====================== */
        @keyframes fade-in {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out both;
        }

        /* ======================
           Mobile Responsiveness
           ====================== */
        @media (max-width: 768px) {
            .input-group {
                padding: 1.5rem;
            }
            
            header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body class="antialiased">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 max-w-4xl animate-[fadeIn_0.6s_ease-out]">
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

        <div class="bg-[#393E46] shadow-2xl rounded-2xl overflow-hidden border border-[#00ADB5]/20 transform hover:scale-[1.01] transition-all duration-300">
            <div class="bg-gradient-to-r from-[#00ADB5] to-[#393E46] p-6 text-[#EEEEEE] relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-[#00ADB5]/0 via-[#00ADB5]/10 to-[#00ADB5]/0 animate-[pulse_3s_infinite]"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Profile Settings</h1>
                        <p class="text-blue-100">Manage and update your personal information</p>
                    </div>
                    <div class="bg-[#00ADB5]/20 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#EEEEEE]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-8">
                <!-- Profile Information -->
                <section class="animate-[slideIn_0.3s_ease-out]">
                    <div class="flex items-center mb-6">
                        <div class="bg-[#00ADB5]/10 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-[#EEEEEE]">Profile Information</h2>
                    </div>
                    <form id="profileForm" action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-[#EEEEEE]/80 mb-2">Full Name</label>
                            <input
                                type="text"
                                name="name"
                                value="<?php echo htmlspecialchars($user['name']); ?>"
                                required
                                minlength="3"
                                class="w-full px-4 py-3 bg-[#393E46] border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all text-[#EEEEEE]"
                                placeholder="Enter your full name"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#EEEEEE]/80 mb-2">Department</label>
                            <input
                                type="text"
                                name="department"
                                value="<?php echo htmlspecialchars($user['department']); ?>"
                                class="w-full px-4 py-3 bg-[#393E46] border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all text-[#EEEEEE]"
                                placeholder="Your academic department"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#EEEEEE]/80 mb-2">Matric Number</label>
                            <input
                                type="text"
                                name="matric_number"
                                value="<?php echo htmlspecialchars($user['matric_number']); ?>"
                                class="w-full px-4 py-3 bg-[#393E46] border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all text-[#EEEEEE]"
                                placeholder="Your student identification number"
                            >
                        </div>
                        <button
                            type="submit"
                            name="update_profile"
                            class="w-full bg-[#00ADB5] text-[#EEEEEE] py-3 rounded-lg hover:bg-[#00ADB5]/90 transition-colors transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#00ADB5]/20 relative overflow-hidden group"
                        >
                            <div class="relative z-10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update Profile
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-r from-[#00ADB5]/0 via-[#00ADB5]/10 to-[#00ADB5]/0 group-hover:translate-x-full transition-transform duration-1000"></div>
                        </button>
                    </form>
                </section>

                <!-- Email Settings -->
                <section class="animate-[slideIn_0.4s_ease-out]">
                    <div class="flex items-center mb-6">
                        <div class="bg-[#00ADB5]/10 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-[#EEEEEE]">Email Settings</h2>
                    </div>
                    <form id="emailForm" action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-[#EEEEEE]/80 mb-2">Current Email</label>
                            <input
                                type="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                class="w-full px-4 py-3 bg-[#393E46] border border-[#00ADB5]/20 rounded-lg text-[#EEEEEE]" 
                                disabled
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#EEEEEE]/80 mb-2">New Email</label>
                            <input
                                type="email"
                                name="new_email"
                                required
                                class="w-full px-4 py-3 bg-[#393E46] border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all text-[#EEEEEE]"
                                placeholder="Enter new email address"
                            >
                        </div>
                        <button
                            type="submit"
                            name="update_email"
                            class="w-full bg-[#00ADB5] text-[#EEEEEE] py-3 rounded-lg hover:bg-[#00ADB5]/90 transition-colors transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#00ADB5]/20 relative overflow-hidden group"
                        >
                            <div class="relative z-10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Update Email
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-r from-[#00ADB5]/0 via-[#00ADB5]/10 to-[#00ADB5]/0 group-hover:translate-x-full transition-transform duration-1000"></div>
                        </button>
                    </form>
                </section>

                <!-- Password Settings -->
                <section class="animate-[slideIn_0.5s_ease-out]">
                    <div class="flex items-center mb-6">
                        <div class="bg-[#00ADB5]/10 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00ADB5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-[#EEEEEE]">Password Settings</h2>
                    </div>
                    <form id="passwordForm" action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-[#EEEEEE]/80 mb-2">Current Password</label>
                            <input
                                type="password"
                                name="current_password"
                                required
                                class="w-full px-4 py-3 bg-[#393E46] border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all text-[#EEEEEE]"
                                placeholder="Enter current password"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#EEEEEE]/80 mb-2">New Password</label>
                            <input
                                type="password"
                                name="new_password"
                                id="new_password"
                                required
                                minlength="8"
                                class="w-full px-4 py-3 bg-[#393E46] border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all text-[#EEEEEE]"
                                placeholder="Enter new password"
                            >
                            <div class="mt-2 h-1 w-full bg-[#393E46] rounded-full">
                                <div id="password-strength" class="password-strength bg-[#00ADB5] rounded-full" style="width: 0;"></div>
                            </div>
                            <p id="password-strength-text" class="mt-2 text-sm text-[#EEEEEE]/80">Password strength: Weak</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#EEEEEE]/80 mb-2">Confirm New Password</label>
                            <input
                                type="password"
                                name="confirm_password"
                                required
                                class="w-full px-4 py-3 bg-[#393E46] border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all text-[#EEEEEE]"
                                placeholder="Confirm new password"
                            >
                        </div>
                        <button
                            type="submit"
                            name="update_password"
                            class="w-full bg-[#00ADB5] text-[#EEEEEE] py-3 rounded-lg hover:bg-[#00ADB5]/90 transition-colors transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#00ADB5]/20 relative overflow-hidden group"
                        >
                            <div class="relative z-10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Update Password
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-r from-[#00ADB5]/0 via-[#00ADB5]/10 to-[#00ADB5]/0 group-hover:translate-x-full transition-transform duration-1000"></div>
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