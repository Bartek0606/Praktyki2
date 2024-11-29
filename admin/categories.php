<?php
include __DIR__ . '/../db_connection.php'; 
include __DIR__ . '/Category.php'; 

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
    <header>
        <h1 class="tittle">HOBBYHUB</h1>
    </header>
    <aside class="sidebar">
            <hr class="hr_nav">
            <div class="profile-section">
                <div class="profile-pic"></div>
                <p class="username">Username</p>
            </div>
            <nav class="menu">
                <hr class="hrbutton">
               <a href="admin.php"> <button class="menu-button">Posts</button></a>
                <hr class="hrbutton">
               <a href="events.php"> <button class="menu-button">Events</button></a>
                 <hr class="hrbutton">
                <a href="comments.php"><button class="menu-button">Comments</button></a>
                 <hr class="hrbutton">
                       <a href="categories.php"> <button class="menu-button">Add new category</button></a>
                 <hr class="hrbutton">
            </nav>
            <hr class="hrbutton">
            <div class="sidebar-bottom">
        <button class="logout-button">
            <span>Log Out</span>
        </button>
    </div>
        </aside>

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
