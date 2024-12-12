<?php
ob_start();
session_start();
include '../../../db_connection.php'; // Adjust this path as needed
include '../../Component/navbar.php'; // Adjust this path as needed

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

if (!$isLoggedIn) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Include the add item form view
include '../../Component/view/add_item_view.php';
include '../../Component/view/footer.php';
?>
