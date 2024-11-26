<?php
session_start(); 

include 'db_connection.php';
include 'Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);

$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$emailError = ''; // Zmienna na komunikat o błędzie e-maila
$usernameError = ''; // Zmienna na komunikat o błędzie username

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

    // Walidacja e-maila
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
    } else {
        // Sprawdzenie, czy e-mail już istnieje w bazie danych (poza bieżącym użytkownikiem)
        $sql_check_email = "SELECT * FROM users WHERE email = '$email' AND user_id != '$user_id'";
        $result_email = $conn->query($sql_check_email);

        if ($result_email->num_rows > 0) {
            $emailError = "This email is already taken.";
        }
    }

    // Walidacja username
    if (empty($username)) {
        $usernameError = "Username is required.";
    } else {
        // Sprawdzenie, czy username już istnieje w bazie danych (poza bieżącym użytkownikiem)
        $sql_check_username = "SELECT * FROM users WHERE username = '$username' AND user_id != '$user_id'";
        $result_username = $conn->query($sql_check_username);

        if ($result_username->num_rows > 0) {
            $usernameError = "This username is already taken.";
        }
    }

    if (empty($emailError) && empty($usernameError)) {
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
<?php
    echo $navbar->render();
    ?>
</header>
<main>
    <!-- Kwadrat: Edycja profilu -->
    <div class="container">
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
                <?php if ($usernameError): ?>
                    <p class="error" style="color: red;"><?php echo $usernameError; ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <?php if ($emailError): ?>
                    <p class="error" style="color: red;"><?php echo $emailError; ?></p>
                <?php endif; ?>
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

    <!-- Kwadrat: Posty użytkownika -->
    <div class="container posts-container">
        <h2>Your Posts</h2>
        <?php
        // Pobranie postów użytkownika z bazy danych wraz z kategorią
        $sql_posts = "
            SELECT posts.post_id, posts.title, posts.content, posts.image, posts.created_at, categories.name AS category_name 
            FROM posts 
            LEFT JOIN categories ON posts.category_id = categories.category_id 
            WHERE posts.user_id = '$user_id' 
            ORDER BY posts.created_at DESC
        ";
        $result_posts = $conn->query($sql_posts);

        if (!$result_posts) {
            echo "<p>Error: " . $conn->error . "</p>";
        }

        if ($result_posts->num_rows > 0): ?>
            <div class="posts">
                <?php while ($post = $result_posts->fetch_assoc()): ?>
                    <a href="post.php?id=<?php echo $post['post_id']; ?>" class="post-link">
                        <div class="post">
                            <?php if (!empty($post['image'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" class="post-image">
                            <?php endif; ?>
                            <div class="post-content">
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="category"><strong>Category: <?php echo htmlspecialchars($post['category_name']); ?></strong></p>
                                <p><?php echo htmlspecialchars($post['content']); ?></p>
                                <div class="post-date">
                                    <strong>Date: </strong><?php echo date($post['created_at']); ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No posts yet. Start creating posts!</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>

<?php
$conn->close();
?>
