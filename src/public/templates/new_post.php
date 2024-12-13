<?php
ob_start();
session_start();
include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/function.php';
include '../../function/new_post_function.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category_id = (int) $_POST['category'];
    $is_question = isset($_POST['is_question']) ? 1 : 0;
    $image = null;

    // Validate and process the uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $error = "Error uploading image.";
    }

    // Insert the post into the database
    if (insertPost($conn, $userId, $title, $content, $category_id, $is_question, $image)) {
        $_SESSION['post_success'] = "Your post has been created successfully!";
        header("Location: index.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}

$categories_result = getCategories($conn);
include '../../Component/view/new_post_view.php';
include '../../Component/view/footer.php';
ob_end_flush();
?>
