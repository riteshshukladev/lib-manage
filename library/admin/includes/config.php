<?php
// PostgreSQL DB credentials for both Admin and User login
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');  // Default PostgreSQL port
define('DB_USER', 'postgres'); // Change this to your PostgreSQL username
define('DB_PASS', '89999');    // Change this to your PostgreSQL password
define('DB_NAME', 'library');  // Your PostgreSQL database name

try {
    // Create a new PDO instance using PostgreSQL DSN
    $dbh = new PDO("pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set error mode to Exception for easier debugging
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
