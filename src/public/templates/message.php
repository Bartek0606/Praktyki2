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
    $sql_profile_user = "SELECT username FROM users WHERE user_id = ?"; 
    $stmt_profile_user = $conn->prepare($sql_profile_user); 
    $stmt_profile_user->bind_param("i", $profileUserId); 
    $stmt_profile_user->execute(); 
    $result_profile_user = $stmt_profile_user->get_result(); 
    if ($result_profile_user->num_rows == 1) { 
        $profileUserRow = $result_profile_user->fetch_assoc(); 
        $profileUserName = $profileUserRow['username']; 
    } else { 
        echo "User not found."; exit(); 
    }
} else {
    echo "User ID not specified.";
    exit();
}
// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_content'])) {
    $messageContent = $_POST['message_content'];
    $sql_message = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
    $stmt_message = $conn->prepare($sql_message);
    $stmt_message->bind_param("iis", $userId, $profileUserId, $messageContent);
    $stmt_message->execute();
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $profileUserId); 
    exit;
}
// Delete message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message_id'])) {
    $deleteMessageId = intval($_POST['delete_message_id']);
    $sql_delete = "DELETE FROM messages WHERE message_id = ? AND sender_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $deleteMessageId, $userId);
    $stmt_delete->execute();
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $profileUserId); 
    exit;
}

// Edit message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_message_id'])) { 
    $editMessageId = intval($_POST['edit_message_id']); 
    $newContent = trim($_POST['new_message_content']); 
    if (!empty($newContent)) { 
        $sql_edit = "UPDATE messages SET content = ? WHERE message_id = ? AND sender_id = ?"; 
        $stmt_edit = $conn->prepare($sql_edit); 
        $stmt_edit->bind_param("sii", $newContent, $editMessageId, $userId); 
        $stmt_edit->execute(); 
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $profileUserId); 
        exit; 
    }
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
<title>Messages</title>
</head>
<body class="bg-gray-900">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="flex-grow container mx-auto mt-6 p-4">
    <div class="bg-gray-600 shadow-md rounded-lg border border-gray-200 p-6 flex flex-col h-[70vh]">
        <h1 class="text-2xl font-bold text-white mb-6">Messages with <?php echo '<a href="user.php?id=' . urlencode($profileUserId) . '" class="text-white hover:underline">' . htmlspecialchars($profileUserName, ENT_QUOTES, 'UTF-8') . '</a>'; ?></h1>
        <div class="flex-grow overflow-y-auto space-y-4 px-4">
            <?php
            if ($result_messages->num_rows > 0) {
                while ($row = $result_messages->fetch_assoc()) {
                    $isSender = $row['sender_id'] == $userId;
                    ?>
                    <div class="flex <?php echo $isSender ? 'justify-end' : 'justify-start'; ?>">
                        <div class="<?php echo $isSender ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'; ?> rounded-lg px-4 py-3 max-w-xs"> 
                            <p class="font-medium whitespace-normal max-h-32 overflow-y-auto overflow-x-hidden"><?php echo htmlspecialchars($row['content']); ?></p>
                            <small class="text-xs <?php echo $isSender ? 'text-blue-200' : 'text-gray-500'; ?>">
                                <?php echo $row['created_at']; ?>
                            </small>
                            <?php if ($isSender): ?>
                            <div class="flex space-x-2 mt-2">
                                <div class="inline">
                                    <button id="edit-button-<?php echo $row['message_id']; ?>" class="text-sm text-blue-600 font-semibold hover:underline" onclick="toggleEditForm(<?php echo $row['message_id']; ?>)">
                                        Edit
                                    </button>
                                </div>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="delete_message_id" value="<?php echo $row['message_id']; ?>">
                                    <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            <!-- Hidden Edit Form -->
                            <form id="edit-form-<?php echo $row['message_id']; ?>" method="POST" style="display: none;" class="mt-4 flex flex-col space-y-2">
                                <input type="hidden" name="edit_message_id" value="<?php echo $row['message_id']; ?>">
                                <!-- Input Field -->
                                <textarea name="new_message_content" rows="2" placeholder="Edit your message..." class="border border-gray-300 rounded-lg p-2 text-sm w-full focus:ring-2 focus:ring-blue-400 focus:outline-none text-black"></textarea>
                                <!-- Save and Cancel Buttons -->
                                <div class="flex justify-end space-x-2">
                                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg text-sm font-semibold hover:bg-green-600 focus:ring-2 focus:ring-green-400 focus:outline-none">
                                        Save
                                    </button>
                                    <button type="button" onclick="toggleEditForm(<?php echo $row['message_id']; ?>)" class="bg-gray-400 text-white py-2 px-4 rounded-lg text-sm font-semibold hover:bg-gray-500 focus:ring-2 focus:ring-gray-300 focus:outline-none">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                            <?php endif; ?>
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
            <textarea name="message_content" id="message_content" required class="flex-grow border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none mr-4" placeholder="Type your message..."></textarea>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
<script>
    function toggleEditForm(messageId) {
        const editForm = document.getElementById(`edit-form-${messageId}`);
        const editButton = document.getElementById(`edit-button-${messageId}`);
        if (editForm.style.display === 'none') {
            editForm.style.display = 'block';
            editButton.style.display = 'none';
        } else {
            editForm.style.display = 'none';
            editButton.style.display = 'inline';
        }
    }
</script>