<?php
// Użyj ścieżki bezwzględnej dla db_connection.php
include '../../db_connection.php';

class PostManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Pobranie wszystkich postów z kategoriami
    public function getAllPosts() {
        $sql = "SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name 
                FROM posts 
                JOIN categories ON posts.category_id = categories.category_id 
                ORDER BY posts.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Pobranie wszystkich kategorii
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

    // Pobranie danych posta do edycji
    public function getPostToEdit($postId) {
        $sql = "SELECT post_id, title, content, category_id FROM posts WHERE post_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Zapisanie zmian w poście
    public function savePostChanges($postId, $newTitle, $newContent, $newCategoryId) {
        $sql = "UPDATE posts SET title = ?, content = ?, category_id = ? WHERE post_id = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssii", $newTitle, $newContent, $newCategoryId, $postId);
            return $stmt->execute();
        }
        return false;
    }

    // Obsługa zapisania zmian w poście
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

    // Usunięcie posta
    public function deletePost($postId) {
        $sql = "DELETE FROM posts WHERE post_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $stmt->close();
    }

    // Obsługa żądania usunięcia posta
    public function handleDeleteRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
            $postId = intval($_POST['delete_post_id']);
            $this->deletePost($postId);
            header("Location: ../admin.php");
            exit;
        }
    }

    // Przekierowanie po zapisaniu zmian
    public function redirectAfterSave() {
        header("Location: ../admin.php");
        exit;
    }
}

// Inicjalizacja klasy PostManager
$postManager = new PostManager($conn);

// Obsługa zapisu zmian, jeśli formularz został wysłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_post'])) {
        $isSaved = $postManager->handleSaveChanges($_POST);
        if ($isSaved) {
            $postManager->redirectAfterSave();
        }
    } elseif (isset($_POST['delete_post_id'])) {
        $postManager->handleDeleteRequest();
    }
}

// Start sesji i sprawdzanie, czy użytkownik jest zalogowany
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../../public/templates/login.php");
    exit;
}

// Załaduj klasę Sidebar (zakładając, że ta klasa znajduje się w oddzielnym pliku)
include __DIR__ . '/../sidebar_admin.php';
$sidebar = new Sidebar($conn, $userId);
?>
