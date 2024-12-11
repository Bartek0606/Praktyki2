<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/item_details_function.php'; // Dołączenie funkcji

// Sprawdzenie, czy użytkownik jest zalogowany
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Obsługa wylogowania
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Pobranie szczegółów przedmiotu
$itemId = $_GET['item_id'] ?? null;
if (!$itemId || !is_numeric($itemId)) {
    echo "Invalid item ID.";
    exit;
}

$item = getItemDetails($conn, $itemId);
$relatedItems = getRelatedItems($conn, $item['category_id'], $itemId);
$userItems = getUserItems($conn, $item['user_id'], $itemId);

include '../../Component/view/item_details_view.php'; // Ładowanie widoku
$conn->close();
ob_end_flush();
?>
