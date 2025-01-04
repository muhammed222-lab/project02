<?php
// Include database connection
include '../php/db.php';

// Flutterwave API Keys
define('FLW_PUBLIC_KEY', 'FLWPUBK_TEST-0ddb40b442d8517b066065c814a52c40-X');
define('FLW_SECRET_KEY', 'FLWSECK_TEST-0a93444ae09378f3732b3b131af4f572-X');
define('FLW_ENCRYPTION_KEY', 'FLWSECK_TEST86dd6dfcf815');

// Payment Processing Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay'])) {
    $amount = $_POST['amount']; // Amount to charge
    $email = $_POST['email'];   // Customer email

    // Prepare payload for Flutterwave
    $payload = [
        'tx_ref' => uniqid(), // Unique transaction reference
        'amount' => $amount,
        'currency' => 'NGN',
        'redirect_url' => 'http://your-domain.com/payment_callback.php', // Update with your callback URL
        'payment_options' => 'card, banktransfer, ussd',
        'customer' => [
            'email' => $email,
            'name' => $_POST['name'],
        ],
    ];

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.flutterwave.com/v3/payments');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . FLW_SECRET_KEY,
        'Content-Type: application/json',
    ]);

    // Execute cURL and handle the response
    $response = curl_exec($ch);
    curl_close($ch);
    $response_data = json_decode($response, true);

    // Redirect to payment link if successful
    if (isset($response_data['status']) && $response_data['status'] === 'success') {
        header("Location: " . $response_data['data']['link']);
        exit();
    } else {
        echo "Error: " . $response_data['message'];
    }
}

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
<<<<<<< HEAD
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #222831 0%, #393E46 100%);
        min-height: 100vh;
        color: #EEEEEE;
    }
=======
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
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #222831;
            min-height: 100vh;
            color: #EEEEEE;
        }
>>>>>>> cbd3d120b905ccab86a7d4f94c9344e9251bb8fd

    /* ======================
           Project Card Styling
           ====================== */
