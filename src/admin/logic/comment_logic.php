<?php
class Comment {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Wszystkie komentarze
    public function getAllPCom($search_query = '') {
        $sql = "
            SELECT comments.comment_id, comments.content, comments.created_at, users.full_name
            FROM comments
            LEFT JOIN users ON comments.user_id = users.user_id
            WHERE users.full_name LIKE ?
            ORDER BY users.full_name ASC, comments.created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $search_param = "%" . $search_query . "%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Usuwanie komentarzy 
    public function deleteComment($comment_id) {

        $sql = "DELETE FROM comments WHERE comment_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $comment_id); 
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: comments.php"); 
            exit();
        } else {
            echo "Error deleting comment.";
        }
    }

    public function searchCommentsByUser($search_query) {
        return $this->getAllPCom($search_query);
    }
}

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../../public/templates/login.php");
    exit();
}

$commentObj = new Comment($conn);

if (isset($_GET['delete_comment_id'])) {
    $comment_id = (int)$_GET['delete_comment_id'];
    $commentObj->deleteComment($comment_id); 
}

$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$comments = $commentObj->getAllPCom($search_query);