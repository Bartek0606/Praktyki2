<?php
ob_start();
session_start();
include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/edit_post_function.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
if ($postId <= 0) {
    die("<p>Invalid post ID.</p>");
}

$result = getPostDetails($conn, $postId);
if ($result->num_rows === 0) {
    die("<p>Post not found.</p>");
}

$post = $result->fetch_assoc();
if (!$isLoggedIn || (int)$userId !== (int)$post['user_id']) {
    die("<p>You are not authorized to edit this post.</p>");
}

$categoriesResult = getCategories($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_post'])) {
    $newTitle = $conn->real_escape_string($_POST['title']);
    $newContent = $conn->real_escape_string($_POST['content']);
    $newCategory = (int)$_POST['category'];

    $newImage = !empty($_FILES['image']['tmp_name']) 
                ? addslashes(file_get_contents($_FILES['image']['tmp_name'])) 
                : null;

    if (updatePost($conn, $postId, $newTitle, $newContent, $newCategory, $newImage)) {
        $_SESSION['edit_success'] = "Post updated successfully!";
        header("Location: post.php?id=$postId");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}

include '../../Component/view/edit_post_view.php';
ob_end_flush();
?>
