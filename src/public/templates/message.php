<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';

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
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $profileUserId); 
    exit;
}
$sql_messages = "SELECT m.message_id, m.sender_id, m.receiver_id, m.content, m.created_at, u.username AS sender_name
                 FROM messages m
                 JOIN users u ON m.sender_id = u.user_id
                 WHERE (sender_id = ? AND receiver_id = ?) 
                    OR (sender_id = ? AND receiver_id = ?)
                 ORDER BY `created_at` ASC";

$stmt_messages = $conn->prepare($sql_messages);
$stmt_messages->bind_param("iiii", $userId, $profileUserId, $profileUserId, $userId);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../navbar.css">
    <link rel="stylesheet" href="../../../glowna.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../../message.css">
<title>Document</title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>
<main>
    <div class="container">
        <h1>Send Message</h1>
        <div class="messages">
            <?php
            if ($result_messages->num_rows > 0) {
                while ($row = $result_messages->fetch_assoc()) {
                    $isSender = $row['sender_id'] == $userId; 
                    ?>
                    <div class="message <?php echo $isSender ? 'sent' : 'received'; ?>">
                        <div class="message-content">
                            <strong><?php echo htmlspecialchars($row['sender_name']); ?>:</strong>
                            <p><?php echo htmlspecialchars($row['content']); ?></p>
                            <small><?php echo $row['created_at']; ?></small>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No messages yet.</p>";
            }
            ?>
        </div>
        <form method="POST">
            <label for="message_content">Message:</label>
            <textarea name="message_content" id="message_content" required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</main>
</body>
</html>
<?php
$conn->close();
ob_end_flush();
?>
