<?php
// Include database connection
include '../php/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Start output buffering
ob_start();

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle API Requests
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    header('Content-Type: application/json'); // Ensure JSON response for API requests
    ob_end_clean(); // Discard any unexpected output

    try {
        $action = $_POST['action'];
        $project_id = $_POST['project_id'] ?? null;

        if (!$project_id) {
            throw new Exception('Project ID is required.');
        }

        if ($action === 'save') {
            // Save Project
            $stmt = $conn->prepare("INSERT INTO project_interests (project_id, user_id, interest_date, is_bought) VALUES (?, ?, NOW(), 0)");
            $stmt->execute([$project_id, $user_id]);
            echo json_encode(["status" => "success", "message" => "Project saved successfully!"]);
        } elseif ($action === 'pay') {
            // Payment Request
            $price = $_POST['price'] ?? null;
            $email = $_POST['email'] ?? null;
            $name = $_POST['name'] ?? null;

            if (!$price || !$email || !$name) {
                throw new Exception('Payment data is incomplete.');
            }

            $payload = [
                'tx_ref' => uniqid(),
                'amount' => $price,
                'currency' => 'NGN',
                'redirect_url' => 'find_project.php',
                'payment_options' => 'card, banktransfer, ussd',
                'customer' => [
                    'email' => $email,
                    'name' => $name,
                ],
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.flutterwave.com/v3/payments');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . FLW_SECRET_KEY,
                'Content-Type: application/json',
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $response_data = json_decode($response, true);

            if (isset($response_data['status']) && $response_data['status'] === 'success') {
                $stmt = $conn->prepare("INSERT INTO project_interests (project_id, user_id, interest_date, is_bought) VALUES (?, ?, NOW(), 1)");
                $stmt->execute([$project_id, $user_id]);
                echo json_encode(["status" => "success", "payment_link" => $response_data['data']['link']]);
            } else {
                throw new Exception($response_data['message'] ?? 'Payment initiation failed.');
            }
        } else {
            throw new Exception('Invalid action.');
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit();
}

// If not an API request, proceed with rendering the HTML page
$search = $_POST['search'] ?? '';
$dateFilter = $_POST['date'] ?? '';
$priceFilter = $_POST['price'] ?? '';
$categoryFilter = $_POST['category'] ?? '';
$budgetFilter = $_POST['budget'] ?? '';
$durationFilter = $_POST['duration'] ?? '';

// Construct the SQL Query with Filters
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
$stmt->execute();

$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'find_project_view.php';
?>