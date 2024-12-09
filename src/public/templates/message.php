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
    <script src="https://cdn.tailwindcss.com"></script>
<title>Document</title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="flex-grow container mx-auto mt-6 p-4">
    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col h-[70vh]">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Messages</h1>
        <div class="flex-grow overflow-y-auto space-y-4 px-4">
            <?php
            if ($result_messages->num_rows > 0) {
                while ($row = $result_messages->fetch_assoc()) {
                    $isSender = $row['sender_id'] == $userId;
                    ?>
                    <div class="flex <?php echo $isSender ? 'justify-end' : 'justify-start'; ?>">
                        <div class="<?php echo $isSender ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'; ?> rounded-lg px-4 py-3 max-w-xs">
                            <p class="font-medium"><?php echo htmlspecialchars($row['content']); ?></p>
                            <small class="text-xs <?php echo $isSender ? 'text-blue-200' : 'text-gray-500'; ?>">
                                <?php echo $row['created_at']; ?>
                            </small>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-gray-600 text-center'>No messages yet.</p>";
            }
            ?>
        </div>
        <form method="POST" class="flex items-center mt-4">
            <textarea name="message_content" id="message_content" required
                      class="flex-grow border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none mr-4"
                      placeholder="Type your message..."></textarea>
            <button type="submit"
                    class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                Send
            </button>
        </form>
    </div>
</main>
</body>
</html>
<?php
$conn->close();
ob_end_flush();
?>
