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
        <div class="w-4/5 mx-auto py-12 dark:bg-gray-900">
            <div class="text-center dark:bg-gray-900">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                if ($this->posts->num_rows > 0) {
                    while ($row = $this->posts->fetch_assoc()) {
                        $post_url = '../templates/post.php?id=' . $row['post_id'];
                        $hasImage = !empty($row['image']);
    
                        $isQuestionClass = $row['is_question'] == 1 ? 'border-yellow-200' : ''; // Klasa dla pytania
    
                        $sql_check_like = "SELECT * FROM `user_likes` WHERE id_user = ? AND id_post = ?";
                        $stmt_check_like = $this->dbConnection->prepare($sql_check_like);
                        $stmt_check_like->bind_param("ii", $this->userId, $row['post_id']);
                        $stmt_check_like->execute();
                        $result_check_like = $stmt_check_like->get_result();
                        $isLiked = $result_check_like->num_rows > 0;
    
                        echo "<div class='relative bg-white shadow-lg rounded-lg overflow-hidden h-[500px] card {$isQuestionClass} transition-transform transform hover:scale-105 hover:shadow-2xl hover:bg-gray-800 hover:text-white'>";
                        echo "<a href='{$post_url}' class='block h-full relative group'>";
                        if ($hasImage) {
                            echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='absolute inset-0 w-full h-full object-cover filter transition-opacity duration-500 ease-in-out hover:opacity-75'>";
                            echo "<div class='absolute inset-0 bg-black bg-opacity-50 transition-opacity duration-500 ease-in-out hover:bg-opacity-75'></div>"; // Nakładka przyciemniająca
                        }
                        echo "<div class='absolute inset-0 card-content p-8 flex flex-col justify-end transition-opacity duration-500 ease-in-out'>";
                        echo "<h3 class='text-xl font-semibold text-white'>" . $row['title'] . "</h3>"; // Tytuł
                        echo "<span class='block text-gray-300'>" . htmlspecialchars($row['category_name']) . "</span>"; // Kategoria
                        echo "<span class='block text-white font-medium'>" . $row['author_name'] . "</span>"; // Twórca
                        echo "<span class='block text-gray-300 text-sm'>" . $row['created_at'] . "</span>"; // Data
                        echo "<small class='block text-white'>Likes: " . htmlspecialchars($row['like_count']) . "</small>"; // Ilość polubień
                        echo "</div>";
                        // Animowana pomarańczowa ramka
                        echo "<div class='absolute inset-0 border-4 border-transparent rounded-lg group-hover:border-orange-400 transition-all duration-500 ease-in-out group-hover:animate-draw-border'></div>";
                        echo "</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-white'>No posts found.</p>";
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
