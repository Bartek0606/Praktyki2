<?php
ob_start();
session_start();
include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/function.php';
include '../../function/subpage_function.php';

$isLoggedIn = isset($_SESSION['user_id']);

$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = intval($_GET['id']); 
} else {
    die('Nieprawidłowe ID kategorii');
}

$result_category = getCategory($conn, $category_id); 

if ($result_category->num_rows > 0) { 
    $category = $result_category->fetch_assoc(); 
    $category_name = htmlspecialchars($category['name']); 
} else { 
    die('Kategoria o podanym ID nie istnieje.'); 
} 

$result_posts = getPosts($conn, $category_id); 
$result_blog_info = getCategoryBlogInfo($conn, $category_id); 

include '../../Component/view/subpage_view.php';
ob_end_flush();
?>