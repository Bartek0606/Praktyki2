<?php 
class PostRender{
    private $dbConnection;
    private $categoryName;
    private $isLoggedIn;
    private $posts;
    private $userId;

    public function __construct($dbConnection, $isLoggedIn = false, $categoryName = null, $userId = null) {
        $this->dbConnection = $dbConnection;
        $this->categoryName = $categoryName;
        $this->isLoggedIn = $isLoggedIn;
        $this->posts = $this->fetchPosts();
        $this->userId = $userId;
    }
    private function fetchPosts() {
        if (!empty($this->categoryName)) {
            $sql = "
                SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name,
                COUNT(user_likes.likes_id) AS like_count
                FROM posts
                JOIN categories ON posts.category_id = categories.category_id
                LEFT JOIN user_likes ON posts.post_id = user_likes.post_id
                WHERE categories.name LIKE ?
                GROUP BY posts.post_id
                ORDER BY posts.created_at DESC
            ";
            $stmt = $this->dbConnection->prepare($sql);
            $categoryName = "%" . $this->categoryName . "%";
            $stmt->bind_param("s", $categoryName);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            $sql = "
                SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name,
                COUNT(user_likes.likes_id) AS like_count
                FROM posts
                JOIN categories ON posts.category_id = categories.category_id
                LEFT JOIN user_likes ON posts.post_id = user_likes.post_id
                GROUP BY posts.post_id
                ORDER BY posts.created_at DESC
            ";
        }

        return $this->dbConnection->query($sql);
    }
    public function like($userId, $isLoggedIn){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
            if ($isLoggedIn) {
                $post_id = $_POST['post_id']; 
                $sql_check = "SELECT * FROM `user_likes` WHERE user_id = ? AND post_id = ?";
                $stmt_check = $this->dbConnection->prepare($sql_check);
                $stmt_check->bind_param("ii", $userId, $post_id);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                if ($result_check->num_rows > 0) {
                    echo "juz ma ";
                } else {
                    $sql_register = "INSERT INTO `user_likes`(`user_id`, `post_id`) VALUES (?, ?)";
                    $stmt_register = $this->dbConnection->prepare($sql_register);
                    $stmt_register->bind_param("ii", $userId, $post_id);
                    $stmt_register->execute();
                    if ($stmt_register->affected_rows > 0) {
                        echo "nie ma ";
                    } else {
                        echo "There was an error adding your like.";
                    }
        
                }
            } else {
                echo "nie jestes zalogowany";
                
            }
        }

    }
    
    public function render() {
        ob_start(); 
        ?>
        <div class="posts">
            <?php
            if ($this->posts->num_rows > 0) {
                while ($row = $this->posts->fetch_assoc()) {
                    $post_url = 'post.php?id=' . $row['post_id'];
                    ?>
                    <a href="<?php echo $post_url; ?>" class="post-link">
                        <div class="post">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" alt="Post Image">
                            <div>
                                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                                <p>Category: <?php echo htmlspecialchars($row['category_name']); ?></p>
                                <p><?php echo $row['content']; ?></p>
                                <p>Date: <?php echo $row['created_at']; ?></p>
                                <form method="POST" action="">
                                    <div>Likes: <?php echo $row['like_count']; ?></div> 
                                    <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                                    <button class="heart" name="like"></button>
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
