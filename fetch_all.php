<?php
require_once 'db.php';

$category = $_POST['category'] ?? 'projects';
$query = $_POST['query'] ?? '';

// Set up the SQL query based on the selected category
if ($category === 'projects') {
    // Use project-specific columns
    $sql = "SELECT title, description, price, created_date, is_sold, category, creator_email 
            FROM projects 
            WHERE title LIKE :query OR description LIKE :query";
} elseif ($category === 'gigs') {
    // Use gig-specific columns
    $sql = "SELECT gig_name AS title, description, price, date AS created_date, rating, tag, category, freelancer_email 
            FROM gigs 
            WHERE gig_name LIKE :query OR description LIKE :query";
}

// Prepare, bind, and execute the query
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display the results in styled cards
foreach ($results as $item) {
    echo "<div class='bg-white p-6 mb-4 rounded-md border transition duration-200 bg-gray-100' style='min-width:400px;'>";
    echo "<h2 class='text-2xl font-bold text-green-800 mb-2 capitalize'>" . htmlspecialchars($item['title']) . "</h2>";
    echo "<p class='text-gray-700'><b>Description:</b> " . htmlspecialchars($item['description']) . "</p>";
    echo "<p class='text-gray-700'><b>Price:</b> $" . htmlspecialchars($item['price'] ?? 'N/A') . "</p>";
    echo "<p class='text-gray-700'><b>Date:</b> " . htmlspecialchars($item['created_date'] ?? 'N/A') . "</p>";
    echo "<p class='text-gray-700'><b>Category:</b> " . htmlspecialchars($item['category'] ?? 'N/A') . "</p>";
    echo "<p class='text-gray-700'><b>Contact:</b> " . htmlspecialchars($item['creator_email'] ?? $item['freelancer_email'] ?? 'N/A') . "</p>";

    // Display the "Buy" button if the item isn't sold (for projects)
    if ($category === 'projects' && !empty($item['is_sold']) && $item['is_sold'] == 0) {
        echo "<a href='./login.php' class='inline-block bg-green-600 text-white px-4 py-2 text-center rounded mt-4 hover:bg-green-700' style='width:100%; text-align:center'>Buy</a>";
    }

    echo "</div>";
}