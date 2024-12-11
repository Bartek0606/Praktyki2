<?php
session_start();
include '../../../db_connection.php'; // Adjust this path as needed
include '../../Component/navbar.php'; // Adjust this path as needed

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
        $image = $conn->real_escape_string($image);

        $sql_add_item = "
            INSERT INTO items (name, description, price, category_id, image, purchased, created_at, user_id)
            VALUES ('$item_name', '$description', $price, $category_id, '$image', '0', NOW(), " . $_SESSION['user_id'] . ")
        ";

        if ($conn->query($sql_add_item) === TRUE) {
            $new_item_id = $conn->insert_id;
            $_SESSION['add_item_success'] = true;
            header("Location: item_details.php?item_id=$new_item_id");
            exit;
        } else {
            $error = "Error adding item: " . $conn->error;
        }
    } else {
        $error = "Error uploading image. Please try again.";
    }
}

$conn->close();
?>
