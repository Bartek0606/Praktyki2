<?php
// event.php
include 'db_connection.php';

// Check if event id is provided
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch event details from the database
    $sql_event_details = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql_event_details);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        // Display event details (You can add more details like description, image, etc.)
        echo "<h1>" . htmlspecialchars($event['event_name']) . "</h1>";
        echo "<p>" . htmlspecialchars($event['event_description']) . "</p>";
        echo "<p><strong>Date:</strong> " . date("F j, Y, g:i a", strtotime($event['event_date'])) . "</p>";
        echo "<p><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>";
    } else {
        echo "<p>Event not found.</p>";
    }
} else {
    echo "<p>No event specified.</p>";
}

$conn->close();
?>
