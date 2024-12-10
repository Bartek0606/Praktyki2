<?php
function getProfileUser($conn, $profileUserId) {
    $sql_profile_user = "SELECT username FROM users WHERE user_id = ?"; 
    $stmt_profile_user = $conn->prepare($sql_profile_user); 
    $stmt_profile_user->bind_param("i", $profileUserId); 
    $stmt_profile_user->execute(); 
    $result_profile_user = $stmt_profile_user->get_result(); 
    if ($result_profile_user->num_rows == 1) { 
        return $result_profile_user->fetch_assoc(); 
    } else { 
        return null;
    }
}

function sendMessage($conn, $userId, $profileUserId, $messageContent) {
    $sql_message = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
    $stmt_message = $conn->prepare($sql_message);
    $stmt_message->bind_param("iis", $userId, $profileUserId, $messageContent);
    $stmt_message->execute();
}

function deleteMessage($conn, $deleteMessageId, $userId) {
    $sql_delete = "DELETE FROM messages WHERE message_id = ? AND sender_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $deleteMessageId, $userId);
    $stmt_delete->execute();
}

function editMessage($conn, $editMessageId, $newContent, $userId) {
    $sql_edit = "UPDATE messages SET content = ? WHERE message_id = ? AND sender_id = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("sii", $newContent, $editMessageId, $userId);
    $stmt_edit->execute();
}

function getMessages($conn, $userId, $profileUserId) {
    $sql_messages = "SELECT m.message_id, m.sender_id, m.receiver_id, m.content, m.created_at, u.username AS sender_name
                     FROM messages m
                     JOIN users u ON m.sender_id = u.user_id
                     WHERE (sender_id = ? AND receiver_id = ?) 
                        OR (sender_id = ? AND receiver_id = ?)
                     ORDER BY `created_at` ASC";

    $stmt_messages = $conn->prepare($sql_messages);
    $stmt_messages->bind_param("iiii", $userId, $profileUserId, $profileUserId, $userId);
    $stmt_messages->execute();
    return $stmt_messages->get_result();
}

function logout() {
    session_start(); 
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function getUserByEmail($conn, $email) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function verifyPassword($inputPassword, $storedPasswordHash) {
    return password_verify($inputPassword, $storedPasswordHash);
}

function checkUserExists($conn, $email, $username) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    return $stmt->get_result();
}

function insertUser($conn, $username, $email, $passwordHash, $fullname, $defaultProfilePicture) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, full_name, created_at, profile_picture, is_admin) VALUES (?, ?, ?, ?, NOW(), ?, 0)");
    $stmt->bind_param('sssss', $username, $email, $passwordHash, $fullname, $defaultProfilePicture);
    return $stmt->execute();
}

?>


