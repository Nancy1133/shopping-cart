<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "shopping_car";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_errno) {
  die("MySQL connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

?>