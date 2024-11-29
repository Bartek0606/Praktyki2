<?php 
class PostRender {
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

        // Fetch posts when object is created
        $this->posts = $this->fetchPosts();
    }
    private function fetchPosts() {
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $orderBy = "posts.created_at DESC"; 
    
        if ($sort === 'oldest') {
            $orderBy = "posts.created_at ASC";
        } elseif ($sort === 'likes') {
            $orderBy = "like_count DESC"; // Zmieniamy to na COUNT(user_likes.id_post)
        }
    
        // Jeśli kategoria jest podana, używamy filtru
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
    
            // Przygotowanie zapytania
            $stmt = $this->dbConnection->prepare($sql);
            if ($stmt === false) {
                die("Error preparing the statement: " . $this->dbConnection->error);
            }
    
            // Binding parameter i wykonanie zapytania
            $categoryName = "%" . $this->categoryName . "%";
            $stmt->bind_param("s", $categoryName);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            // Zapytanie bez filtra kategorii
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
    
            // Wykonanie zapytania
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

        // Redirect to the current page to refresh the results
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    public function render() {
        ob_start(); 
        ?>
        <div class="posts">
            <?php
            if ($this->posts->num_rows > 0) {
                while ($row = $this->posts->fetch_assoc()) {
                    $post_url = 'post.php?id=' . $row['post_id'];
                    $hasImage = !empty($row['image']);
                    $isQuestionClass = $row['is_question'] == 1 ? 'question-post' : ''; // Add class for question posts
                    ?>
                    <a href="<?php echo $post_url; ?>" class="post-link">
                        <div class="post <?php echo $hasImage ? '' : 'no-image'; ?> <?php echo $isQuestionClass; ?>">
                            <?php if ($hasImage): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" alt="Post Image">
                            <?php endif; ?>
                            <div class="post-content">
                                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                                <p>Category: <?php echo htmlspecialchars($row['category_name']); ?></p>
                                <p>By: <?php echo htmlspecialchars($row['author_name']); ?></p>
                                <p><?php echo $row['content']; ?></p>
                                <p>Date: <?php echo $row['created_at']; ?></p>
                                <form method="POST" action="">
                                    <div>Likes: <?php echo $row['like_count']; ?></div> 
                                    <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                                    <button class="heart" name="like" 
                                        <?php if (!$this->isLoggedIn) : ?>
                                            onclick="return alert('You need to log in to like a post.');"
                                        <?php endif; ?>
                                        >
                                    </button>
                                </form>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            } else {
                ?>
                <p>No posts found.</p>
                <?php
            }
            ?>
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
