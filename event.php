<?php
session_start(); 

include 'db_connection.php';
include 'Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);

$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$message = "";

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy(); // Destroy the session
  header("Location: index.php"); // Redirect to homepage
  exit;
}


// Handle registration for event (on button click)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if ($isLoggedIn) {
        $event_id = $_POST['event_id']; 
        $user_id = $_SESSION['user_id']; 
        
        $sql_check = "SELECT * FROM event_registrations WHERE user_id = ? AND event_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $user_id, $event_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $message = "<p>You are already registered for this event.</p>";
        } else {
            // Insert the registration into the event_registrations table
            $sql_register = "INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)";
            $stmt_register = $conn->prepare($sql_register);
            $stmt_register->bind_param("ii", $user_id, $event_id);
            $stmt_register->execute();

            if ($stmt_register->affected_rows > 0) {
                $message = "<p>You have successfully registered for the event!</p>";
            } else {
                $message = "<p>There was an error registering for the event. Please try again.</p>";
            }
        }
    } else {
        $message = "<p>You must be logged in to register for this event.</p>";
    }
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

    // Fetch the number of registrations for this event
    $sql_registration_count = "SELECT COUNT(*) as total_registrations FROM event_registrations WHERE event_id = ?";
    $stmt_count = $conn->prepare($sql_registration_count);
    $stmt_count->bind_param("i", $event_id);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    $registration_count = $result_count->fetch_assoc()['total_registrations'];
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
    <link rel="stylesheet" href="event.css">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="navbar.css">
    <title><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></title> <!-- Dynamic title -->
</head>
<body>
  <header>
    <?php
          echo $navbar->render();
      ?>
  </header> 

  <main class="container">
    <div class="event-details">
        <div class="event-header">
            <div class="event-info">
                <h1><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($event['event_description'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($event['event_date'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Registrations:</strong> <?php echo $registration_count; ?> people registered</p> <!-- Display the registration count -->
            </div>
        </div>

        <!-- Display event description -->
        <p><?php echo nl2br(htmlspecialchars($event['event_description'], ENT_QUOTES, 'UTF-8')); ?></p>
    </div>

    <?php if ($isLoggedIn): ?>
    <div class="registration-form">
        <form method="POST">
            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
            <button type="submit" name="register" class="btn-register">Register for Event</button>
        </form>
    </div>
    <?php else: ?>
        <div class="login-message">
            <p>You need to <a href="login.php" class="login-link">log in</a> to register for this event.</p>
        </div>
    <?php endif; ?>

    <!-- Display messages (success or error) -->
    <?php if (!empty($message)): ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

  </main>

</body>
</html>

<?php
$conn->close();
?>
