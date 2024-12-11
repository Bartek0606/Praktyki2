<?php
function getCategories($conn) {
    $sql = "SELECT * FROM categories";
    return $conn->query($sql);
}

function insertPost($conn, $user_id, $title, $content, $category_id, $is_question, $image) {
    $sql = "INSERT INTO posts (user_id, title, content, category_id, is_question, image, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssis', $user_id, $title, $content, $category_id, $is_question, $image);
    return $stmt->execute();
}
?>
