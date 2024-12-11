<?php
ob_start();
session_start();
 
include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/function.php';
include '../../function/user_function.php';
 
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}
 
$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
    handleLikeUnlike($conn, $userId, $_POST['post_id']);
}

$profileUserId = intval($_GET['id']);
$user = getUserData($conn, $profileUserId);

if (!$user) {
    echo "User not found.";
    exit();
}

$posts = getUserPosts($conn, $profileUserId);
$events = getUserEvents($conn, $profileUserId);
$items = getUserItems($conn, $profileUserId);
$isFollowing = false;

if ($isLoggedIn) {
    $isFollowing = getFollowStatus($conn, $userId, $profileUserId);
    if (isset($_POST['follow'])) {
        handleFollowUnfollow($conn, $userId, $profileUserId, $isFollowing);
    }
}

$followers_count = getFollowersCount($conn, $profileUserId);
$following_count = getFollowingCount($conn, $profileUserId);
$posts_count = getPostsCount($conn, $profileUserId);
$liked_posts = getLikedPosts($conn, $userId);

include '../../Component/view/user_view.php';
ob_end_flush();
?>
 