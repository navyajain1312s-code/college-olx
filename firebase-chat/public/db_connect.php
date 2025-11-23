<?php
// Database connection - works with both SQLite (local) and PostgreSQL (production)

// Check if we're on Railway (production) or local
$isProduction = isset($_ENV['DATABASE_URL']) || isset($_SERVER['DATABASE_URL']);

try {
    if ($isProduction) {
        // Production: Use PostgreSQL from Railway
        $databaseUrl = $_ENV['DATABASE_URL'] ?? $_SERVER['DATABASE_URL'];
        
        // Parse the DATABASE_URL
        $dbParts = parse_url($databaseUrl);
        
        $host = $dbParts['host'];
        $port = $dbParts['port'] ?? 5432;
        $dbname = ltrim($dbParts['path'], '/');
        $user = $dbParts['user'];
        $password = $dbParts['pass'];
        
        // Connect to PostgreSQL
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
        $conn = new PDO($dsn, $user, $password);
        
        // Create tables for PostgreSQL
        $userTableSQL = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($userTableSQL);
        
        $productsTableSQL = "CREATE TABLE IF NOT EXISTS products (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            title VARCHAR(255) NOT NULL,
            price INTEGER NOT NULL,
            category VARCHAR(100),
            description TEXT,
            image_url TEXT,
            seller_name VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        $conn->exec($productsTableSQL);
        
    } else {
        // Local: Use SQLite
        $db_file = __DIR__ . '/database.sqlite';
        $conn = new PDO("sqlite:" . $db_file);
        
        // Create tables for SQLite
        $userTableSQL = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at TEXT DEFAULT (datetime('now'))
        )";
        $conn->exec($userTableSQL);
        
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
    }
    
    // Set errormode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
