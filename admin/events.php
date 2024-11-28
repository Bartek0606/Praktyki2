<?php
// Łączenie z bazą danych
include __DIR__ . '/../db_connection.php';

// Zapytanie do bazy danych, aby pobrać wszystkie wydarzenia
$query = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($query);

// Sprawdzamy, czy zapytanie o usunięcie wydarzenia zostało wysłane
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

// Sprawdzamy, czy zapytanie o dodanie wydarzenia zostało wysłane
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
               <a href="admin.php"> <button class="menu-button">Posts</button></a>
                <hr class="hrbutton">
               <a href="events.php"> <button class="menu-button">Events</button></a>
                 <hr class="hrbutton">
                <a href="comments.php"><button class="menu-button">Comments</button></a>
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
        <button class="add-event-btn" onclick="openAddEventPopup()">Dodaj Wydarzenie</button>
        <?php
        if ($result->num_rows > 0) {
            while ($event = $result->fetch_assoc()) {
                echo "<div class='event'>";
                echo "<h3>" . htmlspecialchars($event['event_name']) . "</h3>";
                echo "<p><strong>Date:</strong> " . htmlspecialchars($event['event_date']) . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>";
                echo "<p><strong>Description:</strong> " . nl2br(htmlspecialchars($event['event_description'])) . "</p>";
                echo "<button class='edit-btn' onclick='openEditPopup(" . $event['event_id'] . ", \"" . addslashes($event['event_name']) . "\", \"" . addslashes($event['event_date']) . "\", \"" . addslashes($event['location']) . "\", \"" . addslashes($event['event_description']) . "\")'>Edytuj</button>";

                echo "<form method='POST' action='events.php' style='display:inline;'>
                        <input type='hidden' name='delete_event_id' value='" . $event['event_id'] . "'>
                        <button type='submit' class='delete-btn'>Usuń</button>
                    </form>";
                echo "</div>";
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </main>
</div>

<div id="add-event-popup" class="popup">
    <div class="popup-content">
        <h3>Dodaj Wydarzenie</h3>
        <form method="POST" action="events.php">
            <input type="hidden" name="add_event" value="1">
            <input type="text" name="event_name" placeholder="Nazwa wydarzenia" required>
            <input type="datetime-local" name="event_date" required>
            <input type="text" name="event_location" placeholder="Lokalizacja" required>
            <textarea name="event_description" placeholder="Opis wydarzenia" required></textarea>
            <button type="submit">Zatwierdź</button>
            <button type="button" onclick="closeAddEventPopup()">Anuluj</button>
        </form>
    </div>
</div>

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
    // Funkcja otwierająca popup dla dodawania wydarzenia
    function openAddEventPopup() {
        document.getElementById('add-event-popup').style.display = 'flex';
    }

    // Funkcja zamykająca popup dla dodawania wydarzenia
    function closeAddEventPopup() {
        document.getElementById('add-event-popup').style.display = 'none';
    }

    // Funkcja otwierająca popup i wypełniająca formularz danymi wydarzenia (do edycji)
    function openEditPopup(eventId, name, date, location, description) {
        document.getElementById('event-id').value = eventId;
        document.getElementById('event-name').value = name;
        document.getElementById('event-date').value = date;
        document.getElementById('event-location').value = location;
        document.getElementById('event-description').value = description;
        document.getElementById('edit-popup').style.display = 'flex';
    }

    // Funkcja zamykająca popup dla edycji
    function closePopup() {
        document.getElementById('edit-popup').style.display = 'none';
    }
</script>

</body>
</html>
