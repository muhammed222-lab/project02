<?php
session_start(); // Start the session
require_once 'db.php'; // Include your database connection

// Check if the user is logged in as a freelancer
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirect to login if not logged in
    exit;
}

// Get freelancer email from session (make sure it's set)


$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, profile_picture FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$freelancer_email = $user['email'];
// OPTIONAL: Debugging output (remove or comment this in production)
// echo "Freelancer Email from session: " . htmlspecialchars($freelancer_email) . "<br>";

// Proceed with gig creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data
    $gig_name = $_POST['gig_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $tag = $_POST['tag'];
    $category = $_POST['category'];

    // Initialize variables for file uploads
    $screenshot1  = null;
    $screenshot2  = null;

    // Handle file uploads
    if (isset($_FILES['screenshot1']) && $_FILES['screenshot1']['error'] === UPLOAD_ERR_OK) {
        $screenshot1 = 'uploads/' . uniqid() . '_' . basename($_FILES['screenshot1']['name']);
        move_uploaded_file($_FILES['screenshot1']['tmp_name'], $screenshot1);
    }

    if (isset($_FILES['screenshot2']) && $_FILES['screenshot2']['error'] === UPLOAD_ERR_OK) {
        $screenshot2 = 'uploads/' . uniqid() . '_' . basename($_FILES['screenshot2']['name']);
        move_uploaded_file($_FILES['screenshot2']['tmp_name'], $screenshot2);
    }

    // Insert the gig into the database
    $stmt = $pdo->prepare("INSERT INTO gigs (freelancer_email, gig_name, description, screenshot1, screenshot2, price, tag, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Execute the statement with the freelancer_email from session
    try {
        $success = $stmt->execute([$freelancer_email, $gig_name, $description, $screenshot1, $screenshot2, $price, $tag, $category]);

        if ($success) {
            echo "<script>alert('Gig created successfully!'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Error creating gig. Please try again.');</script>";
        }
    } catch (PDOException $e) {
        // Catch and display any errors
        echo "<script>alert('Database error: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Gig</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include 'nav.php'; ?>
    <div class="max-w-3xl mx-auto p-8 bg-white rounded-lg border mt-10 shadow-md">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800 text-center">Create Gig -
            <?php echo htmlspecialchars($freelancer_email) ?></h1>
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="gig_name" class="block text-sm font-medium text-gray-700">Gig Name:</label>
                <input type="text" id="gig_name" name="gig_name"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea id="description" name="description"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required></textarea>
            </div>

            <div>
                <label for="screenshot1" class="block text-sm font-medium text-gray-700">Screenshot 1:</label>
                <input type="file" id="screenshot1" name="screenshot1"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" accept="image/*" required>
            </div>

            <div>
                <label for="screenshot2" class="block text-sm font-medium text-gray-700">Screenshot 2:</label>
                <input type="file" id="screenshot2" name="screenshot2"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" accept="image/*" required>
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                <input type="number" id="price" name="price"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" step="0.01" required>
            </div>

            <div>
                <label for="tag" class="block text-sm font-medium text-gray-700">Tag:</label>
                <input type="text" id="tag" name="tag"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                <input type="text" id="category" name="category"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md shadow-md">Create
                    Gig</button>
            </div>
        </form>
    </div>
</body>

</html>