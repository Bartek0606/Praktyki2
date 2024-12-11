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
?>
