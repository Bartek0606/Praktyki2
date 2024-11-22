<?php
session_start();
include 'db_connection.php';


$isLoggedIn = isset($_SESSION['user_id']);

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy(); // Destroy the session
  header("Location: index.php"); // Redirect to homepage
  exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category_id = (int) $_POST['category'];
    $is_question = isset($_POST['is_question']) ? 1 : 0;
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    }

    $sql = "INSERT INTO posts (user_id, title, content, category_id, is_question, image, created_at)
            VALUES ($user_id, '$title', '$content', $category_id, $is_question, '$image', NOW())";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['post_success'] = "Your post has been created successfully!";
        header("Location: index.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}

$categories_sql = "SELECT * FROM categories";
$categories_result = $conn->query($categories_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="post.css">
    <link rel="stylesheet" href="new_post.css">
    <link rel="stylesheet" href="navbar.css">
    <title>Create New Post</title>
</head>
<body>
    <header>
    <nav class="navbar">
      <div class="logo">
        <h1><a href="index.php">HobbyHub</a></h1>
      </div>

      <div class="auth-buttons">
        <?php if ($isLoggedIn): ?>
          <div class="auth-info">
            <button class="btn new-post-btn" onclick="window.location.href='new_post.php'">New Post</button>
            <a href="profile.php" class="profile-link">
              <?php
              
                $sqlProfilePicture = "SELECT profile_picture FROM users WHERE user_id = '$userId'";
                $resultProfilePicture = $conn->query($sqlProfilePicture);
                $profilePictureSrc = 'default.png'; // Default image
                if ($resultProfilePicture->num_rows > 0) {
                    $userProfile = $resultProfilePicture->fetch_assoc();
                    if (!empty($userProfile['profile_picture'])) {
                        // If there's a profile picture, use it
                        $profilePictureSrc = 'data:image/jpeg;base64,' . base64_encode($userProfile['profile_picture']);
                    }
                }
              ?>
              <img src="<?php echo $profilePictureSrc; ?>" alt="Profile Picture" class="profile-img">
              <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </a>
          </div>
          
          <form method="POST" style="display: inline;">
              <button type="submit" name="logout" class="btn logout-btn">Log out</button>
          </form>
        <?php else: ?>
            <button class="btn register-btn" onclick="window.location.href='register.php'">Sign up</button>
            <button class="btn login-btn" onclick="window.location.href='login.php'">Login</button>
        <?php endif; ?>
      </div>

    </nav>
  </header> 

    <main class="container">
        <div class="post-details">
            <h2>Create New Post</h2>

            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="new-post-form">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" required>
                </div>

                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea name="content" id="content" rows="6" required></textarea>
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" id="category" required>
                        <?php while ($category = $categories_result->fetch_assoc()): ?>
                            <option value="<?php echo $category['category_id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="is_question">Is this a question?</label>
                    <input type="checkbox" name="is_question" id="is_question" value="1">
                </div>

                <div class="form-group">
                    <label for="image">Upload Image:</label>
                    <input type="file" name="image" id="image" accept="image/*">
                </div>

                <button type="submit" name="submit_post" class="btn new-post-btn">Post</button>
            </form>
        </div>
    </main>
</body>
</html>

<?php
$conn->close();
?>
