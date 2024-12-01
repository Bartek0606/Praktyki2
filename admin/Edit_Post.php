<?php
class Edit_Post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Wszystkie posty z wszystkimi danymi
    public function getAllPosts() {
        $sql =
"SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name FROM posts JOIN categories ON posts.category_id = categories.category_id ORDER BY posts.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // wszystkie kategorie
    public function getCategories() {
        $categories = [];
        $categoriesQuery = $this->conn->query("SELECT category_id, name FROM categories");
        if ($categoriesQuery) {
            while ($category = $categoriesQuery->fetch_assoc()) {
                $categories[] = $category;
            }
        }
        return $categories;
    }

    // dane posta do edycji
    public function getPostToEdit($postId) {
        $sql = "SELECT post_id, title, content, category_id FROM posts WHERE post_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // zmiany w poście
    public function savePostChanges($postId, $newTitle, $newContent, $newCategoryId) {
        $sql = "UPDATE posts SET title = ?, content = ?, category_id = ? WHERE post_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ssii", $newTitle, $newContent, $newCategoryId, $postId);
            return $stmt->execute();
        }
        return false;
    }

    // zapisanie zmian w formularzu
    public function handleSaveChanges($postData) {
        $postId = intval($postData['post_id']);
        $newTitle = trim($postData['editTitle']);
        $newContent = trim($postData['editContent']);
        $newCategoryId = intval($postData['category_id']);

        if (!empty($newTitle) && !empty($newContent) && $newCategoryId > 0 && $postId > 0) {
            return $this->savePostChanges($postId, $newTitle, $newContent, $newCategoryId);
        }
        return false;
    }

    public function redirectAfterSave() {
        header("Location: admin.php");
        exit;
    }
}
?>
