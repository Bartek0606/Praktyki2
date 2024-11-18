<?php
$servername = "mysql";          // Nazwa usługi MySQL w docker-compose.yml
$username = "user";             // Zaktualizowany użytkownik: "user"
$password = "user123";          // Zaktualizowane hasło: "user123"
$dbname = "hobbyhub";           // Zaktualizowana nazwa bazy danych: "hobbyhub"

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>
