<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/function.php';
include '../../function/post_function.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}

// Retrieve post ID from the URL
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($postId > 0) {
    $resultPostDetails = getPostDetails($conn, $postId);

    if ($resultPostDetails->num_rows > 0) {
        $post = $resultPostDetails->fetch_assoc();
    } else {
        echo "<p>Post not found.</p>";
        exit;
    }

    $resultComments = getComments($conn, $postId); 
    $resultReplies = getReplies($conn, $postId);
    
    $replies = [];
    while ($reply = $resultReplies->fetch_assoc()) {
        $replies[$reply['parent_comment_id']][] = $reply;
    }
} else {
    echo "<p>Invalid post ID.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_comment']) && $isLoggedIn) {
        $commentContent = $conn->real_escape_string($_POST['comment_content']);
        $parentCommentId = isset($_POST['parent_comment_id']) ? (int)$_POST['parent_comment_id'] : NULL;

        if (insertComment($conn, $postId, $userId, $commentContent, $parentCommentId)) {
            $_SESSION['comment_success'] = 'Your comment has been posted successfully!';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }

    if (isset($_POST['edit_post']) && $isLoggedIn && isOwner($userId, $post['user_id'])) {
        $newContent = $conn->real_escape_string($_POST['new_content']);
        if (updatePost($conn, $postId, $newContent)) {
            $_SESSION['edit_success'] = 'Post updated successfully!';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }

    if (isset($_POST['edit_comment']) && $isLoggedIn) {
        $commentId = (int)$_POST['comment_id'];
        $newContent = $conn->real_escape_string($_POST['new_content']);

        $resultCheckOwner = checkCommentOwner($conn, $commentId);
        if ($resultCheckOwner->num_rows > 0) {
            $commentOwner = $resultCheckOwner->fetch_assoc();
            if (isOwner($userId, $commentOwner['user_id'])) {
                if (updateComment($conn, $commentId, $newContent)) {
                    $_SESSION['edit_success'] = 'Comment updated successfully!';
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit();
                } else {
                    echo "<p>Error: " . $conn->error . "</p>";
                }
            }
        }
    }
}
include '../../Component/view/post_view.php';
ob_end_flush();
?>
