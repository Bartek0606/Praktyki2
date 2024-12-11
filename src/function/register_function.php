<?php
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