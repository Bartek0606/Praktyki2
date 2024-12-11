<?php
function getPostDetails($conn, $postId) {
    $sqlPostDetails = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = $conn->prepare($sqlPostDetails);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    return $stmt->get_result();
}

function getCategories($conn) {
    $sqlCategories = "SELECT category_id, name FROM categories";
    return $conn->query($sqlCategories);
}

function updatePost($conn, $postId, $newTitle, $newContent, $newCategory, $newImage) {
    $sqlUpdatePost = $newImage 
        ? "UPDATE posts SET title = ?, content = ?, category_id = ?, image = ? WHERE post_id = ?"
        : "UPDATE posts SET title = ?, content = ?, category_id = ? WHERE post_id = ?";
    
    $stmt = $conn->prepare($sqlUpdatePost);
    if ($newImage) {
        $stmt->bind_param("ssibi", $newTitle, $newContent, $newCategory, $newImage, $postId);
    } else {
        $stmt->bind_param("ssii", $newTitle, $newContent, $newCategory, $postId);
    }

    return $stmt->execute();
}
?>
