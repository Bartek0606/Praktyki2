<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../function/function.php';
include '../../function/login_function.php';

$emailError = '';
$passwordError = '';
$fieldsError = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $password = $_POST['password']; 

    // Sanity check for input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    if (empty($username) || empty($password)) {
        $fieldsError = true;
    }

    if (!empty($username) && !validateEmail($username)) { 
        $emailError = "Invalid email format. Please include '@' in the email address."; 
    }

    if (!$fieldsError && empty($emailError)) {
        // Fetch user data from the database
        $user = getUserByEmail($conn, $username); 
        if ($user) { 
            if (verifyPassword($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                
                // Check if the user is an admin
                if ($user['is_admin'] == 1) {
                    // Redirect to the admin page if the user is an admin
                    header("Location: ../../admin/admin.php");
                } else {
                    // Otherwise, redirect to the normal page
                    header("Location: index.php");
                }
                exit;
            } else {
                $passwordError = "Invalid password. Please try again.";
            }
        } else {
            $emailError = "No account found with that email.";
        }
    }
}
include '../../Component/view/login_view.php';
ob_start();
?>

