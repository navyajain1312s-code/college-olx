<?php
session_start();
header('Content-Type: application/json');

require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    
    // Fetch user's products
    $sql = "SELECT * FROM products WHERE user_id = " . $userId . " ORDER BY created_at DESC";
    $stmt = $conn->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
