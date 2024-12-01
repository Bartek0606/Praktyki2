<?php
include __DIR__ . '/../db_connection.php'; 
include __DIR__ . '/Category.php'; 
include_once 'sidebar_admin.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../login.php");
    exit;
}

$sidebar = new Sidebar($conn, $userId);
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-700">
<div class="admin-panel">
   <?php echo $sidebar->getSidebarHtml(); ?>

    <main class="dashboard ml-64 min-h-screen bg-gray-50 p-8" style="padding-top: 6rem;">
        <div class="form-container mt-12 max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8 space-y-12">

            <form method="POST" class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">Edytuj kategorię</h3>
                <div class="form-group">
                    <label for="edit-category-select" class="block text-sm font-medium text-gray-600 mb-2">
                        Wybierz kategorię:
                    </label>
                    <select id="edit-category-select" name="category_id" required
                            class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">-- Wybierz kategorię --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['category_id']) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-category-input" class="block text-sm font-medium text-gray-600 mb-2">
                        Nowa nazwa kategorii:
                    </label>
                    <input type="text" id="edit-category-input" name="new_category_name" required
                           class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="form-group">
                    <label for="edit-description-input" class="block text-sm font-medium text-gray-600 mb-2">
                        Nowy opis kategorii:
                    </label>
                    <input id="edit-description-input" name="new_description" required
                           class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit" name="edit_category"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                    Zapisz
                </button>
            </form>

            <form method="POST" class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">Usuń kategorię</h3>
                <div class="form-group">
                    <label for="delete-category-select" class="block text-sm font-medium text-gray-600 mb-2">
                        Wybierz kategorię:
                    </label>
                    <select id="delete-category-select" name="category_id" required
                            class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">-- Wybierz kategorię --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['category_id']) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="delete_category"
                        class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition">
                    Usuń
                </button>
            </form>

            <form method="POST" class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">Dodaj nową kategorię</h3>
                <div class="form-group">
                    <label for="add-category-input" class="block text-sm font-medium text-gray-600 mb-2">
                        Nazwa kategorii:
                    </label>
                    <input type="text" id="add-category-input" name="category_name" required
                           class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="form-group">
                    <label for="add-description-input" class="block text-sm font-medium text-gray-600 mb-2">
                        Opis kategorii:
                    </label>
                    <input id="add-description-input" name="description" required
                           class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit" name="add_category"
                        class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition">
                    Zatwierdź
                </button>
            </form>

        </div>
    </main>
</div>
</body>
</html>
