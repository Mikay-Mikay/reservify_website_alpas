<?php
// database.php
$host = 'localhost'; // Hostname
$db = 'test_site'; // Corrected database name
$user = 'root'; // Database username
$pass = ''; // Database password (default is empty for XAMPP)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
