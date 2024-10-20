<?php
$servername = "db";
$username = "webuser";
$password = "webpassword";
$database = "webshop";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
