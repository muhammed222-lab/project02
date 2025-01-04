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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $name = htmlspecialchars($_POST['name']);
    $department = htmlspecialchars($_POST['department']);
    $matric_number = htmlspecialchars($_POST['matric_number']);

    // Update the user details in the database
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
        echo "Error updating profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables for Theme */
        :root {
            --bg-primary: #222831;
            --bg-secondary: #393E46;
            --accent-color: #00ADB5;
            --text-primary: #EEEEEE;
        }

        /* Global Styles */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            min-height: 100vh;
            color: var(--text-primary);
        }

        /* Input Group Styling */
        .input-group {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: var(--bg-secondary);
            border: 1px solid rgba(0, 173, 181, 0.2);
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
            z-index: 1;
        }

        .input-group:hover {
            transform: translateY(-5px);
            background-color: rgba(57, 62, 70, 0.95);
        }

        .input-group:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        /* Input Styles */
        .custom-input {
            background-color: var(--bg-secondary);
            border-color: var(--accent-color);
            color: var(--text-primary);
            transition: all 0.3s ease;
            position: relative;
        }

        .custom-input::placeholder {
            color: var(--accent-color);
            opacity: 0.7;
            transition: all 0.3s ease;
            transform: translateX(0);
        }

        .custom-input:focus::placeholder {
            opacity: 0.5;
            transform: translateX(10px);
        }

        .custom-input:focus {
            box-shadow: 0 0 15px rgba(0, 173, 181, 0.3);
            border-color: var(--accent-color);
            outline: none;
        }

        /* Placeholder Glow Animation */
        @keyframes placeholder-glow {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 0.5; }
        }

        .custom-input:not(:focus)::placeholder {
            animation: placeholder-glow 2s infinite;
        }

        /* Animations */
        @keyframes fade-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes subtle-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out both;
        }

        .animate-pulse {
            animation: subtle-pulse 2s infinite;
        }

        /* Success Message */
        .success-message {
            background-color: rgba(0, 173, 181, 0.1);
            border-left: 4px solid var(--accent-color);
        }

        /* Submit Button */
        .submit-button {
            background: linear-gradient(to right, var(--accent-color), var(--bg-secondary));
            transition: all 0.3s ease;
        }

        .submit-button:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

        /* Responsive Adjustments */
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

    <main class="container mx-auto px-4 py-16 max-w-4xl">
        <?php if(isset($_SESSION['success_message'])): ?>
        <div class="success-message p-4 mb-6 animate-fade-in" role="alert">
            <p class="text-[var(--accent-color)]"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
        <?php endif; ?>

        <div class="bg-[var(--bg-secondary)] shadow-2xl rounded-2xl overflow-hidden animate-fade-in">
            <div class="bg-gradient-to-r from-[var(--accent-color)] to-[var(--bg-secondary)] p-6 text-[var(--text-primary)]">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Profile Settings</h1>
                        <p class="text-opacity-80">Manage and update your personal information</p>
                    </div>
                    <div class="bg-[var(--accent-color)]/20 p-3 rounded-full animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[var(--text-primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <form id="profileForm" action="" method="POST" class="p-8 space-y-6">
                <!-- Name Field -->
                <div class="input-group rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--accent-color)] mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <label class="text-[var(--text-primary)] font-semibold text-lg">Full Name</label>
                    </div>
                    <input
                        type="text"
                        name="name"
                        id="nameInput"
                        value="<?php echo htmlspecialchars($user['name']); ?>"
                        required
                        minlength="3"
                        class="custom-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-transparent transition-all duration-300 outline-none"
                        placeholder="Enter your full name"
                    >
                    <p class="mt-2 text-sm text-opacity-80">Your full name as it appears on official documents</p>
                </div>

                <!-- Department Field -->
                <div class="input-group rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--accent-color)] mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <label class="text-[var(--text-primary)] font-semibold text-lg">Department</label>
                    </div>
                    <input
                        type="text"
                        name="department"
                        id="departmentInput"
                        value="<?php echo htmlspecialchars($user['department']); ?>"
                        required
                        class="custom-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-transparent transition-all duration-300 outline-none"
                        placeholder="Enter your academic department"
                    >
                    <p class="mt-2 text-sm text-opacity-80">Your current academic department or field of study</p>
                </div>

                <!-- Matric Number Field -->
                <div class="input-group rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--accent-color)] mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        <label class="text-[var(--text-primary)] font-semibold text-lg">Matric Number</label>
                    </div>
                    <input
                        type="text"
                        name="matric_number"
                        id="matricInput"
                        value="<?php echo htmlspecialchars($user['matric_number']); ?>"
                        required
                        pattern="[A-Za-z0-9]+"
                        class="custom-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-[var(--accent-color)] focus:border-transparent transition-all duration-300 outline-none"
                        placeholder="Enter your matric number"
                    >
                    <p class="mt-2 text-sm text-opacity-80">Your unique student identification number</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="submit-button w-full text-[var(--text-primary)] py-4 rounded-lg hover:opacity-90 transition-all duration-300 transform hover:scale-105 flex items-center justify-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileForm');
        const nameInput = document.getElementById('nameInput');
        const departmentInput = document.getElementById('departmentInput');
        const matricInput = document.getElementById('matricInput');

        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Name validation
            if (nameInput.value.trim().length < 3) {
                nameInput.classList.add('border-red-500');
                isValid = false;
            } else {
                nameInput.classList.remove('border-red-500');
            }

            // Department validation
            if (departmentInput.value.trim() === '') {
                departmentInput.classList.add('border-red-500');
                isValid = false;
            } else {
                departmentInput.classList.remove('border-red-500');
            }

            // Matric number validation
            const matricRegex = /^[A-Za-z0-9]+$/;
            if (!matricRegex.test(matricInput.value.trim())) {
                matricInput.classList.add('border-red-500');
                isValid = false;
            } else {
                matricInput.classList.remove('border-red-500');
            }

            if (!isValid) {
                event.preventDefault();
                alert('Please check your input and try again.');
            }
        });
    });
    </script>
</body>
</html>