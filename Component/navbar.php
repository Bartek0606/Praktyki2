<?php 
class Navbar{
    private $dbConnection;
    private $categories;
    private $isLoggedIn;
    private $userId;
    private $userName;

    public function __construct($dbConnection, $isLoggedIn = false, $userId = null, $userName = null) {
        $this->dbConnection = $dbConnection;
        $this->categories = $this->fetchCategories($dbConnection);
        $this->isLoggedIn = $isLoggedIn;
        $this->userId = $userId;
        $this->userName = $userName;
    }
    private function fetchCategories($dbConnection) {
        $sql = "SELECT category_id, name FROM categories ORDER BY name ASC";
        $result = $dbConnection->query($sql);
        $categories = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        return $categories;
    }

    private function fetchProfilePicture($userId) {
        $sql = "SELECT profile_picture FROM users WHERE user_id = ?";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $image_src = 'default.png'; 
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!empty($row['profile_picture'])) {
                $image_src = 'data:image/png;base64,' . base64_encode($row['profile_picture']); 
            }
        }
        return $image_src;
    }

    public function render() {
        ob_start(); // Rozpoczynamy buforowanie
    ?>
    <nav class="navbar">
      <div class="logo">
        <h1><a href="index.php">HobbyHub</a></h1>
        <?php 
        if ($_SERVER['REQUEST_URI'] != '/profile.php' && $_SERVER['REQUEST_URI'] != '/new_post.php') {
        ?>
            <div class="dropdown">
                <button class="dropdown-button" onclick="toggleDropdown()">Select Category</button>
                <div class="dropdown-menu" id="dropdownMenu">
                    <?php if (!empty($this->categories)): ?>
                        <?php foreach ($this->categories as $category): ?>
                            <a href="subpage.php?id=<?php echo $category['category_id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a>No categories available</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php 
        }
        ?>
        </div>
        <?php
        if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '' || $_SERVER['PHP_SELF'] == '/index.php') {
        ?>
            <form class="search-form" method="GET" action="">
                <input type="text" name="category" placeholder="Search by category" value="<?php echo htmlspecialchars($search_category ?? ''); ?>">
                <button type="submit">Search</button>
            </form>
        <?php 
        }
        ?>

        <div class="auth-buttons">
            <?php if ($this->isLoggedIn): ?>
            <div class="auth-info">
                <?php if ($_SERVER['REQUEST_URI'] != '/new_post.php') {
                ?>
                <button class="btn new-post-btn" onclick="window.location.href='new_post.php'">New Post</button>
                <?php 
                }
                ?>
                <a href="profile.php" class="profile-link">
                    <?php
                        $image_src = $this->fetchProfilePicture($this->userId);
                    ?>
                    <img src="<?php echo $image_src; ?>" alt="Profile Picture" class="profile-img">
                    <span class="username"><?php echo htmlspecialchars($this->userName); ?></span>
                </a>
            </div>
        
            <form method="POST" style="display: inline;">
                <button type="submit" name="logout" class="btn logout-btn">Log out</button>
            </form>
            
        <?php else: ?>
            <button class="btn register-btn" onclick="window.location.href='register.php'">Sign up</button>
            <button class="btn login-btn" onclick="window.location.href='login.php'">Login</button>
        <?php endif; ?>
    </nav>

<?php
    return ob_get_clean(); // Zwracamy zawartość bufora jako string
    }
}
?>
<script>
// Toggle dropdown menu visibility
function toggleDropdown() {
  const menu = document.getElementById("dropdownMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Close dropdown if clicked outside
window.onclick = function (event) {
  if (!event.target.matches(".dropdown-button")) {
    const dropdowns = document.getElementsByClassName("dropdown-menu");
    for (let i = 0; i < dropdowns.length; i++) {
      dropdowns[i].style.display = "none";
    }
  }
};
</script>