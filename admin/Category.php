<?php
class Category {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCategories() {
        $sql = "SELECT category_id, name FROM categories";
        $result = $this->conn->query($sql);

        if ($result === false) {
            error_log("Błąd podczas pobierania kategorii: " . $this->conn->error);
            return [];
        }

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    public function addCategory($categoryName, $description) {
        $sql = "SELECT MAX(category_id) AS max_id FROM categories";
        $result = $this->conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $newCategoryId = $row['max_id'] + 1;
        } else {
            $newCategoryId = 1;
        }

        $sql = "INSERT INTO categories (category_id, name, description) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iss', $newCategoryId, $categoryName, $description);

        return $stmt->execute();
    }

    // Metoda do edytowania kategorii
    public function editCategory($categoryId, $newCategoryName, $newDescription) {
        $sql = "UPDATE categories SET name = ?, description = ? WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssi', $newCategoryName, $newDescription, $categoryId);

        return $stmt->execute();
    }

    // Metoda do usuwania kategorii
    public function deleteCategory($categoryId) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $categoryId);

        return $stmt->execute();
    }

    // Metoda do obsługi dodawania kategorii z formularza
    public function handleAddCategoryForm($postData) {
        if (isset($postData['category_name']) && isset($postData['description'])) {
            $categoryName = trim($postData['category_name']);
            $description = trim($postData['description']);
            if (!empty($categoryName)) {
                return $this->addCategory($categoryName, $description);
            }
        }
        return false;
    }

    // Metoda do obsługi edytowania kategorii z formularza
    public function handleEditCategoryForm($postData) {
        if (isset($postData['category_id']) && isset($postData['new_category_name']) && isset($postData['new_description'])) {
            $categoryId = $postData['category_id'];
            $newCategoryName = trim($postData['new_category_name']);
            $newDescription = trim($postData['new_description']);
            if (!empty($categoryId) && !empty($newCategoryName)) {
                return $this->editCategory($categoryId, $newCategoryName, $newDescription);
            }
        }
        return false;
    }

    // Metoda do obsługi usuwania kategorii z formularza
    public function handleDeleteCategoryForm($postData) {
        if (isset($postData['category_id'])) {
            $categoryId = $postData['category_id'];
            if (!empty($categoryId)) {
                return $this->deleteCategory($categoryId);
            }
        }
        return false;
    }
}
?>
