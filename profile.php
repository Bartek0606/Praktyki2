<?php
session_start(); // Start session to check login status

// Include the database connection
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user details from the database
$sql = "SELECT username, bio, profile_picture_url FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $username = $_POST['username'];
        $bio = $_POST['bio'];

        // Update profile picture if uploaded
        $profile_picture_url = $user['profile_picture_url']; // Default to existing picture
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
            $profile_picture_url = $target_file;
        }

        // Update user info in the database
        $update_sql = "UPDATE users SET username = ?, bio = ?, profile_picture_url = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $username, $bio, $profile_picture_url, $user_id);
        $stmt->execute();
        $stmt->close();

        // Update session with the new username
        $_SESSION['username'] = $username;  // Update session with the new username

        // Reload the user data after update
        header("Location: profile.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="glowna.css">
    <script src="glowna.js" defer></script>
    <title>Profile - HobbyHub</title>
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
                <span class="welcome-message"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="logout" class="btn logout-btn">Log out</button>
                </form>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="profile-card">
            <form method="POST" enctype="multipart/form-data">
                <!-- Profile Picture -->
                <div class="profile-picture-container">
                    <div class="profile-picture" style="background-image: url('<?php echo $user['profile_picture_url']; ?>');">
                        <label for="profile_picture" class="edit-icon">&#9998;</label>
                        <input type="file" name="profile_picture" id="profile_picture" style="display: none;">
                    </div>
                </div>

                <!-- Username -->
                <div class="profile-info">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <!-- Bio -->
                <div class="profile-info">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4" required><?php echo htmlspecialchars($user['bio']); ?></textarea>
                </div>

                <!-- Submit Button -->
                <div class="profile-info">
                    <button type="submit" name="update_profile" class="btn save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

<?php
$conn->close();
?>
