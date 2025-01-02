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
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .step-indicator {
            transition: all 0.3s ease-in-out;
        }
        .step-indicator.active {
            background-color: #3b82f6;
            color: white;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .tag {
            transition: all 0.3s ease;
        }
        .tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Create Your Project</h1>
                        <p class="text-blue-100">Design your project with precision and clarity</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Step Indicators -->
            <div class="flex bg-gray-50 p-4">
                <div class="flex-1 text-center">
                    <div class="step-indicator px-4 py-2 rounded-lg text-sm font-medium text-gray-600 active" data-step="1">
                        Project Basics
                    </div>
                </div>
                <div class="flex-1 text-center">
                    <div class="step-indicator px-4 py-2 rounded-lg text-sm font-medium text-gray-600" data-step="2">
                        Project Details
                    </div>
                </div>
                <div class="flex-1 text-center">
                    <div class="step-indicator px-4 py-2 rounded-lg text-sm font-medium text-gray-600" data-step="3">
                        Review & Submit
                    </div>
                </div>
            </div>

            <form id="projectForm" action="submit_project.php" method="POST" enctype="multipart/form-data" class="p-8">
                <!-- Step 1: Project Basics -->
                <div class="form-step active" data-step="1">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project Title</label>
                            <input 
                                type="text" 
                                name="project_title" 
                                required 
                                minlength="5" 
                                maxlength="100"
                                placeholder="Enter a clear, descriptive project title"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project Category</label>
                            <select 
                                name="category" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                            >
                                <option value="">Select Project Category</option>
                                <option value="Writing">Writing & Translation</option>
                                <option value="Design">Design & Creative</option>
                                <option value="Marketing">Marketing & Sales</option>
                                <option value="Programming">Programming & Tech</option>
                                <option value="Business">Business & Consulting</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" onclick="nextStep(2)" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                                Next: Project Details
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Project Details -->
                <div class="form-step" data-step="2">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project Description</label>
                            <textarea 
                                name="description" 
                                required 
                                minlength="50" 
                                maxlength="500"
                                rows="4"
                                placeholder="Describe your project requirements, goals, and expectations in detail"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Required Skills</label>
                            <div id="skillTagContainer" class="flex flex-wrap gap-2 mb-2"></div>
                            <div class="flex">
                                <input 
                                    type="text" 
                                    id="skillInput" 
                                    placeholder="Type a skill and press Enter"
                                    class="flex-1 px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                >
                                <input type="hidden" name="skills" id="skillsHidden">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Project Duration</label>
                                <select 
                                    name="duration" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                >
                                    <option value="">Select Duration</option>
                                    <option value="1">Less than 1 week</option>
                                    <option value="2">1-2 weeks</option>
                                    <option value="4">2-4 weeks</option>
                                    <option value="8">1-2 months</option>
                                    <option value="12">2-3 months</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>
                                <select 
                                    name="budget" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                >
                                    <option value="">Select Budget</option>
                                    <option value="50">Under $50</option>
                                    <option value="100">$50 - $100</option>
                                    <option value="250">$100 - $250</option>
                                    <option value="500">$250 - $500</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="prevStep(1)" 
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                Previous
                            </button>
                            <button type="button" onclick="nextStep(3)" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                                Next: Review
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Review & Submit -->
                <div class="form-step" data-step="3">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project Files (Optional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <input 
                                    type="file" 
                                    name="project_files[]" 
                                    multiple 
                                    class="hidden" 
                                    id="fileInput"
                                >
                                <label for="fileInput" class="cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-600">Drag and drop files or click to browse</p>
                                    <p class="text-xs text-gray-500 mt-2">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                                </label>
                                <div id="fileList" class="mt-4 text-sm text-gray-600"></div>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Project Summary</h3>
                            <div id="projectSummary" class="space-y-2 text-blue-700"></div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="prevStep(2)" 
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                Previous
                            </button>
                            <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Create Project
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for student information -->
                <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($user['email']); ?>">
                <input type="hidden" name="student_name" value="<?php echo htmlspecialchars($user['name']); ?>">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            </form>
        </div>
    </main>

    <script>
        // Multi-step form logic
        function nextStep(step) {
            const currentStep = document.querySelector('.form-step.active');
            const nextStep = document.querySelector(`.form-step[data-step="${step}"]`);
            const currentIndicator = document.querySelector('.step-indicator.active');
            const nextIndicator = document.querySelector(`.step-indicator[data-step="${step}"]`);

            if (validateStep(currentStep)) {
                currentStep.classList.remove('active');
                nextStep.classList.add('active');
                currentIndicator.classList.remove('active');
                nextIndicator.classList.add('active');

                if (step === 3) {
                    updateProjectSummary();
                }
            }
        }

        function prevStep(step) {
            const currentStep = document.querySelector('.form-step.active');
            const prevStep = document.querySelector(`.form-step[data-step="${step}"]`);
            const currentIndicator = document.querySelector('.step-indicator.active');
            const prevIndicator = document.querySelector(`.step-indicator[data-step="${step}"]`);

            currentStep.classList.remove('active');
            prevStep.classList.add('active');
            currentIndicator.classList.remove('active');
            prevIndicator.classList.add('active');
        }

        function validateStep(step) {
            const inputs = step.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    isValid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            return isValid;
        }

        // Skill tag input
        const skillInput = document.getElementById('skillInput');
        const skillTagContainer = document.getElementById('skillTagContainer');
        const skillsHidden = document.getElementById('skillsHidden');
        const skills = [];

        skillInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                e.preventDefault();
                const skill = this.value.trim();
                
                if (!skills.includes(skill)) {
                    skills.push(skill);
                    const tag = document.createElement('span');
                    tag.classList.add('tag', 'bg-blue-100', 'text-blue-800', 'px-2', 'py-1', 'rounded-full', 'text-xs', 'inline-flex', 'items-center', 'mr-2', 'mb-2');
                    tag.innerHTML = `
                        ${skill}
                        <button type="button" class="ml-2 text-blue-500 hover:text-blue-700" onclick="removeSkill(this)">
                            &times;
                        </button>
                    `;
                    skillTagContainer.appendChild(tag);
                    this.value = '';
                    updateSkillsHidden();
                }
            }
        });

        function removeSkill(button) {
            const tag = button.closest('.tag');
            const skill = tag.textContent.trim().replace('×', '');
            const index = skills.indexOf(skill);
            
            if (index > -1) {
                skills.splice(index, 1);
            }
            
            tag.remove();
            updateSkillsHidden();
        }

        function updateSkillsHidden() {
            skillsHidden.value = skills.join(',');
        }

        // File upload handling
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');

        fileInput.addEventListener('change', function() {
            fileList.innerHTML = '';
            Array.from(this.files).forEach(file => {
                const fileItem = document.createElement('div');
                fileItem.textContent = `${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
                fileList.appendChild(fileItem);
            });
        });

        // Project summary update
        function updateProjectSummary() {
            const summaryContainer = document.getElementById('projectSummary');
            summaryContainer.innerHTML = `
                <p><strong>Title:</strong> ${document.querySelector('input[name="project_title"]').value}</p>
                <p><strong>Category:</strong> ${document.querySelector('select[name="category"]').value}</p>
                <p><strong>Description:</strong> ${document.querySelector('textarea[name="description"]').value}</p>
                <p><strong>Skills:</strong> ${skills.join(', ')}</p>
                <p><strong>Duration:</strong> ${document.querySelector('select[name="duration"]').value} weeks</p>
                <p><strong>Budget:</strong> $${document.querySelector('select[name="budget"]').value}</p>
            `;
        }

        // Form submission
        document.getElementById('projectForm').addEventListener('submit', function(e) {
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