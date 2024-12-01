<?php
include __DIR__ . '/../db_connection.php';

include_once 'sidebar_admin.php';
// Upewnij się, że sesja jest uruchomiona
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../login.php");
    exit;
}

// Utworzenie instancji sidebaru
$sidebar = new Sidebar($conn, $userId);


$query = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event_id'])) {
    $event_id_to_delete = $_POST['delete_event_id'];
    $delete_query = "DELETE FROM events WHERE event_id = ?";
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param("i", $event_id_to_delete);
        if ($stmt->execute()) {
            header("Location: events.php");
            exit();
        } else {
            echo "Błąd: Nie udało się usunąć wydarzenia.";
        }
        $stmt->close();
    } else {
        echo "Błąd: Nie udało się przygotować zapytania.";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    $result = $conn->query("SELECT MAX(event_id) AS max_id FROM events");
    $row = $result->fetch_assoc();
    $new_event_id = $row['max_id'] + 1; 

    $insert_query = "INSERT INTO events (event_id, event_name, event_description, event_date, location) 
                     VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insert_query)) {
        $stmt->bind_param("issss", $new_event_id, $event_name, $event_description, $event_date, $event_location);
        if ($stmt->execute()) {

            header("Location: events.php");
            exit();
        } else {
            echo "Błąd: Nie udało się dodać wydarzenia. " . $stmt->error; 
        }
        $stmt->close();
    } else {
        echo "Błąd: Nie udało się przygotować zapytania.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="admin.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
    @tailwind base;
@tailwind components;
@tailwind utilities;

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-in-out;
}

</style>
<body>
<div class="admin-panel flex">
      <!-- Renderowanie sidebaru -->
      <?php echo $sidebar->getSidebarHtml(); ?>

    <main class="dashboard bg-gray-50 ml-64 mt-24 p-8 min-h-screen w-full">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Upcoming Events</h2>

    <!-- Kontener przycisku, który będzie wyśrodkowany -->
    <div class="flex justify-center mb-6">
        <button class="add-event-btn bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200" onclick="openAddEventPopup()">Add Event</button>
    </div>
    
    <!-- Lista wydarzeń -->
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


</div>

<!-- Overlay -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

<!-- Add Event Popup -->
<div id="add-event-popup" class="popup fixed inset-0 flex justify-center items-center hidden z-50">
    <div class="popup-content bg-white shadow-lg rounded-lg p-8 w-[40rem]">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Add Event</h3>
        <form method="POST" action="events.php">
            <input type="hidden" name="add_event" value="1">
            <label class="block text-sm font-medium text-gray-700">Event name:</label>
            <input type="text" name="event_name" placeholder="Event Name" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event date:</label>
            <input type="datetime-local" name="event_date" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event location:</label>
            <input type="text" name="event_location" placeholder="Location" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event description:</label>
            <textarea name="event_description" placeholder="Description" required class="w-full h-32 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4"></textarea>

            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Save</button>
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200" onclick="closeAddEventPopup()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Event Popup -->
<div id="edit-popup" class="popup fixed inset-0 flex justify-center items-center hidden z-50">
    <div class="popup-content bg-white shadow-lg rounded-lg p-8 w-[40rem]">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Edit Event</h3>
        <form method="POST" id="edit-form" action="edit_event.php">
            <input type="hidden" id="event-id" name="event_id">

            <label class="block text-sm font-medium text-gray-700">Event name:</label>
            <input type="text" id="event-name" name="event_name" placeholder="Event Name" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event date:</label>
            <input type="datetime-local" id="event-date" name="event_date" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event location:</label>
            <input type="text" id="event-location" name="event_location" placeholder="Location" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event description:</label>
            <textarea id="event-description" name="event_description" placeholder="Event Description" required class="w-full h-32 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4"></textarea>

            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-200">Save</button>
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200" onclick="closePopup()">Cancel</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
