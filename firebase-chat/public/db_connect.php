<?php
$db_file = __DIR__ . '/database.sqlite';

try {
    // Create (connect to) SQLite database in file
    $conn = new PDO("sqlite:" . $db_file);
    // Set errormode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create users table if it doesn't exist
    $userTableSQL = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        created_at TEXT DEFAULT (datetime('now'))
    )";
    $conn->exec($userTableSQL);

    // Create products table if it doesn't exist
    $productsTableSQL = "CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        price INTEGER NOT NULL,
        category TEXT,
        description TEXT,
        image_url TEXT,
        seller_name TEXT,
        created_at TEXT DEFAULT (datetime('now')),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
    $conn->exec($productsTableSQL);

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
