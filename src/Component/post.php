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
            $orderBy = "like_count DESC";
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
                LIMIT 4
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
                LIMIT 4
            ";

            return $this->dbConnection->query($sql);
        }
    }

    public function render() {
        ob_start();
        ?>
          <div class="relative w-5/6 mx-auto h-2/4">
    <img src="../image/tlo.png" alt="Tło" class="w-full h-full object-cover filter blur mx-auto rounded-xl ">
    <div class="absolute inset-0 flex flex-col items-center justify-center">
      <h2 class="text-white text-3xl font-bold mb-2">Blog Posts Section
      <hr class="border-t-4  border-orange-500 mt-3">
      </h2>
    </div>
  </div>
        <section class="blog-posts w-full bg-gray-900 mt-14">
        <div class="w-4/6 mx-auto py-12 mb-12">
        <div class="text-left mb-8">
                <a href="/src/public/templates/all_post.php" class="px-6 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-800 transition">
                    View All Posts
                </a>
            </div>
            <div class="grid grid-cols-2 gap-12 bg-gray-900">
                <?php
                if ($this->posts->num_rows > 0) {
                    while ($row = $this->posts->fetch_assoc()) {
                        $post_url = '../templates/post.php?id=' . $row['post_id'];
                        $hasImage = !empty($row['image']);

                        echo "<div class='flex h-64 bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl'>";

                        if ($hasImage) {
                            echo "<div class='w-1/3 h-full'>";
                            echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='w-full h-full object-cover'>";
                            echo "</div>";
                        }

                        echo "<div class='p-6 w-2/3 bg-gray-800 flex flex-col justify-between'>";
                        echo "<span class='text-sm uppercase text-gray-400 font-semibold'>" . htmlspecialchars($row['category_name']) . "</span>"; 
                        echo "<a href='{$post_url}' class='text-xl font-bold text-white hover:text-blue-400 transition'>" . htmlspecialchars($row['title']) . "</a>"; 
                        echo "<p class='text-gray-300 mt-2 line-clamp-3'>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>"; 
                        echo "<div class='flex items-center mt-4'>";
                        echo "<span class='text-sm text-gray-500'>by " . htmlspecialchars($row['author_name']) . "</span>"; 
                        echo "<span class='text-sm text-gray-400 ml-4'>" . htmlspecialchars($row['created_at']) . "</span>"; 
                        echo "</div>";
                        echo "</div>";

                        echo "</div>"; 
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
    // Przywracanie pozycji scrolla po przeładowaniu
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
