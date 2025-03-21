<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','postgres'); // Update with your PostgreSQL username
define('DB_PASS','89999');         // Update with your PostgreSQL password
define('DB_NAME','library');

// Establish database connection.
try {
    $dbh = new PDO("pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally, you can set additional attributes here.
}
catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>
