<?php
ob_start();
session_start(); 

include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/event_function.php'; // Dołączamy plik z funkcjami

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$message = "";

// Obsługa wylogowania
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy(); 
  header("Location: index.php"); 
  exit;
}

// Obsługa rejestracji na wydarzenie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if ($isLoggedIn) {
        $event_id = $_POST['event_id']; 
        $user_id = $_SESSION['user_id']; 
        
        $sql_check = "SELECT * FROM event_registrations WHERE user_id = ? AND event_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $user_id, $event_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $isRegistered = true;
        } else {
            $sql_register = "INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)";
            $stmt_register = $conn->prepare($sql_register);
            $stmt_register->bind_param("ii", $user_id, $event_id);
            $stmt_register->execute();

            if ($stmt_register->affected_rows > 0) {
                $isRegistered = true;
            }
        }
    }
}

// Obsługa wyrejestrowania się z wydarzenia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unregister'])) {
    if ($isLoggedIn) {
        $event_id = $_POST['event_id']; 
        $user_id = $_SESSION['user_id']; 

        $sql_unregister = "DELETE FROM event_registrations WHERE user_id = ? AND event_id = ?";
        $stmt_unregister = $conn->prepare($sql_unregister);
        $stmt_unregister->bind_param("ii", $user_id, $event_id);
        $stmt_unregister->execute();

        if ($stmt_unregister->affected_rows > 0) {
            $isRegistered = false;
        }
    }
}

// Pobranie szczegółów wydarzenia
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $sql_event_details = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql_event_details);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo "<p>Event not found.</p>";
        exit;
    }

    $isRegistered = false;
    if ($isLoggedIn) {
        $sql_check_registration = "SELECT * FROM event_registrations WHERE user_id = ? AND event_id = ?";
        $stmt_check_registration = $conn->prepare($sql_check_registration);
        $stmt_check_registration->bind_param("ii", $userId, $event_id);
        $stmt_check_registration->execute();
        $result_registration = $stmt_check_registration->get_result();
        $isRegistered = $result_registration->num_rows > 0;
    }

    // Liczba zarejestrowanych użytkowników
    $sql_registration_count = "SELECT COUNT(*) as total_registrations FROM event_registrations WHERE event_id = ?";
    $stmt_count = $conn->prepare($sql_registration_count);
    $stmt_count->bind_param("i", $event_id);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    $registration_count = $result_count->fetch_assoc()['total_registrations'];
} else {
    echo "<p>No event specified.</p>";
    exit;
}

include '../../Component/view/event_view.php'; // Dołączamy plik widoku

$conn->close();
ob_end_flush();
?>
