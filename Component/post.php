<?php 
class PostRender{
    private $dbConnection;
    private $categoryName;
    private $posts;

    public function __construct($dbConnection, $categoryName = '') {
        $this->dbConnection = $dbConnection;
        $this->categoryName = $categoryName;
        $this->posts = $this->fetchPosts();
    }
    private function fetchPosts() {
        if (!empty($this->categoryName)) {
            $sql = "
                SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name
                FROM posts
                JOIN categories ON posts.category_id = categories.category_id
                WHERE categories.name LIKE ?
                ORDER BY posts.created_at DESC
            ";
            $stmt = $this->dbConnection->prepare($sql);
            $categoryName = "%" . $this->categoryName . "%";
            $stmt->bind_param("s", $categoryName);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            $sql = "
                SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name
                FROM posts
                JOIN categories ON posts.category_id = categories.category_id
                ORDER BY posts.created_at DESC
            ";
        }

        return $this->dbConnection->query($sql);
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
                                <p><?php echo htmlspecialchars($row['content']); ?></p>
                                <p>Date: <?php echo htmlspecialchars($row['created_at']); ?></p>
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