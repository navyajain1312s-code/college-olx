<?php
session_start();
header('Content-Type: application/json');

// Include database connection
require_once 'db_connect.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$username = trim($input['username'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$confirmPassword = $input['confirmPassword'] ?? '';

// Validation
if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit();
}

if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit();
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

try {
    // Check if email already exists
    $checkSql = "SELECT id FROM users WHERE email = " . $conn->quote($email);
    $checkStmt = $conn->query($checkSql);
    
    if ($checkStmt && $checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $insertSql = "INSERT INTO users (username, email, password, created_at) VALUES (" 
        . $conn->quote($username) . ", "
        . $conn->quote($email) . ", "
        . $conn->quote($hashed_password) . ", "
        . "datetime('now'))";
    
    $conn->exec($insertSql);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Account created successfully! Please login.'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
