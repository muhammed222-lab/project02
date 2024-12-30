<?php
require_once 'db.php';

$query = $_POST['query'] ?? '';
$sort = $_POST['sort'] ?? null;  // Make sorting optional
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 50;  // Max results per page
$offset = ($page - 1) * $limit;

// Build the base SQL query with pagination
$sql = "SELECT * FROM projects WHERE title LIKE :query OR description LIKE :query";

// Apply sorting only if a valid sort option is specified
if ($sort === 'price') {
    $sql .= " ORDER BY price";
} elseif ($sort === 'date' && columnExists($pdo, 'projects', 'created_at')) {
    $sql .= " ORDER BY created_at DESC";
}

// Append the LIMIT clause
$sql .= " LIMIT :limit OFFSET :offset";

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display the results
foreach ($results as $project) {
    echo "<div class='bg-white p-6 mb-4 rounded-lg border transition duration-200 hover:shadow-xl' style='width:400px'>";
    echo "<h2 class='text-2xl font-bold text-green-800 mb-2'>{$project['title']}</h2>";
    echo "<p class='text-gray-700'>{$project['description']}</p>";
    echo "<p class='mt-4 text-sm text-gray-500'>Price: \${$project['price']}</p>";

    // Conditional date display if the 'created_at' field exists
    if (isset($project['created_at'])) {
        echo "<p class='text-sm text-gray-500'>Date: {$project['created_at']}</p>";
    }

    echo "<a href='./login.php' class='inline-block bg-green-600 text-white px-4 py-2 w-100 rounded mt-4 hover:bg-green-700'>Buy</a>";
    echo "</div>";
}

// Helper function to check column existence
function columnExists($pdo, $table, $column)
{
    try {
        $result = $pdo->query("SHOW COLUMNS FROM $table LIKE '$column'");
        return $result->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

// Count total results for pagination
$stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE title LIKE :query OR description LIKE :query");
$stmt->execute(['query' => "%$query%"]);
$totalResults = $stmt->fetchColumn();
$totalPages = ceil($totalResults / $limit);

// Pagination controls
echo "<div class='flex justify-between mt-4'>";
if ($page > 1) {
    echo "<button class='bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800' onclick='loadResults(\"$query\", \"$sort\", " . ($page - 1) . ")'>Previous</button>";
}
if ($page < $totalPages) {
    echo "<button class='bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800' onclick='loadResults(\"$query\", \"$sort\", " . ($page + 1) . ")'>Next</button>";
}
echo "</div>";