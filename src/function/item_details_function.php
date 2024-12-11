<?php
function getItemDetails($conn, $itemId) {
    $sql_item = "SELECT i.item_id, i.name, i.image, i.description, i.price, i.created_at, i.purchased, c.name AS `category_name`, u.user_id, u.username
    FROM items i
    LEFT JOIN categories c ON i.category_id = c.category_id
    LEFT JOIN users u ON i.user_id = u.user_id
    WHERE i.item_id = ?;";
    $stmt = $conn->prepare($sql_item);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    return $stmt->get_result();
}

function getRelatedItems($conn, $categoryId, $itemId) {
    $sql_related_items = "SELECT i.item_id, i.name, i.image, i.price 
                          FROM items i
                          WHERE i.category_id = ? AND i.item_id != ? LIMIT 4";
    $stmt = $conn->prepare($sql_related_items);
    $stmt->bind_param("ii", $categoryId, $itemId);
    $stmt->execute();
    return $stmt->get_result();
}

function getUserItems($conn, $userId, $itemId) {
    $sql_user_items = "SELECT i.item_id, i.name, i.image, i.price 
                       FROM items i
                       WHERE i.user_id = ? AND i.item_id != ? LIMIT 4";
    $stmt = $conn->prepare($sql_user_items);
    $stmt->bind_param("ii", $userId, $itemId);
    $stmt->execute();
    return $stmt->get_result();
}
?>
