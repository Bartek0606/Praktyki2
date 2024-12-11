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

function logout() {
    session_start(); 
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function verifyPassword($inputPassword, $storedPasswordHash) {
    return password_verify($inputPassword, $storedPasswordHash);
}

?>


