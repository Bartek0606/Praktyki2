<?php 
function getSortOrder($conn, $sort) {
    switch ($sort) {
        case 'oldest':
            return 'created_at ASC';
        case 'price':
            return 'price ASC';
        case 'price_desc':
            return 'price DESC';
        default:
            return 'created_at DESC';
    }
}

function getCategoryCondition($selected_categories) {
    if (!empty($selected_categories)) {
        return "i.category_id IN (" . implode(',', $selected_categories) . ")";
    } else {
        return "1=1"; // Domyślnie brak filtra kategorii
    }
}

function getItems($conn, $category_condition, $order_by) {
    $sql_items = "SELECT i.item_id, i.name, i.image, i.description, i.price, i.created_at, c.name AS category_name, u.user_id, u.username 
                  FROM items i
                  LEFT JOIN categories c ON i.category_id = c.category_id
                  LEFT JOIN users u ON i.user_id = u.user_id
                  WHERE $category_condition
                  ORDER BY $order_by";
    return $conn->query($sql_items);
}

function getCategories($conn) {
    $sql_categories = "SELECT category_id, name FROM categories";
    return $conn->query($sql_categories);
}
?>