<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/edit_item_function.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$itemId = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;
$userId = $_SESSION['user_id'];

$itemResult = getItemDetails($conn, $itemId, $userId);

if ($itemResult->num_rows === 0) {
    die("Item not found or you do not have permission to edit this item.");
}

$item = $itemResult->fetch_assoc();
$categoriesResult = getCategories($conn);

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['username'] : null;
$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];

    if (updateItem($conn, $itemId, $name, $description, $category_id, $price)) {
        $_SESSION['update_success'] = true;
        header("Location: item_details.php?item_id=$itemId");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}

include '../../Component/view/edit_item_view.php';
ob_end_flush();
?>
