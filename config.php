<?php
// includes/config.php
// Edit credentials if you use a DB password or different user.
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "college_bus";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($conn->connect_error) {
    die("MySQL connection failed: " . $conn->connect_error);
}

// Create DB if not exists (helpful on first run)
if (!$conn->select_db($DB_NAME)) {
    $conn->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $conn->select_db($DB_NAME);
}
$conn->set_charset("utf8mb4");
?>
