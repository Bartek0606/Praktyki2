<?php
class AllPostRender {
    private $dbConnection;
    private $categoryName;
    private $isLoggedIn;
    private $posts;
    private $userId;

    public function __construct($dbConnection, $isLoggedIn = false, $categoryName = null, $userId = null) {
        $this->dbConnection = $dbConnection;
        $this->categoryName = $categoryName;
        $this->isLoggedIn = $isLoggedIn;
        $this->userId = $userId;
        $this->posts = $this->fetchPosts();
    }

    private function fetchPosts() {
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $orderBy = "posts.created_at DESC"; 
    
        if ($sort === 'oldest') {
            $orderBy = "posts.created_at ASC";
        } elseif ($sort === 'likes') {
            $orderBy = "like_count DESC";
        }
    
        if (!empty($this->categoryName)) {
            $sql = "
                SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, posts.is_question, 
                       categories.name AS category_name, users.username AS author_name, 
                       COUNT(user_likes.id_post) AS like_count
                FROM posts
                JOIN categories ON posts.category_id = categories.category_id
                JOIN users ON posts.user_id = users.user_id
                LEFT JOIN user_likes ON posts.post_id = user_likes.id_post
                WHERE categories.name LIKE ?
                GROUP BY posts.post_id
                ORDER BY $orderBy
            ";
    
            $stmt = $this->dbConnection->prepare($sql);
            if ($stmt === false) {
                die("Error preparing the statement: " . $this->dbConnection->error);
            }
    
            $categoryName = "%" . $this->categoryName . "%";
            $stmt->bind_param("s", $categoryName);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            $sql = "
                SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, posts.is_question,
                       categories.name AS category_name, users.username AS author_name,
                       COUNT(user_likes.id_post) AS like_count
                FROM posts
                JOIN categories ON posts.category_id = categories.category_id
                JOIN users ON posts.user_id = users.user_id
                LEFT JOIN user_likes ON posts.post_id = user_likes.id_post
                GROUP BY posts.post_id
                ORDER BY $orderBy
            ";
    
            return $this->dbConnection->query($sql);
        }
    }

    public function like($userId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
            $post_id = $_POST['post_id']; 
            $sql_check = "SELECT * FROM `user_likes` WHERE id_user = ? AND id_post = ?";
            $stmt_check = $this->dbConnection->prepare($sql_check);
            $stmt_check->bind_param("ii", $userId, $post_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $sql_delete = "DELETE FROM `user_likes` WHERE id_user = ? AND id_post = ?";
                $stmt_delete = $this->dbConnection->prepare($sql_delete);
                $stmt_delete->bind_param("ii", $userId, $post_id);
                $stmt_delete->execute();
            } else {
                $sql_register = "INSERT INTO `user_likes`(`id_user`, `id_post`) VALUES (?, ?)";
                $stmt_register = $this->dbConnection->prepare($sql_register);
                $stmt_register->bind_param("ii", $userId, $post_id);
                $stmt_register->execute();
            }
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
    public function render() {
        ob_start(); 
        ?>
        <div class="w-4/6 mx-auto py-12">
            <div class="grid grid-cols-2 gap-12 bg-gray-900">
                <?php
                if ($this->posts->num_rows > 0) {
                    while ($row = $this->posts->fetch_assoc()) {
                        $post_url = '../templates/post.php?id=' . $row['post_id'];
                        $hasImage = !empty($row['image']);
        
                        echo "<div class='flex flex-row bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl h-80 ' >";
        
                        if ($hasImage) {
                            echo "<div class='w-1/3 h-full'>";
                            echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='w-full h-full object-cover'>";
                            echo "</div>";
                        }
        
                        echo "<div class='w-2/3 p-6 bg-gray-800 flex flex-col justify-between'>";
                        echo "<span class='text-sm uppercase text-gray-400 font-semibold'>" . htmlspecialchars($row['category_name']) . "</span>"; // Kategoria
                        echo "<a href='{$post_url}' class='text-xl font-bold text-white hover:text-blue-400 transition'>" . htmlspecialchars($row['title']) . "</a>"; // Tytuł
                        echo "<p class='text-gray-300 mt-2 line-clamp-3'>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>"; // Skrócona zawartość
                        echo "<div class='flex items-center mt-4'>";
                        echo "<span class='text-sm text-gray-500'>by " . htmlspecialchars($row['author_name']) . "</span>"; // Autor
                        echo "<span class='text-sm text-gray-400 ml-4'>" . htmlspecialchars($row['created_at']) . "</span>"; // Data
                        echo "</div>";
                        echo "</div>";
        
                        echo "</div>"; // Koniec pojedynczego posta
                    }
                } else {
                    echo "<p class='text-center text-gray-500'>No posts found.</p>";
                }
                ?>
            </div>
        </div>
        <?php
        return ob_get_clean(); 
    }
    
}
?>
<script>
    window.onload = function() {
        var scrollPosition = localStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, scrollPosition);
            localStorage.removeItem('scrollPosition'); 
        }
    }
    window.onbeforeunload = function() {
        localStorage.setItem('scrollPosition', window.scrollY);
    }
</script>
