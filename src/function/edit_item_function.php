<?php
function getItemDetails($conn, $itemId, $userId) {
    $sql = "SELECT * FROM items WHERE item_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $itemId, $userId);
    $stmt->execute();
    return $stmt->get_result();
}

function getCategories($conn) {
    $sql = "SELECT category_id, name FROM categories";
    return $conn->query($sql);
}

function updateItem($conn, $itemId, $name, $description, $category_id, $price) {
    $sql = "UPDATE items SET name = ?, description = ?, category_id = ?, price = ? WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $name, $description, $category_id, $price, $itemId);
    return $stmt->execute();
}
?>
