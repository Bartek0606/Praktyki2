<?php
function getItemDetails($conn, $itemId) {
    $sql = "SELECT i.item_id, i.name, i.image, i.description, i.price, i.created_at, i.purchased, 
                   c.name AS category_name, u.user_id, u.username
            FROM items i
            LEFT JOIN categories c ON i.category_id = c.category_id
            LEFT JOIN users u ON i.user_id = u.user_id
            WHERE i.item_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error (prepare failed): " . $conn->error);
    }
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Item not found.");
    }

    return $result->fetch_assoc();
}

function getRelatedItems($conn, $categoryId, $itemId) {
    $sql = "SELECT item_id, name, image, price FROM items 
            WHERE category_id = ? AND item_id != ? LIMIT 4";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $categoryId, $itemId);
    $stmt->execute();
    return $stmt->get_result();
}

function getUserItems($conn, $userId, $itemId) {
    $sql = "SELECT item_id, name, image, price FROM items 
            WHERE user_id = ? AND item_id != ? LIMIT 4";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $itemId);
    $stmt->execute();
    return $stmt->get_result();
}
?>
