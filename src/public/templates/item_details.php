<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Check if item_id is provided in the URL
if (!isset($_GET['item_id']) || !is_numeric($_GET['item_id'])) {
    echo "Invalid item ID.";
    exit;
}

$itemId = $_GET['item_id'];

// Fetch the item details, including owner information and purchase status
$sql_item = "SELECT i.item_id, i.name, i.image, i.description, i.price, i.created_at, i.purchased, c.name AS `category_name`, u.user_id, u.username
FROM items i
LEFT JOIN categories c ON i.category_id = c.category_id
LEFT JOIN users u ON i.user_id = u.user_id
WHERE i.item_id = ?;";
$stmt = $conn->prepare($sql_item);
$stmt->bind_param("i", $itemId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Item not found.";
    exit;
}

$item = $result->fetch_assoc();
$formatted_date = date("F j, Y, g:i a", strtotime($item['created_at']));
$image_data = base64_encode($item['image']);
$image_html = $item['image'] ? "<img src='data:image/jpeg;base64,$image_data' alt='" . htmlspecialchars($item['name']) . "' class='item-image' />" : '';

// Fetch related items
$sql_related_items = "SELECT i.item_id, i.name, i.image, i.price 
                      FROM items i
                      WHERE i.category_id = ? AND i.item_id != ? LIMIT 4";
$stmt_related = $conn->prepare($sql_related_items);
$stmt_related->bind_param("ii", $item['category_id'], $itemId);
$stmt_related->execute();
$related_items_result = $stmt_related->get_result();

// Fetch other items from the same user
$sql_user_items = "SELECT i.item_id, i.name, i.image, i.price 
                   FROM items i
                   WHERE i.user_id = ? AND i.item_id != ? LIMIT 4";
$stmt_user_items = $conn->prepare($sql_user_items);
$stmt_user_items->bind_param("ii", $item['user_id'], $itemId);
$stmt_user_items->execute();
$user_items_result = $stmt_user_items->get_result();

include '../../Component/view/item_details_view.php';

$stmt->close();
$stmt_related->close();
$stmt_user_items->close();
$conn->close();
ob_end_flush();
?>
