<?php
ob_start();
session_start();

include 'db_connection.php';
include 'Component/navbar.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Pobranie danych użytkownika
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, email, full_name, bio, profile_picture FROM users WHERE user_id = '$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Wylogowanie
    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Resetowanie zdjęcia profilowego
    if (isset($_POST['reset_picture'])) {
        $sql_update = "UPDATE users SET profile_picture = 'default.png' WHERE user_id = '$user_id'";
        if ($conn->query($sql_update) === TRUE) {
            $_SESSION['success_message'] = "Profile picture has been reset.";
            $_SESSION['success_message_type'] = 'reset'; // Typ komunikatu - reset
            header("Location: profile.php");
            exit();
        } else {
            echo "Error resetting profile picture: " . $conn->error;
        }
    }

    // Aktualizacja danych użytkownika
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $bio = $_POST['bio'];

    // Sprawdzenie, czy zdjęcie profilowe zostało przesłane
    $profile_picture = isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0
        ? addslashes(file_get_contents($_FILES['profile_picture']['tmp_name']))
        : $user['profile_picture'];

    // Sprawdź, czy dokonano zmian
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
        $sql_update = isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0
            ? "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name', bio = '$bio', profile_picture = '$profile_picture' WHERE user_id = '$user_id'"
            : "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name', bio = '$bio' WHERE user_id = '$user_id'";

        // Wykonanie zapytania
        if ($conn->query($sql_update) === TRUE) {
            $_SESSION['username'] = $username;
            $_SESSION['success_message'] = "Your profile has been successfully updated.";
            $_SESSION['success_message_changes'] = implode("<br>", $changes_made); // Dodajemy zmiany do komunikatu
            $_SESSION['success_message_type'] = 'success'; // Typ komunikatu - sukces
            header("Location: profile.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        $_SESSION['success_message'] = "No changes were made.";
        $_SESSION['success_message_type'] = 'warning'; // Typ komunikatu - brak zmian
        header("Location: profile.php");
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="edit_profile.css">
    <script src="profile.js" defer></script>
    <link rel="stylesheet" href="navbar.css">
    <title>Profile • HobbyHub</title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>
<main>
    <!-- Edycja profilu -->
    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <!-- Formularz edycji -->
            <div class="form-group">
                <div class="profile-picture-container">
                    <div class="current-profile-picture">
                        <?php if ($user['profile_picture'] && $user['profile_picture'] !== 'default.png'): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img src="default.png" alt="Default Profile Picture">
                        <?php endif; ?>
                    </div>
                    <div class="file-input">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                    </div>
                    <button type="submit" name="reset_picture" class="btn reset-btn">Reset Profile Picture</button>
                </div>
            </div>

            <!-- Pozostałe pola formularza -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
            </div>

            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea name="bio" id="bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </div>

            <button type="submit" class="btn save-btn">Save Changes</button>
        </form>
    </div>

    <!-- Wiadomość o sukcesie -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success-message <?php echo isset($_SESSION['success_message_type']) ? $_SESSION['success_message_type'] : ''; ?>">
        <p><?php echo htmlspecialchars($_SESSION['success_message']); ?></p>
        <?php if (isset($_SESSION['success_message_changes'])): ?>
            <div class="changes-made">
                <strong>Changes made:</strong>
                <p><?php echo $_SESSION['success_message_changes']; ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
    <?php unset($_SESSION['success_message_changes']); ?>
<?php endif; ?>
</main>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>

