<?php
session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add products']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$title = trim($input['title'] ?? '');
$price = isset($input['price']) ? intval($input['price']) : null;
$category = trim($input['category'] ?? '');
$description = trim($input['description'] ?? '');
$imageUrl = trim($input['imageUrl'] ?? '');

// Validation
if (empty($title)) {
    echo json_encode(['success' => false, 'message' => 'Product title is required']);
    exit();
}

if ($price === null || $price < 0) {
    echo json_encode(['success' => false, 'message' => 'Valid price is required']);
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    $sellerName = $_SESSION['username'] ?? 'Seller';

    $sql = "INSERT INTO products (user_id, title, price, category, description, image_url, seller_name) 
            VALUES (" 
            . $conn->quote($userId) . ", "
            . $conn->quote($title) . ", "
            . $price . ", "
            . $conn->quote($category) . ", "
            . $conn->quote($description) . ", "
            . $conn->quote($imageUrl) . ", "
            . $conn->quote($sellerName) . ")";
    
    $conn->exec($sql);
    
    echo json_encode([
        'success' => true,
        'message' => 'Product listed successfully!'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
