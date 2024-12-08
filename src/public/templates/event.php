<?php
session_start(); 

include '../../../db_connection.php';
include '../../Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy(); 
  header("Location: index.php"); 
  exit;
}

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
            $isRegistered = true;
        } else {
            $sql_register = "INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)";
            $stmt_register = $conn->prepare($sql_register);
            $stmt_register->bind_param("ii", $user_id, $event_id);
            $stmt_register->execute();

            if ($stmt_register->affected_rows > 0) {
                $isRegistered = true;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unregister'])) {
    if ($isLoggedIn) {
        $event_id = $_POST['event_id']; 
        $user_id = $_SESSION['user_id']; 

        $sql_unregister = "DELETE FROM event_registrations WHERE user_id = ? AND event_id = ?";
        $stmt_unregister = $conn->prepare($sql_unregister);
        $stmt_unregister->bind_param("ii", $user_id, $event_id);
        $stmt_unregister->execute();

        if ($stmt_unregister->affected_rows > 0) {
            $isRegistered = false;
        }
    }
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

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

$isRegistered = false;
if ($isLoggedIn) {
    $sql_check_registration = "SELECT * FROM event_registrations WHERE user_id = ? AND event_id = ?";
    $stmt_check_registration = $conn->prepare($sql_check_registration);
    $stmt_check_registration->bind_param("ii", $userId, $event_id);
    $stmt_check_registration->execute();
    $result_registration = $stmt_check_registration->get_result();
    $isRegistered = $result_registration->num_rows > 0;
}

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
    <title><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></title> 
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
  <header>
    <?php
          echo $navbar->render();
    ?>
  </header> 

  <main class="container mx-auto px-4 py-8">
    <div class="bg-gray-800 rounded-lg p-6 mb-2">
        <h1 class="text-3xl font-semibold mb-4"><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><strong class="text-orange-400">Description:</strong> <?php echo htmlspecialchars($event['event_description'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong class="text-orange-400">Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($event['event_date'])); ?></p>
        <p><strong class="text-orange-400">Location:</strong> <?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong class="text-orange-400">Registrations:</strong> <?php echo $registration_count; ?> people registered</p> 
    </div>

    <!-- Zielona informacja, gdy użytkownik jest już zapisany -->
    <?php if ($isRegistered): ?>
        <div class="bg-green-800 p-4 rounded-lg mb-8">
            <p class="text-center">You are already registered for this event.</p>
        </div>
    <?php endif; ?>

    <br>

    <?php if (!$isRegistered): ?>
        <?php if ($isLoggedIn): ?>
            <div class="flex justify-center">
                <form method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                    <button type="submit" name="register" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600">Register for Event</button>
                </form>
            </div>
        <?php else: ?>
            <div class="bg-gray-800 p-4 rounded-lg">
                <p>You need to <a href="login.php" class="text-orange-500">log in</a> to register for this event.</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="flex justify-center mt-4">
            <form method="POST">
                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                <button type="submit" name="unregister" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600">Unregister from Event</button>
            </form>
        </div>
    <?php endif; ?>
</main>

</body>
</html>

<?php
$conn->close();
?>
