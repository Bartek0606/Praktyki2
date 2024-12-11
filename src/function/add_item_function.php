<?php
session_start();
include '../../db_connection.php'; // Adjust this path as needed

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Function to handle item addition
function addItem($conn, $data, $image, $userId) {
    $item_name = $conn->real_escape_string($data['item_name']);
    $description = $conn->real_escape_string($data['description']);
    $price = (float)$data['price'];
    $category_id = (int)$data['category'];

    $imageContent = file_get_contents($image['tmp_name']);
    $imageContent = $conn->real_escape_string($imageContent);

    $sql_add_item = "
        INSERT INTO items (name, description, price, category_id, image, purchased, created_at, user_id)
        VALUES ('$item_name', '$description', $price, $category_id, '$imageContent', '0', NOW(), $userId)
    ";

    if ($conn->query($sql_add_item) === TRUE) {
        return $conn->insert_id;
    } else {
        return "Error adding item: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $new_item_id = addItem($conn, $_POST, $_FILES['image'], $_SESSION['user_id']);
        if (is_numeric($new_item_id)) {
            $_SESSION['add_item_success'] = true;
            header("Location: ../public/templates/item_details.php?item_id=$new_item_id");
            exit;
        } else {
            $error = $new_item_id;
        }
    } else {
        $error = "Error uploading image. Please try again.";
    }
}

$conn->close();
?>
