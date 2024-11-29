<?php
include __DIR__ . '/../db_connection.php'; // Dołączamy plik z połączeniem do bazy danych

// Funkcja do pobierania kategorii
function getCategories($conn) {
    $sql = "SELECT category_id, name FROM categories";
    $result = $conn->query($sql);

    if ($result === false) {
        error_log("Błąd podczas pobierania kategorii: " . $conn->error);
        return [];
    }

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    return $categories;
}

// Obsługa dodawania kategorii
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $categoryName = trim($_POST['category_name']);
    $description = trim($_POST['description']); // Pobranie opisu kategorii

    if (!empty($categoryName)) {
        // Pobierz maksymalny category_id z tabeli
        $sql = "SELECT MAX(category_id) AS max_id FROM categories";
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            // Automatycznie ustawiamy category_id na max_id + 1
            $newCategoryId = $row['max_id'] + 1;
        } else {
            // Jeśli tabela jest pusta, zaczynamy od category_id = 1
            $newCategoryId = 1;
        }

        // Zapytanie, aby dodać nową kategorię z przypisanym category_id oraz opisem
        $sql = "INSERT INTO categories (category_id, name, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $newCategoryId, $categoryName, $description);

        if ($stmt->execute()) {
            // Po dodaniu kategorii przekierowanie na categories.php
            header('Location: categories.php');
            exit(); // Ważne: użycie exit() po header(), aby zakończyć dalsze wykonywanie skryptu
        } else {
            echo "<p>Błąd podczas dodawania kategorii: " . $conn->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Proszę podać nazwę kategorii.</p>";
    }
}

// Obsługa edycji kategorii
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $categoryId = $_POST['category_id'];
    $newCategoryName = trim($_POST['new_category_name']);
    $newDescription = trim($_POST['new_description']); // Pobranie nowego opisu

    if (!empty($categoryId) && !empty($newCategoryName)) {
        $sql = "UPDATE categories SET name = ?, description = ? WHERE category_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $newCategoryName, $newDescription, $categoryId);

        if ($stmt->execute()) {
            // Po edytowaniu kategorii przekierowanie na categories.php
            header('Location: categories.php');
            exit(); // Ważne: użycie exit() po header(), aby zakończyć dalsze wykonywanie skryptu
        } else {
            echo "<p>Błąd podczas edytowania kategorii: " . $conn->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Proszę wybrać kategorię i podać nową nazwę.</p>";
    }
}

// Obsługa usuwania kategorii
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $categoryId = $_POST['category_id'];
    if (!empty($categoryId)) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $categoryId);

        if ($stmt->execute()) {
            // Po usunięciu kategorii przekierowanie na categories.php
            header('Location: categories.php');
            exit(); // Ważne: użycie exit() po header(), aby zakończyć dalsze wykonywanie skryptu
        } else {
            echo "<p>Błąd podczas usuwania kategorii: " . $conn->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Proszę wybrać kategorię do usunięcia.</p>";
    }
}

// Pobranie listy kategorii do wyświetlenia w formularzach
$categories = getCategories($conn);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<style>

    /* Prosty CSS */
        .form-container {
            width: 65%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .form-container h3 {
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        .form-group {
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9em;
        }
        select, input {
            width: 100%;
            padding: 8px;
            font-size: 1em;
            margin-top: 5px;
        }
        button {
            width: 10%;
            padding: 8px;
            text-align: left;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            margin-bottom: 45px;
        }
        button:hover {
            background-color: #0056b3;
        }
</style>
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
                <button class="menu-button">Add new category</button>
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
                <button type="submit" name="edit_category">Zapisz zmiany</button>
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
