<?php
session_start(); 

include 'db_connection.php';
include 'COs/navbar.php';
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to homepage
    exit;
}
// Pobieranie nazwy kategorii z pola wyszukiwania
$category_name = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// SQL do filtrowania lub wyświetlania wszystkich postów
if (!empty($category_name)) {
    // Gdy użytkownik wprowadzi nazwę kategorii, dołączamy `categories` do `posts`
    $sql_search = "
        SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name
        FROM posts
        JOIN categories ON posts.category_id = categories.category_id
        WHERE categories.name LIKE '%$category_name%'
        ORDER BY posts.created_at DESC
    ";
} else {
    // Wyświetlenie wszystkich postów
    $sql_search = "
        SELECT posts.post_id, posts.title, posts.content, posts.created_at,posts.image, categories.name AS category_name
        FROM posts
        JOIN categories ON posts.category_id = categories.category_id
        ORDER BY posts.created_at DESC
    ";
}

$result = $conn->query($sql_search);

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
    <div class="posts">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Create the link to the post page
            $post_url = 'post.php?id=' . $row['post_id'];

            // Wrap the entire post div inside the anchor tag
            echo '<a href="' . $post_url . '" class="post-link">';  // Start the anchor tag here
            echo '<div class="post">';
            echo "<img src='".'data:image/jpeg;base64,' . base64_encode($row['image']) ."' alt='Post Image'>";
            echo '<div>';
            echo '<h2>' . $row['title'] . '</h2>';
            echo '<p>Category: ' . $row['category_name']. '</p>';
            echo '<p>' . $row['content'] . '</p>';
            echo '<p>Date: ' . $row['created_at'] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</a>';  // Close the anchor tag here
        }
    } else {
        echo '<p>No posts found.</p>';
    }
    ?>
    </div>
</section>



  </main>

</body>
</html>

<?php
$conn->close();
?>