<<<<<<< HEAD
    .project-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(0);
        background-color: #393E46;
        border: 1px solid rgba(0, 173, 181, 0.2);
        position: relative;
        overflow: hidden;
    }

    .project-card::before {
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

    .project-card:hover {
        transform: translateY(-5px);
        background-color: rgba(57, 62, 70, 0.95);
    }

    .project-card:hover::before {
        transform: translate(-50%, -50%) scale(1);
    }

    /* ======================
=======
        .project-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
            background-color: #393E46;
            border: 1px solid rgba(0, 173, 181, 0.2);
            position: relative;
            overflow: hidden;
            transform-origin: center;
        }

        .project-card:hover {
            transform: translateY(-5px) scale(1.02);
            background-color: rgba(57, 62, 70, 0.95);
            box-shadow: 0 10px 20px rgba(0, 173, 181, 0.1);
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out both;
        }

        .animate-slide-in {
            animation: slideIn 0.4s ease-out both;
        }
        /* Additional hover and interaction effects */
        .project-card::before {
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

        .project-card:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }
        /* ======================
>>>>>>> cbd3d120b905ccab86a7d4f94c9344e9251bb8fd
           View Toggle Buttons
           ====================== */
    .view-toggle-active {
        background-color: #00ADB5;
        color: #EEEEEE;
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
        .project-card {
            margin-bottom: 1.5rem;
        }

        .filter-section {
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

    <main class="container mx-auto px-4 py-16 max-w-7xl animate-fade-in">
        <header class="mb-12 text-center animate-fade-in">
            <h1 class="text-5xl font-bold text-[#EEEEEE] mb-4">Discover Projects</h1>
            <p class="text-xl text-[#EEEEEE]/80 max-w-2xl mx-auto">Find the perfect project that matches your skills and
                interests. Filter, explore, and connect with opportunities.</p>
        </header>

        <!-- ======================
           Advanced Filtering Section
           ====================== -->
        <section
            class="bg-[#393E46]/95 backdrop-blur-md rounded-2xl p-8 mb-12 border border-[#00ADB5]/20 animate-fade-in">
            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
<<<<<<< HEAD
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Projects</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                            placeholder="Keywords, skills, or project name"
                            class="w-full px-4 py-3 border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all bg-[#393E46] text-[#EEEEEE]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project Category</label>
                        <select name="category"
                            class="w-full px-4 py-3 border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all bg-[#393E46] text-[#EEEEEE]">
=======
                        <label class="block text-sm font-medium text-[#EEEEEE] mb-2">Search Projects</label>
                            <input
                                type="text"
                                name="search"
                                value="<?php echo htmlspecialchars($search); ?>"
                                placeholder="Keywords, skills, or project name"
                                class="w-full px-4 py-3 border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all bg-[#393E46] text-[#EEEEEE]"
                            >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#EEEEEE] mb-2">Project Category</label>
                            <select
                                name="category"
                                class="w-full px-4 py-3 border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all bg-[#393E46] text-[#EEEEEE]"
                            >
>>>>>>> cbd3d120b905ccab86a7d4f94c9344e9251bb8fd
                            <option value="">All Categories</option>
                            <option value="Writing">Writing & Translation</option>
                            <option value="Design">Design & Creative</option>
                            <option value="Marketing">Marketing & Sales</option>
                            <option value="Programming">Programming & Tech</option>
                            <option value="Business">Business & Consulting</option>
                        </select>
                    </div>
                    <div>
<<<<<<< HEAD
                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>
                        <select name="budget"
                            class="w-full px-4 py-3 border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all bg-[#393E46] text-[#EEEEEE]">
=======
                        <label class="block text-sm font-medium text-[#EEEEEE] mb-2">Budget Range</label>
                            <select
                                name="budget"
                                class="w-full px-4 py-3 border border-[#00ADB5]/20 rounded-lg focus:ring-2 focus:ring-[#00ADB5] focus:border-transparent transition-all bg-[#393E46] text-[#EEEEEE]"
                            >
>>>>>>> cbd3d120b905ccab86a7d4f94c9344e9251bb8fd
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
<<<<<<< HEAD
                        <span class="text-sm text-gray-600">View:</span>
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button type="button" onclick="toggleView('grid')"
                                class="view-toggle px-4 py-2 rounded-lg view-toggle-active" id="gridViewBtn">
=======
                        <span class="text-sm text-[#EEEEEE]/70">View:</span>
                        <div class="flex bg-[#393E46] rounded-lg p-1">
                            <button 
                                type="button" 
                                onclick="toggleView('grid')" 
                                class="view-toggle px-4 py-2 rounded-lg view-toggle-active"
                                id="gridViewBtn"
                            >
>>>>>>> cbd3d120b905ccab86a7d4f94c9344e9251bb8fd
                                Grid
                            </button>
                            <button type="button" onclick="toggleView('list')" class="view-toggle px-4 py-2 rounded-lg"
                                id="listViewBtn">
                                List
                            </button>
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-[#00ADB5] hover:bg-[#00ADB5]/90 text-[#EEEEEE] px-6 py-3 rounded-lg transition-colors">
                        Apply Filters
                    </button>
                </div>
            </form>
        </section>

        <!-- Project List/Grid -->
        <section id="projectContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $row): ?>
            <div class="project-card rounded-2xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-[#00ADB5]/10 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00ADB5]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#EEEEEE] flex-1">
                            <?php echo htmlspecialchars($row['title']); ?></h2>
                    </div>

                    <p class="text-[#EEEEEE]/80 mb-4 line-clamp-3"><?php echo htmlspecialchars($row['description']); ?>
                    </p>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-[#EEEEEE]/80">Budget</span>
                            <span
                                class="font-semibold text-[#00ADB5]">$<?php echo htmlspecialchars($row['price']); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-[#EEEEEE]/80">Posted</span>
                            <span
                                class="text-[#EEEEEE]"><?php echo date('M j, Y', strtotime($row['created_date'])); ?></span>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button onclick="openModal('<?php echo htmlspecialchars($row['title']); ?>', 
                                                     '<?php echo htmlspecialchars($row['creator_id']); ?>', 
                                                     '<?php echo htmlspecialchars($row['creator_email']); ?>', 
                                                     '<?php echo htmlspecialchars($row['price']); ?>');"
                            class="flex-1 bg-[#00ADB5] hover:bg-[#00ADB5]/90 text-[#EEEEEE] py-3 rounded-lg transition-colors flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Purchase Project
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <!-- No Projects Found Placeholder -->
            <?php endif; ?>
        </section>

        <!-- Modal Structure -->
        <div id="purchaseModal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-[#393E46] rounded-lg p-6 max-w-lg w-full">
                <h3 id="modalTitle" class="text-2xl font-bold text-[#EEEEEE] mb-4"></h3>
                <p id="modalDetails" class="text-[#EEEEEE]/80 mb-6"></p>
                <div class="flex justify-between items-center mb-6">
                    <span class="text-[#EEEEEE]/80">Price:</span>
                    <span id="modalPrice" class="font-semibold text-[#00ADB5]"></span>
                </div>
                <button id="confirmPurchase"
                    class="w-full bg-[#00ADB5] hover:bg-[#00ADB5]/90 text-[#EEEEEE] py-3 rounded-lg transition-colors">Confirm
                    Purchase</button>
                <button onclick="closeModal()" class="w-full mt-3 text-[#EEEEEE]/80">Cancel</button>
            </div>
        </div>

        <script>
        function openModal(title, creatorId, creatorEmail, price) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDetails').textContent = `Creator: ${creatorEmail} (ID: ${creatorId})`;
            document.getElementById('modalPrice').textContent = `$${price}`;
            document.getElementById('purchaseModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('purchaseModal').classList.add('hidden');
        }

        document.getElementById('confirmPurchase').addEventListener('click', () => {
            // Add AJAX call or form submission logic here
            alert('Purchase confirmed!');
            closeModal();
        });
        </script>

    </main>

    <!-- Purchase Modal (Same as previous implementation) -->
    <div id="purchaseModal" class="fixed z-10 inset-0 overflow-y-auto hidden"
        style="background-color: rgba(0, 0, 0, 0.5);">
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