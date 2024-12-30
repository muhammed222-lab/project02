<?php
// connect to the database
include '../php/db.php';

// Initialize search and filter variables
$search = '';
$dateFilter = '';
$priceFilter = '';
$programmingFilter = '';
$techFilter = '';
$topicFilter = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'] ?? '';
    $dateFilter = $_POST['date'] ?? '';
    $priceFilter = $_POST['price'] ?? '';
    $programmingFilter = $_POST['programming'] ?? '';
    $techFilter = $_POST['technology'] ?? '';
    $topicFilter = $_POST['topic'] ?? '';
}

// Construct the SQL query with filters
$sql = "SELECT * FROM projects WHERE 1=1";

// Construct the SQL query with filters

if (!empty($search)) {
    $sql .= " AND (title LIKE :search OR description LIKE :search)";
}
if (!empty($dateFilter)) {
    $sql .= " AND created_date >= :dateFilter";
}
if (!empty($priceFilter)) {
    $sql .= " AND price <= :priceFilter";
}
if (!empty($programmingFilter)) {
    $sql .= " AND programming_lang = :programmingFilter";  // Updated this line
}
if (!empty($techFilter)) {
    $sql .= " AND tech = :techFilter";  // Updated this line
}
if (!empty($topicFilter)) {
    $sql .= " AND topic_name = :topicFilter";  // Updated this line
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
if (!empty($programmingFilter)) {
    $stmt->bindValue(':programmingFilter', $programmingFilter, PDO::PARAM_STR);
}
if (!empty($techFilter)) {
    $stmt->bindValue(':techFilter', $techFilter, PDO::PARAM_STR);
}
if (!empty($topicFilter)) {
    $stmt->bindValue(':topicFilter', $topicFilter, PDO::PARAM_STR);
}

// Execute the statement
$stmt->execute();

// Fetch results
$projects = $stmt->fetchAll();

// Fetch user details

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .filter-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .project-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .custom-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>
    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        <h1 class="text-4xl font-bold mb-8 text-gray-800">Available Projects</h1>

        <!-- Search and Filter Form -->
        <div class="filter-container p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Filter Projects</h3>
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                        placeholder="Search projects..." 
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all" />
                    
                    <input type="date" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all" />
                    
                    <input type="number" name="price" placeholder="Max Price" value="<?php echo htmlspecialchars($priceFilter); ?>"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <select name="category" class="custom-select w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        <option value="">Project Category</option>
                        <option value="Writing">Writing & Translation</option>
                        <option value="Design">Design & Creative</option>
                        <option value="Marketing">Marketing & Sales</option>
                        <option value="Business">Business & Consulting</option>
                        <option value="Programming">Programming & Tech</option>
                        <option value="Education">Education & Training</option>
                        <option value="Legal">Legal Services</option>
                        <option value="Admin">Admin Support</option>
                    </select>

                    <select name="budget" class="custom-select w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        <option value="">Budget Range</option>
                        <option value="50">Under $50</option>
                        <option value="100">Under $100</option>
                        <option value="250">Under $250</option>
                        <option value="500">Under $500</option>
                        <option value="1000">Under $1,000</option>
                        <option value="5000">Under $5,000</option>
                    </select>

                    <select name="duration" class="custom-select w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        <option value="">Project Duration</option>
                        <option value="1">Less than 1 week</option>
                        <option value="2">1-2 weeks</option>
                        <option value="4">2-4 weeks</option>
                        <option value="8">1-2 months</option>
                        <option value="12">2-3 months</option>
                        <option value="24">3+ months</option>
                    </select>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-medium">
                        Find Projects
                    </button>
                    
                    <a href="create_project.php" 
                       class="w-full md:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Your Project
                    </a>
                </div>
            </form>
        </div>

        <!-- Project List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $row): ?>
                    <div class="project-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-indigo-50 rounded-full mr-3">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 flex-1"><?php echo htmlspecialchars($row['title']); ?></h2>
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-600 line-clamp-3"><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Budget</span>
                                <span class="font-semibold text-indigo-600">$<?php echo htmlspecialchars($row['price']); ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Posted</span>
                                <span class="text-gray-700"><?php echo date('M j, Y', strtotime($row['created_date'])); ?></span>
                            </div>
                        </div>

                        <div class="mt-6 flex space-x-3">
                            <button onclick='openModal(<?php echo json_encode($row['title']); ?>, 
                                <?php echo json_encode($row['creator_id']); ?>, 
                                <?php echo json_encode($row['creator_email']); ?>);'
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Purchase Project
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <div class="bg-white rounded-xl shadow-sm p-8 max-w-lg mx-auto">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Projects Found</h3>
                        <p class="text-gray-500 mb-6">Try adjusting your search filters or create your own project!</p>
                        <a href="create_project.php" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                            Create a Project
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Purchase Modal -->
        <div id="purchaseModal" class="fixed z-10 inset-0 overflow-y-auto hidden modal-backdrop">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-lg">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Purchase Project</h2>
                    <form id="purchaseForm" action="./buy_project.php" method="POST">
                        <input type="hidden" name="project_title" id="projectTitle">
                        <input type="hidden" name="creator_id" id="creatorId">
                        <input type="hidden" name="creator_email" id="creatorEmail">

                        <div class="space-y-4">
                            <div>
                                <label for="buyer_name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                                <input type="text" name="buyer_name" id="buyerName" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none" required>
                            </div>

                            <div>
                                <label for="buyer_email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
                                <input type="email" name="buyer_email" id="buyerEmail" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none" required>
                            </div>

                            <div>
                                <label for="buyer_phone" class="block text-sm font-medium text-gray-700 mb-1">Your Phone</label>
                                <input type="text" name="buyer_phone" id="buyerPhone" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none" required>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="buyNow" onclick="toggleDeliveryDate()"
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <label for="buyNow" class="ml-2 text-sm text-gray-700">I want to buy now</label>
                            </div>

                            <div id="deliveryDateContainer">
                                <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    When do you need this project
                                </label>
                                <input type="date" name="delivery_date" id="deliveryDate" 
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                                Proceed
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        function openModal(projectTitle, creatorId, creatorEmail) {
            document.getElementById('projectTitle').value = projectTitle;
            document.getElementById('creatorId').value = creatorId;
            document.getElementById('creatorEmail').value = creatorEmail;
            document.getElementById('purchaseModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('purchaseModal').classList.add('hidden');
        }
    </script>
</body>
</html>