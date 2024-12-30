<?php
session_start();
require_once './db.php';

// Check if user is logged in as a creator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'creator') {
    header("Location: ../login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, profile_picture FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $creator_id = $_SESSION['user_id'];
    $creator_email = $user['email'];

    // Handle file uploads
    $projectFile = $_FILES['project_file'];
    $writeupFile = $_FILES['writeup_file'];
    $uploadDir = '../creator/uploaded/';

    // Check and move project file
    $projectExt = pathinfo($projectFile['name'], PATHINFO_EXTENSION);
    if (in_array($projectExt, ['zip', 'rar'])) {
        $projectFileName = uniqid() . '.' . $projectExt;
        move_uploaded_file($projectFile['tmp_name'], $uploadDir . $projectFileName);
    } else {
        echo "Invalid project file type. Only .zip or .rar files are allowed.";
        exit();
    }

    // Check and move writeup file
    $writeupExt = pathinfo($writeupFile['name'], PATHINFO_EXTENSION);
    if (in_array($writeupExt, ['docx', 'txt'])) {
        $writeupFileName = uniqid() . '.' . $writeupExt;
        move_uploaded_file($writeupFile['tmp_name'], $uploadDir . $writeupFileName);
    } else {
        echo "Invalid writeup file type. Only .docx or .txt files are allowed.";
        exit();
    }

    // Insert new project into the database
    $insertQuery = "INSERT INTO projects (creator_id, title, description, price, category, project_file, writeup_file, creator_email) 
                    VALUES (:creator_id, :title, :description, :price, :category, :project_file, :writeup_file, :creator_email)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([
        ':creator_id' => $creator_id,
        ':title' => $title,
        ':description' => $description,
        ':price' => $price,
        ':category' => $category,
        ':project_file' => $projectFileName,
        ':writeup_file' => $writeupFileName,
        ':creator_email' => $creator_email

    ]);

    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create New Project</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include 'nav.php'; ?>
    <div class="container mx-auto mt-10 flex flex-col lg:flex-row">
        <!-- Sidebar -->
        <aside class="w-full lg:w-1/4 bg-white p-4 border-solid border-gray-200 border mb-6 lg:mb-0 relative">
            <nav class="space-y-4">
                <a href="#" class="project-assist block text-green-700 hover:text-green-800 font-semibold"
                    data-info="Create a title to let the buyer know what kind of project this is.">
                    Project Title
                </a>
                <a href="#" class="project-assist block text-green-700 hover:text-green-800 font-semibold"
                    data-info="Provide a detailed description to give buyers a clear understanding.">
                    Project Description
                </a>
                <a href="#" class="project-assist block text-green-700 hover:text-green-800 font-semibold"
                    data-info="Set a price that reflects the value and effort of the project.">
                    Project Price
                </a>
                <a href="#" class="project-assist block text-green-700 hover:text-green-800 font-semibold"
                    data-info="Choose a category that best fits your project.">
                    Project Category
                </a>
                <a href="#" class="project-assist block text-green-700 hover:text-green-800 font-semibold"
                    data-info="Upload a project file that showcases your work effectively.">
                    Upload Project File
                </a>
                <a href="#" class="project-assist block text-green-700 hover:text-green-800 font-semibold"
                    data-info="Provide a project writeup in .docx or .txt format.">
                    Upload Project Writeup
                </a>
            </nav>
            <div
                class="info-box hidden absolute top-0 left-full ml-4 w-72 p-2 bg-white border border-gray-300 rounded shadow-lg">
                <p class="text-gray-700"></p>
            </div>
        </aside>

        <style>
            .info-box {
                display: none;
            }

            .project-assist:hover+.info-box,
            .project-assist:focus+.info-box {
                display: block;
            }

            .project-assist:hover~.info-box p,
            .project-assist:focus~.info-box p {
                display: block;
            }

            .project-assist:hover~.info-box p:after,
            .project-assist:focus~.info-box p:after {
                content: attr(data-info);
            }
        </style>

        <script>
            // JavaScript to handle dynamic content on hover
            const projectLinks = document.querySelectorAll('.project-assist');
            const infoBox = document.querySelector('.info-box');
            const infoText = infoBox.querySelector('p');

            projectLinks.forEach(link => {
                link.addEventListener('mouseenter', () => {
                    infoText.textContent = link.getAttribute('data-info');
                    infoBox.style.display = 'block';
                });
                link.addEventListener('mouseleave', () => {
                    infoBox.style.display = 'none';
                });
            });
        </script>



        <!-- Main Content -->
        <main class="w-full lg:w-3/4 p-4">
            <h1 class="text-3xl font-bold text-green-700 mb-6">Create New Project</h1>
            <form action="create_project.php" method="POST" enctype="multipart/form-data"
                class="bg-white p-6 rounded-md border-solid border-gray-200 border space-y-6">
                <div>
                    <label class="block text-lg font-semibold" for="title">Project Title</label>
                    <input type="text" id="title" name="title"
                        class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-green-200" required>
                </div>

                <div>
                    <label class="block text-lg font-semibold" for="description">Description</label>
                    <textarea id="description" name="description"
                        class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-green-200" rows="5"
                        required></textarea>
                </div>

                <div>
                    <label class="block text-lg font-semibold" for="price">Price</label>
                    <input type="number" id="price" name="price"
                        class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-green-200" step="0.01"
                        required>
                </div>

                <div>
                    <label class="block text-lg font-semibold" for="category">Category</label>
                    <input type="text" id="category" name="category" list="category-list"
                        class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-green-200" required>
                    <datalist id="category-list">
                        <option value="Web Development">
                        <option value="Mobile App Development">
                        <option value="Data Science">
                        <option value="Machine Learning">
                        <option value="Graphic Design">
                    </datalist>
                </div>

                <div>
                    <label class="block text-lg font-semibold" for="project_file">Upload Project File (.zip,
                        .rar)</label>
                    <input type="file" id="project_file" name="project_file" accept=".zip,.rar"
                        class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-green-200" required>
                </div>

                <div>
                    <label class="block text-lg font-semibold" for="writeup_file">Upload Writeup (.docx, .txt)</label>
                    <input type="file" id="writeup_file" name="writeup_file" accept=".docx,.txt"
                        class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-green-200" required>
                </div>
                <div>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" hidden>

                </div>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-md font-semibold">Create
                    Project</button>
            </form>
        </main>
    </div>
</body>

</html>