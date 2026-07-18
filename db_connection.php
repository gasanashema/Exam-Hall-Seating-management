<?php
// Database credentials
$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "phpmyadmin";
$password = getenv('DB_PASSWORD') ?: "disaster";
$dbname = getenv('DB_NAME') ?: "examhall";
$port = getenv('DB_PORT') ?: "3306";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
