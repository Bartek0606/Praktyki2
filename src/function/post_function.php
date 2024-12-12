<?php
function isOwner($userId, $ownerId) {
    return (int)$userId === (int)$ownerId;
}

function getPostDetails($conn, $postId) {
    $sql = "SELECT p.post_id, p.title, p.created_at, p.content, p.image, u.user_id, u.username, c.category_id, c.name AS category_name, p.is_question 
            FROM posts p
            LEFT JOIN users u ON p.user_id = u.user_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    return $stmt->get_result();
}

function getComments($conn, $postId) {
    $sql = "SELECT c.comment_id, c.content, c.created_at, u.user_id, u.username, c.parent_comment_id 
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.user_id
            WHERE c.post_id = ? 
            AND c.parent_comment_id IS NULL
            ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    return $stmt->get_result();
}

function getReplies($conn, $postId) {
    $sql = "SELECT c.comment_id, c.content, c.created_at, u.user_id, u.username 
FROM comments c
LEFT JOIN users u ON c.user_id = u.user_id
WHERE c.post_id = ? AND c.parent_comment_id IS NOT NULL
ORDER BY c.created_at ASC
";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    return $stmt->get_result();
}

function insertComment($conn, $postId, $userId, $commentContent, $parentCommentId) {
    $sql = "INSERT INTO comments (post_id, user_id, content, parent_comment_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $postId, $userId, $commentContent, $parentCommentId);
    return $stmt->execute();
}

function getUserImage($userId, $conn) {
    $image_src = '../image/default.png';
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['profile_picture']) && $row['profile_picture'] != 'default.png') {
            $image_src = 'data:image/png;base64,' . base64_encode($row['profile_picture']);
        }
    }
    $stmt->close();
    return htmlspecialchars($image_src, ENT_QUOTES, 'UTF-8');
}

function updatePost($conn, $postId, $newContent) {
    $sql = "UPDATE posts SET content = ? WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newContent, $postId);
    return $stmt->execute();
}

function updateComment($conn, $commentId, $newContent) {
    $sql = "UPDATE comments SET content = ? WHERE comment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newContent, $commentId);
    return $stmt->execute();
}

function updateReply($conn, $commentId, $newContent) {
    $sql = "UPDATE comments SET content = ? WHERE comment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newContent, $commentId);
    return $stmt->execute();
}

function checkCommentOwner($conn, $commentId) {
    $sql = "SELECT user_id FROM comments WHERE comment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    return $stmt->get_result();
}
function handleLikeAction($conn, $userId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
        $post_id = intval($_POST['post_id']); // Ensure $post_id is an integer
        
        // Check if the user already liked the post
        $sql_check = "SELECT * FROM `user_likes` WHERE id_user = ? AND id_post = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $userId, $post_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // If liked, remove the like
            $sql_delete = "DELETE FROM `user_likes` WHERE id_user = ? AND id_post = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("ii", $userId, $post_id);
            $stmt_delete->execute();
        } else {
            // If not liked, add the like
            $sql_register = "INSERT INTO `user_likes`(`id_user`, `id_post`) VALUES (?, ?)";
            $stmt_register = $conn->prepare($sql_register);
            $stmt_register->bind_param("ii", $userId, $post_id);
            $stmt_register->execute();
        }
    }
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
function hasUserLikedPost($conn, $userId, $postId) {
    // Default to false
    $isLiked = false;

    if ($userId) {
        // Check if the user has liked the post
        $sql_check_like = "SELECT * FROM `user_likes` WHERE id_user = ? AND id_post = ?";
        $stmt_check_like = $conn->prepare($sql_check_like);
        $stmt_check_like->bind_param("ii", $userId, $postId);
        $stmt_check_like->execute();
        $result_check_like = $stmt_check_like->get_result();

        // If the user has liked the post, return true
        if ($result_check_like->num_rows > 0) {
            $isLiked = true;
        }
    }

    return $isLiked;
}
function getLikeCountForPost($conn, $postId) {
    $sql = "
        SELECT COUNT(user_likes.id_post) AS like_count
        FROM posts
        LEFT JOIN user_likes ON posts.post_id = user_likes.id_post
        WHERE posts.post_id = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['like_count'];
}
?>
