<?php
// connect to the database
include '../php/db.php';

// Initialize search and filter variables
$search = '';
$dateFilter = '';
$priceFilter = '';
$categoryFilter = '';
$budgetFilter = '';
$durationFilter = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'] ?? '';
    $dateFilter = $_POST['date'] ?? '';
    $priceFilter = $_POST['price'] ?? '';
    $categoryFilter = $_POST['category'] ?? '';
    $budgetFilter = $_POST['budget'] ?? '';
    $durationFilter = $_POST['duration'] ?? '';
}

// Construct the SQL query with filters
$sql = "SELECT * FROM projects WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (title LIKE :search OR description LIKE :search)";
}
if (!empty($dateFilter)) {
    $sql .= " AND created_date >= :dateFilter";
}
if (!empty($priceFilter)) {
    $sql .= " AND price <= :priceFilter";
}
if (!empty($categoryFilter)) {
    $sql .= " AND category = :categoryFilter";
}
if (!empty($budgetFilter)) {
    $sql .= " AND price <= :budgetFilter";
}
if (!empty($durationFilter)) {
    $sql .= " AND duration_weeks <= :durationFilter";
}

$stmt = $conn->prepare($sql);

// Bind parameters if they are not empty
if (!empty($search)) {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
if (!empty($dateFilter)) {
    $stmt->bindValue(':dateFilter', $dateFilter, PDO::PARAM_STR);
}
if (!empty($priceFilter)) {
    $stmt->bindValue(':priceFilter', $priceFilter, PDO::PARAM_INT);
}
if (!empty($categoryFilter)) {
    $stmt->bindValue(':categoryFilter', $categoryFilter, PDO::PARAM_STR);
}
if (!empty($budgetFilter)) {
    $stmt->bindValue(':budgetFilter', $budgetFilter, PDO::PARAM_INT);
}
if (!empty($durationFilter)) {
    $stmt->bindValue(':durationFilter', $durationFilter, PDO::PARAM_INT);
}

// Execute the statement
$stmt->execute();

// Fetch results
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Projects | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .project-card {
            transition: all 0.3s ease-in-out;
            transform: translateY(0);
        }
        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .view-toggle-active {
            background-color: #3b82f6;
            color: white;
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

    <main class="container mx-auto px-4 py-16 max-w-7xl">
        <header class="mb-12 text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Discover Projects</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Find the perfect project that matches your skills and interests. Filter, explore, and connect with opportunities.</p>
        </header>

        <!-- Advanced Filtering Section -->
        <section class="bg-white shadow-xl rounded-2xl p-8 mb-12">
            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Projects</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="<?php echo htmlspecialchars($search); ?>"
                            placeholder="Keywords, skills, or project name" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project Category</label>
                        <select 
                            name="category" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                        >
                            <option value="">All Categories</option>
                            <option value="Writing">Writing & Translation</option>
                            <option value="Design">Design & Creative</option>
                            <option value="Marketing">Marketing & Sales</option>
                            <option value="Programming">Programming & Tech</option>
                            <option value="Business">Business & Consulting</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>
                        <select 
                            name="budget" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        >
                            <option value="">Any Budget</option>
                            <option value="50">Under $50</option>
                            <option value="100">Under $100</option>
                            <option value="250">Under $250</option>
                            <option value="500">Under $500</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">View:</span>
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button 
                                type="button" 
                                onclick="toggleView('grid')" 
                                class="view-toggle px-4 py-2 rounded-lg view-toggle-active"
                                id="gridViewBtn"
                            >
                                Grid
                            </button>
                            <button 
                                type="button" 
                                onclick="toggleView('list')" 
                                class="view-toggle px-4 py-2 rounded-lg"
                                id="listViewBtn"
                            >
                                List
                            </button>
                        </div>
                    </div>
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors"
                    >
                        Apply Filters
                    </button>
                </div>
            </form>
        </section>

        <!-- Project List/Grid -->
        <section id="projectContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $row): ?>
                    <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-all">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-50 p-3 rounded-full mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-gray-800 flex-1"><?php echo htmlspecialchars($row['title']); ?></h2>
                            </div>

                            <p class="text-gray-600 mb-4 line-clamp-3"><?php echo htmlspecialchars($row['description']); ?></p>

                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Budget</span>
                                    <span class="font-semibold text-blue-600">$<?php echo htmlspecialchars($row['price']); ?></span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Posted</span>
                                    <span class="text-gray-700"><?php echo date('M j, Y', strtotime($row['created_date'])); ?></span>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <button 
                                    onclick="openModal(<?php echo json_encode($row['title']); ?>, 
                                    <?php echo json_encode($row['creator_id']); ?>, 
                                    <?php echo json_encode($row['creator_email']); ?>);"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors flex items-center justify-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Purchase Project
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16">
                    <div class="bg-white rounded-2xl shadow-xl p-12 max-w-xl mx-auto">
                        <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">No Projects Found</h3>
                        <p class="text-gray-600 mb-8">Try adjusting your search filters or create your own project to get started!</p>
                        <a href="create_project.php" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create a Project
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Purchase Modal (Same as previous implementation) -->
    <div id="purchaseModal" class="fixed z-10 inset-0 overflow-y-auto hidden" style="background-color: rgba(0, 0, 0, 0.5);">
        <!-- Modal content remains the same as in the previous implementation -->
    </div>

    <script>
        function toggleView(view) {
            const projectContainer = document.getElementById('projectContainer');
            const gridViewBtn = document.getElementById('gridViewBtn');
            const listViewBtn = document.getElementById('listViewBtn');

            if (view === 'grid') {
                projectContainer.classList.remove('md:grid-cols-1');
                projectContainer.classList.add('md:grid-cols-3');
                gridViewBtn.classList.add('view-toggle-active');
                listViewBtn.classList.remove('view-toggle-active');
            } else {
                projectContainer.classList.remove('md:grid-cols-3');
                projectContainer.classList.add('md:grid-cols-1');
                listViewBtn.classList.add('view-toggle-active');
                gridViewBtn.classList.remove('view-toggle-active');
            }
        }

        // Modal functions remain the same as in the previous implementation
        function openModal(projectTitle, creatorId, creatorEmail) {
            document.getElementById('projectTitle').value = projectTitle;
            document.getElementById('creatorId').value = creatorId;
            document.getElementById('creatorEmail').value = creatorEmail;
            document.getElementById('purchaseModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('purchaseModal').classList.add('hidden');
        }

        function toggleDeliveryDate() {
            const deliveryDateContainer = document.getElementById('deliveryDateContainer');
            const buyNowCheckbox = document.getElementById('buyNow');
            if (buyNowCheckbox.checked) {
                deliveryDateContainer.style.display = 'none';
                document.getElementById('deliveryDate').value = '';
            } else {
                deliveryDateContainer.style.display = 'block';
            }
        }
    </script>
</body>
</html>