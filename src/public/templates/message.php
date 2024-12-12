<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../function/function.php';
include '../../function/message_function.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

if (isset($_GET['id'])) { 
    $profileUserId = intval($_GET['id']); 
    $profileUserRow = getProfileUser($conn, $profileUserId); 
    if ($profileUserRow) { 
        $profileUserName = $profileUserRow['username']; 
    } else { 
        echo "User not found."; exit(); 
    } 
} else { 
    echo "User ID not specified."; 
    exit(); 
}
// Handle POST requests 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if (isset($_POST['message_content'])) { 
        sendMessage($conn, $userId, $profileUserId, $_POST['message_content']); 
    } elseif (isset($_POST['delete_message_id'])) { 
        deleteMessage($conn, $_POST['delete_message_id'], $userId); 
    } elseif (isset($_POST['edit_message_id'])) { 
        editMessage($conn, $_POST['edit_message_id'], trim($_POST['new_message_content']), $userId); 
    } 
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $profileUserId); 
    exit; 
}
$result_messages = getMessages($conn, $userId, $profileUserId); 
include '../../Component/view/messages_view.php'; 
include '../../Component/view/footer.php';
$conn->close(); 
ob_end_flush(); 
?>