<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main class="dashboard bg-gray-50 ml-64 mt-24 p-8 min-h-screen w-full">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Upcoming Events</h2>
        <div class="flex justify-center mb-6">
            <button class="add-event-btn bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200" onclick="openAddEventPopup()">Add Event</button>
        </div>

        <div class="events-list mt-6 mx-auto w-4/6 space-y-6">
    <?php
    if ($result->num_rows > 0) {
        while ($event = $result->fetch_assoc()) {
            echo "<div class='event bg-white shadow-lg rounded-lg p-6 relative'>";
            echo "<h3 class='text-xl font-semibold text-gray-700'>" . htmlspecialchars($event['event_name']) . "</h3>";
            echo "<p class='text-gray-600 mt-2'><strong>Date:</strong> " . htmlspecialchars($event['event_date']) . "</p>";
            echo "<p class='text-gray-600'><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>";
            echo "<p class='text-gray-600 mt-2'><strong>Description:</strong> " . nl2br(htmlspecialchars($event['event_description'])) . "</p>";
            echo "<div class='actions absolute bottom-4 right-4 flex space-x-2'>";
            echo "<button class='edit-btn bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-200' 
                    onclick='openEditPopup(" . $event['event_id'] . ", \"" . addslashes($event['event_name']) . "\", \"" . addslashes($event['event_date']) . "\", \"" . addslashes($event['location']) . "\", \"" . addslashes($event['event_description']) . "\")'>Edit</button>";

            echo "<form method='POST' action='events.php' style='display:inline;'>
                    <input type='hidden' name='delete_event_id' value='" . $event['event_id'] . "'>
                    <button type='submit' class='delete-btn bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-200'>Delete</button>
                </form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p class='text-gray-600 text-center'>No events found.</p>";
    }
    ?>
</div>

    </main>
</body>
</html>