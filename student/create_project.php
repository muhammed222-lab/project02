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

            <form action="submit_project.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="project_title" class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
                        <input type="text" id="project_title" name="project_title"
                            class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                            placeholder="Enter a clear, descriptive title" required>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Project Category</label>
                        <select name="category" id="category" 
                            class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                            required>
                            <option value="">Select Category</option>
                            <option value="Writing">Writing & Translation</option>
                            <option value="Design">Design & Creative</option>
                            <option value="Marketing">Marketing & Sales</option>
                            <option value="Business">Business & Consulting</option>
                            <option value="Programming">Programming & Tech</option>
                            <option value="Education">Education & Training</option>
                            <option value="Legal">Legal Services</option>
                            <option value="Admin">Admin Support</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Project Description</label>
                    <textarea id="description" name="description"
                        class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                        rows="4" placeholder="Describe your project requirements, goals, and expectations in detail" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Required Skills</label>
                        <input type="text" id="skills" name="skills"
                            class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                            placeholder="e.g., Content Writing, SEO, Research" required>
                        <p class="mt-1 text-sm text-gray-500">Separate skills with commas</p>
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Project Duration</label>
                        <select name="duration" id="duration"
                            class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                            required>
                            <option value="">Select Duration</option>
                            <option value="1">Less than 1 week</option>
                            <option value="2">1-2 weeks</option>
                            <option value="4">2-4 weeks</option>
                            <option value="8">1-2 months</option>
                            <option value="12">2-3 months</option>
                            <option value="24">3+ months</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Budget Range ($)</label>
                        <select name="budget" id="budget"
                            class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                            required>
                            <option value="">Select Budget Range</option>
                            <option value="50">Under $50</option>
                            <option value="100">$50 - $100</option>
                            <option value="250">$100 - $250</option>
                            <option value="500">$250 - $500</option>
                            <option value="1000">$500 - $1,000</option>
                            <option value="5000">$1,000 - $5,000</option>
                            <option value="5001">$5,000+</option>
                        </select>
                    </div>

                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" id="deadline" name="deadline"
                            class="form-input w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Files</label>
                    <div class="file-input-wrapper">
                        <div class="custom-file-upload w-full px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg text-center hover:bg-gray-50 transition-colors">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm text-gray-600" id="file-name">Drop your files here or click to browse</p>
                            <p class="text-xs text-gray-500 mt-1">Include any relevant documents, briefs, or examples</p>
                            <input type="file" id="project_files" name="project_files[]" class="hidden" multiple>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for student information -->
                <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($user['email']); ?>">
                <input type="hidden" name="student_name" value="<?php echo htmlspecialchars($user['name']); ?>">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user['id']); ?>">

                <div class="flex justify-end mt-8">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Project
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
                // Handle file selection
                document.getElementById('project_files').addEventListener('change', function(e) {
                    const fileCount = e.target.files.length;
                    const fileNameElement = document.getElementById('file-name');
                    
                    if (fileCount > 0) {
                        if (fileCount === 1) {
                            fileNameElement.textContent = e.target.files[0].name;
                        } else {
                            fileNameElement.textContent = `${fileCount} files selected`;
                        }
                    } else {
                        fileNameElement.textContent = 'Drop your files here or click to browse';
                    }
                });

                // Form submission handler
                document.querySelector('form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    
                    fetch('submit_project.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = 'my_projects.php';
                        } else {
                            alert('Error creating project: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while creating the project');
                    });
                });
    </script>
</body>
</html>