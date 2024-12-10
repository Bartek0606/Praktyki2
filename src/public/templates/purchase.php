<?php
ob_start();
session_start();

include '../../../db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Handle purchase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase'])) {
    $itemId = $_POST['item_id'];

    // Validate item ID
    if (!is_numeric($itemId)) {
        echo "Invalid item ID.";
        exit;
    }

    // Prepare the order data
    $orderDate = date('Y-m-d H:i:s'); // Current date and time
    $status = 'pending'; // Initial order status

    // Use prepared statement to insert order into the database
    $sql_purchase = "INSERT INTO orders (item_id, user_id, order_date, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_purchase);

    // Bind parameters to the prepared statement
    $stmt->bind_param("iiss", $itemId, $userId, $orderDate, $status);

    // Execute the statement
    if ($stmt->execute()) {
        // Update the purchased status for the item
        $sql_update_item = "UPDATE items SET purchased = 1 WHERE item_id = ?";
        $stmt_update = $conn->prepare($sql_update_item);
        $stmt_update->bind_param("i", $itemId);

        if ($stmt_update->execute()) {
            $_SESSION['purchase_success'] = true;
            header("Location: item_details.php?item_id=$itemId&success=true");
            exit;
        } else {
            echo "Error updating item purchased status.";
        }

        $stmt_update->close();
    } else {
        echo "Error processing purchase.";
    }

    $stmt->close();
}

$conn->close();
ob_end_flush();
?>
