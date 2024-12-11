<?php
include  '../../../db_connection.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../../public/templates/login.php");
    exit;
}

// Funkcja do pobierania eventów
function getEvents($conn) {
    $query = "SELECT * FROM events ORDER BY event_date DESC";
    return $conn->query($query);
}

// Funkcja do usuwania eventu
function deleteEvent($conn, $eventId) {
    $query = "DELETE FROM events WHERE event_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $eventId);
        return $stmt->execute();
    }
    return false;
}

// Funkcja do dodawania eventu
function addEvent($conn, $data) {
    $query = "INSERT INTO events (event_name, event_description, event_date, location) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssss", $data['event_name'], $data['event_description'], $data['event_date'], $data['event_location']);
        return $stmt->execute();
    }
    return false;
}
?>
