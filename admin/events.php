<?php
// Łączenie z bazą danych
include __DIR__ . '/../db_connection.php';

// Zapytanie do bazy danych, aby pobrać wszystkie wydarzenia
$query = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-panel">
    <header>
        <h1 class="tittle">HOBBYHUB</h1>
    </header>
    <aside class="sidebar">
        <hr class="hr_nav">
        <div class="profile-section">
            <div class="profile-pic"></div>
            <p class="username">Username</p>
        </div>
        <nav class="menu">
            <hr class="hrbutton">
            <button class="menu-button">Posts</button>
            <hr class="hrbutton">
            <button class="menu-button">Events</button>
            <hr class="hrbutton">
            <button class="menu-button">Comments</button>
            <hr class="hrbutton">
            <button class="menu-button">Add new category</button>
            <hr class="hrbutton">
        </nav>
        <hr class="hrbutton">
        <div class="sidebar-bottom">
            <button class="logout-button">
                <span>Log Out</span>
            </button>
        </div>
    </aside>

    <main class="dashboard">
        <h2>Upcoming Events</h2>
        <?php
        // Sprawdzamy, czy zapytanie zwróciło jakieś wyniki
        if ($result->num_rows > 0) {
            // Pętla po wszystkich wydarzeniach
            while ($event = $result->fetch_assoc()) {
                echo "<div class='event'>";
                echo "<h3>" . htmlspecialchars($event['event_name']) . "</h3>";
                echo "<p><strong>Date:</strong> " . htmlspecialchars($event['event_date']) . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>";
                echo "<p><strong>Description:</strong> " . nl2br(htmlspecialchars($event['event_description'])) . "</p>";
                // Przycisk Edytuj
                echo "<button class='edit-btn' onclick='openEditPopup(" . $event['event_id'] . ", \"" . addslashes($event['event_name']) . "\", \"" . addslashes($event['event_date']) . "\", \"" . addslashes($event['location']) . "\", \"" . addslashes($event['event_description']) . "\")'>Edytuj</button>";
                echo "</div>";
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </main>
</div>

<!-- Popup dla edycji -->
<div id="edit-popup" class="popup">
    <div class="popup-content">
        <h3>Edit Event</h3>
        <form method="POST" id="edit-form" action="edit_event.php">
            <input type="hidden" id="event-id" name="event_id">
            <input type="text" id="event-name" name="event_name" placeholder="Event Name" required>
            <input type="datetime-local" id="event-date" name="event_date" required>
            <input type="text" id="event-location" name="event_location" placeholder="Location" required>
            <textarea id="event-description" name="event_description" placeholder="Event Description" required></textarea>
            <button type="submit">Zatwierdź</button>
            <button type="button" onclick="closePopup()">Anuluj</button>
        </form>
    </div>
</div>

<script>
    // Funkcja otwierająca popup i wypełniająca formularz danymi wydarzenia
    function openEditPopup(eventId, name, date, location, description) {
        document.getElementById('event-id').value = eventId;
        document.getElementById('event-name').value = name;
        document.getElementById('event-date').value = date;
        document.getElementById('event-location').value = location;
        document.getElementById('event-description').value = description;
        document.getElementById('edit-popup').style.display = 'flex';
    }

    // Funkcja zamykająca popup
    function closePopup() {
        document.getElementById('edit-popup').style.display = 'none';
    }
</script>

</body>
</html>
