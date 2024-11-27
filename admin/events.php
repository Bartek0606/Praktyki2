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
                echo "</div>";
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </main>
</div>
</body>
</html>
