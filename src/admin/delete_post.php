<?php

class Delete_post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; 
    }
    public function deletePost($postId) {
        $sql = "DELETE FROM posts WHERE post_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $stmt->close();  
    }

    public function handleDeleteRequest() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
            $postId = intval($_POST['delete_post_id']); 
            $this->deletePost($postId); 
            header("Location: admin.php");
            exit;
        }
    }
}
