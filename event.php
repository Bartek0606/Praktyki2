<?php
session_start(); 

include 'db_connection.php';

$isLoggedIn = isset($_SESSION['user_id']);

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy(); // Destroy the session
  header("Location: index.php"); // Redirect to homepage
  exit;
}

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
    } else {
        echo "<p>Event not found.</p>";
        exit;
    }
} else {
    echo "<p>No event specified.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="event.css">
    <title><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></title> <!-- Dynamic title -->
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <h1><a href="index.php">HobbyHub</a></h1>
      </div>
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

      <div class="auth-buttons">
        <?php if ($isLoggedIn): ?>
          <span class="welcome-message"><?php echo htmlspecialchars($_SESSION['username']); ?></span>

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
    <div class="event-details">
        <div class="event-header">
            <div class="event-info">
                <h1><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($event['event_description'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($event['event_date'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <!-- Display event description -->
        <p><?php echo nl2br(htmlspecialchars($event['event_description'], ENT_QUOTES, 'UTF-8')); ?></p>
    </div>
  </main>

</body>
</html>

<?php
$conn->close();
?>
