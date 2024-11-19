<?php
// Database connection parameters
$host = 'mysql'; // MySQL service name from docker-compose.yml
$user = 'user'; // MySQL user defined in docker-compose.yml
$password = 'user123'; // MySQL password defined in docker-compose.yml
$database = 'hobbyhub'; // Database name defined in docker-compose.yml

// Create a new mysqli connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
