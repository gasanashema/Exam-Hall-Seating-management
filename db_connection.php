<?php
// Database credentials
$servername = "localhost";
$username = "phpmyadmin";
$password = "disaster";
$dbname = "examhall";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection success: " . $conn->connect_error);
}
?>
