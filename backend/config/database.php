<?php

require_once __DIR__ . '/database_helper.php';

$host = 'yamanote.proxy.rlwy.net';     // Update these credentials
$dbname = 'MedFlex';    // as needed
$username = 'root';
$password = 'tAWjVHKfEHTnQbpNfEYAJDNJKIYPDZoO';
$port = 45058;

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");