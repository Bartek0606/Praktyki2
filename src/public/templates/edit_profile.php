<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/edit_profile_function.php'; // Dodajemy plik z funkcjami

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Obsługa wylogowania
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Usunięcie sesji i przekierowanie na stronę logowania
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Pobranie danych użytkownika
$user = getUserData($conn, $userId);

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sprawdzamy, czy przycisk do resetowania zdjęcia został kliknięty
    if (isset($_POST['reset_picture'])) {
        resetProfilePicture($conn, $userId); // Wywołanie funkcji resetującej zdjęcie
    } else {
        // Obsługa formularza edycji
        handleProfileUpdate($conn, $userId, $_POST, $_FILES);
    }
}

include '../../Component/view/edit_profile_view.php'; // Dołączenie widoku z HTML
include '../../Component/view/footer.php';
$conn->close();
ob_end_flush();
?>
