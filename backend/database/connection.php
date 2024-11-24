<?php

$servername = "localhost";
$username = "root"; // Default username for XAMPP MySQL
$password = "";     // Default password for XAMPP MySQL (empty)
$dbname = "health_system"; // Database name for the health system

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
