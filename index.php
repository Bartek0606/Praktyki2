<?php
session_start(); 

include 'db_connection.php';
include 'Component/navbar.php';
include 'Component/post.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Pobieranie nazwy kategorii z pola wyszukiwania
$category_name = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

$posts = new PostRender($conn, $category_name); 

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to homepage
    exit;
}

$sql_events = "SELECT event_id, event_name, event_description, event_date, location 
               FROM events 
               ORDER BY event_date ASC";  // Możesz zmienić kolejność, np. DESC jeśli chcesz pokazać najnowsze wydarzenia jako pierwsze

$events_result = $conn->query($sql_events);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="navbar.css">
    <script src="glowna.js" defer></script>
    <title>HobbyHub</title>
</head>
<body>
<header>
    <?php
        echo $navbar->render();
    ?>
</header>
<main class="container">
    <div class="nagl">
      <h2>Events</h2>
      <hr class="divider">
    </div>

    <div class="event-slider-container">
    <button class="arrow-btn left-btn">←</button>
    <div class="event-slider">
        <?php
        if ($events_result->num_rows > 0) {
            while ($event = $events_result->fetch_assoc()) {
                // Formatowanie daty
                $formatted_date = date("F j, Y, g:i a", strtotime($event['event_date']));

                // Create the link to the event page
                $event_url = 'event.php?id=' . $event['event_id'];

                // Wrap the event card in an anchor tag for navigation
                echo "<a href='$event_url' class='event-card'>";
                echo "<h3>" . htmlspecialchars($event['event_name']) . "</h3>";
                echo "<p><strong>Date:</strong> " . $formatted_date . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>";
                echo "</a>";  // Close the anchor tag
            }
        } else {
            echo "<p>No events available.</p>";
        }
        ?>
    </div>
    <button class="arrow-btn right-btn">→</button>
</div>

    <div class="nagl">
      <h2>Latest posts</h2>
      <hr class="divider">
    </div>

   <section class="blog-posts">
    <?php 
        echo $posts->render();
    ?>
</section>



  </main>

</body>
</html>

<?php
$conn->close();
?>
