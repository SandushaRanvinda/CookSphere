<?php
// includes/db.php - Database connection file
$host = 'localhost';
$dbname = 'cooksphere';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Auto migration to add image column if it doesn't exist
    try {
        $pdo->exec("ALTER TABLE recipes ADD COLUMN image VARCHAR(255) DEFAULT 'images/default-recipe.png'");
    } catch (PDOException $e) {
        // Table doesn't exist yet or column already exists, safely ignore
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
