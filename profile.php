<?php
session_start(); // Start session to check login status

// Include the database connection
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, email, full_name, bio, profile_picture FROM users WHERE user_id = '$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

// Update profile if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle log out
    if (isset($_POST['logout'])) {
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        header("Location: login.php"); // Redirect to login page
        exit();
    }

    // Update the profile data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $bio = $_POST['bio'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = addslashes(file_get_contents($_FILES['profile_picture']['tmp_name']));
        $sql_update = "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name', bio = '$bio', profile_picture = '$profile_picture' WHERE user_id = '$user_id'";
    } else {
        $sql_update = "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name', bio = '$bio' WHERE user_id = '$user_id'";
    }

    if ($conn->query($sql_update) === TRUE) {
        // Success message
        $_SESSION['username'] = $username;
        header("Location: profile.php"); // Redirect to profile page to see updated data
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="profile.css"> <!-- Separate CSS for profile -->
    <script src="profile.js" defer></script>
    <title>Profile • HobbyHub</title>
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">
            <h1><a href="index.php">HobbyHub</a></h1>
        </div>
        <ul class="nav-links">
            <li><a href="#">Fotografia</a></li>
            <li><a href="#">Gaming</a></li>
            <li><a href="#">Gotowanie</a></li>
            <li><a href="#">Ogrodnictwo</a></li>
            <li><a href="#">Sporty zimowe</a></li>
            <li><a href="#">Sporty wodne</a></li>
        </ul>

        <div class="auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="profile-info">
                    <a href="profile.php" class="profile-link">
                        <?php
                        // Fetch profile picture
                        $sql_image = "SELECT profile_picture FROM users WHERE user_id = '$user_id'";
                        $result_image = $conn->query($sql_image);
                        $image_src = 'default.png'; // Default image
                        if ($result_image->num_rows > 0) {
                            $row = $result_image->fetch_assoc();
                            if (!empty($row['profile_picture'])) {
                                // If there's a profile picture, use it
                                $image_src = 'data:image/jpeg;base64,' . base64_encode($row['profile_picture']);
                            }
                        }
                        ?>
                        <img src="<?php echo $image_src; ?>" alt="Profile Picture" class="profile-img">
                        <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </a>
                </div>
                <form method="POST" class="logout-form" style="display: inline;">
                    <button type="submit" name="logout" class="btn logout-btn">Log out</button>
                </form>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main class="container">
    <div class="profile-form-container">
        <h2>Edit Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <div class="profile-picture-container">
                    <div class="current-profile-picture">
                        <?php if ($user['profile_picture']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img src="default-avatar.jpg" alt="Default Profile Picture">
                        <?php endif; ?>
                    </div>
                    <div class="file-input">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                    </div>
                </div>
            </div>

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
</main>

</body>
</html>

<?php
$conn->close();
?>
