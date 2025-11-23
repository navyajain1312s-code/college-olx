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

$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

// Validation
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Please enter both email and password']);
    exit();
}

try {
    // Query user
    $sql = "SELECT id, username, password FROM users WHERE email = " . $conn->quote($email);
    $stmt = $conn->query($sql);
    
    if ($stmt && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $username = $row['username'];
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            // Password is correct, start session
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Login successful!',
                'username' => $username
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No account found with that email']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
