<?php
include __DIR__ . '/../db_connection.php'; 
include __DIR__ . '/Category.php'; 

include_once 'sidebar_admin.php';

// Tworzenie obiektu klasy Sidebar
$sidebar = new Sidebar();

$categoryManager = new Category($conn);
$categories = $categoryManager->getCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        if ($categoryManager->handleAddCategoryForm($_POST)) {
            header('Location: categories.php');
            exit();
        } else {
            echo "<p>Błąd podczas dodawania kategorii.</p>";
        }
    } 
    
    elseif (isset($_POST['edit_category'])) {
        if ($categoryManager->handleEditCategoryForm($_POST)) {
            header('Location: categories.php');
            exit();
        } else {
            echo "<p>Błąd podczas edytowania kategorii.</p>";
        }
    } 
    
    elseif (isset($_POST['delete_category'])) {
        if ($categoryManager->handleDeleteCategoryForm($_POST)) {
            header('Location: categories.php');
            exit();
        } else {
            echo "<p>Błąd podczas usuwania kategorii.</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
<div class="admin-panel">
    <?php $sidebar->render(); ?>

     <main class="dashboard">
        <div class="form-container">
            <!-- Formularz edycji -->
            <form method="POST">
                <h3>Edytuj kategorię</h3>
                <div class="form-group">
                    <label for="edit-category-select">Wybierz kategorię:</label>
                    <select id="edit-category-select" name="category_id" required>
                        <option value="">-- Wybierz kategorię --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['category_id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-category-input">Nowa nazwa kategorii:</label>
                    <input type="text" id="edit-category-input" name="new_category_name" required>
                </div>
                <div class="form-group">
                    <label for="edit-description-input">Nowy opis kategorii:</label>
                    <input id="edit-description-input" name="new_description" required></i>
                </div>
                <button type="submit" name="edit_category">Zapisz</button>
            </form>

            <!-- Formularz usuwania -->
            <form method="POST">
                <h3>Usuń kategorię</h3>
                <div class="form-group">
                    <label for="delete-category-select">Wybierz kategorię:</label>
                    <select id="delete-category-select" name="category_id" required>
                        <option value="">-- Wybierz kategorię --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['category_id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="delete_category">Usuń</button>
            </form>

            <!-- Formularz dodawania -->
            <form method="POST">
                <h3>Dodaj nową kategorię</h3>
                <div class="form-group">
                    <label for="add-category-input">Nazwa kategorii:</label>
                    <input type="text" id="add-category-input" name="category_name" required>
                </div>
                <div class="form-group">
                    <label for="add-description-input">Opis kategorii:</label>
                    <input id="add-description-input" name="description" class="add-description-input" required></i>
                </div>
                <button type="submit" name="add_category">Zatwierdź</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>
