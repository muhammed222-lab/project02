<?php
// Connect to the database
include '../php/db.php';

// Set default filter variables
$search = $_POST['search'] ?? '';
$dateFilter = $_POST['date'] ?? '';
$priceFilter = $_POST['price'] ?? '';
$programmingFilter = $_POST['programming'] ?? '';
$techFilter = $_POST['technology'] ?? '';
$topicFilter = $_POST['topic'] ?? '';

// Build the SQL query
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
if (!empty($programmingFilter)) {
    $sql .= " AND programming_lang = :programmingFilter";
}
if (!empty($techFilter)) {
    $sql .= " AND tech = :techFilter";
}
if (!empty($topicFilter)) {
    $sql .= " AND topic_name = :topicFilter";
}

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
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

// Execute and fetch results
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return data as JSON
echo json_encode($projects);