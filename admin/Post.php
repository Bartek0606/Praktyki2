<?php

class Post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllPosts() {
        // Pobranie wszystkich postów z kategoriami
        $sql = "
            SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name
            FROM posts
            JOIN categories ON posts.category_id = categories.category_id
            ORDER BY posts.created_at DESC
        ";

        // Przygotowanie i wykonanie zapytania
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        // Zwracamy wyniki jako tablicę
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
