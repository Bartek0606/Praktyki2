<?php
// Załaduj plik połączenia z bazą danych
require_once '../../db_connection.php';
 // Uzupełnij ścieżkę do pliku db_connection.php

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

// Pobierz kategorie z bazy danych
$categories = [];
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $categoryName = $_POST['category_name'];
        $description = $_POST['description'];

        // Dodanie nowej kategorii
        $sql = "INSERT INTO categories (name, description) VALUES ('$categoryName', '$description')";
        if ($conn->query($sql) === TRUE) {
            header('Location: categories.php');
            exit();
        } else {
            $error = "Błąd podczas dodawania kategorii.";
        }
    } elseif (isset($_POST['edit_category'])) {
        $categoryId = $_POST['category_id'];
        $newCategoryName = $_POST['new_category_name'];
        $newDescription = $_POST['new_description'];

        // Edycja kategorii
        $sql = "UPDATE categories SET name = '$newCategoryName', description = '$newDescription' WHERE category_id = '$categoryId'";
        if ($conn->query($sql) === TRUE) {
            header('Location: categories.php');
            exit();
        } else {
            $error = "Błąd podczas edytowania kategorii.";
        }
    } elseif (isset($_POST['delete_category'])) {
        $categoryId = $_POST['category_id'];

        // Usuwanie kategorii
        $sql = "DELETE FROM categories WHERE category_id = '$categoryId'";
        if ($conn->query($sql) === TRUE) {
            header('Location: categories.php');
            exit();
        } else {
            $error = "Błąd podczas usuwania kategorii.";
        }
    }
}
?>
