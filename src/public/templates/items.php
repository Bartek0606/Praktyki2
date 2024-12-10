<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function.php';

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Obsługa sortowania 
$sort = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : 'newest'; 
$order_by = getSortOrder($conn, $sort); 
// Obsługa filtrowania kategorii 
$selected_categories = isset($_GET['categories']) ? array_map('intval', $_GET['categories']) : []; 
$category_condition = getCategoryCondition($selected_categories); 
// Pobieranie przedmiotów 
$items_result = getItems($conn, $category_condition, $order_by); 
// Pobieranie kategorii 
$categories_result = getCategories($conn);

include '../../Component/view/items_view.php'; 
ob_end_flush();
?>
