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
    
        $image_src = '../default.png'; 
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!empty($row['profile_picture']) && $row['profile_picture']!='default.png') {
                $image_src = 'data:image/png;base64,' . base64_encode($row['profile_picture']); 
            }
        }
        return $image_src;
    }

    public function render() {
        ob_start(); // Rozpoczynamy buforowanie
    ?>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="index.php" class="flex items-center space-x-3">
                    <span class="text-2xl font-semibold whitespace-nowrap dark:text-white">HobbyHub</span>
                </a>

                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-8 p-4 md:p-0 bg-gray-50 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900">
                        <li><a href="index.php" class="block py-2 px-3 text-gray-900 hover:text-blue-700 dark:text-white">Home</a></li>
                        <li>
                            <div class="relative">
                                <button
                                    class="block py-2 px-3 text-gray-900 hover:text-blue-700 dark:text-white"
                                    onclick="toggleDropdown()"
                                >
                                    Categories
                                </button>
                                <div id="dropdownMenu" class="absolute hidden bg-white shadow-lg dark:bg-gray-800 z-20 top-full left-0 w-40">
                                    <?php foreach ($this->categories as $category): ?>
                                        <a href="subpage.php?id=<?php echo $category['category_id']; ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </li>
                        <?php if ($this->isLoggedIn): ?>
                            <li><a href="new_post.php" class="block py-2 px-3 text-gray-900 hover:text-blue-700 dark:text-white">New Post</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="flex space-x-4">
                    <?php if ($this->isLoggedIn): ?>
                        <a href="user.php?id=<?php echo $this->userId; ?>" class="flex items-center space-x-2">
                            <img src="<?php echo $this->fetchProfilePicture($this->userId); ?>" class="w-8 h-8 rounded-full">
                            <span class="text-gray-900 dark:text-white"><?php echo htmlspecialchars($this->userName); ?></span>
                        </a>
                        <form method="POST">
                            <button type="submit" name="logout" class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-500">Log out</button>
                        </form>
                    <?php else: ?>
                         <a href="register.php" class="px-4 py-2 text-white bg-gray-900 rounded hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600">Sign up</a>
                        <a href="login.php" class="px-4 py-2 text-white bg-gray-900 rounded hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600">Login</a>
                    <?php endif; ?>
                </div>
            </div>
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