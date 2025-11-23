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
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = intval($input['id'] ?? 0);

file_put_contents('debug_delete.log', date('Y-m-d H:i:s') . " - Request: " . print_r($input, true) . "\n", FILE_APPEND);
file_put_contents('debug_delete.log', date('Y-m-d H:i:s') . " - Parsed ID: " . $productId . "\n", FILE_APPEND);
file_put_contents('debug_delete.log', date('Y-m-d H:i:s') . " - Session User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "\n", FILE_APPEND);

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    
    // Check if product belongs to current user
    $checkSql = "SELECT user_id FROM products WHERE id = " . $productId;
    $stmt = $conn->query($checkSql);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }
    
    if ($product['user_id'] != $userId) {
        echo json_encode(['success' => false, 'message' => 'You can only delete your own products']);
        exit();
    }
    
    // Delete the product
    $deleteSql = "DELETE FROM products WHERE id = " . $productId;
    $conn->exec($deleteSql);
    
    echo json_encode([
        'success' => true,
        'message' => 'Product deleted successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
