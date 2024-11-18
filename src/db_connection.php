<?php
$servername = "mysql";              // Musi być ustawione na nazwę serwisu z docker-compose.yml
$username = "user";        // Upewnij się, że to jest zgodne z MYSQL_USER
$password = "user123";        // Upewnij się, że to jest zgodne z MYSQL_PASSWORD
$dbname = "hobbyhub";     // Upewnij się, że to jest zgodne z MYSQL_DATABASE

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>
