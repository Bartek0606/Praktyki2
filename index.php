<?php
ob_start();
session_start(); 

include 'db_connection.php';
include 'Component/navbar.php';
include 'Component/post.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$category_name = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

$posts = new PostRender($conn, $isLoggedIn, $category_name, $userId); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}

if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
    $posts->like($userId);  
}

$sql_events = "SELECT event_id, event_name, event_description, event_date, location 
               FROM events 
               ORDER BY event_date ASC"; 

$events_result = $conn->query($sql_events);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="navbar.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
           
                $formatted_date = date("F j, Y, g:i a", strtotime($event['event_date']));

                $event_url = 'event.php?id=' . $event['event_id'];

                echo "<a href='$event_url' class='event-card'>";
                echo "<h3>" . htmlspecialchars($event['event_name']) . "</h3>";
                echo "<p><strong>Date:</strong> " . $formatted_date . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>";
                echo "</a>";  
            }
        } else {
            echo "<p>No events available.</p>";
        }
        ?>
    </div>
    <button class="arrow-btn right-btn">→</button>
</div>

<div class="nagl">
    <h2>Posts</h2>
    <hr class="divider">
</div>
<div class="sort-menu">
    <form method="GET" action="">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="newest" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'newest' ? 'selected' : ''; ?>>Newest</option>
            <option value="oldest" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
            <option value="likes" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'likes' ? 'selected' : ''; ?>>Most Liked</option>
        </select>
    </form>
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
ob_end_flush();
?>
