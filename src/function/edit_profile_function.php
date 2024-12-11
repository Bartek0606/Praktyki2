<?php

// Funkcja do pobrania danych użytkownika z bazy danych
function getUserData($conn, $userId) {
    $sql_user = "SELECT username, email, full_name, bio, profile_picture FROM users WHERE user_id = '$userId'";
    $result_user = $conn->query($sql_user);
    if ($result_user) {
        return $result_user->fetch_assoc();
    } else {
        die("Error retrieving user data: " . $conn->error);
    }
}

// Funkcja obsługująca aktualizację profilu
function handleProfileUpdate($conn, $userId, $postData, $fileData) {
    // Zmienna z danymi z formularza
    $username = $postData['username'];
    $email = $postData['email'];
    $full_name = $postData['full_name'];
    $bio = $postData['bio'];

    // Sprawdzanie, czy username lub email już istnieją
    $sql_check_username = "SELECT * FROM users WHERE username = '$username' AND user_id != '$userId'";
    $result_username = $conn->query($sql_check_username);

    $sql_check_email = "SELECT * FROM users WHERE email = '$email' AND user_id != '$userId'";
    $result_email = $conn->query($sql_check_email);

    // Debugging - Sprawdź, czy zapytania zwróciły wiersze
    if ($result_username === false) {
        die("SQL error checking username: " . $conn->error);
    }
    if ($result_email === false) {
        die("SQL error checking email: " . $conn->error);
    }

    // Jeżeli username lub email są już zajęte, wyświetl błąd
    if ($result_username->num_rows > 0) {
        $_SESSION['error_message'] = "Username is already taken.";
    } elseif ($result_email->num_rows > 0) {
        $_SESSION['error_message'] = "Email is already taken.";
    } else {
        // Sprawdzenie, czy zdjęcie profilowe zostało przesłane
        $profile_picture = isset($fileData['profile_picture']) && $fileData['profile_picture']['error'] === 0
            ? addslashes(file_get_contents($fileData['profile_picture']['tmp_name']))
            : getUserData($conn, $userId)['profile_picture'];

        // Sprawdzamy, czy dokonano zmian
        $user = getUserData($conn, $userId);
        $noChanges = $username === $user['username'] &&
                     $email === $user['email'] &&
                     $full_name === $user['full_name'] &&
                     $bio === $user['bio'] &&
                     $profile_picture === $user['profile_picture'];

        // Zmienna do przechowywania informacji o zmianach
        $changes_made = [];

        if (!$noChanges) {
            if ($username !== $user['username']) {
                $changes_made[] = "Username: $user[username] → $username";
            }
            if ($email !== $user['email']) {
                $changes_made[] = "Email: $user[email] → $email";
            }
            if ($full_name !== $user['full_name']) {
                $changes_made[] = "Full Name: $user[full_name] → $full_name";
            }
            if ($bio !== $user['bio']) {
                $changes_made[] = "Bio: " . ($user['bio'] ? $user['bio'] : 'None') . " → $bio";
            }
            if ($profile_picture !== $user['profile_picture']) {
                $changes_made[] = "Profile Picture: Changed";
            }

            // Tworzenie zapytania SQL do aktualizacji
            $sql_update = isset($fileData['profile_picture']) && $fileData['profile_picture']['error'] == 0
                ? "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name', bio = '$bio', profile_picture = '$profile_picture' WHERE user_id = '$userId'"
                : "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name', bio = '$bio' WHERE user_id = '$userId'";

            // Wykonanie zapytania
            if ($conn->query($sql_update) === TRUE) {
                $_SESSION['username'] = $username;
                $_SESSION['success_message'] = "Your profile has been successfully updated.";
                $_SESSION['success_message_changes'] = implode("<br>", $changes_made); // Dodajemy zmiany do komunikatu
                $_SESSION['success_message_type'] = 'success'; // Typ komunikatu - sukces
                header("Location: edit_profile.php");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            $_SESSION['success_message'] = "No changes were made.";
            $_SESSION['success_message_type'] = 'warning'; // Typ komunikatu - brak zmian
            header("Location: edit_profile.php");
            exit();
        }
    }
}

// Funkcja resetująca zdjęcie profilowe
function resetProfilePicture($conn, $userId) {
    $sql_update = "UPDATE users SET profile_picture = 'default.png' WHERE user_id = '$userId'";
    if ($conn->query($sql_update) === TRUE) {
        $_SESSION['success_message'] = "Profile picture has been reset.";
        $_SESSION['success_message_type'] = 'reset'; // Typ komunikatu - reset
        header("Location: edit_profile.php");
        exit();
    } else {
        echo "Error resetting profile picture: " . $conn->error;
    }
}
?>
