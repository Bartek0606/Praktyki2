<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';

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
        header("Location: edit_profile.php");
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

    // Sprawdzanie, czy username lub email już istnieją
    $sql_check_username = "SELECT * FROM users WHERE username = '$username' AND user_id != '$user_id'";
    $result_username = $conn->query($sql_check_username);

    $sql_check_email = "SELECT * FROM users WHERE email = '$email' AND user_id != '$user_id'";
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../edit_profile.css">
    <script src="../js/profile.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Profile • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main>
        <!-- Edycja profilu -->
        <div class="container mx-auto p-6">
            <div class="bg-gray-800 p-8 rounded-lg shadow-lg space-y-8">
                <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <!-- Formularz edycji -->
                    <div class="space-y-4">
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <div class="flex items-center space-x-4">
                            <?php
$image_src = '/src/public/image/default.png';  // Zmienna z pełną ścieżką do default.png
?>

<div class="w-24 h-24 rounded-full overflow-hidden">
    <?php if ($user['profile_picture'] && $user['profile_picture'] !== 'default.png'): ?>
        <img class="w-full h-full object-cover" src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
    <?php else: ?>
        <img class="w-full h-full object-cover" src="<?php echo $image_src; ?>" alt="Default Profile Picture">
    <?php endif; ?>
</div>


                                <div class="flex items-center space-x-2">
                                    <div class="w-80">
                                        <label for="profile_picture" class="block mb-2">Profile Picture</label>
                                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="bg-gray-700 p-2 rounded-lg w-full text-gray-300 h-10">
                                    </div>
                                    <button type="submit" name="reset_picture" class="mt-8 bg-orange-400 p-2 rounded-full text-white hover:bg-gray-500 h-10 w-40">Reset Profile Picture</button>

                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Pozostałe pola formularza -->
                <div class="space-y-4">
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <label for="username" class="block mb-2">Username</label>
                            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="bg-gray-700 p-2 rounded-lg w-full text-gray-300">
                        </div>

                        <div class="bg-gray-800 p-4 rounded-lg">
                            <label for="email" class="block mb-2">Email</label>
                            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="bg-gray-700 p-2 rounded-lg w-full text-gray-300">
                        </div>

                        <div class="bg-gray-800 p-4 rounded-lg">
                            <label for="full_name" class="block mb-2">Full Name</label>
                            <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="bg-gray-700 p-2 rounded-lg w-full text-gray-300">
                        </div>

                        <div class="bg-gray-800 p-4 rounded-lg">
                            <label for="bio" class="block mb-2">Bio</label>
                            <textarea name="bio" id="bio" class="bg-gray-700 p-2 rounded-lg w-full text-gray-300"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="bg-orange-400 p-2 rounded-full text-white hover:bg-gray-500 w-full">Save Changes</button>
                </form>
            </div>

    <!-- Wiadomość o błędach i sukcesie -->
    <?php
    $message_class = '';
    $message_text = '';

    if (isset($_SESSION['error_message'])) {
        $message_class = 'bg-red-500 p-4 rounded-lg text-white'; 
            $message_text = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        } elseif (isset($_SESSION['success_message'])) {
            $message_class = 'bg-green-500 p-4 rounded-lg text-white'; 
            $message_text = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            unset($_SESSION['success_message_changes']);
        }
    ?>

    <?php if ($message_text): ?>
        <div class="<?php echo $message_class; ?> mt-6">
            <p><?php echo htmlspecialchars($message_text); ?></p>
            <?php if (isset($_SESSION['success_message_changes'])): ?>
                <div class="mt-4 bg-gray-700 p-4 rounded-lg">
                    <strong>Changes made:</strong>
                    <p><?php echo $_SESSION['success_message_changes']; ?></p>
                </div>
            <?php endif; ?>
        </div>
<?php endif; ?>
</div>

</main>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
