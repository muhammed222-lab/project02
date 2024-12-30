<?php
session_start();
include('../php/db.php'); // Include the database connection

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Project | Project 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/css/ionicons.min.css">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .form-input {
            transition: all 0.2s ease;
        }
        .form-input:focus {
            box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
        }
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
        }
        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .custom-file-upload {
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .custom-file-upload:hover {
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include 'nav.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Create a Custom Project</h2>
                <p class="text-gray-600 mt-2">Fill in the details below to submit your project</p>
            </div>

            <form action="php/submit_project.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="project_title" class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
                    <input type="text" id="project_title" name="project_title"
                        class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                        placeholder="Enter project title" required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description"
                        class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                        rows="4" placeholder="Describe your project in detail" required></textarea>
                </div>

                <div>
                    <label for="keywords" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                    <input type="text" id="keywords" name="keywords"
                        class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                        placeholder="Enter keywords separated by commas" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" id="deadline" name="deadline"
                            class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                            required>
                    </div>

                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Budget ($)</label>
                        <input type="number" id="budget" name="budget"
                            class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                            placeholder="Enter your budget" required>
                    </div>
                </div>

                <div>
                    <label for="project_proposal" class="block text-sm font-medium text-gray-700 mb-1">Project Proposal</label>
                    <div class="file-input-wrapper">
                        <div class="custom-file-upload w-full px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg text-center hover:bg-gray-50 transition-colors">
                            <i class="icon ion-md-cloud-upload text-2xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600">Drop your file here or click to browse</p>
                            <p class="text-xs text-gray-500 mt-1">Supported formats: docx, txt, images</p>
                            <input type="file" id="project_proposal" name="project_proposal" class="hidden" required>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for student information -->
                <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($user['email']); ?>">
                <input type="hidden" name="student_name" value="<?php echo htmlspecialchars($user['name']); ?>">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user['id']); ?>">

                <div class="flex justify-end mt-8">
                    <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center">
                        <span>Submit Project</span>
                        <i class="icon ion-md-arrow-forward ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add file name display functionality
        document.getElementById('project_proposal').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const fileTextElement = this.parentElement.querySelector('p');
                fileTextElement.textContent = fileName;
            }
        });
    </script>
</body>
</html>