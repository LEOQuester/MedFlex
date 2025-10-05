<?php

require_once __DIR__ . '/database_helper.php';

$host = 'localhost';     // Update these credentials
$dbname = 'medflex';    // as needed
$username = 'root';
$password = '';
$port = 3306;

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");