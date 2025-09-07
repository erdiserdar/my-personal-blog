<?php
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "blog_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the database
$conn->select_db($dbname);

// --- TABLE AND COLUMN VERIFICATION ---
// This function checks if a column exists in a table.
function columnExists($conn, $tableName, $columnName) {
    $result = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");
    return $result->num_rows > 0;
}

// Function to add a column if it doesn't exist.
function addColumnIfNeeded($conn, $tableName, $columnName, $columnDef) {
    if (!columnExists($conn, $tableName, $columnName)) {
        $conn->query("ALTER TABLE `$tableName` ADD COLUMN `$columnName` $columnDef");
    }
}

// Ensure the 'posts' table exists.
$conn->query("
    CREATE TABLE IF NOT EXISTS posts (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        author VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// Add new columns for our feature update if they don't already exist.
addColumnIfNeeded($conn, 'posts', 'category', 'VARCHAR(50) NOT NULL AFTER `author`');
addColumnIfNeeded($conn, 'posts', 'excerpt', 'TEXT AFTER `category`');
addColumnIfNeeded($conn, 'posts', 'image_url', 'VARCHAR(255) AFTER `excerpt`');

?>

