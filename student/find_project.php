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
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Available Projects</h1>

        <!-- Search and Filter Form -->
        <h3>Filter:</h3>
        <form method="POST" class="mb-6">
            <div class="flex flex-wrap mb-4">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search projects..." class="border p-2 rounded w-40 md:w-1/3 mr-2" />
                <input type="date" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>"
                    class="border p-2 rounded w-40 md:w-1/3 mr-2" />
                <input type="number" name="price" placeholder="Max Price"
                    value="<?php echo htmlspecialchars($priceFilter); ?>"
                    class="border p-2 rounded w-40 md:w-1/3 mr-2" />
            </div>
            <div class="flex flex-wrap mb-4">
                <select name="programming" class="border p-2 rounded w-40 md:w-1/3 mr-2">
                    <option value="">Select Programming Language</option>
                    <option value="PHP" <?php if ($programmingFilter == 'PHP') echo 'selected'; ?>>PHP</option>
                    <option value="JavaScript" <?php if ($programmingFilter == 'JavaScript') echo 'selected'; ?>>
                        JavaScript</option>
                    <option value="Python" <?php if ($programmingFilter == 'Python') echo 'selected'; ?>>Python</option>
                </select>
                <select name="technology" class="border p-2 rounded w-40 md:w-1/3 mr-2">
                    <option value="">Select Technology</option>
                    <option value="React" <?php if ($techFilter == 'React') echo 'selected'; ?>>React</option>
                    <option value="Node.js" <?php if ($techFilter == 'Node.js') echo 'selected'; ?>>Node.js</option>
                </select>
                <select name="topic" class="border p-2 rounded w-40 md:w-1/3 mr-2">
                    <option value="">Select Topic</option>
                    <option value="Web Development" <?php if ($topicFilter == 'Web Development') echo 'selected'; ?>>Web
                        Development</option>
                    <option value="Machine Learning" <?php if ($topicFilter == 'Machine Learning') echo 'selected'; ?>>
                        Machine Learning</option>
                </select>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Search</button>

            <div class="bg-green-100 p-3 mt-3">
                <p>You want something different ? </p>
                <a href="create_custom.php" class="text-green-500">
                    Tell us about it and we'll get the right creator to get it done in no time.
                </a>
            </div>
        </form>

        <!-- Project List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $row): ?>
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-bold"><?php echo htmlspecialchars($row['title']); ?></h2>
                <p class="text-gray-700 mb-2"><?php echo htmlspecialchars($row['description']); ?></p>
                <p class="text-gray-500">Price: $<?php echo htmlspecialchars($row['price']); ?></p>
                <p class="text-gray-500">Created Date: <?php echo htmlspecialchars($row['created_date']); ?></p>
                <a href="javascript:void(0);" onclick='openModal(<?php echo json_encode($row['title']); ?>, 
                      <?php echo json_encode($row['creator_id']); ?>, 
                      <?php echo json_encode($row['creator_email']); ?>);'
                    class="bg-green-600 text-white px-4 py-2 rounded mt-2 inline-block">
                    Buy Project
                </a>


            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>No projects found matching your criteria.</p>
            <?php endif; ?>
        </div>


        <!-- Popup Form HTML -->
        <div id="purchaseModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
                    <h2 class="text-2xl font-bold mb-4">Purchase Project</h2>
                    <form id="purchaseForm" action="./buy_project.php" method="POST">
                        <input type="hidden" name="project_title" id="projectTitle">
                        <input type="hidden" name="creator_id" id="creatorId">
                        <input type="hidden" name="creator_email" id="creatorEmail">

                        <div class="mb-4">
                            <label for="buyer_name" class="block text-gray-700">Your Name:</label>
                            <input type="text" name="buyer_name" id="buyerName" class="border p-2 rounded w-full"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="buyer_email" class="block text-gray-700">Your Email:</label>
                            <input type="email" name="buyer_email" id="buyerEmail" class="border p-2 rounded w-full"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="buyer_phone" class="block text-gray-700">Your Phone:</label>
                            <input type="text" name="buyer_phone" id="buyerPhone" class="border p-2 rounded w-full"
                                required>
                        </div>

                        <div class="mb-4">
                            <input type="checkbox" id="buyNow" onclick="toggleDeliveryDate()">
                            <label for="buyNow" class="text-gray-700">I want to buy now</label>
                        </div>

                        <div class="mb-4" id="deliveryDateContainer">
                            <label for="delivery_date" class="block text-gray-700">When do you need this
                                project:</label>
                            <input type="date" name="delivery_date" id="deliveryDate" class="border p-2 rounded w-full">
                        </div>

                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Proceed</button>
                        <button type="button" onclick="closeModal()"
                            class="ml-2 bg-red-600 text-white px-4 py-2 rounded">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
    // Function to toggle delivery date input
    function toggleDeliveryDate() {
        const deliveryDateContainer = document.getElementById('deliveryDateContainer');
        const buyNowCheckbox = document.getElementById('buyNow');
        if (buyNowCheckbox.checked) {
            deliveryDateContainer.style.display = 'none';
            document.getElementById('deliveryDate').value = ''; // Clear the date
        } else {
            deliveryDateContainer.style.display = 'block';
        }
    }

    // Open the modal and populate form fields
    function openModal(projectTitle, creatorId, creatorEmail) {
        document.getElementById('projectTitle').value = projectTitle;
        document.getElementById('creatorId').value = creatorId;
        document.getElementById('creatorEmail').value = creatorEmail;
        document.getElementById('purchaseModal').classList.remove('hidden');
    }

    // Close the modal
    function closeModal() {
        document.getElementById('purchaseModal').classList.add('hidden');
    }
    </script>
</body>

</html>