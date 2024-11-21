<?php
session_start(); // Start session to check login status

// Include the database connection
include 'db_connection.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

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

// Fetch blog posts from the database
$sql = "SELECT p.post_id, p.title, p.created_at, u.username, c.name AS category_name, p.image 
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.user_id
        LEFT JOIN categories c ON p.category_id = c.category_id
        ORDER BY p.created_at DESC"; // Sort by date, showing latest first

$result = $conn->query($sql);

// Kod do pobierania kategorii z bazy
$sql_categories = "SELECT category_id, name FROM categories ORDER BY name ASC"; 
$categories_result = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="glowna.css">
    <script src="glowna.js" defer></script>
    <title>HobbyHub</title>
</head>
<body>
<header>
    <nav class="navbar">
      <div class="logo">
        <h1><a href="index.php">HobbyHub</a></h1>

            <!-- Kod do menu rozwijanego -->
    <div class="dropdown">
        <button class="dropdown-button" onclick="toggleDropdown()">Wybierz kategorię</button>
        <div class="dropdown-menu" id="dropdownMenu">
            <?php
            if ($categories_result->num_rows > 0) {
                while ($row = $categories_result->fetch_assoc()) {
                    echo '<a href="subpage.php?id=' . $row['category_id'] . '">' . htmlspecialchars($row['name']) . '</a>';
                }
            } else {
                echo '<a>Brak kategorii</a>';
            }
            ?>
        </div>
    </div>
      </div>
  

      <div class="auth-buttons">
        <?php if ($isLoggedIn): ?>
          <div class="auth-info">
            <a href="profile.php" class="profile-link">
              <?php
                // Pobranie ścieżki do zdjęcia profilowego z bazy danych (założenie, że zdjęcie jest w tabeli 'users')
                $user_id = $_SESSION['user_id'];
                $sql_image = "SELECT profile_picture FROM users WHERE user_id = '$user_id'";
                $result_image = $conn->query($sql_image);
                $image_src = 'default.png'; // Default image
                if ($result_image->num_rows > 0) {
                    $row = $result_image->fetch_assoc();
                    if (!empty($row['profile_picture'])) {
                        // If there's a profile picture, use it
                        $image_src = 'data:image/jpeg;base64,' . base64_encode($row['profile_picture']);
                    }
                }
              ?>
              <img src="<?php echo $image_src; ?>" alt="Profile Picture" class="profile-img">
              <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </a>
          </div>
          
            <form method="POST" style="display: inline;">
                <button type="submit" name="logout" class="btn logout-btn">Log out</button>
            </form>
            
        <?php else: ?>
            <button class="btn register-btn" onclick="window.location.href='register.php'">Sign up</button>
            <button class="btn login-btn" onclick="window.location.href='login.php'">Login</button>
        <?php endif; ?>
      </div>
    </nav>
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
      <div class="blog-grid">
        <?php
        if ($result->num_rows > 0) {
            // Output the data for each blog post
            while ($row = $result->fetch_assoc()) {
                // Convert the image blob into a base64 string
                $image_data = base64_encode($row['image']);
                $image_src = 'data:image/jpeg;base64,' . $image_data; // Assuming the image is in JPEG format

                echo "<div class='blog-post' onclick=\"location.href='post.php?id=" . $row['post_id'] . "'\">";
                echo "<img src='" . $image_src . "' alt='Post Image'>"; // Display image from the database
                echo "<div class='blog-post-info'>";
                echo "<h3>" . $row['title'] . "</h3>";  // Display title
                echo "<p><strong>Category:</strong> " . $row['category_name'] . "</p>";
                echo "<p><strong>By:</strong> " . $row['username'] . "</p>";
                echo "<p><strong>Date:</strong> " . $row['created_at'] . "</p>"; // Date displayed below the username
                echo "</div></div>";
            }
        } else {
            echo "<p>No blog posts available.</p>";
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
