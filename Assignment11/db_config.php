<?php
// Database connection configuration
$dbHost = 'elvis.rowan.edu';
$dbUsername = 'tumusa92';
$dbPassword = '1MageNTA7kItten!'
$dbName = 'tumusa92';

// Create a new MySQLi connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
