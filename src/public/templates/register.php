<?php
ob_start(); 
require_once '../../../db_connection.php';
include '../../function.php';

$emailError = '';
$passwordError = '';
$confirmPasswordError = '';
$fullnameError = '';
$usernameError = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if (empty($email)) {
        $emailError = "Email is required.";
    } elseif (!validateEmail($email)) {
        $emailError = "Invalid email address.";
    }

    if (empty($fullname)) {
        $fullnameError = "Full name is required.";
    }

    if (empty($username)) {
        $usernameError = "Username is required.";
    }

    if (empty($password)) {
        $passwordError = "Password is required.";
    } elseif ($password !== $confirmPassword) {
        $passwordError = "Passwords do not match.";
    }

    if (empty($emailError) && empty($fullnameError) && empty($usernameError) && empty($passwordError)) {
      
        $passwordHash = password_hash($password, PASSWORD_BCRYPT); 
        $defaultProfilePicture = file_get_contents('../image/default.png'); 
        $result = checkUserExists($conn, $email, $username); 
        if ($result->num_rows > 0) { 
            $emailError = "Email or username is already taken."; 
        } else { 
            if (insertUser($conn, $username, $email, $passwordHash, $fullname, $defaultProfilePicture)) { 
                $successMessage = "<b>Registration successful! You can now log in.</b>"; 
                $_POST = []; 
            } else { 
                die("Error inserting data: " . $conn->error); 
            } 
        } 
    } 
}
include '../../Component/view/register_view.php'; 
ob_end_flush();
?>