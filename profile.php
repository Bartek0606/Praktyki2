<?php
session_start(); 

include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, email, full_name, bio, profile_picture FROM users WHERE user_id = '$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['logout'])) {
        session_unset(); 
        session_destroy(); 
        header("Location: login.php"); 
        exit();
    }

    if (isset($_POST['reset_picture'])) {
    
        $sql_update = "UPDATE users SET profile_picture = 'default.png' WHERE user_id = '$user_id'";

        if ($conn->query($sql_update) === TRUE) {
        
            header("Location: profile.php");
            exit();
        } else {
            echo "Error resetting profile picture: " . $conn->error;
        }
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $bio = $_POST['bio'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = addslashes(file_get_contents($_FILES['profile_picture']['tmp_name']));
        $sql_update = "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name', bio = '$bio', profile_picture = '$profile_picture' WHERE user_id = '$user_id'";
    } else {
        $sql_update = "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name', bio = '$bio' WHERE user_id = '$user_id'";
    }

    if ($conn->query($sql_update) === TRUE) {
        
        $_SESSION['username'] = $username;
        header("Location: profile.php"); 
        exit(); 
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="profile.css">
    <script src="profile.js" defer></script>
    <link rel="stylesheet" href="navbar.css">
    <title>Profile • HobbyHub</title>
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">
            <h1><a href="index.php">HobbyHub</a></h1>
        </div>
       
        <div class="navbar-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php
                
                $sql_image = "SELECT profile_picture FROM users WHERE user_id = '$user_id'";
                $result_image = $conn->query($sql_image);
                $image_src = 'default-avatar.jpg'; 
                if ($result_image->num_rows > 0) {
                    $row = $result_image->fetch_assoc();
                    if (!empty($row['profile_picture']) && $row['profile_picture'] !== 'default.png') {
                        $image_src = 'data:image/jpeg;base64,' . base64_encode($row['profile_picture']);
                    } else {
                        $image_src = 'default.png'; 
                    }
                }
                ?>
                <a href="profile.php" class="profile-link">
                    <img src="<?php echo $image_src; ?>" alt="Profile Picture" class="navbar-profile-img">
                    <span class="navbar-username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
                <form method="POST" class="logout-form">
                    <button type="submit" name="logout" class="logout-btn">Log out</button>
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
