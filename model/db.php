<?php
$host = "localhost";         // Replace with your database host
$usernameDb = "root"; // Replace with your database username
$passwordDb = ""; // Replace with your database password
$database = "loaning_db";    // Name of the database

$conn = new mysqli($host, $usernameDb, $passwordDb, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
