<?php
ob_start();
session_start();

include 'db_connection.php';
include 'Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to homepage
    exit;
}

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// ID do kogo
if (isset($_GET['id'])) {
    $profileUserId = intval($_GET['id']);
} else {
    echo "User ID not specified.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_content'])) {
    $messageContent = $_POST['message_content'];
    $sql_message = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
    $stmt_message = $conn->prepare($sql_message);
    $stmt_message->bind_param("iis", $userId, $profileUserId, $messageContent);
    $stmt_message->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="glowna.css">
    <title>Document</title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>
<main>
    <form method="POST">
        <label for="message_content">Message:</label>
        <textarea name="message_content" id="message_content" required></textarea>
        <button type="submit">Send Message</button>
    </form>
</main>
</body>
</html>
<?php
$conn->close();
ob_end_flush();
?>
